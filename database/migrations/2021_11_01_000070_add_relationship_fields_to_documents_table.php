<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDocumentsTable extends Migration
{
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id', 'employee_fk_5245277')->references('id')->on('employees');
            $table->unsignedBigInteger('created_by_id');
            $table->foreign('created_by_id', 'created_by_fk_5245281')->references('id')->on('users');
        });
    }
}
