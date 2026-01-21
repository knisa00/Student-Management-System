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

class AdminController extends Controller
{
    public function dashboard()
    {
        $courses = Course::all();
        return view('admin.dashboard', compact('courses'));
    }

    public function courses()
    {
        $courses = Course::with('lecturer.user')->get();
        $lecturers = \App\Models\Lecturer::with('user')->get();
        return view('admin.courses', compact('courses', 'lecturers'));
    }

    public function students()
    {
        $students = \App\Models\Student::with('user')->get();
        return view('admin.students', compact('students'));
    }

    public function registrations()
    {
        $registrations = \App\Models\Registration::where('status', 'pending')->with(['student.user', 'course'])->get();
        return view('admin.registrations', compact('registrations'));
    }

    // Store new course
    public function store(Request $request)
    {
        $request->validate([
            'course_code' => 'required|unique:courses',
            'name' => 'required',
            'credit_hours' => 'required|integer|min:1',
            'max_students' => 'required|integer|min:1',
            'semester' => 'required',
            'lecturer_id' => 'nullable|exists:lecturers,id'
        ]);

        Course::create([
            'course_code' => $request->course_code,
            'title' => $request->name,
            'credit_hours' => $request->credit_hours,
            'max_students' => $request->max_students,
            'semester' => $request->semester,
            'lecturer_id' => $request->lecturer_id
        ]);

        return redirect()->route('admin.courses')->with('success', 'Course added successfully!');
    }

    // Show edit form
    public function edit(Course $course)
    {
        $lecturers = \App\Models\Lecturer::with('user')->get();
        return view('admin.edit_course', compact('course', 'lecturers'));
    }

    // Update course
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'course_code' => 'required|unique:courses,course_code,' . $course->id,
            'name' => 'required',
            'credit_hours' => 'required|integer|min:1',
            'max_students' => 'required|integer|min:1',
            'semester' => 'required',
            'lecturer_id' => 'nullable|exists:lecturers,id'
        ]);

        $course->update([
            'course_code' => $request->course_code,
            'section' => $request->section,
            'title' => $request->name,
            'credit_hours' => $request->credit_hours,
            'max_students' => $request->max_students,
            'semester' => $request->semester,
            'lecturer_id' => $request->lecturer_id
        ]);

        return redirect()->route('admin.courses')->with('success', 'Course updated successfully!');
    }

    // Delete course
    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses')->with('success', 'Course deleted successfully!');
    }

    // Approve/Reject/Cancel pending registrations
    public function amendRegistration(Registration $reg, Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject'
        ]);

        $course = $reg->course;
        $student = $reg->student;

        if ($request->action === 'approve') {
            $approvedCount = $course->registrations()->where('status', 'approved')->count();

            // Check if course is full
            if ($approvedCount >= $course->max_students) {
                return redirect()->back()->with('error', "Cannot approve: {$course->course_code} is at capacity ({$approvedCount}/{$course->max_students}). Registration remains pending.");
            }

            // Approve the registration
            $reg->update(['status' => 'approved']);

            // Create notification for student
            Notification::create([
                'user_id' => $student->user->id,
                'type' => 'student',
                'message' => "Your registration for {$course->course_code} has been approved by admin.",
            ]);

            // Queue approval email to student
            try {
                Mail::to($student->user->email)->queue(new RegistrationApproved($course));
                Log::info("Approval email queued for: " . $student->user->email);
            } catch (\Exception $e) {
                Log::error("Failed to queue approval email: " . $e->getMessage());
            }

            $message = "✅ Registration approved for {$student->user->name} - {$course->course_code}";

        } else {
            // Cancel the registration
            $reg->update(['status' => 'cancelled']);

            // Create notification for student
            Notification::create([
                'user_id' => $student->user->id,
                'type' => 'student',
                'message' => "Your registration for {$course->course_code} has been cancelled by admin.",
            ]);

            // Queue cancellation email
            try {
                Mail::to($student->user->email)->queue(new RegistrationCancelled($course));
                Log::info("Cancellation email queued for: " . $student->user->email);
            } catch (\Exception $e) {
                Log::error("Failed to queue cancellation email: " . $e->getMessage());
            }

            $message = "❌ Registration cancelled for {$student->user->name} - {$course->course_code}";
        }

        return redirect()->back()->with('success', $message);
    }
}