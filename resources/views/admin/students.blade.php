@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">🎓 All Students</h2>
        <p class="text-muted">Manage student accounts and their course registrations.</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">📋 Student List</h5>
            </div>
            <div class="card-body p-0">
                @if($students->isEmpty())
                    <div class="alert alert-info m-3">No students found.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th class="ps-3">Name</th>
                                    <th>Matric No</th>
                                    <th>Email</th>
                                    <th>Programme</th>
                                    <th>Year</th>
                                    <th>Courses</th>
                                    <th class="pe-3" style="width: 100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                @php
                                    $regCount = $student->registrations()->where('status', 'approved')->count();
                                    $pendingCount = $student->registrations()->where('status', 'pending')->count();
                                @endphp
                                <tr class="border-bottom">
                                    <td class="ps-3">
                                        <div class="fw-bold">{{ $student->user->name }}</div>
                                        <small class="text-muted">{{ $student->full_name ?? '—' }}</small>
                                    </td>
                                    <td><code>{{ $student->matric_no }}</code></td>
                                    <td>
                                        <a href="mailto:{{ $student->user->email }}" class="text-decoration-none">
                                            {{ $student->user->email }}
                                        </a>
                                    </td>
                                    <td>{{ $student->programme ?? '—' }}</td>
                                    <td>{{ $student->year }}</td>
                                    <td>
                                        @if($regCount > 0 || $pendingCount > 0)
                                            {{ $regCount }}@if($pendingCount > 0) (+{{ $pendingCount }})@endif
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td class="pe-3 text-center">
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#studentModal{{ $student->id }}" title="View and manage registrations">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-eye me-1" viewBox="0 0 16 16">
                                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                            </svg>
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

<!-- Student Details Modals -->
@foreach($students as $student)
<div class="modal fade" id="studentModal{{ $student->id }}" tabindex="-1" aria-labelledby="studentModalLabel{{ $student->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light border-bottom">
                <div>
                    <h5 class="modal-title" id="studentModalLabel{{ $student->id }}">{{ $student->user->name }}</h5>
                    <small class="text-muted">{{ $student->matric_no }}</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted small">MATRIC NO</label>
                            <div class="fw-bold"><code>{{ $student->matric_no }}</code></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">PROGRAMME</label>
                            <div class="fw-bold">{{ $student->programme ?? '—' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">YEAR</label>
                            <div class="fw-bold">Year {{ $student->year }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted small">FULL NAME</label>
                            <div class="fw-bold">{{ $student->full_name ?? '—' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">EMAIL</label>
                            <div><a href="mailto:{{ $student->user->email }}">{{ $student->user->email }}</a></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">SEMESTER</label>
                            <div class="fw-bold">
                                @if($student->semester)
                                    <span class="badge bg-primary">Semester {{ $student->semester }}</span>
                                @else
                                    <span class="badge bg-secondary">Not assigned</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <h6 class="mb-3">📚 Course Registrations</h6>
                @if($student->registrations->isEmpty())
                    <div class="alert alert-info mb-0">No course registrations.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Course</th>
                                    <th>Section</th> 
                                    <th>Semester</th>
                                    <th>Status</th>
                                    <th style="width: 120px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($student->registrations as $reg)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $reg->course->course_code }}</div>
                                        <small class="text-muted">{{ $reg->course->title }}</small>
                                    </td>
                                     <td>
                                        <span class="badge bg-light text-dark">Section {{ $reg->course->section }}</span> <!-- NEW -->
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $reg->course->semester }}</span>
                                    </td>
                                    <td>
                                        @if($reg->status === 'approved')
                                            <span class="badge bg-success">✅ Approved</span>
                                        @elseif($reg->status === 'pending')
                                            <span class="badge bg-warning text-dark">⏳ Pending</span>
                                        @else
                                            <span class="badge bg-secondary">❌ Cancelled</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($reg->status !== 'cancelled')
                                            <form action="{{ route('admin.registrations.amend', $reg) }}" method="POST" class="d-inline" onsubmit="return confirm('Cancel registration for {{ $reg->course->course_code }}?');">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="action" value="reject">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Cancel this registration">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-trash me-1" viewBox="0 0 16 16">
                                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 1a.5.5 0 0 0-.5.5v1h6V1.5a.5.5 0 0 0-.5-.5h-5z"/>
                                                    </svg>
                                                    Cancel
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
