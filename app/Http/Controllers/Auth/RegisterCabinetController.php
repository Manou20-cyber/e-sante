<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterCabinetRequest;
use App\Models\CabinetOptique;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterCabinetController extends Controller
{
    public function create(): View
    {
        return view('auth.register-cabinet');
    }

    public function store(RegisterCabinetRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole('cabinet_admin');

            CabinetOptique::create([
                'user_id' => $user->id,
                'nom' => $request->cabinet_nom,
                'adresse' => $request->cabinet_adresse,
                'ville' => $request->cabinet_ville,
                'code_postal' => $request->cabinet_code_postal,
                'telephone' => $request->cabinet_telephone,
                'email' => $request->cabinet_email,
                'siret' => $request->cabinet_siret,
                'est_actif' => false,
            ]);

            event(new Registered($user));

            Auth::login($user);
        });

        return redirect(route('admin.dashboard'))
            ->with('pending_validation', true);
    }
}
