<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;


Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');

Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');

Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');

Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');

Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');

Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');





Route::prefix('courses/{course}')->name('lessons.')->group(function () {

    Route::get('/lessons', [LessonController::class, 'index'])->name('index');

    Route::get('/lessons/create', [LessonController::class, 'create'])->name('create');

    Route::post('/lessons', [LessonController::class, 'store'])->name('store');

    Route::get('/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('edit');

    Route::put('/lessons/{lesson}', [LessonController::class, 'update'])->name('update');

    Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])->name('destroy');





});