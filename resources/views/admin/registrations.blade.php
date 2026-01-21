<!-- resources/views/admin/registrations.blade.php -->
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">📝 Pending Registrations Review</h2>
        <p class="text-muted">Review and approve or cancel student course registrations.</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pending Course Requests</h5>
                    <span class="badge bg-warning">{{ $registrations->count() }} Pending</span>
                </div>
            </div>
            <div class="card-body">
                @if($registrations->isEmpty())
                    <div class="alert alert-info" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-info-circle me-2" viewBox="0 0 16 16" style="vertical-align: -3px;">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533l1.48-6.677c.067-.378.112-.591.18-.591.061 0 .147.016.31.05l.599-.6c.072-.01.147-.016.203-.016.226 0 .185.04-.443.1z"/>
                        </svg>
                        No pending registrations to review.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Student Name</th>
                                    <th>Matric No</th>
                                    <th>Course</th>
                                    <th>Enrollment</th>
                                    <th>Requested</th>
                                    <th style="width: 200px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registrations as $reg)
                                <tr>
                                    <td>
                                        <strong>{{ $reg->student->user->name }}</strong>
                                    </td>
                                    <td>{{ $reg->student->matric_no }}</td>
                                    <td>
                                        <div>
                                            <strong>{{ $reg->course->course_code }}</strong><br>
                                            <small class="text-muted">{{ $reg->course->title }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $approved = $reg->course->registrations()->where('status', 'approved')->count();
                                            $max = $reg->course->max_students;
                                            $available = $max - $approved;
                                        @endphp
                                        <span class="badge @if($available <= 0) bg-danger @elseif($available <= 2) bg-warning @else bg-success @endif">
                                            {{ $approved }}/{{ $max }}
                                            @if($available <= 0)
                                                (FULL)
                                            @elseif($available == 1)
                                                (1 slot)
                                            @else
                                                ({{ $available }} slots)
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $reg->course->course_code }}</strong> 
                                            <small class="text-muted">Section {{ $reg->course->section }}</small> <!-- NEW -->
                                            <br>
                                            <small class="text-muted">{{ $reg->course->title }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $reg->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <form action="{{ route('admin.registrations.amend', $reg) }}" method="POST" class="d-inline" title="Approve this registration">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit" class="btn btn-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-check-circle me-1" viewBox="0 0 16 16">
                                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                        <path d="m10.97 4.97-.02.02-3.6 3.85-1.74-1.885a.5.5 0 0 0-.712.712l2.55 2.75a.5.5 0 0 0 .74-.037l4.005-4.287a.5.5 0 0 0-.738-.847z"/>
                                                    </svg>
                                                    Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.registrations.amend', $reg) }}" method="POST" class="d-inline" title="Cancel this registration" onsubmit="return confirm('Are you sure you want to cancel this registration?\n\nStudent: {{ $reg->student->user->name }}\nCourse: {{ $reg->course->course_code }}');">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="action" value="reject">
                                                <button type="submit" class="btn btn-danger">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-x-circle me-1" viewBox="0 0 16 16">
                                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708l2.647-2.646-2.647-2.646a.5.5 0 0 1 0-.708z"/>
                                                    </svg>
                                                    Cancel
                                                </button>
                                            </form>
                                        </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
