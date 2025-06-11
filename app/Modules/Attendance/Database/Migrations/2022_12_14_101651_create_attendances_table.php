<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->integer('org_id')->default(0)->nullable();
            $table->integer('emp_id')->default(0)->comment('id from employment table');
            $table->date('date');
            $table->text('nepali_date')->nullable();
            $table->time('checkin')->nullable();
            $table->time('checkout')->nullable();
            $table->double('total_working_hr', 5, 2)->nullable();
            $table->double('ot_hr', 5, 2)->nullable();
            $table->string('location')->nullable();
            $table->decimal('lat', 11, 8)->nullable();
            $table->decimal('long', 11, 8)->nullable();
            $table->string('checkin_status')->comment('Pending, Approved, Rejected')->nullable();
            $table->string('checkin_from')->default('biometric')->comment('web; biometric')->nullable();
            $table->string('checkout_status')->comment('Pending, Approved, Rejected')->nullable();
            $table->string('checkout_from')->default('biometric')->comment('web; biometric')->nullable();
            $table->string('fieldwork_status')->comment('Pending, Approved, Rejected')->nullable();
            $table->string('ot_status')->comment('Pending, Approved, Rejected')->nullable();
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
        Schema::dropIfExists('attendances');
    }
}
