<?php

namespace App\Http\Controllers;
use App\Http\Requests\doctor\StoreDoctorRequest;
use App\Http\Requests\doctor\updateDoctorRequest;
use App\services\DoctorService;
use App\services\SpecialityService;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(private DoctorService $doctorService, private SpecialityService $specialityService) {}
    public function index()
    {
        $doctors = $this->doctorService->getAll();
        $specialities = $this->specialityService->getAll();
        return view('doctors.index', compact('doctors', 'specialities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $specialities = $this->specialityService->getAll();
        return view('doctors.create', compact('specialities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDoctorRequest $request, DoctorService $doctorService)
    {
        $doctorService->addNew($request->validated());

        return redirect()
            ->route('doctors.index')
            ->with('message', 'Doctor added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(updateDoctorRequest $request, string $id)
    {
        $isUpdated = $this->doctorService->update($request->validated(), $id);

        if ($isUpdated) {
            return redirect()
                ->route('doctors.index')
                ->with('message', 'تم تعديل بيانات الطبيب بنجاح');
        }

        return redirect()
            ->back()
            ->with('error', 'حصل خطأ أثناء التعديل');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->doctorService->deleteById($id);
        return redirect()->route('doctors.index')->with('message', 'doctor deleted successfully');
    }
}
