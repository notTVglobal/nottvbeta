<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::table('news_people', function (Blueprint $table) {
      $table->softDeletes();
      $table->string('name')->after('user_id')->default('')->index();
      $table->string('slug')->after('name')->default('')->index();
      $table->foreignId('image_id')->after('slug')->nullable()->constrained('images')->nullOnDelete();
      $table->text('biography')->after('image_id')->default('');
      $table->string('contact_info')->after('biography')->nullable();
      $table->string('other_details')->after('contact_info')->nullable();
      $table->json('social_media')->after('other_details')->default('{}');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::table('news_people', function (Blueprint $table) {
      $table->dropColumn('social_media');
      $table->dropColumn('other_details');
      $table->dropColumn('contact_info');
      $table->dropColumn('biography');

      // Drop foreign key constraint before dropping the column
      $table->dropForeign(['image_id']);
      $table->dropColumn('image_id');

      $table->dropColumn('slug');
      $table->dropColumn('name');

      $table->dropSoftDeletes();
    });
  }
};
