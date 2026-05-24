<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function show()
    {
        $classes = SchoolClass::orderBy('name')->get();
        return view('auth.register', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:student,teacher',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        // Create profile based on role
        if ($request->role === 'student') {
            $request->validate([
                'student_id' => 'required|string|unique:student_profiles,student_id',
                'class_id'   => 'required|exists:classes,id',
            ]);

            StudentProfile::create([
                'user_id'    => $user->id,
                'student_id' => $request->student_id,
                'class_id'   => $request->class_id,
            ]);
        }

        if ($request->role === 'teacher') {
            $request->validate([
                'teacher_id'     => 'required|string|unique:teacher_profiles,teacher_id',
                'employee_email' => 'required|email',
            ]);

            TeacherProfile::create([
                'user_id'        => $user->id,
                'teacher_id'     => $request->teacher_id,
                'employee_email' => $request->employee_email,
            ]);
        }

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Welcome to SmartWorkshop!');
    }
}