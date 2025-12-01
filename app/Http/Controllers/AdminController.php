<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Organization;
use App\Models\Student;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {

        $organizations = Organization::all();
        $lessons = Lesson::all();
        $student = Student::all();
        $contact = Contact::all();
        $course = Course::all();
        return view('admin.index', [
            'organizations' => $organizations,
            'lessons' => $lessons,
            'contact' => $contact,
            'student' => $student,
            'course' => $course
        ]);
    }
}
