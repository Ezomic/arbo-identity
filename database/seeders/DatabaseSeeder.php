<?php

namespace Database\Seeders;

use App\Models\AppDefinition;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use App\Models\UserLink;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * user_types (arbo/employer/employee/medical_doctor -> app_slug) are
     * seeded in a migration, not here, since a later migration needs them
     * to already exist to backfill this dev database's earlier seed data.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['username' => 'dev'],
            [
                'name' => 'Dev Admin',
                'email' => 'dev@identity.test',
                'password' => 'password',
                'user_type_id' => 'platform_admin',
                'tenant_id' => null,
                'role_id' => null,
                'scope_id' => null,
            ],
        );

        $appDefinitions = collect([
            ['slug' => 'case-officers', 'name' => 'Case Officers', 'base_url' => 'https://case-officers.test'],
            ['slug' => 'employers', 'name' => 'Employers', 'base_url' => 'https://employers.test'],
            ['slug' => 'doctors', 'name' => 'Doctors', 'base_url' => 'https://doctors.test'],
            ['slug' => 'employees', 'name' => 'Employees', 'base_url' => 'https://employees.test'],
            ['slug' => 'admin', 'name' => 'Admin', 'base_url' => 'https://admin.test'],
        ])->map(fn (array $attributes) => AppDefinition::query()->updateOrCreate(['slug' => $attributes['slug']], $attributes));

        $roles = [
            'case-officers' => 'case_officer',
            'employers' => 'employer_contact',
            'doctors' => 'doctor',
            'employees' => 'employee',
            'admin' => 'application_manager',
        ];

        $roleModels = collect($roles)->map(
            fn (string $roleName, string $appSlug) => Role::query()->updateOrCreate(
                ['app_slug' => $appSlug, 'name' => $roleName],
            ),
        );

        $tenant = Tenant::query()->updateOrCreate(
            ['slug' => 'acme-arbo'],
            ['name' => 'Acme Arbodienst', 'status' => 'active'],
        );

        $caseOfficer = User::factory()->create([
            'name' => 'Casey Officer',
            'username' => 'casey',
            'email' => 'casey@acme-arbo.test',
            'user_type_id' => 'arbo',
            'role_id' => $roleModels['case-officers']->id,
            'tenant_id' => $tenant->id,
        ]);

        $employerContact = User::factory()->create([
            'name' => 'Emma Employer',
            'username' => 'emma',
            'email' => 'emma@client-company.test',
            'user_type_id' => 'employer',
            'role_id' => $roleModels['employers']->id,
            'tenant_id' => $tenant->id,
            // scope_id (which Employer record this account represents) is
            // set later once that Employer exists in Case Officers' own
            // database — see its own seeder/tinker notes.
        ]);

        $admin = User::factory()->create([
            'name' => 'Ann Admin',
            'username' => 'ann',
            'email' => 'ann@acme-arbo.test',
            'user_type_id' => 'application_manager',
            'role_id' => $roleModels['admin']->id,
            'tenant_id' => $tenant->id,
        ]);

        $doctor = User::factory()->create([
            'name' => 'Derek Doctor',
            'username' => 'derek',
            'email' => 'derek@acme-arbo.test',
            'user_type_id' => 'medical_doctor',
            'role_id' => $roleModels['doctors']->id,
            'tenant_id' => $tenant->id,
        ]);

        UserLink::query()->firstOrCreate([
            'user_id' => $caseOfficer->id,
            'linked_user_id' => $employerContact->id,
        ]);

        $this->command->info('Seeded tenant "Acme Arbodienst" with test users (Casey/Emma linked to each other):');
        $this->command->info('  dev / password   -> identity.test/master  (platform admin)');
        $this->command->info('  casey / password -> case-officers.test');
        $this->command->info('  emma / password  -> employers.test');
        $this->command->info('  ann / password   -> admin.test');
        $this->command->info('  derek / password -> doctors.test');
    }
}
