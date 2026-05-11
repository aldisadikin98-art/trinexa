<?php

namespace App\Services;

use App\Models\User;
use App\Models\LoyaltyPoint;
use App\Models\Badge;
use App\Models\LoginStreak;
use Carbon\Carbon;

class LoyaltyService
{
    /**
     * Add points to a user and check for badge unlocks
     */
    public function addPoints(User $user, int $points, string $activityType, string $description = null)
    {
        LoyaltyPoint::create([
            'user_id' => $user->id,
            'activity_type' => $activityType,
            'points' => $points,
            'description' => $description,
        ]);

        $this->checkAndUnlockBadges($user);
    }

    /**
     * Update login streak for a user
     */
    public function updateLoginStreak(User $user)
    {
        $streak = $user->loginStreak;
        $today = Carbon::today();

        if (!$streak) {
            $streak = LoginStreak::create([
                'user_id' => $user->id,
                'current_streak' => 1,
                'highest_streak' => 1,
                'last_login_date' => $today,
            ]);
            $this->addPoints($user, 10, 'login', 'Daily Login');
        } else {
            $lastLogin = Carbon::parse($streak->last_login_date);
            
            if ($lastLogin->isToday()) {
                $this->checkAndUnlockBadges($user);
                return; // Already logged in today, do not give points again
            }

            if ($lastLogin->isYesterday()) {
                $streak->current_streak += 1;
                if ($streak->current_streak > $streak->highest_streak) {
                    $streak->highest_streak = $streak->current_streak;
                }
            } else {
                $streak->current_streak = 1; // Reset streak
            }
            
            $streak->last_login_date = $today;
            $streak->save();
            
            $this->addPoints($user, 10, 'login', 'Daily Login');
        }

        // Check milestones
        if (in_array($streak->current_streak, [3, 7, 14, 30])) {
            $bonus = $streak->current_streak * 10; // e.g. 7 days -> 70 points
            $this->addPoints($user, $bonus, 'streak_bonus', "Login Streak {$streak->current_streak} Days Bonus");
        }
        
        $this->checkAndUnlockBadges($user);
    }

    /**
     * Check and unlock eligible badges for a user
     */
    public function checkAndUnlockBadges(User $user)
    {
        $badges = Badge::all();
        $userPoints = $user->loyalty_points;
        $userLevel = $user->loyalty_level;
        $userStreak = $user->loginStreak ? $user->loginStreak->highest_streak : 0;
        
        foreach ($badges as $badge) {
            // Skip if already unlocked
            if ($user->badges()->where('badge_id', $badge->id)->exists()) {
                continue;
            }

            $unlocked = false;

            switch ($badge->requirement_type) {
                case 'points':
                    if ($userPoints >= $badge->requirement_value) $unlocked = true;
                    break;
                case 'streak':
                    if ($userStreak >= $badge->requirement_value) $unlocked = true;
                    break;
                case 'level':
                    $levelMap = ['Member' => 0, 'Loyal' => 1, 'Premium' => 2, 'VIP' => 3];
                    if (isset($levelMap[$userLevel]) && $levelMap[$userLevel] >= $badge->requirement_value) $unlocked = true;
                    break;
                case 'shop':
                    $activityCount = $user->loyaltyPoints()->where('activity_type', 'shop')->count();
                    if ($activityCount >= $badge->requirement_value) $unlocked = true;
                    break;
            }

            if ($unlocked) {
                $user->userBadges()->create(['badge_id' => $badge->id]);
            }
        }
    }

    /**
     * Redeem a voucher
     */
    public function redeemVoucher(User $user, $voucherId)
    {
        $voucher = \App\Models\Voucher::findOrFail($voucherId);

        // Map fallback for old voucher min_levels to new tier names
        $voucherMinLevel = $voucher->min_level;
        if ($voucherMinLevel === 'Bronze') $voucherMinLevel = 'Member';
        if ($voucherMinLevel === 'Silver') $voucherMinLevel = 'Loyal';
        if ($voucherMinLevel === 'Gold') $voucherMinLevel = 'Premium';
        if ($voucherMinLevel === 'Platinum') $voucherMinLevel = 'VIP';

        $levelMap = ['Member' => 0, 'Loyal' => 1, 'Premium' => 2, 'VIP' => 3];
        if (isset($levelMap[$user->loyalty_level]) && isset($levelMap[$voucherMinLevel])) {
            if ($levelMap[$user->loyalty_level] < $levelMap[$voucherMinLevel]) {
                throw new \Exception("Your loyalty level is not high enough to redeem this voucher.");
            }
        }

        if ($user->loyalty_points < $voucher->points_required) {
            throw new \Exception("Not enough loyalty points.");
        }

        // Deduct points
        LoyaltyPoint::create([
            'user_id' => $user->id,
            'activity_type' => 'redeem',
            'points' => -$voucher->points_required,
            'description' => "Redeemed voucher: " . $voucher->name,
        ]);

        // Add to user vouchers
        $user->userVouchers()->create([
            'voucher_id' => $voucher->id,
            'is_used' => false
        ]);

        return true;
    }
}
