@extends('layouts-main.App')

@section('title', 'مواعيدي - كلينيكو')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<script src="https://cdn.tailwindcss.com"></script>

<style>
  * { font-family: 'Cairo', sans-serif; }

  body { background: #f8fafc; direction: rtl; }

  /* Sidebar gradient */
  .sidebar {
    background: linear-gradient(180deg, #0f766e 0%, #0d9488 60%, #115e59 100%);
    min-height: 100vh;
  }

  /* Status badges */
  .badge-confirmed  { background:#d1fae5; color:#065f46; border:1px solid #6ee7b7; }
  .badge-pending    { background:#fef9c3; color:#854d0e; border:1px solid #fde047; }
  .badge-cancelled  { background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; }
  .badge-completed  { background:#e0f2fe; color:#0c4a6e; border:1px solid #7dd3fc; }
  .badge-noshow     { background:#f3f4f6; color:#4b5563; border:1px solid #d1d5db; }

  /* Card entrance */
  @keyframes slideUp {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0);    }
  }
  .appointment-card {
    animation: slideUp .4s ease both;
  }
  .appointment-card:nth-child(1) { animation-delay:.05s }
  .appointment-card:nth-child(2) { animation-delay:.1s  }
  .appointment-card:nth-child(3) { animation-delay:.15s }
  .appointment-card:nth-child(4) { animation-delay:.2s  }
  .appointment-card:nth-child(5) { animation-delay:.25s }

  /* Hover lift */
  .appointment-card { transition: box-shadow .25s, transform .25s; }
  .appointment-card:hover { box-shadow:0 12px 32px rgba(15,118,110,.12); transform:translateY(-3px); }

  /* Stat gradient cards */
  .stat-teal   { background: linear-gradient(135deg,#0f766e,#14b8a6); }
  .stat-amber  { background: linear-gradient(135deg,#d97706,#f59e0b); }
  .stat-blue   { background: linear-gradient(135deg,#1d4ed8,#3b82f6); }
  .stat-rose   { background: linear-gradient(135deg,#be123c,#f43f5e); }

  /* Filter tab active */
  .tab-active {
    background:#0f766e !important;
    color:#fff !important;
    box-shadow: 0 4px 12px rgba(15,118,110,.3);
  }

  /* Timeline dot */
  .tl-dot { width:12px; height:12px; border-radius:50%; border:2px solid white; flex-shrink:0; }

  /* Scrollbar */
  ::-webkit-scrollbar        { width:5px; }
  ::-webkit-scrollbar-track  { background:#f1f5f9; }
  ::-webkit-scrollbar-thumb  { background:#0d9488; border-radius:10px; }

  /* Mobile sidebar toggle */
  .sidebar-mobile { transform: translateX(100%); transition: transform .3s ease; }
  .sidebar-mobile.open { transform: translateX(0); }
</style>

<div class="flex min-h-screen" dir="rtl">

  {{-- ===================== SIDEBAR ===================== --}}
  <aside class="sidebar w-64 hidden lg:flex flex-col flex-shrink-0 sticky top-0 h-screen">

    {{-- Logo --}}
    <div class="p-6 border-b border-white/10">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
          <i class="fas fa-stethoscope text-white text-lg"></i>
        </div>
        <div>
          <p class="text-white font-bold text-lg leading-none">كلينيكو</p>
          <p class="text-teal-200 text-xs">منصة المواعيد الطبية</p>
        </div>
      </div>
    </div>

    {{-- User --}}
    <div class="p-5 border-b border-white/10">
      <div class="flex items-center gap-3">
        <div class="w-11 h-11 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-base flex-shrink-0">
          {{ mb_substr(auth()->user()->FirstName ?? 'م', 0, 1) }}
        </div>
        <div class="overflow-hidden">
          <p class="text-white font-semibold text-sm truncate">{{ auth()->user()->FirstName ?? 'محمد أحمد' }} {{ auth()->user()->LastName ?? '' }}</p>
          <p class="text-teal-200 text-xs">مريض</p>
        </div>
      </div>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 p-4 space-y-1">

      <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-white/20 text-white font-semibold text-sm">
        <i class="fas fa-calendar-check w-4 text-center"></i>
        مواعيدي
      </a>

      <a href="{{ route('search.results') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-teal-100 hover:bg-white/10 text-sm transition">
        <i class="fas fa-search w-4 text-center"></i>
        ابحث عن عيادة
      </a>

      <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-teal-100 hover:bg-white/10 text-sm transition">
        <i class="fas fa-user w-4 text-center"></i>
        بياناتي
      </a>

      <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-teal-100 hover:bg-white/10 text-sm transition">
        <i class="fas fa-bell w-4 text-center"></i>
        الإشعارات
        @if(($unreadCount ?? 0) > 0)
          <span class="mr-auto bg-orange-400 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
            {{ $unreadCount }}
          </span>
        @endif
      </a>

    </nav>

    {{-- Logout --}}
    <div class="p-4 border-t border-white/10">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-teal-100 hover:bg-white/10 text-sm transition">
          <i class="fas fa-sign-out-alt w-4 text-center"></i>
          تسجيل الخروج
        </button>
      </form>
    </div>

  </aside>

  {{-- ===================== MAIN ===================== --}}
  <main class="flex-1 overflow-x-hidden">

    {{-- Top Bar --}}
    <div class="bg-white border-b border-gray-100 sticky top-0 z-30 px-4 sm:px-8 py-4 flex items-center justify-between shadow-sm">
      <div>
        <h1 class="text-lg sm:text-xl font-bold text-gray-900">مواعيدي</h1>
        <p class="text-gray-400 text-xs sm:text-sm">تابع جميع مواعيدك الطبية</p>
      </div>
      <a href="{{ route('search.results') }}"
         class="flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition shadow-sm">
        <i class="fas fa-plus text-xs"></i>
        حجز جديد
      </a>
    </div>

    <div class="p-4 sm:p-8 space-y-6">

      {{-- ===== STATS ROW ===== --}}
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="stat-teal rounded-2xl p-4 sm:p-5 text-white shadow-lg">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-teal-100 text-xs sm:text-sm">إجمالي المواعيد</p>
              <p class="text-2xl sm:text-3xl font-black mt-1">{{ $stats['total'] ?? 12 }}</p>
            </div>
            <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
              <i class="fas fa-calendar-alt text-sm"></i>
            </div>
          </div>
        </div>

        <div class="stat-amber rounded-2xl p-4 sm:p-5 text-white shadow-lg">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-amber-100 text-xs sm:text-sm">قادمة</p>
              <p class="text-2xl sm:text-3xl font-black mt-1">{{ $stats['upcoming'] ?? 3 }}</p>
            </div>
            <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
              <i class="fas fa-clock text-sm"></i>
            </div>
          </div>
        </div>

        <div class="stat-blue rounded-2xl p-4 sm:p-5 text-white shadow-lg">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-blue-100 text-xs sm:text-sm">مكتملة</p>
              <p class="text-2xl sm:text-3xl font-black mt-1">{{ $stats['completed'] ?? 8 }}</p>
            </div>
            <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
              <i class="fas fa-check-circle text-sm"></i>
            </div>
          </div>
        </div>

        <div class="stat-rose rounded-2xl p-4 sm:p-5 text-white shadow-lg">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-rose-100 text-xs sm:text-sm">ملغية</p>
              <p class="text-2xl sm:text-3xl font-black mt-1">{{ $stats['cancelled'] ?? 1 }}</p>
            </div>
            <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
              <i class="fas fa-times-circle text-sm"></i>
            </div>
          </div>
        </div>

      </div>

      {{-- ===== FILTER TABS ===== --}}
      <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-none flex-nowrap">
        @php
          $filters = [
            'all'       => ['label' => 'الكل',     'icon' => 'fa-th-large'],
            'upcoming'  => ['label' => 'القادمة',   'icon' => 'fa-clock'],
            'confirmed' => ['label' => 'مؤكدة',     'icon' => 'fa-check'],
            'pending'   => ['label' => 'معلقة',     'icon' => 'fa-hourglass-half'],
            'completed' => ['label' => 'مكتملة',    'icon' => 'fa-check-double'],
            'cancelled' => ['label' => 'ملغية',     'icon' => 'fa-ban'],
          ];
          $active = request('filter', 'all');
        @endphp

        @foreach($filters as $key => $f)
          <a href="{{ request()->fullUrlWithQuery(['filter' => $key]) }}"
             class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition border
             {{ $active === $key ? 'tab-active border-transparent' : 'bg-white border-gray-200 text-gray-600 hover:border-teal-300 hover:text-teal-700' }}">
            <i class="fas {{ $f['icon'] }} text-xs"></i>
            {{ $f['label'] }}
          </a>
        @endforeach
      </div>

      {{-- ===== APPOINTMENTS LIST ===== --}}
      @php
        // Demo data — replace with $appointments from controller
        $demoAppointments = [
          [
            'id'            => 1,
            'doctor_name'   => 'د. أحمد محمود',
            'specialty'     => 'باطنة وقلب',
            'clinic_name'   => 'عيادة الشفاء',
            'address'       => 'القاهرة - العباسية - شارع رمسيس',
            'date'          => 'الأحد، 1 يونيو 2025',
            'time'          => '10:00 ص',
            'status'        => 'confirmed',
            'status_label'  => 'مؤكد',
            'service'       => 'كشف عام',
            'exam_price'    => 300,
            'deposit'       => 60,
            'booking_src'   => 'mobileApp',
            'can_cancel'    => true,
          ],
          [
            'id'            => 2,
            'doctor_name'   => 'د. سارة علي',
            'specialty'     => 'باطنة',
            'clinic_name'   => 'مركز النور الطبي',
            'address'       => 'الجيزة - المهندسين - شارع السودان',
            'date'          => 'الثلاثاء، 3 يونيو 2025',
            'time'          => '1:00 م',
            'status'        => 'pending',
            'status_label'  => 'في الانتظار',
            'service'       => 'رسم قلب',
            'exam_price'    => 200,
            'deposit'       => 40,
            'booking_src'   => 'website',
            'can_cancel'    => true,
          ],
          [
            'id'            => 3,
            'doctor_name'   => 'د. محمد رامي',
            'specialty'     => 'عظام',
            'clinic_name'   => 'مستشفى الأمل',
            'address'       => 'القاهرة - مدينة نصر - شارع عباس العقاد',
            'date'          => 'الخميس، 22 مايو 2025',
            'time'          => '11:30 ص',
            'status'        => 'completed',
            'status_label'  => 'مكتمل',
            'service'       => 'كشف عظام',
            'exam_price'    => 400,
            'deposit'       => 80,
            'booking_src'   => 'website',
            'can_cancel'    => false,
          ],
          [
            'id'            => 4,
            'doctor_name'   => 'د. نورا حسام',
            'specialty'     => 'جلدية',
            'clinic_name'   => 'عيادة الجمال والصحة',
            'address'       => 'القاهرة - الزمالك - شارع حسن صبري',
            'date'          => 'الاثنين، 19 مايو 2025',
            'time'          => '3:00 م',
            'status'        => 'cancelled',
            'status_label'  => 'ملغي',
            'service'       => 'كشف جلدية',
            'exam_price'    => 350,
            'deposit'       => 70,
            'booking_src'   => 'mobileApp',
            'can_cancel'    => false,
          ],
        ];
        $appointments = $appointments ?? $demoAppointments;
      @endphp

      @if(count($appointments) === 0)
        {{-- Empty state --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
          <div class="w-20 h-20 bg-teal-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-calendar-times text-teal-400 text-3xl"></i>
          </div>
          <h3 class="text-gray-700 font-bold text-lg mb-2">لا توجد مواعيد</h3>
          <p class="text-gray-400 text-sm mb-6">ابدأ بحجز أول موعد طبي لك</p>
          <a href="{{ route('home') }}"
             class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-6 py-2.5 rounded-xl font-semibold transition text-sm">
            <i class="fas fa-search text-xs"></i>
            ابحث عن عيادة
          </a>
        </div>

      @else

        <div class="space-y-4">
          @foreach($appointments as $appt)

          @php
            $badgeClass = match($appt['status']) {
              'confirmed' => 'badge-confirmed',
              'pending'   => 'badge-pending',
              'cancelled' => 'badge-cancelled',
              'completed' => 'badge-completed',
              'noShow'    => 'badge-noshow',
              default     => 'badge-pending',
            };
            $statusIcon = match($appt['status']) {
              'confirmed' => 'fa-check-circle',
              'pending'   => 'fa-hourglass-half',
              'cancelled' => 'fa-times-circle',
              'completed' => 'fa-check-double',
              'noShow'    => 'fa-user-times',
              default     => 'fa-circle',
            };
          @endphp

          <div class="appointment-card bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

            {{-- Card Top Bar --}}
            <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-100">

              <div class="flex items-center gap-2 text-gray-500 text-xs">
                <i class="fas fa-hashtag text-gray-300"></i>
                <span>حجز رقم #{{ $appt['id'] }}</span>
                <span class="text-gray-300 mx-1">|</span>
                <i class="fas fa-{{ $appt['booking_src'] === 'mobileApp' ? 'mobile-alt' : 'globe' }} text-gray-300"></i>
                <span>{{ $appt['booking_src'] === 'mobileApp' ? 'تطبيق الجوال' : 'الموقع' }}</span>
              </div>

              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                <i class="fas {{ $statusIcon }}"></i>
                {{ $appt['status_label'] }}
              </span>

            </div>

            {{-- Card Body --}}
            <div class="p-5">

              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                {{-- Doctor Info --}}
                <div class="flex items-start gap-3">
                  <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center flex-shrink-0 border border-teal-100">
                    <i class="fas fa-user-md text-teal-600 text-lg"></i>
                  </div>
                  <div>
                    <p class="text-xs text-gray-400 mb-0.5">الطبيب</p>
                    <p class="font-bold text-gray-900 text-sm">{{ $appt['doctor_name'] }}</p>
                    <span class="inline-block mt-1 text-xs bg-teal-50 text-teal-700 px-2 py-0.5 rounded-full border border-teal-100">
                      {{ $appt['specialty'] }}
                    </span>
                  </div>
                </div>

                {{-- Date & Time --}}
                <div class="flex items-start gap-3">
                  <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0 border border-blue-100">
                    <i class="fas fa-calendar-day text-blue-500 text-lg"></i>
                  </div>
                  <div>
                    <p class="text-xs text-gray-400 mb-0.5">التاريخ والوقت</p>
                    <p class="font-bold text-gray-900 text-sm">{{ $appt['date'] }}</p>
                    <span class="inline-flex items-center gap-1 mt-1 text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full border border-blue-100">
                      <i class="fas fa-clock text-xs"></i>
                      {{ $appt['time'] }}
                    </span>
                  </div>
                </div>

                {{-- Clinic & Address --}}
                <div class="flex items-start gap-3">
                  <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center flex-shrink-0 border border-orange-100">
                    <i class="fas fa-hospital text-orange-500 text-lg"></i>
                  </div>
                  <div>
                    <p class="text-xs text-gray-400 mb-0.5">العيادة</p>
                    <p class="font-bold text-gray-900 text-sm">{{ $appt['clinic_name'] }}</p>
                    <p class="text-xs text-gray-500 mt-0.5 flex items-start gap-1">
                      <i class="fas fa-map-marker-alt text-gray-400 mt-0.5 flex-shrink-0"></i>
                      {{ $appt['address'] }}
                    </p>
                  </div>
                </div>

              </div>

              {{-- Divider --}}
              <div class="border-t border-dashed border-gray-200 my-4"></div>

              {{-- Service + Prices + Actions --}}
              <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">

                {{-- Service & Prices --}}
                <div class="flex flex-wrap items-center gap-3">

                  <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2">
                    <i class="fas fa-notes-medical text-gray-400 text-xs"></i>
                    <div>
                      <p class="text-xs text-gray-400 leading-none">الخدمة</p>
                      <p class="text-xs font-bold text-gray-700 mt-0.5">{{ $appt['service'] }}</p>
                    </div>
                  </div>

                  <div class="flex items-center gap-2 bg-teal-50 border border-teal-100 rounded-xl px-3 py-2">
                    <i class="fas fa-money-bill-wave text-teal-500 text-xs"></i>
                    <div>
                      <p class="text-xs text-teal-600 leading-none">سعر الكشف</p>
                      <p class="text-xs font-black text-teal-700 mt-0.5">{{ $appt['exam_price'] }} جنيه</p>
                    </div>
                  </div>

                  <div class="flex items-center gap-2 bg-amber-50 border border-amber-100 rounded-xl px-3 py-2">
                    <i class="fas fa-coins text-amber-500 text-xs"></i>
                    <div>
                      <p class="text-xs text-amber-600 leading-none">العربون المدفوع</p>
                      <p class="text-xs font-black text-amber-700 mt-0.5">{{ $appt['deposit'] }} جنيه</p>
                    </div>
                  </div>

                  <div class="flex items-center gap-2 bg-indigo-50 border border-indigo-100 rounded-xl px-3 py-2">
                    <i class="fas fa-receipt text-indigo-400 text-xs"></i>
                    <div>
                      <p class="text-xs text-indigo-600 leading-none">المتبقي</p>
                      <p class="text-xs font-black text-indigo-700 mt-0.5">{{ $appt['exam_price'] - $appt['deposit'] }} جنيه</p>
                    </div>
                  </div>

                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 flex-shrink-0">

                  @if($appt['status'] === 'confirmed' || $appt['status'] === 'pending')

                    {{-- Reschedule --}}
                    <a href="#"
                       class="flex items-center gap-1.5 px-3 py-2 rounded-xl border border-teal-200 bg-teal-50 text-teal-700 hover:bg-teal-100 text-xs font-semibold transition">
                      <i class="fas fa-sync-alt text-xs"></i>
                      إعادة جدولة
                    </a>

                    {{-- Cancel --}}
                    @if($appt['can_cancel'])
                    <button
                      onclick="confirmCancel({{ $appt['id'] }})"
                      class="flex items-center gap-1.5 px-3 py-2 rounded-xl border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 text-xs font-semibold transition">
                      <i class="fas fa-times text-xs"></i>
                      إلغاء
                    </button>
                    @endif

                  @elseif($appt['status'] === 'completed')
                    <a href="#"
                       class="flex items-center gap-1.5 px-3 py-2 rounded-xl border border-blue-200 bg-blue-50 text-blue-600 hover:bg-blue-100 text-xs font-semibold transition">
                      <i class="fas fa-redo text-xs"></i>
                      حجز مرة أخرى
                    </a>

                  @elseif($appt['status'] === 'cancelled')
                    <a href="#"
                       class="flex items-center gap-1.5 px-3 py-2 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 hover:bg-gray-100 text-xs font-semibold transition">
                      <i class="fas fa-redo text-xs"></i>
                      إعادة الحجز
                    </a>
                  @endif

                </div>

              </div>

            </div>
          </div>

          @endforeach
        </div>

      @endif

      {{-- Pagination --}}
      {{-- @if(isset($appointments) && method_exists($appointments, 'links'))
        <div class="mt-4">
          {{ $appointments->links() }}
        </div> --}}
      {{-- @endif --}}

    </div>
  </main>
</div>

{{-- ===== CANCEL CONFIRM MODAL ===== --}}
<div id="cancel-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4"
     onclick="closeCancelModal(event)">

  <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm" onclick="event.stopPropagation()">

    <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
      <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
    </div>

    <h3 class="text-center font-bold text-gray-900 text-lg mb-2">تأكيد إلغاء الموعد</h3>
    <p class="text-center text-gray-500 text-sm mb-1">
      هل أنت متأكد من إلغاء هذا الموعد؟
    </p>
    <p class="text-center text-amber-600 text-xs bg-amber-50 rounded-xl px-3 py-2 mt-3 border border-amber-100">
      <i class="fas fa-info-circle ml-1"></i>
      إذا كان الإلغاء قبل أقل من 6 ساعات سيتم خصم جزء من العربون
    </p>

    <div class="flex gap-3 mt-5">
      <button onclick="closeCancelModal()"
              class="flex-1 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 font-semibold text-sm transition">
        تراجع
      </button>

      <form id="cancel-form" method="POST" action="" class="flex-1">
        @csrf
        @method('PATCH')
        <button type="submit"
                class="w-full py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white font-semibold text-sm transition">
          تأكيد الإلغاء
        </button>
      </form>
    </div>

  </div>
</div>

<script>
  function confirmCancel(id) {
    document.getElementById('cancel-form').action = `/appointments/${id}/cancel`;
    const modal = document.getElementById('cancel-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
  }
  function closeCancelModal(e) {
    if (e && e.target !== document.getElementById('cancel-modal')) return;
    const modal = document.getElementById('cancel-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }
</script>

@endsection