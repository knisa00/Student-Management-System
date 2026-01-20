@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header text-center bg-white">
                <h4>User Registration</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('register.user') }}" id="registrationForm">
                    @csrf

                    <!-- Role Selection -->
                    <div class="mb-3">
                        <label for="role" class="form-label">Register As</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="">-- Select Role --</option>
                            <option value="student">Student</option>
                            <option value="lecturer">Lecturer</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <!-- Common Fields -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required minlength="8">
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    </div>

                    <!-- Student Fields -->
                    <div id="studentFields" class="d-none">
                        <div class="mb-3">
                            <label for="matric_no" class="form-label">Matric Number</label>
                            <input type="text" name="matric_no" id="matric_no" class="form-control" value="{{ old('matric_no') }}">
                        </div>
                        <div class="mb-3">
                            <label for="programme" class="form-label">Programme</label>
                            <input type="text" name="programme" id="programme" class="form-control" value="{{ old('programme') }}">
                        </div>
                        <div class="mb-3">
                            <label for="year" class="form-label">Year of Study</label>
                            <select name="year" id="year" class="form-control">
                                <option value="">-- Select Year --</option>
                                <option value="1">Year 1</option>
                                <option value="2">Year 2</option>
                                <option value="3">Year 3</option>
                                <option value="4">Year 4</option>
                            </select>
                        </div>
                    </div>

                    <!-- Lecturer Fields -->
                    <div id="lecturerFields" class="d-none">
                        <div class="mb-3">
                            <label for="staff_no" class="form-label">Staff Number</label>
                            <input type="text" name="staff_no" id="staff_no" class="form-control" value="{{ old('staff_no') }}">
                        </div>
                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" name="department" id="department" class="form-control" value="{{ old('department') }}">
                        </div>
                    </div>

                    <!-- Admin Fields (none needed) -->
                    <div id="adminFields" class="d-none">
                        <p class="text-muted">Admin account will be created with minimal info.</p>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Register</button>
                    <div class="text-center mt-3">
                        <a href="{{ route('login.form') }}">Already have an account? Login</a>
                    </div>
                </form>

                @if($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('role').addEventListener('change', function() {
    document.getElementById('studentFields').classList.add('d-none');
    document.getElementById('lecturerFields').classList.add('d-none');
    document.getElementById('adminFields').classList.add('d-none');

    const role = this.value;
    if (role === 'student') {
        document.getElementById('studentFields').classList.remove('d-none');
    } else if (role === 'lecturer') {
        document.getElementById('lecturerFields').classList.remove('d-none');
    } else if (role === 'admin') {
        document.getElementById('adminFields').classList.remove('d-none');
    }
});
</script>
@endsection