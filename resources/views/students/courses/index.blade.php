<!-- resources/views/student/courses/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="my-4">📚 Available Courses</h2>

    <!-- Search and Filter Form -->
    <form method="GET" action="{{ route('student.courses.index') }}" class="mb-4">
        <div class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search by course code or title..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
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

    <!-- Course Cards -->
    <div class="row">
        @forelse($courses as $course)
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $course->course_code }}</h5>
                    <p class="card-text">
                        <strong>{{ $course->title }}</strong><br>
                        Semester: {{ $course->semester }}<br>
                        Credit: {{ $course->credit_hours }} | Max Students: {{ $course->max_students }}<br>
                        <small class="text-muted">Enrolled: {{ $course->registrations->where('status', 'approved')->count() }} / {{ $course->max_students }}</small>
                    </p>

                    @php
                        $registration = $course->registrations->where('student_id', auth()->user()->student->id)->first();
                        $approvedCount = $course->registrations->where('status', 'approved')->count();
                        $isFull = $approvedCount >= $course->max_students;
                    @endphp

                    @if($registration)
                        @if($registration->status === 'approved')
                            <span class="badge bg-success">✅ Approved</span>
                        @elseif($registration->status === 'pending')
                            <span class="badge bg-warning text-dark">⏳ Pending Review</span>
                        @else
                            <span class="badge bg-secondary">❌ Cancelled</span>
                        @endif

                        @if(in_array($registration->status, ['approved', 'pending']))
                            <form action="{{ route('student.cancel.registration', $registration) }}" method="POST" class="mt-2 d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Cancel registration?')">
                                    Cancel
                                </button>
                            </form>
                        @endif
                    @else
                        @if($isFull)
                            <button type="button" class="btn btn-secondary btn-sm" disabled>
                                Course Full
                            </button>
                        @else
                            <form action="{{ route('student.register', $course->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">Register</button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">No courses found.</div>
        </div>
        @endforelse
    </div>
</div>
@endsection