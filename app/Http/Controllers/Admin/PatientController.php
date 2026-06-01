<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePatientRequest;
use App\Http\Requests\Admin\UpdatePatientRequest;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(): View
    {
        $patients = Patient::with('user')->latest()->paginate(15);

        return view('admin.patients.index', compact('patients'));
    }

    public function store(StorePatientRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole('patient');

            $user->patient()->create($request->only([
                'date_naissance', 'sexe', 'adresse', 'ville', 'code_postal',
            ]));
        });

        return back()->with('success', 'Patient créé avec succès.');
    }

    public function update(UpdatePatientRequest $request, Patient $patient): RedirectResponse
    {
        DB::transaction(function () use ($request, $patient) {
            $patient->user->update($request->only(['name', 'email', 'phone']));

            $patient->update($request->only([
                'date_naissance', 'sexe', 'adresse', 'ville', 'code_postal',
            ]));
        });

        return back()->with('success', 'Patient mis à jour avec succès.');
    }

    public function destroy(Patient $patient): RedirectResponse
    {
        $patient->user->delete();

        return back()->with('success', 'Patient supprimé avec succès.');
    }
}
