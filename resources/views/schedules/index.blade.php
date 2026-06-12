@extends('layouts-main.dashboard')

@section('title', 'مواعيد الأطباء')

@section('content')

    <div class="p-6 min-h-screen bg-gray-50" dir="rtl">
        @php
            $doctors = [
                (object) [
                    'id' => 1,
                    'name' => 'د. أحمد سامي',
                    'specialty' => 'باطنة',
                    'schedules' => collect([
                        (object) [
                            'id' => 101,
                            'days' => ['saturday', 'monday', 'wednesday'],
                            'work_start' => '09:00',
                            'work_end' => '15:00',
                            'break_start' => '12:00',
                            'break_end' => '12:30',
                            'session_duration' => 30,
                        ],
                        (object) [
                            'id' => 102,
                            'days' => ['tuesday', 'thursday'],
                            'work_start' => '10:00',
                            'work_end' => '16:00',
                            'break_start' => null,
                            'break_end' => null,
                            'session_duration' => 20,
                        ],
                    ]),
                ],

                (object) [
                    'id' => 2,
                    'name' => 'د. منى عبد الرحمن',
                    'specialty' => 'أطفال',
                    'schedules' => collect([
                        (object) [
                            'id' => 201,
                            'days' => ['sunday', 'tuesday', 'thursday'],
                            'work_start' => '11:00',
                            'work_end' => '17:00',
                            'break_start' => '14:00',
                            'break_end' => '14:30',
                            'session_duration' => 15,
                        ],
                    ]),
                ],

                (object) [
                    'id' => 3,
                    'name' => 'د. كريم عبد الله',
                    'specialty' => 'عظام',
                    'schedules' => collect([
                        (object) [
                            'id' => 301,
                            'days' => ['monday', 'wednesday', 'friday'],
                            'work_start' => '09:30',
                            'work_end' => '15:30',
                            'break_start' => '13:00',
                            'break_end' => '13:45',
                            'session_duration' => 45,
                        ],
                    ]),
                ],

                (object) [
                    'id' => 4,
                    'name' => 'د. سارة محمود',
                    'specialty' => 'جلدية',
                    'schedules' => collect([]),
                ],
            ];
        @endphp
        {{-- DOCTORS LIST --}}
        <div class="flex flex-col gap-4">

            @forelse ($doctors as $doctor)
                <x-schedules.doctor-card />

                <x-schedules.doctor-row :$doctor />

                <x-schedules.schedules-table :$doctor />

                <x-schedules.add-model :$doctor />

                <x-schedules.edite-model />
        </div>
        {{-- END DOCTOR CARD --}}

    @empty
        <x-schedules.empty-doctors-cart />
        @endforelse
    </div>
    </div>
@endsection
