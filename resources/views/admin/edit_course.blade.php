<!-- resources/views/admin/edit_course.blade.php -->
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Edit Course</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.courses.update', $course) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="course_code" class="form-label">Course Code</label>
                        <input type="text" name="course_code" id="course_code" class="form-control" value="{{ $course->course_code }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Course Title</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $course->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="credit_hours" class="form-label">Credit Hours</label>
                        <input type="number" name="credit_hours" id="credit_hours" class="form-control" value="{{ $course->credit_hours }}" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="max_students" class="form-label">Max Students</label>
                        <input type="number" name="max_students" id="max_students" class="form-control" value="{{ $course->max_students }}" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <input type="text" name="semester" id="semester" class="form-control" value="{{ $course->semester }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="lecturer_id" class="form-label">Assign Lecturer</label>
                        <select name="lecturer_id" id="lecturer_id" class="form-control">
                            <option value="">Select Lecturer</option>
                            @foreach($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}" {{ $course->lecturer_id == $lecturer->id ? 'selected' : '' }}>
                                    {{ $lecturer->staff_no }} - {{ $lecturer->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update Course</button>
                    <a href="{{ route('admin.courses') }}" class="btn btn-secondary mt-2 w-100">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection