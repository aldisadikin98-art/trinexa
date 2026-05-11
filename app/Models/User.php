<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'phone', 'address', 'gender', 'birth_date', 'avatar'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────────

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function faceScanHistories()
    {
        return $this->hasMany(FaceScanHistory::class);
    }

    public function chatSessions()
    {
        return $this->hasMany(ChatSession::class);
    }

    public function faceScanResults()
    {
        return $this->hasMany(FaceScanResult::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(UserBookmark::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function savingGoals()
    {
        return $this->hasMany(SavingGoal::class);
    }

    public function rewardPoints()
    {
        return $this->hasMany(RewardPoint::class);
    }

    public function skinQuestionnaires()
    {
        return $this->hasMany(SkinQuestionnaire::class);
    }

    public function skinProgresses()
    {
        return $this->hasMany(UserSkinProgress::class);
    }

    public function loyaltyPoints()
    {
        return $this->hasMany(LoyaltyPoint::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withPivot('unlocked_at')->withTimestamps();
    }

    public function userBadges()
    {
        return $this->hasMany(UserBadge::class);
    }

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'user_vouchers')->withPivot('is_used', 'used_at')->withTimestamps();
    }

    public function userVouchers()
    {
        return $this->hasMany(UserVoucher::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function shopVoucherUsages()
    {
        return $this->hasMany(ShopVoucherUsage::class);
    }

    public function loginStreak()
    {
        return $this->hasOne(LoginStreak::class);
    }

    // ─── Helpers ───────────────────────────────────────────────────

    public function getTotalRewardPointsAttribute(): int
    {
        $earned = $this->rewardPoints()->where('type', 'earn')->sum('points');
        $redeemed = $this->rewardPoints()->where('type', 'redeem')->sum('points');
        return $earned - $redeemed;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function getLoyaltyPointsAttribute(): int
    {
        return $this->loyaltyPoints()->sum('points');
    }

    public function getLoyaltyLevelAttribute(): string
    {
        $transactionCount = $this->transactions()->where('status', 'selesai')->count();

        if ($transactionCount >= 25) return 'VIP';
        if ($transactionCount >= 10) return 'Premium';
        if ($transactionCount >= 3) return 'Loyal';
        return 'Member';
    }

    public function kareblaRedemptions()
    {
        return $this->hasMany(KareblaRedemption::class);
    }
}
