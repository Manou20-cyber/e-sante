<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOpticienRequest;
use App\Http\Requests\Admin\UpdateOpticienRequest;
use App\Models\CabinetOptique;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class OpticienController extends Controller
{
    private function cabinet(): CabinetOptique
    {
        return auth()->user()->cabinetOptique;
    }

    public function index(): View
    {
        $cabinet = $this->cabinet();
        $opticiens = $cabinet->opticiens()->with('creneaux')->get();

        return view('admin.opticiens.index', compact('cabinet', 'opticiens'));
    }

    public function store(StoreOpticienRequest $request): RedirectResponse
    {
        $cabinet = $this->cabinet();

        $opticien = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'cabinet_id' => $cabinet->id,
        ]);

        $opticien->assignRole('opticien');

        return back()->with('success', "Opticien {$opticien->name} créé avec succès.");
    }

    public function update(UpdateOpticienRequest $request, User $opticien): RedirectResponse
    {
        abort_if($opticien->cabinet_id !== $this->cabinet()->id, 403);

        $data = $request->only(['name', 'email', 'phone']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $opticien->update($data);

        return back()->with('success', 'Opticien mis à jour.');
    }

    public function destroy(User $opticien): RedirectResponse
    {
        abort_if($opticien->cabinet_id !== $this->cabinet()->id, 403);

        $opticien->delete();

        return back()->with('success', 'Opticien supprimé.');
    }
}
