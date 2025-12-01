<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class StudentRegisterController extends Controller
{
    public function show()
    {
        return view('auth.student-register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|confirmed|min:8',
            'g-recaptcha-response' => 'required|string',
        ]);

        // Server-side verify (v3)
        $resp = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => config('recaptcha.secret_key'),   // <-- from config/recaptcha.php
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ])->json();

        if (!($resp['success'] ?? false) || ($resp['score'] ?? 0) < 0.5 || ($resp['action'] ?? '') !== 'register') {
            return back()->withErrors([
                'g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.',
            ])->withInput();
        }

        $user = User::create([
            'name'     => $validated['first_name'].' '.$validated['last_name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $studentRoleId = Role::where('slug', 'student')->value('id');
        if (!$studentRoleId) {
            return back()->withErrors([
                'role' => 'Student role was not found. Please seed the roles table.',
            ])->withInput();
        }
        $user->roles()->attach($studentRoleId);

        Student::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'user_id'    => $user->id,
        ]);

        Auth::login($user);

        return redirect()->route('student.dashboard')
            ->with('success', 'Registration complete. Welcome!');
    }



}
