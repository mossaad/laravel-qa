<?php
/**
 * we need to change answers column name to answers_count to prevent conflict with answers() relation in question model relation
 * we used renaming table as(php artisan make:migration rename_answers_in_questions_table --table=questions)
 * to migrate renaming table we need to install dependency (composer require doctrine/dbal) and then migrate src(https://laravel.com/docs/5.8/migrations#modifying-columns)
 * and we change answers in question index blade, question factory, question policy, question model to answers_count
 */
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameAnswersInQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->renameColumn('answers', 'answers_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->renameColumn('answers_count', 'answers');
        });
    }
}
