<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->string('tipo'); // diario, mensual, stock_agotado
            $table->date('fecha');
            $table->integer('total_ventas')->default(0);
            $table->decimal('total_costillas', 8, 1)->default(0);
            $table->decimal('total_ingresos', 10, 2)->default(0);
            $table->integer('stock_final')->default(0);
            $table->text('observaciones')->nullable();
            $table->boolean('leido')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reportes');
    }
};