<!-- resources/views/welcome.blade.php -->
@extends('layouts.app')

@section('title', 'Welcome - Student Management System')

@section('content')
<div class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="text-center p-4" style="max-width: 600px;">
        <div class="mb-4">
            <div class="bg-primary bg-opacity-10 d-inline-block p-3 rounded-circle mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-mortarboard text-primary" viewBox="0 0 16 16">
                    <path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917L7.5 7.028V13h1v-5.972l7.184.564a.5.5 0 0 0 .025-.917l-7.5-3.5a.5.5 0 0 0-.422 0z"/>
                </svg>
            </div>
        </div>

        <h1 class="display-5 fw-bold text-dark mb-3">Student Management System</h1>
        <p class="lead text-muted mb-4">
            A clean, intuitive platform for students, lecturers, and administrators to manage course registration and academic records.
        </p>

        @guest
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 py-2">
                Get Started
            </a>
        @else
            @if(auth()->user()->role === 'student')
                <a href="{{ route('student.dashboard') }}" class="btn btn-primary btn-lg px-4 py-2">Go to Dashboard</a>
            @elseif(auth()->user()->role === 'lecturer')
                <a href="{{ route('lecturer.courses') }}" class="btn btn-primary btn-lg px-4 py-2">My Courses</a>
            @elseif(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-lg px-4 py-2">Admin Panel</a>
            @endif
        @endguest

        <div class="mt-5 pt-4 border-top border-light">
            <p class="text-muted small">
                © {{ date('Y') }} UTM Student Management System. All rights reserved.
            </p>
        </div>
    </div>
</div>
@endsection