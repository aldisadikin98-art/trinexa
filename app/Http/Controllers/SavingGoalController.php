<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SavingGoal;
use App\Models\Product;

class SavingGoalController extends Controller
{
    public function index(Request $request)
    {
        $goals = $request->user()->savingGoals()->orderBy('created_at', 'desc')->get();
        return view('user.saving-goals.index', compact('goals'));
    }

    public function create()
    {
        $products = Product::all();
        return view('user.saving-goals.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'product_id' => 'nullable|exists:products,id',
            'deadline' => 'nullable|date|after:today'
        ]);

        $request->user()->savingGoals()->create($request->all());

        return redirect()->route('user.saving-goals.index')->with('success', 'Target menabung berhasil dibuat!');
    }
}
