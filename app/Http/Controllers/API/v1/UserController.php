<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Notifications\SignupActivate;
use App\Mail\ForgotPassword;
use App\Mail\VerifyMail;
use App\User;
use App\Diagnose;
use App\Countyareas;
use App\FcmUser;
use App\PatientProfile;
use App\Caregiver;
use App\Relation;
use App\UserRelation;
use App\Booking;
use App\Qualification;
use Image;
use Validator;
use DB;
use Carbon\Carbon;
use App\Service_requests;
use App\Service_requests_attributes;
use App\AssignedCaregiver;

class UserController extends Controller{
    public $successStatus = 200;
    public $errorStatus = 400;

    /**
     * Register api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:40',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'type' => ['required', Rule::in(['caregiver', 'patient'])],
        ]);

        $user_exist = User::where('email', $request->email)->first();
        if($user_exist){
            if($user_exist->email_verified == 0)
            return response()->json(['status_code' => 300, 'message' => 'Your email is not verified . Please verify your email first.', 'data' => null]);

            else
            return response()->json(['status_code' => 400, 'message' => 'Email already exists.', 'data' => null]);    
        }

        if ($validator->fails())
            return response()->json(['status_code'=> $this->errorStatus, 'message'=> $validator->errors()->first(), 'data' => null]);

        $input = $request->all();

        if($input['type'] == 'caregiver' )
            $input['role_id'] = 2;
        else
            $input['role_id'] = 3;

        $input['password'] = Hash::make($input['password']);
        $input['email_activation_token'] = str_random(60);
        $user = User::create($input);

        if($user && $input['type'] == 'patient'){
            $profile['user_id'] = $user->id;
            $profile['f_name'] = $input['name'];
            $profile = PatientProfile::create($profile);
        }elseif($user && $input['type'] == 'caregiver'){
            $caregiver['user_id'] = $user->id;
            $caregiver['first_name'] = $input['name'];
            $caregiver = Caregiver::create($caregiver);
        }

        if ($user) {
            User::where('email',$input['email'])->update(['otp' => rand(1000,9999)]);
            $user = User::where('email', $input['email'])->first();
            Mail::to($input['email'])->send(new VerifyMail($user));
            return response()->json(['status_code' => 300, 'message' => 'Your email is not verified . Please verify your email first.', 'data' => null]);
        } else {
            return response()->json(['status_code' => $this->errorStatus, 'message' => 'Unable to register. Please try again.', 'data'=> null]);
        }
    }

    /**
     * login api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6',
            'type' => ['required', Rule::in(['caregiver', 'patient'])]
        ]);

        if ($validator->fails())
            return response()->json(['status_code'=> $this->errorStatus, 'message'=> $validator->errors()->first(), 'data' => null]);

            $input = $request->input();

        if (Auth::attempt(['email' => $input['email'], 'password' => $input['password'] , 'type' => $input['type']])) {
            $user = Auth::user();
            if($user->email_verified){

                if ($user->is_blocked) {
                    return response()->json(['status_code' => 999, 'message' => 'Your account is blocked by admin. Please contact to admin: admin@gmail.com.', 'data' => null]);
                } else {
                    DB::table('oauth_access_tokens')
                        ->where('user_id', $user->id)
                        ->update([
                            'revoked' => 1
                        ]);
                    
                    $token = $user->createToken($user->name)->accessToken;
                    $data = Self::getAllListData();

                    if($input['type'] == 'patient'){

                        $userDetails =  User::where('users.id', Auth::id())->join('patients_profiles', 'users.id', 'user_id')->first();
                        
                        $success['token'] =  $token;
                        if($userDetails == null){
                            $user->height = '';
                            $user->weight = '';
                            $user->language = '';
                            $user->alt_contact_name = '';
                            $user->alt_contact_no = '';
                            $success['userDetails'] =  $user;
                        }else{
                            $success['userDetails'] =  $userDetails ;
                        }
                        $success['relations'] =  $data['relations'];
                        $success['user_added_relations'] =  $data['user_added_relations'];
                        $success['services'] =  $data['services'];
                        $success['diagnosis'] =  $data['diagnosis'];
                        $success['service_area'] =  $data['county'];
                        $success['height'] = PROFILE_HEIGHT;
                        $success['weight'] = PROFILE_WEIGHT;
                        $success['language'] = PROFILE_LANGUAGE;
                    }else{
                        $userDetails =  User::where('users.id', Auth::id())->first();
                        $userDetails['service_in'] = DB::table('caregiver_attributes')
                                    ->select('county_areas.id','county_areas.area')
                                    ->join('county_areas', 'county_areas.id','caregiver_attributes.value')
                                    ->where('caregiver_id', '=', $userDetails->id)
                                    ->where('type', '=', 'service_area')->get();
                        $success['token'] =  $token;
                        $success['userDetails'] =  $userDetails;
                        $success['service_area'] =  $data['county'];
                    }           

                    return response()->json(['status_code' => $this->successStatus, 'message' => '', 'data' => $success]);
                } 
            }else{
                return response()->json(['status_code' => 300, 'message' => 'Your email is not verified . Please verify your email first.', 'data' => null]);
            }
        } else {
            return response()->json(['status_code' => $this->errorStatus, 'message' => 'You have entered invalid credentials.', 'data' => null]);
        }
    }

    /**
     * Forgot / Reset password api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'type' => ['required', Rule::in(['caregiver', 'patient'])]
        ]);

        $userDetails = User::where('email', $request->input('email'))->where('type' ,$request->input('type') )->first();
        if ($userDetails) {
            User::where('email', $request->input('email'))->update(['otp' => rand(1000,9999)]);
            $user = User::where('email', $request->input('email'))->first();
            Mail::to($request->input('email'))->send(new ForgotPassword($user));

            return response()->json(['status_code' => $this->successStatus , 'message' => 'Your one time password has been sent to your mail.', 'data' => null]);
        } else {
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'Unauthorized user.', 'data' => null]);
        }
    }

    /**
     * Verify OTP api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'otp' => 'required|max:4'
        ],[
            'required' => 'Please enter :attribute.'
        ]);

        if ($validator->fails())
            return response()->json(['status_code'=> $this->errorStatus, 'message'=> $validator->errors()->first(), 'data' => null]);

        $check = User::where('email', $request->input('email'))->first();

        if ($check) {
            $otp = User::where('email', $request->input('email'))->where('otp', $request->input('otp'))->first();

            if ($otp) {
                $otp->otp = '';
                $otp->email_verified = 1;
                $otp->save();

                DB::table('oauth_access_tokens')
                    ->where('user_id', $otp->id)
                    ->update([
                        'revoked' => 1
                    ]);

                return response()->json(['status_code' => $this->successStatus, 'message' => 'Otp verified.', 'data'=> null]);
            } else {
                return response()->json(['status_code' => 400, 'message' => 'Incorrect OTP.', 'data'=> null]);
            }
        } else {
            return response()->json(['status_code' => 400, 'message' => 'Please enter registered email Id.', 'data'=> null]);
        }
    }

    /**
     * Reset password api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails())
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);

        $user = User::where('email',$request->input('email'))->first();

        if($user){
            if ($user->is_blocked) {
                return response()->json(['status_code' => 999, 'message' => 'Your account is blocked by admin. Please contact to admin: admin@gmail.com.', 'data' => null]);
            } else {
                User::where('email', $request->input('email'))->update(['password' => Hash::make($request->input('password'))]);
                return response()->json(['status_code' => $this->successStatus, 'message' => 'Password changed successfully.', 'data' => null]);
            }
        } else {
            return response()->json(['status_code' => 400 , 'message' => 'Unauthorized user.', 'data' => null]);
        }
    }

    /**
     * change password api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6',
        ]);

        if ($validator->fails())
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);

        $user = Auth::user();

        if (!empty($user)) {
            if (Hash::check($request->input('old_password'), Auth::user()->password)) {
                $user = User::findOrFail(Auth::id());
                $user->password = Hash::make($request->input('new_password'));
                $user->save();

                return response()->json(['status_code' => $this->successStatus , 'message' => 'Password updated successfully.', 'data' => null]);
            } else {
                return response()->json(['status_code' => 400 , 'message' => 'Old password is not correct.', 'data' => null]);
            }
        } else {
            return response()->json(['status_code' => 400 , 'message' => 'Unauthorized user.', 'data' => null]);
        }
    }

    /**
     * logout api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->OauthAcessToken()->delete();
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Logged out successfully.', 'data' => null]);
        } else {
            return response()->json(['status_code' => 400 , 'message' => 'Unauthorized user.', 'data' => null]);
        }
    }

    /**
     * Upload user profile image api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadProfileImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_image' => 'required',
        ]);

        if ($validator->fails())
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);

        $user = Auth::user();

        $data = $request->input('profile_image');

        $img = str_replace('data:image/jpeg;base64,', '', $data);
        $img = str_replace(' ', '+', $img);

        $data = base64_decode($img);

        $fileName = md5(uniqid(rand(), true));

        $image = $fileName.'.'.'png';

        $file = config('image.user_image_path').$image;

        $success = file_put_contents($file, $data);

        $img = Image::make(config('image.user_image_path').$image);

        $user->profile_image = $image;

        if ($user->save()) {
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Profile image updated successfully.', 'data' => $user->profile_image]);
        } else {
            return response()->json(['status_code' => 400 , 'message' => 'Profile image cannot be uploaded. Please try again!', 'data' => null]);
        }
    }


    /**
     * edit user profile details api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editProfileDetails(Request $request)
    {
        $input = $request->input(); 
        $user = Auth::user();
        $user->fill($input);
        $user->save();

        if($user->type == 'patient'){
            if($user->patient){
                $user->patient->where('user_id',$user->id)->first()->fill($input)->save();
            }else{
                $userPatient = new PatientProfile;
                $userPatient->user_id = $user->id;
                $userPatient->f_name = $user->name;
                $userPatient->save();
            }

            $user = User::where('users.id', Auth::id())->join('patients_profiles', 'users.id', 'user_id')->first();
        }else{
            $input['first_name'] = $user->name;
            Caregiver::where('user_id',$user->id)->first()->fill($input)->save();

            DB::table('caregiver_attributes')->where('caregiver_id', '=', $user->id)->where('type', '=', 'service_area')->delete();
            if($request->exists('service_in')){                
                $service_area = $input['service_in'];
                foreach($service_area as $area){
                    $data[] = array(
                        'caregiver_id' => $user->id,
                        'value' => $area,
                        'type' => 'service_area'
                    );
                }
                DB::table('caregiver_attributes')->insert($data);
            }
            
            $user['service_in'] = DB::table('caregiver_attributes')->select('county_areas.id','county_areas.area')->join('county_areas', 'county_areas.id','caregiver_attributes.value')->where('caregiver_id', '=', $user->id)->where('type', '=', 'service_area')->get();
        }

        if ($user)
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Profile details updated successfully.', 'data' => $user]);
        else
            return response()->json(['status_code' => 400 , 'message' => 'Profile details cannot be updated. Please try again!', 'data' => null]);
    }

    /**
     * User Relations API
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addUserRelation(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'mobile_number' => 'required|min:9|unique:user_relations',
            'relation' => 'required'
        ]);

        if ($validator->fails())
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);

        $user = Auth::user();
        $input = $request->input(); 
        $input['relation'] = Relation::where('title', $input['relation'])->pluck('id')[0];
        $addedRelation = UserRelation::where('name', $input['name'])->where('relation_id', $input['relation'])->where('user_id', $user->id)->get();

        if(count($addedRelation) > 0){
            return response()->json(['status_code'=> 400, 'message'=> 'This name already exists for the selected relation.', 'data' => null]);
        }
 
        $data['user_id'] = $user->id;
        $data['name'] = $input['name'];
        $data['mobile_number'] = $input['mobile_number'];
        $data['relation_id'] = $input['relation'];
        $relation = UserRelation::create($data);

        $relation = UserRelation::select('user_relations.*', 'relations.title')->join('relations' , 'relation_id' , 'relations.id')->where('user_id', $user->id)->get();

        if (!empty($relation))
            return response()->json(['status_code' => $this->successStatus , 'message' => 'User relation added successfully. ', 'data' => $relation]);
        else
            return response()->json(['status_code' => 400 , 'message' => 'Unauthorized user.', 'data' => null]);
    }

    /**
     * Delete User Relation API
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroyUserRelation(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->fails())
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);

        $user = Auth::User();
         
        $relationdelete = UserRelation::where('id' , $request->input('id'))->where('user_id' , $user->id)->delete();
        $relation = UserRelation::select('user_relations.*', 'relations.title')->join('relations' , 'relation_id' , 'relations.id')->where('user_id', $user->id)->get();     

        if($relationdelete)
            return response()->json(['status_code' => $this->successStatus , 'message' => 'User relation deleted successfully.' , 'data' => $relation]);
        else
            return response()->json(['status_code' => 400 , 'message' => 'User relation cannot be deleted.']);
    }

    /**
     * details api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        $data = Self::getAllListData();

        if($user->type == 'patient'){

            $userDetails =  User::where('users.id', Auth::id())->join('patients_profiles', 'users.id', 'user_id')->first();
            if($userDetails == null){
                $user->height = '';
                $user->weight = '';
                $user->language = '';
                $user->alt_contact_name = '';
                $user->alt_contact_no = '';
                $success['userDetails'] =  $user;
            }else{
                $success['userDetails'] =  $userDetails ;
            }
            $success['relations'] =  $data['relations'];
            $success['user_added_relations'] =  $data['user_added_relations'];
            $success['services'] =  $data['services'];
            $success['diagnosis'] =  $data['diagnosis'];
            $success['service_area'] =  $data['county'];
            $success['height'] = PROFILE_HEIGHT;
            $success['weight'] = PROFILE_WEIGHT;
            $success['language'] = PROFILE_LANGUAGE;
        }else{
            $userDetails =  User::where('users.id', Auth::id())->first();
            $userDetails['service_in'] = DB::table('caregiver_attributes')
                        ->select('county_areas.id','county_areas.area')
                        ->join('county_areas', 'county_areas.id','caregiver_attributes.value')
                        ->where('caregiver_id', '=', $userDetails->id)
                        ->where('type', '=', 'service_area')->get();
            $success['userDetails'] =  $userDetails;
            $success['service_area'] =  $data['county'];
        }          
        if (!empty($user))
            return response()->json(['status_code' => $this->successStatus , 'message' => '', 'data' => $success]);
        else
            return response()->json(['status_code' => 400 , 'message' => 'Unauthorized user.', 'data' => null]);
    }

    /**
     * listing data api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAllListData()
    {
        $success['county'] = Countyareas::select('id','area')->where('area', '!=' ,'0')->where('is_area_blocked', '=', '1')->orderBy('area', 'ASC')->get();

        $success['services'] = DB::table('services')->select('id', 'title', 'description', 'service_image')->where('is_blocked', '=', '0')->orderBy('title', 'asc')->get();

        $success['diagnosis'] = Diagnose::select('id', 'title')->where('is_blocked',0)->orderBy('title', 'asc')->get();

        $success['relations'] = Relation::pluck('title');

        $success['user_added_relations'] = UserRelation::select('user_relations.*', 'relations.title')->join('relations' , 'relation_id' , 'relations.id')->where('user_id', Auth::id())->get();

        return $success;
    }


    /**
     * Booking API 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function booking(Request $request){
        $input = $request->input();
        $user = Auth::user();
        $validator =  Validator::make($input,
            [
                'relation_id' => 'required|string',
                'booking_type' => 'required',
                'height' =>'required',
                'weight' =>'required',
                'pets' => 'required',
                'diagnosis_id' => 'required',
                'service_location_id' => 'required',
                'start_date'=>'required',
                'end_date'=>'required',
                'weekdays' => 'array',
                '24_hours' => 'required',
                'start_time' => 'required',
                'end_time' =>'required',
                'no_of_weeks' =>'required',
                'address' => 'required',
                'country' => 'required',
                'state' => 'required',
                'city' => 'required',
                'zip_code' => 'required',
                'timezone' => 'required'
            ]
        );

        if($input['booking_type'] == 'Select from week' )
            $validator = Validator::make($input, ['weekdays.0' => 'required'],['weekdays.0.required' => 'Weekdays is required.']);

        if ($validator->fails()) {
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);
        }

        if($input['relation_id'] == 'Myself' )
            $input['relation_id'] = null;

        if(($input['booking_type'] == 'Daily') || ($input['booking_type'] == 'Select date') || ($input['booking_type'] == 'Select from week')){
            if($input['24_hours'] == '1'){
                $input['start_time'] = '00:00:00';
                $input['end_time'] = '23:59:59';
            }
        }

        if($input['booking_type'] == 'Select from week'){
             $input['start_date'] = Carbon::now()->format('m/d/Y');
             $input['end_date'] = Carbon::now()->addweek($input['no_of_weeks'])->format('m/d/Y');
        }

        foreach ($input['diagnosis_id'] as $key => $value) {
            $input['diagnosis'][] = Diagnose::select('id')->where('title', 'like', '%'.$value.'%')->first()->id;
        }

        $input['service_location_id'] = Countyareas::select('id')->where('area', 'like', '%'.$input['service_location_id'].'%')->first()->id;

        $input['user_id'] = $user->id;
        $input['weekdays'] = serialize($input['weekdays']);
        $input['diagnosis_id'] = serialize($input['diagnosis']);
        $input['status'] = 'Booking Request';
        $booking = Booking::create($input);

        if($booking){
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Booking created successfully.', 'data' => null]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'Booking not created successfully.', 'data' => null]);
        }
    }

    /**
     * MyBookings API 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function my_bookings(){
        $user = Auth::user();

        $bookings = Booking::where('user_id' , $user->id)->get()->toArray();
         
        foreach ($bookings as $key => $value) {
            if($value['weekdays'] != null){
                $data = unserialize($value['weekdays']);
                $bookings[$key]['weekdays'] = $data;

                $bookings[$key]['dates'] = Self::getDates($value['start_date'] , $value['end_date'] , unserialize($value['weekdays']));
            }

            $diagnosis = unserialize($value['diagnosis_id']);
            foreach ($diagnosis as $a => $value) {
                $diagnose[$a]= Diagnose::select('title')->where('id', $value)->get()->toArray()[0]['title'];
            }
            $bookings[$key]['diagnosis_id'] = $diagnose;
        }

        if(count($bookings) > 0){
            return response()->json(['status_code' => $this->successStatus , 'message' => '', 'data' => $bookings]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'No bookings yet.', 'data' => null]);
        }

    }

    public function getDates($startDate , $endDate ,$weekDays)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        
        $today=Carbon::now();
        $data = array();

        while($endDate->gte($startDate))
        {  
            if (in_array($today->format('D'), $weekDays)) {
                $data[]= $startDate->format('Y-m-d');
            }
            $startDate = $startDate->addDay(1);
            $today= $today->addDay(1);
        }
        return $data;
    }

    public function caregiverRequestsList()
    {
        $bookings = Booking::select('id','relation_id', 'start_date', 'end_date', '24_hours', 'start_time', 'end_time','weekdays')->where('status', 'Caregiver Assigned')->where('user_id' , Auth::id())->get();

        foreach ($bookings as $key => $value) {

            if($value['relation_id'] != null){
                $value['booking_for'] = $value->relation->name .' - '. $value->relation->user->name;
            }else{
                $value['booking_for'] = 'Myself';
            }
            if($value['weekdays'] != null){
                $data = unserialize($value['weekdays']);
                $bookings[$key]['weekdays'] = $data;
            }

            foreach ($value->caregivers as $k => $care) {
                $bookings[$key]['caregivers'][$k]['name'] = $care->caregiver->user->name;
                $bookings[$key]['caregivers'][$k]['profile_image'] = $care->caregiver->user->profile_image;
                $bookings[$key]['caregivers'][$k]['language'] = $care->caregiver->language;
                $bookings[$key]['caregivers'][$k]['description'] = $care->caregiver->description;
                $bookings[$key]['caregivers'][$k]['discipline'] = Qualification::select('name')->join('caregiver_attributes' ,'caregiver_attributes.value' , 'qualifications.id')->where('type' , 'qualification')->where('caregiver_id', $care->caregiver->user->id)->get()->toArray();
            }
        }

        if(count($bookings) > 0){
            return response()->json(['status_code' => $this->successStatus , 'message' => '', 'data' => $bookings]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'No bookings.', 'data' => null]);
        }
    }

    public function request_for_booking(Request $request){

        $input = $request->input();
        $assign = AssignedCaregiver::where('booking_id' , $input['booking_id'])->where('caregiver_id', $input['caregiver_id'])->update(array('status' => 'Caregiver Requested'));
        //Status Update
        Booking::where('id', '=', $input['booking_id'])->update(array('status' =>  'Caregiver Requested'));

        if($assign){
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Request sent successfully.', 'data' => '']);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'Request not sent successfully.', 'data' => null]);
        }
    }

    /**
     * set notification status api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function setNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notify' => 'required|boolean',
        ]);

        if ($validator->fails())
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);

        $user = Auth::user();
        $user->is_notify = $request->input('notify');
        $user->save();

        if (!empty($user))
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Notification settings updated successfully.', 'data' => null]);
        else
            return response()->json(['status_code' => 400 , 'message' => 'Notification settings cannot be updated. Please try again.', 'data' => null]);
    }


    //get master list
    //get discipline list
    public function getDisciplineList(Request $request){
        $discipline = DB::table('qualifications')->select('id', 'name')->where('is_blocked', '=', '1')->orderBy('name', 'asc')->get();
        return response()->json(['status_code' => $this->successStatus , 'message' => 'Get list of all enable discipline list', 'data' => $discipline]);
    }

    //get service list
    public function getServices(Request $request){
        $service = DB::table('services')->select('id', 'title', 'description', 'service_image')->where('is_blocked', '=', '0')->orderBy('title', 'asc')->get();
        return response()->json(['status_code' => $this->successStatus , 'message' => 'Get list of all enable services list', 'data' => $service]);
    }

    //get service list
    public function getDiagnose(Request $request){
        $diagnose = DB::table('diagnosis')->select('id', 'title')->where('is_blocked', '=', '1')->orderBy('title', 'asc')->get();
        return response()->json(['status_code' => $this->successStatus , 'message' => 'Get list of all enable diagnose list', 'data' => $diagnose]);
    }

    //get county list
    public function getCounty(Request $request){
        $county = DB::table('county_areas')->select('id', 'county')->where('is_blocked', '=', '1')->where('area', '=', '0')->orderBy('county', 'asc')->get();
        return response()->json(['status_code' => $this->successStatus , 'message' => 'Get list of all enable county list', 'data' => $county]);
    }

    //get county area
    public function getCountyArea(Request $request){
        $validator = Validator::make($request->all(), [
            'county_id' => 'required|string',
        ]);

        if ($validator->fails())
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);

        $input = $request->input();

        $county = DB::table('county_areas')->where('area', '=', '0')->where('id', '=', $input['county_id'])->first();
        if(empty($county)){
            return response()->json(['status_code'=> 400, 'message'=> 'County not found', 'data' => null]);
        }else{
            if($county->is_blocked == 0)
                return response()->json(['status_code'=> 400, 'message'=> 'County is blocked', 'data' => null]);

            $countyareas = DB::table('county_areas')->where('county', '=', $input['county_id'])->where('is_area_blocked', '=', '1')->orderBy('area', 'asc')->get();
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Get list of enable county area list', 'data' => $countyareas]);
        }
    }

    

    public function updateServiceRequest(Request $request){
        $input = $request->input();

        $validator =  Validator::make($input,[
            'request_id' => 'required',
            'service' => 'required|not_in:0',
            'start_date' => 'required',
            'end_date' => 'required|date|after:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
            'min_expected_bill' => 'required|min:0',
            'max_expected_bill' => 'required|min:1|gt:min_expected_bill',
            'location' => 'required',
            'zipcode' => 'required',
            'city' => 'required',
            'state' => 'required',
            'description' => 'required'
        ],
        ['max_expected_bill.gt' => 'The max price must be greater than min price.']);

        if ($validator->fails()) {
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);
        }

        $service_request = Service_requests::findOrFail($input['request_id']);
        $service_request->location = $input['location'];
        $service_request->city = $input['city'];
        $service_request->state = $input['state'];
        $service_request->zip = $input['zipcode'];
        $service_request->service = $input['service'];
        $service_request->min_expected_bill = $input['min_expected_bill'];
        $service_request->max_expected_bill = $input['max_expected_bill'];
        $service_request->start_time = $input['start_time'];
        $service_request->end_time = $input['end_time'];
        //$service_request->start_date = $input['start_date'];
        //$service_request->end_date = $input['end_date'];
        $service_request->description = $input['description'];
        $service_request->updated_at = date('Y-m-d h:i:s');
        $service_request->save();

        return response()->json(['status_code' => $this->successStatus , 'message' => 'Request detail updated successfully.', 'data' => null]);
    }

    public function getRequestDetails(Request $request){
        $input = $request->input();        
        $validator =  Validator::make($input,
            [
                'request_id' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);
        }

        $id = $input['request_id'];


        $services = DB::table('service_requests')->select('service_requests.id', 'service_requests.description', 'service_requests.created_at', 'service_requests.start_time', 'service_requests.end_time', 'service_requests.service', 'service_requests.id', 'service_requests.user_id', 'service_requests.location', 'service_requests.city', 'service_requests.state', 'service_requests.zip', 'service_requests.country', 'service_requests.min_expected_bill', 'service_requests.max_expected_bill', 'service_requests.start_date', 'service_requests.end_date', 'service_requests.status', 'users.name', 'users.email', 'users.mobile_number', 'users.name', 'users.name', 'users.is_blocked', 'services.title as picked_service')->Join('users', 'service_requests.user_id', '=', 'users.id')->Join('services', 'services.id', '=', 'service_requests.service')->where('service_requests.id', $id)->first();
        if(empty($services)){
            return response()->json(['status_code'=> 400, 'message'=> 'Request not Found', 'data' => null]);
        }

        $caregiver_list = 

        $final_caregivers =  DB::table('service_requests_attributes')->select('service_requests_attributes.value', 'users.name', 'users.email')->Join('users', 'users.id', '=', 'service_requests_attributes.value')->where('service_request_id', '=', $id)->where('service_requests_attributes.type', '=', 'caregiver_list')->first();

        $upload_docs = DB::table('service_requests_attributes')->select('service_requests_attributes.*')->where('service_request_id', '=', $id)->where('type', '=', 'carepack_docs')->orderBy('id', 'desc')->get();

        return response()->json(['status_code' => $this->successStatus , 'message' => 'Request detail updated successfully.', 'data' => array('request' => $services, 'final_caregiver' => $final_caregivers, 'upload_docs' => $upload_docs)]);
    }
}
