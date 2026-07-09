<div class="c-appointment-card bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <x-appointments.cart-top-bar :$appt :$badgeClass :$statusIcon />
{{-- <x-appointments.clinic-appointment-card :$appt /> --}}
    <x-appointments.cart-body :$appt />
</div>
