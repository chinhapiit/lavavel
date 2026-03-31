<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;

class EnrollmentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        $courses = Course::all();
        $enrollments = Enrollment::with(['student', 'course'])->get();

        return view('enrollments.index', compact('students', 'courses', 'enrollments'));
    }

    public function store(Request $request)
    {
        $student_id = $request->student_id;
        $course_id = $request->course_id;

        // check trùng
        $exists = Enrollment::where('student_id', $student_id)
            ->where('course_id', $course_id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['msg' => 'Đã đăng ký môn này']);
        }

        // tính tín chỉ
        $currentCredits = Enrollment::where('student_id', $student_id)
            ->join('courses', 'courses.id', '=', 'enrollments.course_id')
            ->sum('courses.credits');

        $course = Course::find($course_id);

        if ($currentCredits + $course->credits > 18) {
            return back()->withErrors(['msg' => 'Tối đa 18 tín chỉ']);
        }

        Enrollment::create([
            'student_id' => $student_id,
            'course_id' => $course_id
        ]);

        return back()->with('success', 'Đăng ký thành công');
    }
    public function addCourse(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'credits' => 'required|integer|min:1|max:10'
        ]);

        \App\Models\Course::create([
            'name' => $request->name,
            'credits' => $request->credits
        ]);

        return back()->with('success', 'Thêm môn học thành công');
    }
}