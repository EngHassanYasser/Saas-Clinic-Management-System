<?php
return [
    'clinic' => [
        ['label'=>'الإحصائيات','route'=>'dashboard','icon'=>'fas fa-dashboard'],
        ['label' => 'المواعيد', 'route' => 'appointments.index', 'icon' => 'fas fa-calendar-check'],
        ['label' => 'اضافة موعد', 'route' => 'clinic.create', 'icon' => 'fas fa-calendar-plus'],
        ['label' => 'الدكاتره', 'route' => 'clinic.create', 'icon' => 'fa-solid fa-user-doctor'],
        ['label' => 'بيانات العياده', 'route' => 'clinic.edite', 'icon' => 'fas fa-user'],
        ['label' => 'الاعدادات', 'route' => 'clinic.create', 'icon' => 'fas fa-cog'],
    ],
    'patient' => [
        ['label' => 'مواعيدي', 'route' => 'my-appointments', 'icon' => 'fa-solid fa-calendar-check'],
        ['label' => 'ابحث عن عيادة', 'route' => 'search.results', 'icon' => 'fas fa-search'],
        ['label' => 'بياناتي', 'route' => 'search.results', 'icon' => 'fas fa-user'],
    ],
    'super_admin' => [],

];
