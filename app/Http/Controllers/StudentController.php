<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        // tìm kiếm
        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        // số dòng mỗi trang
        $perPage = $request->per_page ?? 5;

        $students = $query->orderBy('name', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        return view('students.index', compact('students', 'perPage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:100',
            'major' => 'required',
            'email' => 'required|email|unique:students,email',
        ], [
            'name.required' => 'Tên không được để trống',
            'name.min' => 'Tên phải ít nhất 3 ký tự',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
        ]);
    
        Student::create([
            'name' => $request->name,
            'major' => $request->major,
            'email' => $request->email,
        ]);
    
        return redirect()->back()->with('success', 'Thêm thành công');
    }
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollments');
    }
}
