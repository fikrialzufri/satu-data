<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class UsersTableSeedeer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = new Role();
        $superadmin->name = 'Superadmin';
        $superadmin->save();

        $adminRole = new Role();
        $adminRole->name = 'admin';
        $adminRole->save();

        $adminUnitRole = new Role();
        $adminUnitRole->name = 'Admin Unit';
        $adminUnitRole->save();

        $superadmin = Role::where('slug', 'superadmin')->first();

        $superadminUser = new User();
        $superadminUser->name = 'Superadmin';
        $superadminUser->username = 'Superadmin';
        $superadminUser->email = 'Superadmin@admin.com';
        $superadminUser->password = bcrypt('samarinda');
        // $superadminUser->icon = 'default-icon.png';
        $superadminUser->save();

        $superadminUser->role()->attach($superadmin);

        $admin = Role::where('slug', 'admin')->first();

        $adminUser = new User();
        $adminUser->name = 'admin';
        $adminUser->username = 'admin';
        $adminUser->email = 'admin@admin.com';
        $adminUser->password = bcrypt('secret');
        // $adminUser->icon = 'default-icon.png';
        $adminUser->save();

        $adminUser->role()->attach($admin);

        // ------------ Task

        $taskUser = new Task();
        $taskUser->name = 'User';
        $taskUser->description = 'Manajemen User';
        $taskUser->save();

        $taskRole = new Task();
        $taskRole->name = 'Roles';
        $taskRole->description = 'Manajemen Hak Akses ';
        $taskRole->save();

        $taskSatuan = new Task();
        $taskSatuan->name = 'Satuan';
        $taskSatuan->description = 'Manajemen Satuan';
        $taskSatuan->save();

        $taskElement = new Task();
        $taskElement->name = 'Element';
        $taskElement->description = 'Manajemen Element';
        $taskElement->save();

        $taskSubElement = new Task();
        $taskSubElement->name = 'Sub Element';
        $taskSubElement->description = 'Manajemen Sub Element';
        $taskSubElement->save();

        $taskGroup = new Task();
        $taskGroup->name = 'Group';
        $taskGroup->description = 'Manajemen Group';
        $taskGroup->save();

        $taskJenisData = new Task();
        $taskJenisData->name = 'Jenis Data';
        $taskJenisData->description = 'Manajemen Jenis Data';
        $taskJenisData->save();

        $taskJenisUnit = new Task();
        $taskJenisUnit->name = 'Jenis Unit';
        $taskJenisUnit->description = 'Manajemen Jenis Unit';
        $taskJenisUnit->save();

        $taskUnit = new Task();
        $taskUnit->name = 'Unit';
        $taskUnit->description = 'Manajemen Unit';
        $taskUnit->save();


        $taskLegenda = new Task();
        $taskLegenda->name = 'Legenda';
        $taskLegenda->description = 'Manajemen Legenda';
        $taskLegenda->save();

        $tasks = Task::all();

        foreach ($tasks as $task) {
            $name = $task->name;
            $data = array(

                [
                    'name'    => 'View ' . $name,
                    'task_id' => $task->id
                ],
                [
                    'name'    => 'Create ' . $name,
                    'task_id' => $task->id
                ],
                [
                    'name'    => 'Edit ' . $name,
                    'task_id' => $task->id
                ],
                [
                    'name'    => 'Delete ' . $name,
                    'task_id' => $task->id
                ],
            );

            foreach ($data as $induk) {
                $Permission = Permission::Create($induk);
            }
        }

        $taskElementPermissiion = Task::where('name', 'Element')->first();
        $taskSubElementPermissiion = Task::where('name', 'Sub Element')->first();

        if ($taskElementPermissiion) {
            $dataElement = array(

                [
                    'name'    => 'Download Element',
                    'task_id' =>  $taskElement->id
                ],
                [
                    'name'    => 'Import Element',
                    'task_id' =>  $taskElement->id
                ],
            );
            foreach ($dataElement as $element) {
                $Permission = Permission::Create($element);
            }
        }
        if ($taskSubElementPermissiion) {
            $dataSubElement = array(

                [
                    'name'    => 'Download Sub Element',
                    'task_id' =>  $taskSubElementPermissiion->id
                ],
                [
                    'name'    => 'Import Sub Element',
                    'task_id' =>  $taskSubElementPermissiion->id
                ],
                [
                    'name'    => 'Input Nilai Sub Element',
                    'task_id' =>  $taskSubElementPermissiion->id
                ],
                [
                    'name'    => 'Edit Legenda Sub Element',
                    'task_id' =>  $taskSubElementPermissiion->id
                ],
            );
            foreach ($dataSubElement as $element) {
                $Permission = Permission::Create($element);
            }
        }
    }
}
