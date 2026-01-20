<!-- resources/views/admin/registrations.blade.php -->
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">📝 Pending Registrations</h2>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Review Course Requests
            </div>
            <div class="card-body">
                @if($registrations->isEmpty())
                    <p class="text-muted">No pending registrations.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Matric No</th>
                                    <th>Course</th>
                                    <th>Date Requested</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registrations as $reg)
                                <tr>
                                    <td>{{ $reg->student->user->name }}</td>
                                    <td>{{ $reg->student->matric_no }}</td>
                                    <td>{{ $reg->course->course_code }} - {{ $reg->course->title }}</td>
                                    <td>{{ $reg->created_at->format('d M Y') }}</td>
                                    <td>
                                        <form action="{{ route('admin.registrations.amend', $reg) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="btn btn-sm btn-success">✅ Approve</button>
                                        </form>
                                        <form action="{{ route('admin.registrations.amend', $reg) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Reject this registration?')">❌ Reject</button>
                                        </form>
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
@endsection
