<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Course;
use App\Models\Registration;
use App\Models\Notification;
use App\Mail\RegistrationApproved;

class RegistrationController extends Controller
{
    public function store(Request $request, Course $course)
    {
        // Ensure user is a student
        if (!auth()->user()->student) {
            return back()->withErrors('Only students can register for courses.');
        }

        $student = auth()->user()->student;

        // Check if already registered (any status)
        if ($student->registrations()->where('course_id', $course->id)->exists()) {
            return back()->withErrors('You are already registered for this course.');
        }

        // Count approved registrations for this course
        $currentCount = $course->registrations()->where('status', 'approved')->count();

        // Check if course is full
        if ($currentCount >= $course->max_students) {
            return back()->withErrors('Course is already full.');
        }

        // Auto-approve if course not full
        $status = 'approved';

        // Create registration
        $registration = Registration::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => $status
        ]);

        // Create in-app notification
        Notification::create([
            'user_id' => $student->user_id,
            'type' => 'student',
            'message' => "Your registration for {$course->course_code} has been approved.",
        ]);

        // Send approval email
        try {
            Mail::to(auth()->user()->email)->send(new RegistrationApproved($course));
            Log::info("Approval email sent to: " . auth()->user()->email);
        } catch (\Exception $e) {
            Log::error("Failed to send approval email: " . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Registration approved successfully!');
    }

    public function destroy(Registration $registration)
    {
        // Only allow student to cancel their own registration
        if ($registration->student_id !== auth()->user()->student->id) {
            return redirect()->back()->withErrors('Unauthorized action.');
        }

        $registration->update(['status' => 'cancelled']);

        // Optional: add notification
        Notification::create([
            'user_id' => auth()->user()->id,
            'type' => 'student',
            'message' => "Registration for {$registration->course->course_code} has been cancelled.",
        ]);

        return redirect()->back()->with('success', 'Registration cancelled.');
    }
}
