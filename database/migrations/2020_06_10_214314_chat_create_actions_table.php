<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChatCreateActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('chat_caches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('identifier');
            $table->text('user_id');
            $table->datetime('expires');
            $table->timestamps();
        });

        Schema::create('chat_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('contact_type')->nullable();
            $table->string('user_id')->nullable();
            $table->string('source')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->timestamps();
        });

        Schema::create('chat_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('activity_type_id')->nullable();
            $table->string('activity_status_id')->nullable();
            $table->string('subject')->nullable();
            $table->string('details')->nullable();
            $table->string('target_contact_id')->nullable();
            $table->string('source_contact_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->timestamps();
        });


        Schema::create('chat_conversation_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('timeout');
            $table->integer('first_question_id');
            $table->timestamps();
        });


        Schema::create('chat_hears', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('chat_conversation_type_id');
            $table->string('text');
            $table->timestamps();
        });


        Schema::create('chat_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('chat_conversation_type_id');
            $table->string('text');
            $table->timestamps();
        });


        Schema::create('chat_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('contact_id');
            $table->string('service');
            $table->string('user_id');
            $table->timestamps();
        });


        Schema::create('chat_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('question_id');
            $table->string('type');
            $table->string('check_object');
            $table->string('action_data');
            $table->integer('weight');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_actions');
        Schema::dropIfExists('chat_activities');
        Schema::dropIfExists('chat_contacts');
        Schema::dropIfExists('chat_users');
        Schema::dropIfExists('chat_questions');
        Schema::dropIfExists('chat_hears');
        Schema::dropIfExists('chat_conversation_types');
        Schema::dropIfExists('chat_caches');
    }
}
