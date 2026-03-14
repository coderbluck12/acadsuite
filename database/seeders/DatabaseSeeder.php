<?php

namespace Database\Seeders;

use App\Models\SuperAdmin;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Pentagonware super admin
        SuperAdmin::create([
            'name'     => 'Pentagonware Admin',
            'email'    => 'admin@pentagonware.com',
            'password' => Hash::make('password'),
        ]);

        // Demo tenant
        $tenant = Tenant::create([
            'name'        => 'Academic Suite Demo',
            'subdomain'   => 'demo',
            'owner_name'  => 'Ayomide Temilola Ibiyinka',
            'email'       => 'ayomide@acadsuite.local',
            'phone'       => '+234 812 345 6789',
            'bio'         => 'Ayomide is an astute researcher and scholar who has over the years worked on educational resources and innovative solutions aimed at empowering learners and educators across diverse fields.',
            'orcid_url'   => 'https://orcid.org/0000-0000-0000-0000',
            'address'     => '203 University Road, Lagos, Nigeria',
            'social_links' => [
                'twitter'   => 'https://twitter.com/ayomide',
                'facebook'  => 'https://facebook.com/ayomide',
                'instagram' => 'https://instagram.com/ayomide',
            ],
            'is_active'   => true,
            'approved_at' => now(),
        ]);

        // Demo tenant admin user
        User::create([
            'tenant_id' => $tenant->id,
            'name'      => 'Ayomide Temilola',
            'email'     => 'admin@demo.local',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'status'    => 'approved',
        ]);

        // Demo student users
        User::create([
            'tenant_id' => $tenant->id,
            'name'      => 'Mariam Yusuf',
            'email'     => 'mariam@demo.local',
            'password'  => Hash::make('password'),
            'role'      => 'student',
            'status'    => 'approved',
        ]);

        User::create([
            'tenant_id' => $tenant->id,
            'name'      => 'James Eze',
            'email'     => 'james@demo.local',
            'password'  => Hash::make('password'),
            'role'      => 'student',
            'status'    => 'pending',
        ]);
    }
}
