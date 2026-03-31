@extends('layout')

@section('content')

<div id="courseForm" 
     style="display:none;
            position:fixed;
            top:0; left:0;
            width:100%;
            height:100vh;
            background:rgba(0,0,0,0.5);
            display:flex;
            justify-content:center;
            align-items:center;
            z-index:999;">

    <!-- BOX -->
    <div onclick="event.stopPropagation()" 
         style="background:white;
                padding:20px;
                border-radius:12px;
                width:400px;
                box-shadow:0 4px 20px rgba(0,0,0,0.2);">

        <h3 style="margin-bottom:15px; text-align:center">Thêm môn học</h3>

        <form method="POST" action="/courses" style="display:flex; flex-direction:column; gap:10px">
            @csrf

            <input name="name" placeholder="Tên môn"
                style="padding:10px; border-radius:6px; border:1px solid #ccc">

            <input name="credits" type="number" placeholder="Tín chỉ"
                style="padding:10px; border-radius:6px; border:1px solid #ccc">

            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:10px">
                <button style="background:green; color:white; border:none; padding:8px 16px; border-radius:6px">
                    Lưu
                </button>

                <button type="button" onclick="toggleForm()" 
                    style="background:#ccc; border:none; padding:8px 16px; border-radius:6px">
                    Hủy
                </button>
            </div>
        </form>

    </div>

</div>
<div style="max-width:1200px; margin:auto; padding:20px">

    <h2 style="margin-bottom:20px">📚 Đăng ký môn học</h2>

    <!-- ALERT -->
    @if(session('success'))
        <div style="background:#f6ffed; border:1px solid #b7eb8f; padding:10px; margin-bottom:15px; border-radius:6px">
            {{ session('success') }}
        </div>
    @endif

    <!-- ERROR -->
    @if ($errors->any())
        <div style="background:#fff2f0; border:1px solid #ffccc7; padding:10px; margin-bottom:15px; border-radius:6px">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- FORM -->
    <div style="background:white; padding:20px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1); margin-bottom:20px">
        
        <form method="POST" action="/enrollments" style="display:flex; gap:10px; align-items:center">
            @csrf

            <!-- CHỌN SINH VIÊN -->
            <select name="student_id" id="studentSelect" style="padding:8px; border-radius:6px; border:1px solid #ccc">
                @foreach($students as $s)
                    <option value="{{ $s->id }}" data-current="{{ $s->courses->sum('credits') }}">
                        {{ $s->name }}
                    </option>
                @endforeach
            </select>

            <!-- CHỌN MÔN -->
            <select name="course_id" id="courseSelect" style="padding:8px; border-radius:6px; border:1px solid #ccc">
                @foreach($courses as $c)
                    <option value="{{ $c->id }}" data-credits="{{ $c->credits }}">
                        {{ $c->name }} ({{ $c->credits }} TC)
                    </option>
                @endforeach
            </select>

            <button style="background:#1677ff; color:white; border:none; padding:8px 16px; border-radius:6px">
                Đăng ký
            </button>
            <button type="button" onclick="toggleForm()"
    style="background:green; color:white; border:none; padding:8px 16px; border-radius:6px">
    ➕ Thêm môn
</button>
          
        </form>

        <!-- HIỂN THỊ TÍN CHỈ -->
        <div style="margin-top:15px">
            <p>Tín chỉ môn: <b id="courseCredits">0</b></p>
            <p>Tổng sau đăng ký: <b id="totalCredits">0</b></p>
            <p id="warning" style="color:red"></p>
        </div>

    </div>

    <!-- TABLE -->
    <div style="background:white; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1); overflow:hidden">

        <table style="width:100%; border-collapse:collapse">

            <thead style="background:#fafafa">
                <tr>
                    <th style="padding:12px">Sinh viên</th>
                    <th style="padding:12px">Môn học</th>
                    <th style="padding:12px">Tín chỉ</th>
                </tr>
            </thead>

            <tbody>
                @forelse($enrollments as $e)
                <tr style="border-top:1px solid #f0f0f0">
                    <td style="padding:12px">{{ $e->student->name }}</td>
                    <td style="padding:12px">{{ $e->course->name }}</td>
                    <td style="padding:12px">{{ $e->course->credits }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align:center; padding:20px">
                        Chưa có đăng ký
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>

    <!-- TỔNG TÍN CHỈ -->
    <div style="margin-top:20px; background:white; padding:20px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1)">

        <h3>Tổng tín chỉ</h3>

        @foreach($students as $s)
            <p>
                <b>{{ $s->name }}</b>:
                <span style="color: {{ $s->courses->sum('credits') >= 15 ? 'red' : 'black' }}">
                    {{ $s->courses->sum('credits') }} tín chỉ
                </span>
            </p>
        @endforeach

    </div>

</div>

<!-- JS REALTIME -->
<script>
    const courseSelect = document.getElementById('courseSelect');
    const studentSelect = document.getElementById('studentSelect');

    const courseCredits = document.getElementById('courseCredits');
    const totalCredits = document.getElementById('totalCredits');
    const warning = document.getElementById('warning');

    function updateCredits() {
        let selectedCourse = courseSelect.options[courseSelect.selectedIndex];
        let selectedStudent = studentSelect.options[studentSelect.selectedIndex];

        let courseCredit = parseInt(selectedCourse.getAttribute('data-credits'));
        let currentCredit = parseInt(selectedStudent.getAttribute('data-current'));

        courseCredits.innerText = courseCredit;

        let total = currentCredit + courseCredit;
        totalCredits.innerText = total;

        if (total > 18) {
            warning.innerText = "⚠️ Vượt quá 18 tín chỉ!";
        } else {
            warning.innerText = "";
        }
    }

    courseSelect.addEventListener('change', updateCredits);
    studentSelect.addEventListener('change', updateCredits);

    // chạy lần đầu
    updateCredits();
</script>
<script>
    function toggleForm() {
        let form = document.getElementById('courseForm');
        form.style.display = (form.style.display === 'none') ? 'block' : 'none';
    }
</script>
@endsection