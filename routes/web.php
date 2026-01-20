<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\RegistrationController;    
use App\Http\Controllers\ProfileController;        

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/login', [AuthController::class, 'loginForm'])->name('login.form');
Route::post('/login',[AuthController::class,'login'])->name('login');

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'registerUser'])->name('register.user');

Route::post('/logout',[AuthController::class,'logout'])->name('logout');

// Student routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses.index');    
    Route::get('/courses/{course}', [StudentCourseController::class, 'show'])->name('courses.show');
    Route::post('/register/{course}', [RegistrationController::class, 'store'])->name('register');
    Route::delete('/cancel/{registration}', [RegistrationController::class, 'destroy'])->name('cancel.registration');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Lecturer routes 
Route::middleware(['auth', 'role:lecturer'])->prefix('lecturer')->name('lecturer.')->group(function () {
    Route::get('/dashboard', [LecturerController::class, 'dashboard'])->name('dashboard');
    Route::get('/courses', [LecturerController::class, 'courses'])->name('courses');
    Route::get('/course/{course}/students', [LecturerController::class, 'courseStudents'])->name('course.students');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/courses', [AdminController::class, 'courses'])->name('courses');
    Route::post('/courses', [AdminController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [AdminController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [AdminController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [AdminController::class, 'destroy'])->name('courses.destroy');
    Route::patch('/registrations/{reg}/amend', [AdminController::class, 'amendRegistration'])->name('registrations.amend');
    
    Route::get('/students', [AdminController::class, 'students'])->name('students');
    Route::get('/registrations', [AdminController::class, 'registrations'])->name('registrations');
});


// Route::get('/test-mail', function () {
//     Mail::raw('This is a test email from Laravel!', function ($message) {
//         $message->to('knisa74800@gmail.com')
//                 ->subject('Test Email');
//     });
//     return 'Email sent!';
// });

Route::get('/test-email', function () {
    $course = \App\Models\Course::first();
    Mail::to('your_email@gmail.com')->send(new \App\Mail\RegistrationApproved($course));
    return 'Email sent!';
});