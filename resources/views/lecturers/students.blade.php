<!-- resources/views/lecturers/students.blade.php -->
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">👥 Students in {{ $course->course_code }} (Section {{ $course->section }}) - {{ $course->title }}</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                @if($course->registrations->isEmpty())
                    <p class="text-center text-muted">No students registered for this course.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Matric No</th>
                                    <th>Full Name</th>
                                    <th>Programme</th>
                                    <th>Year</th>
                                    <th>Status</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($course->registrations as $reg)
                                <tr>
                                    <td>{{ $reg->student->matric_no }}</td>
                                    <td>{{ $reg->student->full_name }}</td>
                                    <td>{{ $reg->student->programme ?? 'N/A' }}</td>
                                    <td>Year {{ $reg->student->year }}</td>
                                    <td>
                                        @if($reg->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($reg->status === 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-secondary">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Button to show full details -->
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#studentModal{{ $reg->student->id }}">
                                            View
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- All Modals (Outside Table) -->
@foreach($course->registrations as $reg)
<div class="modal fade" id="studentModal{{ $reg->student->id }}" tabindex="-1" aria-labelledby="studentModalLabel{{ $reg->student->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentModalLabel{{ $reg->student->id }}">Student Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Matric No:</strong> {{ $reg->student->matric_no }}</p>
                <p><strong>Full Name:</strong> {{ $reg->student->full_name }}</p>
                <p><strong>Programme:</strong> {{ $reg->student->programme ?? 'N/A' }}</p>
                <p><strong>Year:</strong> Year {{ $reg->student->year }}</p>
                <p><strong>Email:</strong> {{ $reg->student->user->email }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection