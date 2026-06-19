<?php

namespace App\services;

use App\Models\doctor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class DoctorService
{
    public function getAll()
    {
        return Doctor::select('name', 'email', 'phone')->get();
    }
    public function addNew($data, UploadedFile $doctor_image)
    {
        $image_name = ImageService::upload($doctor_image);
        DB::transaction(function () use ($data, $image_name) {

            $doctor = Doctor::create([
                'image' => $image_name,
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
            ]);
            $doctor->speciality()->sync([$data['speciality_id']]);
        });
    }
}
