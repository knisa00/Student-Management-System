@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">📊 Admin Dashboard</h2>
        <p class="text-muted">Manage courses and registrations.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5>Courses</h5>
                <p class="display-4">{{ $courses->count() }}</p>
                <a href="{{ route('admin.courses') }}" class="btn btn-outline-primary">View All</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5>Pending Registrations</h5>
                <p class="display-4">
                    {{ \App\Models\Registration::where('status', 'pending')->count() }}
                </p>
                <a href="{{ route('admin.registrations') }}" class="btn btn-outline-primary">Review</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5>Total Students</h5>
                <p class="display-4">{{ \App\Models\Student::count() }}</p>
                <a href="{{ route('admin.students') }}" class="btn btn-outline-primary">View List</a>
            </div>
        </div>
    </div>
</div>
@endsection