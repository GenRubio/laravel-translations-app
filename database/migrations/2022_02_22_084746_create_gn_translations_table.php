<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGnTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gn_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gn_section_id')->nullable();
            $table->unsignedBigInteger('gn_lang_file_id')->nullable();
            $table->string('key');
            $table->string('format_key');
            $table->text('value');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('gn_section_id')
                ->references('id')->on('gn_sections')
                ->onDelete('cascade');
            $table->foreign('gn_lang_file_id')
                ->references('id')->on('gn_lang_files')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gn_translations');
    }
}
