<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTravelExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name');
            $table->string('department');
            $table->string('designation');
            $table->integer('expenses_type');
            $table->date('from_date');
            $table->date('to_date');
            $table->string('departure');
            $table->string('destination')->nullable();
            $table->text('purpose')->nullable();
            $table->decimal('total_amount', 12, 2)->default(0);
             $table->json('expense_details')->nullable();
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
        Schema::dropIfExists('travel_expenses');
    }
}
