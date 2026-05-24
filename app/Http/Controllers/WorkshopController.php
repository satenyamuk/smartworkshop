<?php

namespace App\Http\Controllers;

use App\Models\Workshop;
use App\Models\WorkshopCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkshopController extends Controller
{
    public function index(Request $request)
    {
        $categories = WorkshopCategory::all();

        $query = Workshop::with(['instructor', 'category'])
            ->where('status', 'published');

        // Filter by audience based on role
        if (Auth::check()) {
            $role = Auth::user()->role;
            if ($role === 'student') {
                $query->whereIn('audience', ['student', 'public']);
            } elseif ($role === 'teacher') {
                $query->whereIn('audience', ['teacher', 'public']);
            }
        } else {
            $query->where('audience', 'public');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by price
        if ($request->filled('price')) {
            if ($request->price === 'free') {
                $query->where('price', 0);
            } elseif ($request->price === 'paid') {
                $query->where('price', '>', 0);
            }
        }

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $workshops = $query->orderBy('start_at')->paginate(9);

        return view('workshops.index', compact('workshops', 'categories'));
    }

    public function show(Workshop $workshop)
    {
        // Check audience access
        if (Auth::check()) {
            $role = Auth::user()->role;
            if ($workshop->audience === 'student' && $role !== 'student') {
                abort(403, 'This workshop is for students only.');
            }
            if ($workshop->audience === 'teacher' && $role !== 'teacher') {
                abort(403, 'This workshop is for teachers only.');
            }
        } else {
            if ($workshop->audience !== 'public') {
                return redirect()->route('login')->with('error', 'Please login to view this workshop.');
            }
        }

        $workshop->load(['instructor', 'category']);

        return view('workshops.show', compact('workshop'));
    }
}