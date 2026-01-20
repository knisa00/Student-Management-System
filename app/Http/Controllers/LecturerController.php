<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lecturer;
use App\Models\Course;

class LecturerController extends Controller
{
    public function dashboard()
    {
        $lecturer = auth()->user()->lecturer;
        $courses = $lecturer->courses()->withCount(['registrations as enrolled_count' => function ($query) {
            $query->where('status', 'approved');
        }])->get();

        return view('lecturers.dashboard', compact('lecturer', 'courses'));
    }

    public function courseStudents(Course $course)
    {
        $course->load(['registrations' => function ($query) {
            $query->where('status', 'approved')->with('student');
        }]);
        
        return view('lecturers.students', compact('course'));
    }

    public function courses(Request $request)
    {
        $lecturer = auth()->user()->lecturer;

        if ($lecturer->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $query = $lecturer->courses()->withCount(['registrations as enrolled_count' => function ($q) {
            $q->where('status', 'approved');
        }]);

        // Add semester filter
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        $courses = $query->get();
        
        // Provide semesters 1-8 for dropdown
        $semesters = range(1, 8);

        return view('lecturers.courses', compact('courses', 'semesters'));
    }
}