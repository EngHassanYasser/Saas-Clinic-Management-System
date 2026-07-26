@extends('layouts-main.App')
@section('content')
<div class="bg-gray-50 min-h-screen">
   <x-home.hero-section />
   <x-home.ads />
   <x-home.specialities />
   <x-home.clinics />
   <x-home.famous_doctors />
   <x-home.nearest_appointments />
</div>
@endsection
