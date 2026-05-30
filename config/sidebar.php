<?php
return [
    'clinic' => [
        ['label' => 'الإحصائيات', 'route' => 'dashboard', 'icon' => 'fas fa-dashboard'],
        ['label' => 'المواعيد', 'route' => 'appointments.index', 'icon' => 'fas fa-calendar-check'],
        ['label' => 'اضافة موعد', 'route' => 'clinic.create', 'icon' => 'fas fa-calendar-plus'],
        ['label' => 'الدكاتره', 'route' => 'doctor.index', 'icon' => 'fa-solid fa-user-doctor'],
        ['label' => 'الأجازات', 'route' => 'vacation.index', 'icon' => 'fa fa-umbrella-beach'],
        ['label' => 'الشكاوي', 'route' => 'complain.index', 'icon' => 'fa-regular fa-comment-dots'],
        ['label' => 'اعدادات العيادة', 'route' => 'clinic.edite', 'icon' => 'fas fa-cog'],
    ],
    'patient' => [
        ['label' => 'مواعيدي', 'route' => 'my-appointments', 'icon' => 'fa-solid fa-calendar-check'],
        ['label' => 'ابحث عن عيادة', 'route' => 'search.results', 'icon' => 'fas fa-search'],
        ['label' => 'بياناتي', 'route' => 'search.results', 'icon' => 'fas fa-user'],
        ['label' => 'الشكاوي', 'route' => 'complain.index', 'icon' => 'fa-regular fa-comment-dots'],
    ],
    'super_admin' => [],

];
