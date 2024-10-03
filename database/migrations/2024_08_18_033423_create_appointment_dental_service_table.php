<?php

use App\Models\Appointment;
use App\Models\DentalService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('appointment_dental_service', function (Blueprint $table) {
            $table->foreignIdFor(Appointment::class)->constrained();
            $table->foreignIdFor(DentalService::class)->constrained();
            $table->integer('quantity')->default(1);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_dental_service');
    }
};
