<?php

namespace App\Services\Location;
use App\Models\City;
use App\Models\Day;
use Illuminate\Support\Facades\Cache;

class LocationQueryService
{
    public function getCountries()
    {
        //
    }

    public function getCitiesByCountry(int $countryId)
    {
        //
    }

    public function getCities():array
    {
        return Cache::rememberForever(
            'cities.all',
            fn () => City::get(['id', 'name'])->toArray()
        );
    }

    public function getDays():array
    {
        return Cache::rememberForever(
            'days.all',
            fn () => Day::get(['id', 'name'])->toArray()
        );
    }
}
