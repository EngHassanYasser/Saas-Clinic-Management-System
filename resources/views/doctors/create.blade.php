@extends('layouts-main.dashboard')

@section('title', 'إضافة طبيب جديد')

@section('content')
    <div class="p-6 min-h-screen bg-gray-50" dir="rtl" x-data="doctorForm(@js(old('work_days', [])))">

        <form action="{{ route('doctors.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('doctors._form');
        </form>
    </div>
@endsection
