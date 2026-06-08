<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Roles
        $roles = ['super-admin', 'admin', 'supervisor', 'consulta'];
        foreach ($roles as $role) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Super Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@inventario.com'],
            ['name' => 'Super Administrador', 'password' => bcrypt('admin123'), 'active' => true]
        );
        $admin->syncRoles(['super-admin']);

        // Admin user
        $admin2 = User::firstOrCreate(
            ['email' => 'gestor@inventario.com'],
            ['name' => 'Gestor Inventario', 'password' => bcrypt('gestor123'), 'active' => true]
        );
        $admin2->syncRoles(['admin']);

        // Departments
        $depts = [
            ['name' => 'Tecnología',    'code' => 'TI'],
            ['name' => 'Administración','code' => 'ADM'],
            ['name' => 'Ventas',        'code' => 'VEN'],
            ['name' => 'Recursos Humanos','code' => 'RRHH'],
            ['name' => 'Finanzas',      'code' => 'FIN'],
        ];
        foreach ($depts as $dept) {
            \App\Models\Department::firstOrCreate(['code' => $dept['code']], array_merge($dept, ['active' => true]));
        }

        // Sample collaborators
        $ti = \App\Models\Department::where('code', 'TI')->first();
        $adm = \App\Models\Department::where('code', 'ADM')->first();

        \App\Models\Collaborator::firstOrCreate(['cedula' => '8-888-0001'], [
            'department_id' => $ti->id, 'first_name' => 'Juan', 'last_name' => 'Pérez',
            'position' => 'Analista TI', 'email' => 'juan.perez@empresa.com', 'phone' => '6000-0001', 'status' => 'active',
        ]);
        \App\Models\Collaborator::firstOrCreate(['cedula' => '8-888-0002'], [
            'department_id' => $adm->id, 'first_name' => 'María', 'last_name' => 'García',
            'position' => 'Asistente Admin', 'email' => 'maria.garcia@empresa.com', 'phone' => '6000-0002', 'status' => 'active',
        ]);

        // Sample equipment
        \App\Models\Equipment::firstOrCreate(['serial_number' => 'SN-DEMO-001'], [
            'barcode' => 'EQ-DEMO001', 'qr_code' => 'QR-DEMO001',
            'brand' => 'Samsung', 'model' => 'Galaxy S23', 'type' => 'smartphone',
            'status' => 'available', 'imei1' => '350000000000001',
            'operating_system' => 'Android 14', 'storage_capacity' => '256GB', 'ram' => '8GB',
            'purchase_date' => '2024-01-15', 'warranty_expiry' => '2026-01-15',
            'supplier' => 'Claro Panamá', 'cost' => 850.00,
        ]);

        \App\Models\Equipment::firstOrCreate(['serial_number' => 'SN-DEMO-002'], [
            'barcode' => 'EQ-DEMO002', 'qr_code' => 'QR-DEMO002',
            'brand' => 'Lenovo', 'model' => 'ThinkPad E15', 'type' => 'laptop',
            'status' => 'available', 'ip_address' => '192.168.1.101', 'mac_address' => 'AA:BB:CC:DD:EE:01',
            'operating_system' => 'Windows 11 Pro', 'storage_capacity' => '512GB SSD', 'ram' => '16GB',
            'purchase_date' => '2024-03-10', 'warranty_expiry' => '2026-03-10',
            'supplier' => 'Tecnología S.A.', 'cost' => 1200.00,
        ]);
    }
}
