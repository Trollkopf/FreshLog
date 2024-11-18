<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Clave única para identificar la configuración (e.g., 'operario_password')
            $table->string('value');         // Valor de la configuración
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }

};
