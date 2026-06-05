<?php
return [
    'clinic' => [
        ['label' => 'الإحصائيات', 'route' => 'dashboard', 'icon' => 'fas fa-dashboard'],
        ['label' => 'المواعيد', 'route' => 'appointments.index', 'icon' => 'fas fa-calendar-check'],
        ['label' => 'اضافة موعد', 'route' => 'clinics.create', 'icon' => 'fas fa-calendar-plus'],
        ['label' => 'الدكاتره', 'route' => 'doctors.index', 'icon' => 'fa-solid fa-user-doctor'],
        ['label' => 'الأجازات', 'route' => 'vacations.index', 'icon' => 'fa fa-umbrella-beach'],
        ['label' => 'الشكاوي', 'route' => 'complains.index', 'icon' => 'fa-regular fa-comment-dots'],
        ['label' => 'اعدادات العيادة', 'route' => 'clinics.edite', 'icon' => 'fas fa-cog'],

    ],
    'patient' => [
        ['label' => 'المواعيد', 'route' => 'appointments.index', 'icon' => 'fas fa-calendar-check'],
        ['label' => 'ابحث عن عيادة', 'route' => 'clinics.SearchResults', 'icon' => 'fas fa-search'],
        ['label' => 'الشكاوي', 'route' => 'complains.index', 'icon' => 'fa-regular fa-comment-dots'],
        ['label' => 'بياناتي', 'route' => 'profile.edit', 'icon' => 'fas fa-user'],
    ],
    'super_admin' => [
        ['label' => 'الإحصائيات', 'route' => 'dashboard', 'icon' => 'fas fa-dashboard'],
        ['label' => 'إدارة العيادات', 'route' => 'clinics.index', 'icon' => 'fa-solid fa-calendar-check'],
        ['label' => 'الاشتراكات', 'route' => 'subscriptions.index', 'icon' => 'fa-solid fa-calendar-check'],
        ['label' => 'الإعلانات', 'route' => 'ads.index', 'icon' => 'fa-solid fa-calendar-check'],

    ],
];
