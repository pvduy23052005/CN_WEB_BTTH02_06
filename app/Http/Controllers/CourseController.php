<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CourseController extends Controller
{
    
    public function showMyCourses()
    {
        return view('students.my_courses', [
            "title" => "my Course"
        ]);
    }
    
}
