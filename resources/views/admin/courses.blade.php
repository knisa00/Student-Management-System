<!-- resources/views/admin/courses.blade.php -->
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">📚 Course Management</h2>
    </div>
</div>

<!-- Add New Course Form -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Add New Course</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.courses.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="course_code" class="form-label">Course Code</label>
                        <input type="text" name="course_code" id="course_code" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Course Title</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="credit_hours" class="form-label">Credit Hours</label>
                        <input type="number" name="credit_hours" id="credit_hours" class="form-control" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="max_students" class="form-label">Max Students</label>
                        <input type="number" name="max_students" id="max_students" class="form-control" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <input type="text" name="semester" id="semester" class="form-control" required placeholder="e.g., Semester 1">
                    </div>
                    <div class="mb-3">
                        <label for="lecturer_id" class="form-label">Assign Lecturer</label>
                        <select name="lecturer_id" id="lecturer_id" class="form-control">
                            <option value="">Select Lecturer</option>
                            @foreach($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}">{{ $lecturer->staff_no }} - {{ $lecturer->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add Course</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Course List -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">All Courses</h5>
            </div>
            <div class="card-body">
                @if($courses->isEmpty())
                    <p class="text-muted">No courses found.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Title</th>
                                    <th>Credit Hours</th>
                                    <th>Max Students</th>
                                    <th>Semester</th>
                                    <th>Lecturer</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courses as $course)
                                <tr>
                                    <td>{{ $course->course_code }}</td>
                                    <td>{{ $course->title }}</td>
                                    <td>{{ $course->credit_hours }}</td>
                                    <td>{{ $course->max_students }}</td>
                                    <td>{{ $course->semester }}</td>
                                    <td>{{ $course->lecturer ? $course->lecturer->user->name : 'Unassigned' }}</td>
                                    <td>
                                        <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Delete this course?')">
                                                Delete
                                            </button>
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