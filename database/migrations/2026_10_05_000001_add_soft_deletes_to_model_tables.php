<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tables represented by models in app/Models.
     *
     * @var array<int, string>
     */
    protected $tables = [
        'audit_logs',
        'banner',
        'ckan_visitors',
        'element',
        'galleries',
        'group',
        'infografik',
        'jenis_data',
        'jenis_unit',
        'kategori_infografik',
        'legenda',
        'media',
        'notifikasi',
        'operator',
        'permissions',
        'personal_access_tokens',
        'roles',
        'satuan',
        'setting_notifactions',
        'sub_element',
        'sub_element_tahun',
        'tasks',
        'unit',
        'users',
    ];

    public function up()
    {
        foreach ($this->tables as $tableName) {
            if (!Schema::hasTable($tableName) || Schema::hasColumn($tableName, 'deleted_at')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        foreach ($this->tables as $tableName) {
            if (!Schema::hasTable($tableName) || !Schema::hasColumn($tableName, 'deleted_at')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
