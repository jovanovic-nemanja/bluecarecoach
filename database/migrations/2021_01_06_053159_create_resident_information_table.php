<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResidentInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resident_information', function (Blueprint $table) {
            $table->increments('id');

            $table->date('date_admitted')->nullable();
            $table->text('ssn')->nullable();
            $table->text('primary_language')->nullable();
            
            $table->text('representing_party_firstname')->nullable();
            $table->text('representing_party_lastname')->nullable();
            $table->text('representing_party_street1')->nullable();
            $table->text('representing_party_street2')->nullable();
            $table->text('representing_party_city')->nullable();
            $table->text('representing_party_zip_code')->nullable();
            $table->text('representing_party_state')->nullable();
            $table->text('representing_party_home_phone')->nullable();
            $table->text('representing_party_cell_phone')->nullable();
            

            $table->text('secondary_representative_firstname')->nullable();
            $table->text('secondary_representative_lastname')->nullable();
            $table->text('secondary_representative_street1')->nullable();
            $table->text('secondary_representative_street2')->nullable();
            $table->text('secondary_representative_city')->nullable();
            $table->text('secondary_representative_zip_code')->nullable();
            $table->text('secondary_representative_state')->nullable();
            $table->text('secondary_representative_home_phone')->nullable();
            $table->text('secondary_representative_cell_phone')->nullable();
            

            $table->text('physician_or_medical_group_firstname')->nullable();
            $table->text('physician_or_medical_group_lastname')->nullable();          
            $table->text('physician_or_medical_group_street1')->nullable();
            $table->text('physician_or_medical_group_street2')->nullable();
            $table->text('physician_or_medical_group_city')->nullable();
            $table->text('physician_or_medical_group_zip_code')->nullable();
            $table->text('physician_or_medical_group_state')->nullable();
            $table->text('physician_or_medical_group_phone')->nullable();
            $table->text('physician_or_medical_group_fax')->nullable();
            

            $table->text('pharmacy_firstname')->nullable();
            $table->text('pharmacy_lastname')->nullable(); 
            $table->text('pharmacy_street1')->nullable();
            $table->text('pharmacy_street2')->nullable();
            $table->text('pharmacy_city')->nullable();
            $table->text('pharmacy_zip_code')->nullable();
            $table->text('pharmacy_state')->nullable();
            $table->text('pharmacy_home_phone')->nullable();
            $table->text('pharmacy_fax')->nullable();
            

            $table->text('dentist_name')->nullable();
            $table->text('dentist_street1')->nullable();
            $table->text('dentist_street2')->nullable();
            $table->text('dentist_city')->nullable();
            $table->text('dentist_zip_code')->nullable();
            $table->text('dentist_state')->nullable();
            $table->text('dentist_home_phone')->nullable();
            $table->text('dentist_fax')->nullable();
            
            $table->text('advance_directive')->nullable();
            $table->text('polst')->nullable();
            $table->text('alergies')->nullable();
            
            $table->datetime('signDate');

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
        Schema::dropIfExists('resident_information');
    }
}
