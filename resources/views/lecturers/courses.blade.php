@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">My Assigned Courses</h2>
    </div>
</div>

<form method="GET" action="{{ route('lecturer.courses') }}" class="mb-4">
    <div class="row g-2">
        <div class="col-md-4">
            <select name="semester" class="form-select">
                <option value="">All Semesters</option>
                @for($i = 1; $i <= 8; $i++)
                    <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>
                        Semester {{ $i }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-outline-secondary w-100">Filter</button>
        </div>
    </div>
</form>

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
                    <h5 class="card-title">
                        {{ $course->course_code }} (Section {{ $course->section }})
                    </h5>
                    <p class="card-text">
                        <strong>{{ $course->title }}</strong><br>
                        Semester: {{ $course->semester }}<br>
                        Enrolled: {{ $course->enrolled_count }} / {{ $course->max_students }}
                    </p>
                    <a href="{{ route('lecturer.course.students', $course) }}"
                       class="btn btn-sm btn-primary">
                        View Students
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection
