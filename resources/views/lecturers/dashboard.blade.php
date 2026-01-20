@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Lecturer Dashboard</h2>
        <p class="text-muted">Welcome, {{ $lecturer->full_name }}!</p>
    </div>
</div>

@if($courses->isEmpty())
    <div class="alert alert-info">
        You are not assigned to any courses yet.
    </div>
@else
    <div class="row">
        @foreach($courses as $course)
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $course->course_code }}</h5>
                    <p class="card-text">
                        <strong>{{ $course->title }}</strong><br>
                        Semester: {{ $course->semester }}<br>
                        Enrolled: {{ $course->enrolled_count }} / {{ $course->max_students }}
                    </p>
                    <div class="mt-3">
                        <a href="{{ route('lecturer.course.students', $course) }}" class="btn btn-sm btn-primary">
                            View Students
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection