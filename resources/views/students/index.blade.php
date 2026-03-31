@extends('layout')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/antd/5.13.2/reset.min.css">

<style>
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 20px;
    }
    .input {
        padding: 8px;
        border: 1px solid #d9d9d9;
        border-radius: 6px;
        width: 100%;
    }
    .btn {
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
    }
    .btn-primary {
        background: #1677ff;
        color: white;
    }
    .btn-default {
        border: 1px solid #d9d9d9;
        background: white;
    }
    table th {
        background: #fafafa;
    }
    table td, table th {
        padding: 12px;
    }
    tr:hover {
        background: #f5f5f5;
    }
</style>

<div style="max-width:1200px; margin:auto; padding:20px">

    <h2 style="margin-bottom:20px">🎓 Quản lý sinh viên</h2>

    <!-- ALERT -->
    @if(session('success'))
        <div class="card" style="background:#f6ffed; border:1px solid #b7eb8f; margin-bottom:15px">
            {{ session('success') }}
        </div>
    @endif

    <!-- ERROR -->
    @if ($errors->any())
        <div class="card" style="background:#fff2f0; border:1px solid #ffccc7; margin-bottom:15px">
            
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
           
        </div>
    @endif

    <!-- FORM + SEARCH -->
    <div class="card" style="margin-bottom:20px">
        <div style="display:flex; gap:20px">

            <!-- FORM -->
            <form method="POST" action="/students" style="flex:1; display:flex; gap:10px">
                @csrf
                <input class="input" name="name" placeholder="Tên">
                <input class="input" name="major" placeholder="Ngành">
                <input class="input" name="email" placeholder="Email">
                <button class="btn btn-primary">Thêm</button>
            </form>

            <!-- SEARCH -->
            <form method="GET" action="/students" style="display:flex; gap:10px">
                <input class="input" name="keyword" placeholder="🔍 Tìm theo tên">
                <button class="btn btn-default">Tìm</button>
            </form>

        </div>
    </div>

    <!-- TABLE -->
    <div class="card">
        <table style="width:100%; border-collapse:collapse; table-layout:fixed">

            <thead>
                <tr>
                    <th style="width:10%; text-align:center">ID</th>
                    <th style="width:30%">Tên</th>
                    <th style="width:20%; text-align:center">Ngành</th>
                    <th style="width:40%">Email</th>
                </tr>
            </thead>

            <tbody>
                @forelse($students as $sv)
                <tr style="border-top:1px solid #f0f0f0">
                    <td style="text-align:center">{{ $sv->id }}</td>
                    <td>{{ $sv->name }}</td>
                    <td style="text-align:center">{{ $sv->major }}</td>
                    <td>{{ $sv->email }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; padding:20px">
                        Không có dữ liệu
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

        <!-- PAGINATION -->
        <div style="margin-top:20px; display:flex; justify-content:space-between; align-items:center">

            <!-- per page -->
            <form method="GET">
                <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                <select name="per_page" onchange="this.form.submit()" class="input" style="width:auto">
                    <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5 dòng</option>
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 dòng</option>
                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20 dòng</option>
                </select>
            </form>

            <!-- info -->
            <div>
                Hiển thị 
                <b>{{ $students->firstItem() ?? 0 }}</b> - 
                <b>{{ $students->lastItem() ?? 0 }}</b> 
                / Tổng <b>{{ $students->total() }}</b>
            </div>

        </div>

        <!-- links -->
        <div style="margin-top:10px">
            {{ $students->links('pagination::bootstrap-5') }}
        </div>

    </div>

</div>

@endsection