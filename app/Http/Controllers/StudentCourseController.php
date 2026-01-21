<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class StudentCourseController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user()->student;
        $query = Course::query();

        // If semester is selected in filter, use that; otherwise use student's semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        } else {
            if ($student->semester) {
                // Optionally include previous semester when requested
                if ($request->boolean('include_previous')) {
                    $prev = max(1, intval($student->semester) - 1);
                    $query->whereIn('semester', [$student->semester, $prev]);
                } else {
                    $query->where('semester', $student->semester);
                }
            }
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('course_code', 'LIKE', "%{$request->search}%")
                ->orWhere('title', 'LIKE', "%{$request->search}%");
            });
        }

        $courses = $query->get();
        $semesters = range(1, 8);

        return view('students.courses.index', compact('courses', 'semesters'));
    }

    public function show(Course $course)
    {
        return view('students.courses.show', compact('course'));
    }
}