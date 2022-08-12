<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Municipality;
use App\Models\Barangay;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public $type;
    public $typeVal;
    public $munVal;
     
    public function __construct($type, $mun, $value) {
        $this->type = $type;
        $this->munVal = $mun;
        $this->typeVal = $value;
    }
    
    public function collection()
    {
        $arr = User::role(['superadmin', 'admin', 'team lead', 'site lead', 'encoder'])->get('id');
        if($this->type == "all") {
            return User::select('first_name', 'last_name', 'fb_url', 'id_number', 'birth_year', 'gender', 'province_name', 'municipality_name', 'barangay_name', 'district_no', 'email', 'mobile', 'created_at', 'updated_at')->whereNotIn('id', $arr)->get();
        } else if($this->type == "municipality") {
            return User::select('first_name', 'last_name', 'fb_url', 'id_number', 'birth_year', 'gender', 'province_name', 'municipality_name', 'barangay_name', 'district_no', 'email', 'mobile', 'created_at', 'updated_at')->where('municipality', $this->typeVal)->whereNotIn('id', $arr)->get();
        } else {
            return User::select('first_name', 'last_name', 'fb_url', 'id_number', 'birth_year', 'gender', 'province_name', 'municipality_name', 'barangay_name', 'district_no', 'email', 'mobile', 'created_at', 'updated_at')->where('municipality', $this->munVal)->where('barangay', $this->typeVal)->whereNotIn('id', $arr)->get();
        }
    }
    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'FB URL',
            'ID Number',
            'Birth Year',
            'Gender',
            'Province',
            'Municipality',
            'Barangay',
            'District No.',
            'Email',
            'Mobile',
            'Date Created',
            'Date Updated',
        ];
    }
}