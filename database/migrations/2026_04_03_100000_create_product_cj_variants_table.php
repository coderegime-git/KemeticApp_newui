<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_cj_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('cj_pid', 100);
            $table->string('vid', 100);
            $table->string('variant_name', 255)->nullable();
            $table->string('variant_key', 255)->nullable();
            $table->string('variant_sku', 100)->nullable();
            $table->decimal('sell_price', 10, 2)->default(0.00);
            $table->text('variant_image')->nullable();
            $table->boolean('is_selected')->default(false);
            $table->timestamps();

            $table->index('product_id');
            $table->foreign('product_id')
                  ->references('id')->on('products')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_cj_variants');
    }
};
