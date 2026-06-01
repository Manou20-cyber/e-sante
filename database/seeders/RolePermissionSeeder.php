<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Patients
            'patient.profile.view', 'patient.profile.edit',
            'patient.rendezvous.create', 'patient.rendezvous.view', 'patient.rendezvous.cancel',
            'patient.dossier.view',
            'patient.documents.view', 'patient.documents.download',
            'patient.messages.send', 'patient.messages.view',
            'patient.commandes.create', 'patient.commandes.view', 'patient.commandes.return',
            'patient.historique.view',

            // Cabinets
            'cabinet.rendezvous.manage',
            'cabinet.creneaux.manage',
            'cabinet.stock.manage',
            'cabinet.consultations.manage',
            'cabinet.examens.manage',
            'cabinet.ordonnances.manage',
            'cabinet.dossiers.manage',
            'cabinet.factures.manage',
            'cabinet.paiements.manage',
            'cabinet.garanties.manage',
            'cabinet.retours.manage',
            'cabinet.messages.manage',
            'cabinet.rapports.view',
            'cabinet.staff.manage',

            // Admin système
            'admin.cabinets.manage',
            'admin.users.manage',
            'admin.roles.manage',
            'admin.parametres.manage',
            'admin.audit.view',
            'admin.rapports.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $patient = Role::firstOrCreate(['name' => 'patient']);
        $patient->syncPermissions([
            'patient.profile.view', 'patient.profile.edit',
            'patient.rendezvous.create', 'patient.rendezvous.view', 'patient.rendezvous.cancel',
            'patient.dossier.view',
            'patient.documents.view', 'patient.documents.download',
            'patient.messages.send', 'patient.messages.view',
            'patient.commandes.create', 'patient.commandes.view', 'patient.commandes.return',
            'patient.historique.view',
        ]);

        $opticien = Role::firstOrCreate(['name' => 'opticien']);
        $opticien->syncPermissions([
            'cabinet.rendezvous.manage',
            'cabinet.creneaux.manage',
            'cabinet.stock.manage',
            'cabinet.consultations.manage',
            'cabinet.examens.manage',
            'cabinet.ordonnances.manage',
            'cabinet.dossiers.manage',
            'cabinet.factures.manage',
            'cabinet.paiements.manage',
            'cabinet.garanties.manage',
            'cabinet.retours.manage',
            'cabinet.messages.manage',
            'cabinet.rapports.view',
        ]);

        $cabinetAdmin = Role::firstOrCreate(['name' => 'cabinet_admin']);
        $cabinetAdmin->syncPermissions(array_merge(
            $opticien->permissions->pluck('name')->toArray(),
            ['cabinet.staff.manage']
        ));

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->syncPermissions(Permission::all());
    }
}
