<?php
namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class RequestExport implements FromCollection, WithHeadings, ShouldAutoSize{
    public function collection(){
        $services = DB::table('service_requests')->select('service_requests.id', 'service_requests.description', 'service_requests.created_at', 'service_requests.start_time', 'service_requests.end_time', 'service_requests.service', 'service_requests.id', 'service_requests.user_id', 'service_requests.location', 'service_requests.city', 'service_requests.state', 'service_requests.zip', 'service_requests.country', 'service_requests.min_expected_bill', 'service_requests.max_expected_bill', 'service_requests.start_date', 'service_requests.end_date', 'service_requests.status', 'users.name', 'users.email', 'users.mobile_number', 'users.name', 'users.name', 'users.is_blocked', 'services.title')->Join('users', 'service_requests.user_id', '=', 'users.id')->Join('services', 'services.id', '=', 'service_requests.service')->get();

        $output = array();
        if(!empty($services)){
            $count = 1;
            foreach ($services as $row) {
                $caregivername = 'NA';
                if(!empty($final_caregivers)){
                    $caregivername = ucfirst($final_caregivers->name)." (".$final_caregivers->email.")<br/>";
                }

                switch($row->status){
                    case '0':
                        $request_status = "Pending";
                        break;
                    case '1':
                        $request_status = "Reject";
                        break;    
                    case '2':
                        $request_status = "Approved";
                        break;    
                    case '3':
                        $request_status = "Caregiver not Assign";
                        break;
                    case '4':
                        $request_status = "Assign to Caregiver"; 
                        break;       
                    case '5':
                        $request_status = "Caregiver confirm and sent mail of basic careservice pack";
                        break;    
                    case '6':
                        $request_status = "Document upload by patient, but document not varified";
                        break;        
                    case '7':
                        $request_status = "Uploaded document varified";
                        break;            
                    case '8':
                        $request_status = "Re-schedule";
                    case '9':
                        $request_status = "Close";    
                        break;  
                }

                $range = "$".$row->min_expected_bill." to $".$row->max_expected_bill;
                $shift = substr_replace($row->start_time, ":", 2, 0)." to ".substr_replace($row->end_time,  ":", 2, 0);

                $output[] = array(
                    $count.".", 
                    ucfirst(str_replace(",", " ", $row->name)), 
                    $caregivername,
                    $row->location,
                    $row->city,
                    $row->state,
                    $row->country,
                    $row->zip,
                    $range,
                    $shift,
                    date_format(date_create($row->start_date), 'd M, Y'),
                    date_format(date_create($row->start_date), 'd M, Y'),
                    $request_status,
                    date_format(date_create($row->created_at), 'd M, Y')
                );
                $count++;
            } 
        }

        return collect([$output]);
    }

    public function headings(): array{
        return [
            'S. No.', 
            'Patient Name', 
            'Caregiver Name', 
            'Street',
            'City',
            'State',
            'Country',
            'Pin Code',
            'Price Range',
            'Shift',
            'From',
            'To',
            'status',
            'Created On',
        ];
    }
}
?>