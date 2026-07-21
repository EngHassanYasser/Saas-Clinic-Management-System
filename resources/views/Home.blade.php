@extends('layouts-main.App')

@section('content')

<div class="bg-gray-50 min-h-screen">

   @include('layouts-main.partials.Hero-section');
   @include('layouts-main.partials.Ads');
   @include('layouts-main.partials.Specialties');
   @include('layouts-main.partials.Clinics');
   @include('layouts-main.partials.FamousDoctors');
   @include('layouts-main.partials.NearestAppointments');
</div>
@endsection
