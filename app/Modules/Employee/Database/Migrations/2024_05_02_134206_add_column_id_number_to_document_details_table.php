<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIdNumberToDocumentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_details', function (Blueprint $table) {
            $table->integer('id_number')->nullable()->after('document_name');
            $table->date('issued_date')->nullable()->after('id_number');
            $table->date('expiry_date')->nullable()->after('issued_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_details', function (Blueprint $table) {
            $table->dropColumn('id_number');
            $table->dropColumn('issued_date');
            $table->dropColumn('expiry_date');
        });
    }
}
