<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enquiry;

class EnquiryController extends Controller{
    public function index(){
    	$enquiries = Enquiry::orderBy('created_at', 'desc')->get();
    	return view('enquiries.index', compact('enquiries'));
    }

    public function show($id){
    	$enquiry = Enquiry::findOrFail($id);
    	return view('enquiries.view', compact('enquiry'));
    }

    public function destroy($id){
        $enquiry = Enquiry::findOrFail($id);
        if ($enquiry->delete()) {
            $response = array(
                'status' => 'success',
                'message' => 'Inquiry deleted successfully',
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Inquiry can not be deleted, Please try again',
            );
        }
        return json_encode($response);
    }
}