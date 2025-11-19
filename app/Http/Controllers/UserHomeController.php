<?php

namespace App\Http\Controllers;

use App\Models\HairOption;
use Illuminate\View\View;

class UserHomeController extends Controller
{
    public function index(): View
    {
        $lengths = HairOption::where('category', 'length')->pluck('name')->toArray();
        $types = HairOption::where('category', 'type')->pluck('name')->toArray();
        $conditions = HairOption::where('category', 'condition')->pluck('name')->toArray();

        // Fallback jika belum seeding
        $lengths = $lengths ?: ['Pendek', 'Panjang'];
        $types = $types ?: ['Lurus', 'Ikal', 'Bergelombang', 'Keriting'];
        $conditions = $conditions ?: ['Rusak', 'Kering', 'Sehat'];

        return view('user.home', compact('lengths', 'types', 'conditions'));
    }

    public function scan(): View
    {
        $lengths = HairOption::where('category', 'length')->pluck('name')->toArray();
        $types = HairOption::where('category', 'type')->pluck('name')->toArray();
        $conditions = HairOption::where('category', 'condition')->pluck('name')->toArray();

        // Fallback jika belum seeding
        $lengths = $lengths ?: ['Pendek', 'Panjang'];
        $types = $types ?: ['Lurus', 'Ikal', 'Bergelombang', 'Keriting'];
        $conditions = $conditions ?: ['Rusak', 'Kering', 'Sehat'];

        return view('user.scan', compact('lengths', 'types', 'conditions'));
    }
}