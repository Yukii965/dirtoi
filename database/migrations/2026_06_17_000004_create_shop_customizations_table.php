<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shop_customizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('primary_color', 7)->default('#F4A429');   // couleur principale
            $table->string('banner_image')->nullable();                 // photo bannière
            $table->text('bio')->nullable();                            // description boutique
            $table->longText('blocks')->nullable();                     // JSON blocs premium
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('shop_customizations'); }
};