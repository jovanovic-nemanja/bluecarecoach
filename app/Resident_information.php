<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Resident_information extends Model
{
    public $table = "resident_information";

    public $fillable = ['date_admitted', 'ssn', 'primary_language', 'representing_party_firstname', 'representing_party_lastname', 'representing_party_street1', 'representing_party_street2', 'representing_party_city', 'representing_party_zip_code', 'representing_party_state', 'representing_party_home_phone', 'representing_party_cell_phone', 'secondary_representative_firstname', 'secondary_representative_lastname', 'secondary_representative_street1', 'secondary_representative_street2', 'secondary_representative_city', 'secondary_representative_zip_code', 'secondary_representative_state', 'secondary_representative_home_phone', 'secondary_representative_cell_phone', 'physician_or_medical_group_firstname', 'physician_or_medical_group_lastname', 'physician_or_medical_group_street1', 'physician_or_medical_group_street2', 'physician_or_medical_group_city', 'physician_or_medical_group_zip_code', 'physician_or_medical_group_state', 'physician_or_medical_group_phone', 'physician_or_medical_group_fax', 'pharmacy_firstname', 'pharmacy_lastname', 'pharmacy_street1', 'pharmacy_street2', 'pharmacy_city', 'pharmacy_zip_code', 'pharmacy_state', 'pharmacy_home_phone', 'pharmacy_fax', 'dentist_name', 'dentist_street1', 'dentist_street2', 'dentist_city', 'dentist_zip_code', 'dentist_state', 'dentist_home_phone', 'dentist_fax', 'advance_directive', 'polst', 'alergies', 'signDate'];
}
