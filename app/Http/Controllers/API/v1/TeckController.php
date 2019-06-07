<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Teck;
use App\Helper;
use Carbon\Carbon;
use Validator;
use Auth;

class TeckController extends Controller
{
    public $successStatus = 200;

    /**
     * get tt details api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function teckDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teck_id' => 'required|numeric',
        ]);

        if ($validator->fails()) 
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);

        $teck = Teck::find($request->input('teck_id'));

        if ($teck)
            return response()->json(['status_code' => $this->successStatus, 'message' => null, 'data'=> $teck]);
        else
            return response()->json(['status_code' => 400, 'message' => 'Teck details not found. Please try again!', 'data'=> null]);
    }

    /**
     * add quick tt api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addQuickTeck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => ['required', Rule::in(['quick'])],
            'title' => 'required|string|max:30',
            'start_location' => 'required|string|max:255',
            'end_location' => 'required|string|max:255',
            'eta' => 'required|string',
            'start_time' => 'required|date_format:h:i A',
            'start_lat_lng' => 'required|string',
            'end_lat_lng' => 'required|string',
            'current_lat_lng' => 'required|string'
        ]);

        if ($validator->fails()) 
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);

        $input = $request->input();
        $input['user_id'] = Auth::id();
        $input['start_date'] = $input['end_date'] = Carbon::now()->format('d-m-Y');
        $input['end_time'] = Carbon::today()->endOfDay()->format('h:i A');

        $startLocation = Helper::geocode($input['start_lat_lng']);
        $currentLocation = Helper::geocode($input['current_lat_lng']);

        $input['start_location_city'] = $startLocation['city'];
        $input['start_location_state'] = $startLocation['state'];
        $input['start_location_country'] = $startLocation['country'];

        if ($currentLocation['state'] == $startLocation['state'])
            $input['status'] = 1;
        else
            $input['status'] = 0; 

        $teck =  Teck::create($input); 

        if ($teck->save())
            return response()->json(['status_code' => $this->successStatus, 'message' => 'Teck created successfully.', 'data'=> null]);
        else
            return response()->json(['status_code' => 400, 'message' => 'Teck cannot created. Please try again!', 'data'=> null]); 
    }

    /**
     * add/edit my tt api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addMyTeck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teck_id'=> 'nullable|numeric',
            'type' => ['required', Rule::in(['my'])],
            'title' => 'required|string|max:30',
            'start_location' => 'required|string|max:255',
            'end_location' => 'required|string|max:255',
            'eta' => 'required|string',
            'start_time' => 'required|date_format:h:i A',
            'end_time' => 'required|date_format:h:i A',
            'threshold_min_time' => 'required|date_format:H:i',
            'threshold_max_time' => 'required|date_format:H:i',
            'repetitions' => ['required', Rule::in(['weekends', 'weekdays', 'recurring'])],
            'start_lat_lng' => 'required|string',
            'end_lat_lng' => 'required|string',
            'current_lat_lng' => 'required|string',
            'threshold_min_minutes' => 'required|string',
        ]);

        if ($validator->fails()) 
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);

        $input = $request->input();

        $createdTime = Carbon::now('+5:30')->format('h:i A');
        $currentTime = Carbon::createFromFormat('h:i A', $createdTime);
        $startTime = Carbon::createFromFormat('h:i A', $input['start_time']);
        $diff = $currentTime->diffInMinutes($startTime);
        
        if ($input['threshold_min_minutes'] > $diff) 
            return response()->json(['status_code'=> 400, 'message'=> 'Threshold min time should be less than '.$diff.' minutes', 'data' => null]);

        $startLocation = Helper::geocode($input['start_lat_lng']);
        $currentLocation = Helper::geocode($input['current_lat_lng']);

        $input['start_location_city'] = $startLocation['city'];
        $input['start_location_state'] = $startLocation['state'];
        $input['start_location_country'] = $startLocation['country'];

        if ($currentLocation['state'] == $startLocation['state'])
            $input['status'] = 1;
        else
            $input['status'] = 0; 

        if ($request->has('teck_id') && ($request->input('teck_id') == null)) {

            $input['user_id'] = Auth::id();
            $input['start_date'] = Carbon::now()->format('d-m-Y');

            $teck =  Teck::create($input);

            if ($teck->save()) 
                return response()->json(['status_code' => $this->successStatus, 'message' => 'Teck created successfully', 'data'=> null]);
            else 
                return response()->json(['status_code' => 400, 'message' => 'Teck cannot created. Please try again!', 'data'=> null]); 
        } else {
            $teck = Teck::where('id', $input['teck_id'])->update($input);

            if ($teck) 
                return response()->json(['status_code' => $this->successStatus, 'message' => 'Teck updated successfully', 'data'=> null]);
            else 
                return response()->json(['status_code' => 400, 'message' => 'Teck cannot be updated. Please try again!', 'data'=> null]); 
        }
    }

    /**
     * delete teck api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteTeck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teck_id' => 'required|numeric'
        ]);

        if ($validator->fails()) 
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);
        
        $teck = Teck::find($request->input('teck_id'));

        if ($teck) {
            if ($teck->delete()) {
                return response()->json(['status_code'=> 200, 'message'=> 'Teck deleted successfully.', 'data' => null]);
            } else {
                return response()->json(['status_code'=> 400, 'message'=> 'Teck can not deleted.Please try again!', 'data' => null]);
            }
        } else {
            return response()->json(['status_code'=> 400, 'message'=> 'Teck does not exist.', 'data' => null]);
        }
    }

    /**
     * notify Teck api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function notifyTeck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teck_id' => 'required|numeric',
            'is_notify' => 'required|boolean',
        ]);

        if ($validator->fails()) 
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);
        
        $teck = Teck::where('id', $request->input('teck_id'))->update(['is_notify' => $request->input('is_notify')]);

        if ($teck) {
            return response()->json(['status_code'=> 200, 'message'=> 'Teck notification updated successfully.', 'data' => null]);
        } else {
            return response()->json(['status_code'=> 400, 'message'=> 'Teck cannot be updated. Please try again!', 'data' => null]);
        }
    }

    /**
     * make active or inactive Teck api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function inactivateTeck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teck_id' => 'required|numeric',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) 
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);
        
        $teck = Teck::where('id', $request->input('teck_id'))->update(['is_active' => $request->input('is_active')]);

        if ($teck) {
            return response()->json(['status_code'=> 200, 'message'=> 'Teck status updated successfully.', 'data' => null]);
        } else {
            return response()->json(['status_code'=> 400, 'message'=> 'Teck status cannot be updated. Please try again!', 'data' => null]);
        }
    }  
}
