<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCabinetRequest;
use App\Http\Requests\Admin\UpdateCabinetRequest;
use App\Models\CabinetOptique;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CabinetController extends Controller
{
    public function index(): View
    {
        $cabinets = CabinetOptique::with('admin')->latest()->paginate(15);
        $admins = User::role('cabinet_admin')->get();

        return view('admin.cabinets.index', compact('cabinets', 'admins'));
    }

    public function store(StoreCabinetRequest $request): RedirectResponse
    {
        CabinetOptique::create($request->validated());

        return back()->with('success', 'Cabinet créé avec succès.');
    }

    public function update(UpdateCabinetRequest $request, CabinetOptique $cabinet): RedirectResponse
    {
        $cabinet->update($request->validated());

        return back()->with('success', 'Cabinet mis à jour avec succès.');
    }

    public function destroy(CabinetOptique $cabinet): RedirectResponse
    {
        $cabinet->delete();

        return back()->with('success', 'Cabinet supprimé avec succès.');
    }
}
