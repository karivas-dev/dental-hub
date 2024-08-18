<?php

use App\Models\Patient;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medic_records', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('details');
            $table->string('treatment')->nullable();
            $table->boolean('hereditary');
            $table->enum('kinship',
                ['Padre', 'Madre', "Hermano\/a", "Abuelo\/a", "Tio\/a", "Primo\/a", "Tatara-abuelo\/a"])->nullable();
            $table->enum('system', [
                'Respiratorio', 'Cardiovascular', 'Digestivo', 'Endocrino', 'Excretor', "Inmunol\u00f3gico", 'Muscular',
                'Nervioso', 'Reproductor', "\u00d3seo", 'Circulatorio', "Linf\u00e1tico", 'Tegumentario'
            ])->nullable();
            $table->foreignIdFor(Patient::class)->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medic_records');
    }
};
