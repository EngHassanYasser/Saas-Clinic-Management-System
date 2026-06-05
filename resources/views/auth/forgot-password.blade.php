@extends('layouts-main.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

        {{-- Logo / Title --}}
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">استعادة كلمة المرور</h1>
            <p class="text-sm text-gray-500 mt-2">
                ادخل الإيميل الخاص بيك وهبعت لك رابط إعادة تعيين كلمة المرور
            </p>
        </div>

        {{-- Success Message --}}
        @if (session('status'))
            <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm border border-green-200">
                {{ session('status') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm border border-red-200">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    البريد الإلكتروني
                </label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition px-4 py-3"
                    placeholder="example@mail.com"
                >
            </div>

            {{-- Submit --}}
            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-xl transition"
            >
                إرسال رابط إعادة التعيين
            </button>
        </form>

        {{-- Back to login --}}
        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">
                العودة لتسجيل الدخول
            </a>
        </div>

    </div>

</div>
@endsection