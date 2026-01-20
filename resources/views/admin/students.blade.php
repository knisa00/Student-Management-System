<!-- resources/views/admin/students.blade.php -->
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">🎓 All Students</h2>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                All Students
            </div>
            <div class="card-body">
                @if($students->isEmpty())
                    <p class="text-muted">No students found.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Matric No</th>
                                    <th>Programme</th>
                                    <th>Year</th>
                                    <th>Account Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                <tr>
                                    <td>{{ $student->user->name }}</td>
                                    <td>{{ $student->matric_no }}</td>
                                    <td>{{ $student->programme }}</td>
                                    <td>Year {{ $student->year }}</td>
                                    <td>{{ $student->created_at->format('d M Y') }}</td>
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
