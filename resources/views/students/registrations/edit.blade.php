@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Modify Registration - {{ $registration->course->course_code }}: {{ $registration->course->title }}</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('student.registrations.update', $registration) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Select New Course (same semester)</label>
            <select name="course_id" class="form-select" required>
                <option value="">-- Choose course --</option>
                @foreach($available as $c)
                    @php
                        $approved = $c->registrations->where('status','approved')->count();
                        $isFull = $approved >= $c->max_students;
                    @endphp
                    <option value="{{ $c->id }}">{{ $c->course_code }} (Section {{ $c->section }})  - {{ $c->title }} 
                        @if($isFull) 
                            (Full) 
                        @endif
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Registration</button>
        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>

@endsection
