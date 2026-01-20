<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Course;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard()
    {
        $student = auth()->user()->student;
    
        if ($student->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $registrations = $student->registrations()->with('course')->get();
        return view('students.dashboard', compact('student','registrations'));
    }

    public function registerCourse(Request $request, $course_id)
    {
        $student = Auth::user()->student;

        $course = Course::findOrFail($course_id);

        // Auto approve if max_students not reached
        $count = $course->registrations()->where('status','approved')->count();
        $status = $count < $course->max_students ? 'approved' : 'pending';

        Registration::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => $status
        ]);

        return redirect()->back()->with('success', 'Course registered successfully');
    }

    public function cancelRegistration($registration_id)
    {
        $registration = Registration::findOrFail($registration_id);
        $registration->update(['status'=>'cancelled']);
        return redirect()->back()->with('success','Registration cancelled');
    }
}