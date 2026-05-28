@extends('layouts.App')

@section('content')

<div class="bg-gray-50 min-h-screen">

   @include('layouts.partials.Hero-section');
   @include('layouts.partials.Specialties');
   @include('layouts.partials.Clinics');
   @include('layouts.partials.FamousDoctors');
   @include('layouts.partials.NearestAppointments');
   @include('layouts.partials.Ads');
</div>
@endsection
