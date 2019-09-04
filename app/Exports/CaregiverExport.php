<?php
namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use DB;

class CaregiverExport implements FromCollection, WithHeadings, ShouldAutoSize{
    public function collection(){
        $usre_data = DB::table('users')->select('users.*', 'caregiver.service', 'caregiver.min_price', 'caregiver.max_price', 'caregiver.description', 'caregiver.zipcode')->Join('caregiver', 'caregiver.user_id', '=', 'users.id')->orderBy('users.id', 'desc')->get();

        $output = array();
        if(!empty($usre_data)){
            $count = 1;
            foreach ($usre_data as $row) {
                $output[] = array(  
                    $count.".", 
                    ucfirst(str_replace(",", " ", $row->name)), 
                    $row->email,
                    $row->mobile_number,
                    $row->gender,
                    date("d-m-Y", strtotime($row->dob)),
                    ucfirst($row->location),
                    $row->city, 
                    $row->state, 
                    $row->zipcode,
                    "$".$row->min_price." - $".$row->max_price,
                    date("d-m-Y", strtotime($row->created_at))
                );
                $count++;
            } 
        }

        return collect([$output]);
    }

    public function headings(): array{
        return [
            'S. No.', 
            'Name', 
            'Email', 
            'Mobile No.',
            'Gender',
            'Date Of Birth',
            'Street',
            'City',
            'State',
            'Zip Code',
            'Price Range',
            'Created On',
        ];
    }
}
?>