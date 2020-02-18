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
        $usre_data = DB::table('users')
        ->select('users.*', 'caregiver.service', 'caregiver.min_price', 'caregiver.max_price')
        ->Join('caregiver', 'caregiver.user_id', '=', 'users.id')
        ->orderBy('users.id', 'desc')->get();

        foreach ($usre_data as $key => $value) {
          $value->qualification = DB::table('caregiver_attributes')
          ->Join('qualifications', 'qualifications.id', '=', 'caregiver_attributes.value')
          ->where('caregiver_attributes.type', '=', 'qualification')
          ->where('caregiver_attributes.caregiver_id', '=', $value->id)
          ->pluck('qualifications.name')->toArray();
        }

        $output = array();
        if(!empty($usre_data)){
            $count = 1;
            foreach ($usre_data as $row) {
                $desciplines = implode(',', $row->qualification);
                $output[] = array(
                    $count.".",
                    ucfirst(str_replace(",", " ", $row->f_name).' '.$row->m_name.' '.$row->l_name),
                    $row->email,
                    $row->mobile_number != '' ? '+'.$row->country_code.' '.substr_replace(substr_replace($row->mobile_number, '-', '3','0'), '-', '7','0') : '' ,
                    $row->gender,
                    date("d-m-Y", strtotime($row->dob)),
                    $row->language,
                    $desciplines,
                    ucfirst($row->street),
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
            'Language',
            'Disciplines',
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
