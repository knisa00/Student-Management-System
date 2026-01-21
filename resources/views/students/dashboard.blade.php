@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">👋 Hi, {{$student->user->name }}!</h2>
        <p class="text-muted">Welcome to your student dashboard.</p>
        
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">📚 Current Semester Courses (Semester {{ $student->semester ?? 1 }})</h5>
            </div>
            <div class="card-body">
                @if($currentRegistrations->isEmpty())
                    <p class="text-center text-muted">No courses registered for current semester.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Course Code</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($currentRegistrations as $reg)
                                <tr>
                                    <td>{{ $reg->course->course_code }}</td>
                                    <td>{{ $reg->course->title }}</td>
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
                                        @if(in_array($reg->status, ['approved','pending']))
                                            <a href="{{ route('student.registrations.edit', $reg) }}" class="btn btn-sm btn-outline-primary me-1">Modify</a>
                                            <form action="{{ route('student.cancel.registration', $reg) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Cancel this registration?')">
                                                    Cancel
                                                </button>
                                            </form>
                                        @endif
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

@if(!$previousRegistrations->isEmpty())
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">📖 Previous Semester Courses (Semester {{ max(1, ($student->semester ?? 1) - 1) }})</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($previousRegistrations as $reg)
                            <tr>
                                <td>{{ $reg->course->course_code }}</td>
                                <td>{{ $reg->course->title }}</td>
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
                                    @if(in_array($reg->status, ['approved','pending']))
                                        <a href="{{ route('student.registrations.edit', $reg) }}" class="btn btn-sm btn-outline-primary me-1">Modify</a>
                                        <form action="{{ route('student.cancel.registration', $reg) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Cancel this registration?')">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if(!$otherRegistrations->isEmpty())
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">📅 Other Semesters</h5>
                    <form method="GET" action="{{ route('student.dashboard') }}" class="d-flex gap-2" id="semesterFilter">
                        <select name="view_semester" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                            <option value="">All Other Semesters</option>
                            @foreach($otherSemesters as $sem)
                                <option value="{{ $sem }}" {{ request('view_semester') == $sem ? 'selected' : '' }}>Semester {{ $sem }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @php
                    $filteredOther = $otherRegistrations;
                    if (request('view_semester')) {
                        $filteredOther = $otherRegistrations->filter(function($reg) {
                            return (int)$reg->course->semester === (int)request('view_semester');
                        });
                    }
                    // Show approved only
                    $filteredOther = $filteredOther->filter(function($reg) {
                        return $reg->status === 'approved';
                    });
                @endphp
                
                @if($filteredOther->isEmpty())
                    <p class="text-center text-muted">No approved courses in selected semester.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Course Code</th>
                                    <th>Semester</th>
                                    <th>Title</th>
                                    <th>Credit Hours</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($filteredOther as $reg)
                                <tr>
                                    <td>{{ $reg->course->course_code }}</td>
                                    <td>{{ $reg->course->semester }}</td>
                                    <td>{{ $reg->course->title }}</td>
                                    <td>{{ $reg->course->credit_hours }}</td>
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
@endif
    </div>
</div>
@endsection