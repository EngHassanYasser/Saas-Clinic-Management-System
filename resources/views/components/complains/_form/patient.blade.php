@if (auth()->user()->type == 'clinic')
    <div>
        @php
            $patients = [
                (object) ['id' => 1, 'name' => 'Ali'],
                (object) ['id' => 2, 'name' => 'mohammed'],
            ];
        @endphp
        <label class="block text-sm font-medium text-gray-700 mb-2">اسم المريض</label>
        <select name="patient_id"
            class="w-full rounded-lg border border-gray-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            <option value="">اختر المريض (اختياري)</option>
            @foreach ($patients as $patient)
                <option value="{{ $patient->id }}">{{ $patient->name }}</option>
            @endforeach
        </select>
    </div>
@endif
