<!-- resources/views/students/courses/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $course->course_code }} - {{ $course->title }}</h5>
                <a href="{{ route('student.courses.index') }}" class="btn btn-sm btn-outline-secondary">Back to Courses</a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Credits:</strong> {{ $course->credit_hours }}</p>
                        <p><strong>Semester:</strong> {{ $course->semester }}</p>
                        <p><strong>Max Students:</strong> {{ $course->max_students }}</p>
                    </div>
                    <div class="col-md-6">
                        @php
                            $registration = $course->registrations->where('student_id', auth()->user()->student->id)->first();
                        @endphp

                        @if($registration)
                            <p><strong>Status:</strong>
                                @if($registration->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($registration->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-secondary">Cancelled</span>
                                @endif
                            </p>
                        @else
                            <p><strong>Status:</strong> Not Registered</p>
                        @endif
                    </div>
                </div>

                <!-- Register/Cancel Button -->
                <div class="mt-3">
                    @if($registration)
                        @if(in_array($registration->status, ['approved', 'pending']))
                            <form action="{{ route('cancel.registration', $registration) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Cancel this registration?')">
                                    Cancel Registration
                                </button>
                            </form>
                        @endif
                    @else
                        <form action="{{ route('register', $course) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">Register for This Course</button>
                        </form>
                    @endif
                </div>

                <!-- Current Enrolled Students (Optional) -->
                <div class="mt-4">
                    <h6>Enrolled Students ({{ $course->registrations->where('status', 'approved')->count() }} / {{ $course->max_students }})</h6>
                    <ul class="list-group">
                        @forelse($course->registrations->where('status', 'approved') as $reg)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $reg->student->full_name }}
                                <span class="badge bg-primary rounded-pill">{{ $reg->student->matric_no }}</span>
                            </li>
                        @empty
                            <li class="list-group-item">No students enrolled yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection