<?php

use App\Models\Branch;
use App\Models\Patient;
use App\Models\User;
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

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->string('details')->nullable();
            $table->enum('status', ['Programada', 'Reagendada', 'Cancelada', 'Completada']);
            $table->decimal('amount', 10, 2)->nullable();
            $table->foreignIdFor(User::class)->nullable()->constrained();
            $table->foreignIdFor(Patient::class)->constrained();
            $table->foreignIdFor(Branch::class)->constrained();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
