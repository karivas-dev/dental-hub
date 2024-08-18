<?php

use App\Models\Patient;
use App\Models\Tooth;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tooth_records', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->text('details');
            $table->foreignIdFor(Patient::class)->constrained();
            $table->foreignIdFor(Tooth::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tooth_records');
    }
};
