<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // equipment — búsquedas frecuentes por estado, tipo, escáner
        Schema::table('equipment', function (Blueprint $table) {
            $table->index('status',  'idx_equipment_status');
            $table->index('type',    'idx_equipment_type');
            $table->index('barcode', 'idx_equipment_barcode');
            $table->index('qr_code', 'idx_equipment_qr_code');
            $table->index('warranty_expiry', 'idx_equipment_warranty');
        });

        // collaborators — búsquedas por cédula y estado
        Schema::table('collaborators', function (Blueprint $table) {
            $table->index('status',        'idx_collaborators_status');
            $table->index('department_id', 'idx_collaborators_dept');
        });

        // assignments — consultas por equipo, colaborador y estado
        Schema::table('assignments', function (Blueprint $table) {
            $table->index('equipment_id',    'idx_assignments_equipment');
            $table->index('collaborator_id', 'idx_assignments_collaborator');
            $table->index('status',          'idx_assignments_status');
            $table->index(['equipment_id', 'status'], 'idx_assignments_equipment_status');
            $table->index('expected_return', 'idx_assignments_expected_return');
        });

        // activity_logs — filtros por usuario, acción y fecha
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('user_id',    'idx_logs_user');
            $table->index('action',     'idx_logs_action');
            $table->index('model_type', 'idx_logs_model_type');
            $table->index('created_at', 'idx_logs_created_at');
        });

        // notifications — consultas por usuario y estado de lectura
        Schema::table('notifications', function (Blueprint $table) {
            $table->index('user_id',  'idx_notifications_user');
            $table->index('read_at',  'idx_notifications_read_at');
            $table->index(['user_id', 'read_at'], 'idx_notifications_user_unread');
        });

        // maintenance_records — filtros por equipo y estado
        Schema::table('maintenance_records', function (Blueprint $table) {
            $table->index('equipment_id', 'idx_maintenance_equipment');
            $table->index('status',       'idx_maintenance_status');
        });

        // software_licenses — vencimientos
        Schema::table('software_licenses', function (Blueprint $table) {
            $table->index('equipment_id', 'idx_licenses_equipment');
            $table->index('expiry_date',  'idx_licenses_expiry');
            $table->index('status',       'idx_licenses_status');
        });
    }

    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropIndex('idx_equipment_status');
            $table->dropIndex('idx_equipment_type');
            $table->dropIndex('idx_equipment_barcode');
            $table->dropIndex('idx_equipment_qr_code');
            $table->dropIndex('idx_equipment_warranty');
        });

        Schema::table('collaborators', function (Blueprint $table) {
            $table->dropIndex('idx_collaborators_status');
            $table->dropIndex('idx_collaborators_dept');
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->dropIndex('idx_assignments_equipment');
            $table->dropIndex('idx_assignments_collaborator');
            $table->dropIndex('idx_assignments_status');
            $table->dropIndex('idx_assignments_equipment_status');
            $table->dropIndex('idx_assignments_expected_return');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex('idx_logs_user');
            $table->dropIndex('idx_logs_action');
            $table->dropIndex('idx_logs_model_type');
            $table->dropIndex('idx_logs_created_at');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('idx_notifications_user');
            $table->dropIndex('idx_notifications_read_at');
            $table->dropIndex('idx_notifications_user_unread');
        });

        Schema::table('maintenance_records', function (Blueprint $table) {
            $table->dropIndex('idx_maintenance_equipment');
            $table->dropIndex('idx_maintenance_status');
        });

        Schema::table('software_licenses', function (Blueprint $table) {
            $table->dropIndex('idx_licenses_equipment');
            $table->dropIndex('idx_licenses_expiry');
            $table->dropIndex('idx_licenses_status');
        });
    }
};
