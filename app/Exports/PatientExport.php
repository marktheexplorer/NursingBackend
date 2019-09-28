<?php
namespace App\Exports;

use App\User;
use App\Diagnose;
use App\Qualification;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class PatientExport implements FromCollection, WithHeadings, ShouldAutoSize{
    public function collection(){
        $user_data = DB::table('users')
        ->select('users.*', 'patients_profiles.pin_code','patients_profiles.disciplines','patients_profiles.diagnose_id','patients_profiles.language')
        ->Join('patients_profiles', 'patients_profiles.user_id', '=', 'users.id')
        ->orderBy('users.created_at', 'desc')->get();

        foreach ($user_data as $key => $value) {
          $value->diagnosis = Diagnose::select('title')->where('id',$value->diagnose_id)->first();
          $value->disciplines_name = Qualification::whereIn('id',explode(',', $value->disciplines))->pluck('name')->toArray();
        }

        $output = array();
        if(!empty($user_data)){
            $count = 1;
            foreach ($user_data as $row) {
                $output[] = array(
                   $count.".",
                    ucfirst(str_replace(",", " ", $row->name)),
                    $row->email,
                    $row->mobile_number,
                    $row->gender,
                    date("d-m-Y", strtotime($row->dob)),
                    $row->language,
                    ucfirst($row->street),
                    $row->city,
                    $row->state,
                    $row->pin_code,
                    $row->diagnosis->title,
                    implode(', ', $row->disciplines_name) ,
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
            'Street',
            'City',
            'State',
            'Zip Code',
            'Diagnosis',
            'Disciplines',
            'Created On',
        ];
    }
}
?>
