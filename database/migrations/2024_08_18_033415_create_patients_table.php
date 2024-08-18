<?php

use App\Models\Clinic;
use App\Models\Municipality;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('dui', 9)->unique()->nullable();
            $table->string('email');
            $table->enum('genre', ['Femenino', 'Masculino']);
            $table->string('phone');
            $table->string('cellphone');
            $table->string('address');
            $table->string('occupation');
            $table->date('birthdate');
            $table->foreignIdFor(Municipality::class)->constrained();
            $table->foreignIdFor(Clinic::class)->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
