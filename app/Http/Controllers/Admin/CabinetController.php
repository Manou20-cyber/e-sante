<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCabinetRequest;
use App\Http\Requests\Admin\UpdateCabinetRequest;
use App\Models\CabinetOptique;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CabinetController extends Controller
{
    public function index(): View
    {
        /** @var User $user */
        $user = auth()->user();

        $query = CabinetOptique::with('admin')->latest();

        if ($user->hasRole('cabinet_admin')) {
            $query->where('user_id', $user->id);
        }

        $cabinets = $query->paginate(15);
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
        /** @var User $user */
        $user = auth()->user();

        if ($user->hasRole('cabinet_admin') && $cabinet->user_id !== $user->id) {
            abort(403);
        }

        $data = $request->safe()->except(['logo', 'supprimer_logo', 'user_id', 'est_actif']);

        if ($user->hasRole('super_admin')) {
            $data['user_id'] = $request->user_id;
            $data['est_actif'] = $request->boolean('est_actif');
        }

        if ($request->hasFile('logo')) {
            if ($cabinet->logo) {
                Storage::disk('public')->delete($cabinet->logo);
            }
            $data['logo'] = $request->file('logo')->store('cabinets/logos', 'public');
        } elseif ($request->boolean('supprimer_logo') && $cabinet->logo) {
            Storage::disk('public')->delete($cabinet->logo);
            $data['logo'] = null;
        }

        $cabinet->update($data);

        return back()->with('success', 'Cabinet mis à jour avec succès.');
    }

    public function valider(CabinetOptique $cabinet): RedirectResponse
    {
        $cabinet->update(['est_actif' => true]);

        return back()->with('success', "Cabinet « {$cabinet->nom} » validé avec succès.");
    }

    public function destroy(CabinetOptique $cabinet): RedirectResponse
    {
        $cabinet->delete();

        return back()->with('success', 'Cabinet supprimé avec succès.');
    }
}
