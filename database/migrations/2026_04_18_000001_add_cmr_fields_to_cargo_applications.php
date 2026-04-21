<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cargo_applications', function (Blueprint $table) {
            // Use string rather than enum so future statuses require no schema change.
            // Enforced at the application layer via CargoApplication constants.
            $table->string('cmr_status', 20)->default('not_uploaded')->after('approved_by');
            $table->string('cmr_file_path')->nullable()->after('cmr_status');
            $table->string('cmr_original_filename')->nullable()->after('cmr_file_path');
            $table->timestamp('cmr_uploaded_at')->nullable()->after('cmr_original_filename');
            $table->unsignedBigInteger('cmr_confirmed_by')->nullable()->after('cmr_uploaded_at');
            $table->timestamp('cmr_confirmed_at')->nullable()->after('cmr_confirmed_by');
            $table->text('cmr_rejection_reason')->nullable()->after('cmr_confirmed_at');
            $table->timestamp('cmr_rejected_at')->nullable()->after('cmr_rejection_reason');

            $table->foreign('cmr_confirmed_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            // Review-queue query hits this constantly.
            $table->index('cmr_status');
        });
    }

    public function down(): void
    {
        Schema::table('cargo_applications', function (Blueprint $table) {
            $table->dropForeign(['cmr_confirmed_by']);
            $table->dropIndex(['cmr_status']);

            $table->dropColumn([
                'cmr_status',
                'cmr_file_path',
                'cmr_original_filename',
                'cmr_uploaded_at',
                'cmr_confirmed_by',
                'cmr_confirmed_at',
                'cmr_rejection_reason',
                'cmr_rejected_at',
            ]);
        });
    }
};
