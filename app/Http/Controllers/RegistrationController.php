<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Course;
use App\Models\Registration;
use App\Models\Notification;
use App\Mail\RegistrationApproved;
use App\Mail\RegistrationCancelled;

class RegistrationController extends Controller
{
    public function store(Request $request, Course $course)
    {
        if (!auth()->user()->student) {
            return back()->withErrors('Only students can register for courses.');
        }

        $student = auth()->user()->student;

        if ($student->registrations()->where('course_id', $course->id)->exists()) {
            return back()->withErrors('You are already registered for this course.');
        }

        $currentCount = $course->registrations()->where('status', 'approved')->count();

        if ($currentCount >= $course->max_students) {
            return back()->withErrors('Course is already full.');
        }

        $status = 'approved';

        $registration = Registration::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => $status
        ]);

        Notification::create([
            'user_id' => $student->user_id,
            'type' => 'student',
            'message' => "Your registration for {$course->course_code} has been approved.",
        ]);

        try {
            Mail::to(auth()->user()->email)->send(new RegistrationApproved($course));
            Log::info("Approval email sent to: " . auth()->user()->email);
        } catch (\Exception $e) {
            Log::error("Failed to send approval email: " . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Registration approved successfully!');
    }

    // Show form to modify a registration (change to another course)
    public function edit(Registration $registration)
    {
        $user = auth()->user();
        if (!$user || !$user->student) {
            return redirect()->back()->withErrors('Unauthorized action.');
        }

        $student = $user->student;

        if ($registration->student_id !== $student->id) {
            return redirect()->back()->withErrors('Unauthorized action.');
        }

        // Only allow modification for pending or approved registrations
        if (!in_array($registration->status, ['pending', 'approved'])) {
            return redirect()->back()->withErrors('This registration cannot be modified.');
        }

        // Offer courses in the same semester as the student's semester
        $semester = $student->semester ?? $registration->course->semester;
        $available = Course::where('semester', $semester)
            ->where('id', '!=', $registration->course_id)
            ->get()
            ->filter(function($c) use ($student) {
                // exclude courses the student is already registered for
                return !$student->registrations()->where('course_id', $c->id)->exists();
            });

        return view('students.registrations.edit', compact('registration', 'available'));
    }

    // Update registration to switch to another course
    public function update(Request $request, Registration $registration)
    {
        $user = auth()->user();
        if (!$user || !$user->student) {
            return redirect()->back()->withErrors('Unauthorized action.');
        }

        $student = $user->student;

        if ($registration->student_id !== $student->id) {
            return redirect()->back()->withErrors('Unauthorized action.');
        }

        $data = $request->validate([
            'course_id' => 'required|integer|exists:courses,id'
        ]);

        $newCourse = Course::findOrFail($data['course_id']);

        // Prevent selecting the same course
        if ($newCourse->id === $registration->course_id) {
            return redirect()->back()->withErrors('Please choose a different course.');
        }

        // Ensure same semester (business rule)
        $origSemester = $registration->course->semester;
        if ($newCourse->semester != $origSemester) {
            return redirect()->back()->withErrors('You can only switch to courses within the same semester.');
        }

        // Prevent duplicate registration
        if ($student->registrations()->where('course_id', $newCourse->id)->exists()) {
            return redirect()->back()->withErrors('You are already registered for the selected course.');
        }

        // Determine status based on capacity
        $approvedCount = $newCourse->registrations()->where('status', 'approved')->count();
        $status = $approvedCount < $newCourse->max_students ? 'approved' : 'pending';

        $registration->update([
            'course_id' => $newCourse->id,
            'status' => $status
        ]);

        Notification::create([
            'user_id' => $user->id,
            'type' => 'student',
            'message' => "Your registration has been updated to {$newCourse->course_code} ({$status}).",
        ]);

        try {
            if ($status === 'approved') {
                Mail::to($user->email)->send(new RegistrationApproved($newCourse));
            }
            Log::info('Registration updated for user: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Failed to send registration update email: ' . $e->getMessage());
        }

        return redirect()->route('student.dashboard')->with('success', 'Registration updated successfully.');
    }

    public function destroy(Registration $registration)
    {
        // Get the authenticated student
        $user = auth()->user();
        if (!$user || !$user->student) {
            return redirect()->back()->withErrors('Unauthorized action.');
        }

        $student = $user->student;

        // Verify the registration belongs to this student
        if ($registration->student_id !== $student->id) {
            return redirect()->back()->withErrors('Unauthorized action.');
        }

        // Only allow cancellation of pending or approved registrations
        if (!in_array($registration->status, ['pending', 'approved'])) {
            return redirect()->back()->withErrors('Cannot cancel an already cancelled registration.');
        }

        $registration->update(['status' => 'cancelled']);

        Notification::create([
            'user_id' => $user->id,
            'type' => 'student',
            'message' => "Registration for {$registration->course->course_code} has been cancelled.",
        ]);

        // Send cancellation email
        try {
            Mail::to($user->email)->send(new RegistrationCancelled($registration->course));
            Log::info("Cancellation email sent to: " . $user->email);
        } catch (\Exception $e) {
            Log::error("Failed to send cancellation email: " . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Registration cancelled successfully.');
    }
}
