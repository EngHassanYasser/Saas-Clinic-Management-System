@extends('layouts-main.dashboard')

@section('title', 'إضافة طبيب جديد')

@section('content')

    <div class="p-6 min-h-screen bg-gray-50" dir="rtl" x-data="{
        workDays: {{ json_encode(old('work_days', [])) }},
        isActive: true,
        imagePreview: null,
        slots: [],
    
        generateSlots() {
            const start = document.querySelector('[name=work_start]').value;
            const end = document.querySelector('[name=work_end]').value;
            const bStart = document.querySelector('[name=break_start]').value;
            const bEnd = document.querySelector('[name=break_end]').value;
            const duration = parseInt(document.querySelector('[name=session_duration]').value) || 30;
    
            if (!start || !end) return;
    
            const toMins = t => { const [h, m] = t.split(':').map(Number); return h * 60 + m; };
            const toTime = m => `${String(Math.floor(m/60)).padStart(2,'0')}:${String(m%60).padStart(2,'0')}`;
    
            let result = [],
                cur = toMins(start);
            const endM = toMins(end);
            const bs = bStart ? toMins(bStart) : null;
            const be = bEnd ? toMins(bEnd) : null;
    
            while (cur + duration <= endM) {
                if (bs && be && cur >= bs && cur < be) { cur = be; continue; }
                result.push(toTime(cur));
                cur += duration;
            }
            this.slots = result;
        }
    }">

        <form action="{{ route('doctors.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
           @include('doctors._form');
        </form>
    </div>

@endsection
