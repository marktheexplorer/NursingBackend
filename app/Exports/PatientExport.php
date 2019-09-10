<?php
namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class PatientExport implements FromCollection, WithHeadings, ShouldAutoSize{
    public function collection(){
        $usre_data = DB::table('users')->select('users.*', 'patients_profiles.pin_code')->Join('patients_profiles', 'patients_profiles.user_id', '=', 'users.id')->orderBy('users.name', 'desc')->get();

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
                    ucfirst($row->street),
                    $row->city,
                    $row->state,
                    $row->pin_code,
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
            'Created On',
        ];
    }
}
?>
