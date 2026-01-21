<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Admin;
use App\Mail\WelcomeEmail;

class AuthController extends Controller
{
    // Redirect after login based on role
    protected function authenticated(Request $request, $user)
    {
        switch ($user->role) {
            case 'student':
                return redirect()->route('student.dashboard');
            case 'lecturer':
                return redirect()->route('lecturer.dashboard');
            case 'admin':
                return redirect()->route('admin.dashboard');
            default:
                return redirect('/'); 
        }
    }
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect based on role
            $role = auth()->user()->role;
            if ($role == 'student') return redirect()->route('student.dashboard');
            if ($role == 'lecturer') return redirect()->route('lecturer.dashboard');
            if ($role == 'admin') return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials',
        ]);
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function registerUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:student,lecturer,admin',
            'matric_no' => 'required_if:role,student|nullable|unique:students,matric_no',
            'programme' => 'required_if:role,student|nullable',
            'year' => 'required_if:role,student|nullable|in:1,2,3,4',
            'staff_no' => 'required_if:role,lecturer|nullable|unique:lecturers,staff_no',
            'department' => 'required_if:role,lecturer|nullable',
        ]);

        DB::beginTransaction();

        try{
            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            // Create role-specific record
            switch ($request->role) {
                case 'student':
                    Student::create([
                        'user_id' => $user->id,
                        'matric_no' => $request->matric_no,
                        'programme' => $request->programme,
                        'year' => $request->year,
                        'full_name' => $user->name, // use user's name
                        'semester' => null, // Will be assigned later by admin
                    ]);
                    break;

                case 'lecturer':
                    Lecturer::create([
                        'user_id' => $user->id,
                        'staff_no' => $request->staff_no,
                        'department' => $request->department,
                        'full_name' => $user->name, // use user's name
                    ]);
                    break;

                case 'admin':
                    Admin::create([
                        'user_id' => $user->id,
                        'full_name' => $user->name,
                    ]);
                    break;
            }
                    
            DB::commit();
        
            // Send welcome email
            try {
                Mail::to($user->email)->send(new WelcomeEmail($user));
                Log::info("Welcome email sent to: " . $user->email);
            } catch (\Exception $e) {
                Log::error("Failed to send welcome email: " . $e->getMessage());
            }
        
            Auth::login($user);

            return match($user->role) {
                'student' => redirect()->route('student.dashboard'),
                'lecturer' => redirect()->route('lecturer.dashboard'),
                'admin' => redirect()->route('admin.dashboard'),
            };

        } catch (\Exception $e) {
            DB::rollBack(); // undo everything if any error occurs
            return back()->withErrors(['error' => 'Registration failed: '.$e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}