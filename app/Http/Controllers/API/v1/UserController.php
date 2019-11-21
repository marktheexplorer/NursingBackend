<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\User;
use App\Diagnose;
use App\Countyareas;
use App\PatientProfile;
use App\Caregiver;
use App\Relation;
use App\UserRelation;
use Image;
use Validator;
use DB;
use Carbon\Carbon;
use Twilio\Rest\Client;

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
            'email' => 'required|email|unique:users',
            'mobile_number' => 'required|unique:users',
            'country_code' => 'required',
            'type' => ['required', Rule::in(['caregiver', 'patient'])],
        ]);        

        if ($validator->fails())
            return response()->json(['status_code'=> $this->errorStatus, 'message'=> $validator->errors()->first(), 'data' => null]);

        $input = $request->all();

        if($input['type'] == 'caregiver' )
            $input['role_id'] = 2;
        else
            $input['role_id'] = 3;

        $input['otp'] = rand(1000,9999);
        $data = Self::sendTwilioOTP($input['mobile_number'], $input['country_code'], $input['otp']); 
        if($data == false){
            return response()->json(['status_code' => $this->errorStatus, 'message' => 'Your mobile number is invalid.', 'data' => null]);
        }
        $user = User::create($input);

        if($user && $input['type'] == 'patient')
        {
            $profile['user_id'] = $user->id;
            $profile['f_name'] = $input['name'];
            $profile = PatientProfile::create($profile);
        } elseif($user && $input['type'] == 'caregiver')
        {
            $caregiver['user_id'] = $user->id;
            $caregiver['first_name'] = $input['name'];
            $caregiver = Caregiver::create($caregiver);
        }

        if ($user) {
            
            return response()->json(['status_code' => 300, 'message' => 'Please verify the mobile number to proceed. Otp, send it to your registered mobile number.', 'data' => null]);
        } else {
            return response()->json(['status_code' => $this->errorStatus, 'message' => 'Unable to register. Please try again.', 'data'=> null]);
        }
    }

    public function sendTwilioOTP($mobileNumber , $countryCode, $otp)
    {   
        $client = new Client(env('TWILIO_SID'), env('TWILIO_TOKEN'));

        try{
            $response = $client->messages->create(
                // the number you'd like to send the message to
                '+'.$countryCode.$mobileNumber ,
                array(
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => '+13343397984',
                    // the body of the text message you'd like to send
                    'body' => 'Your OTP is '.$otp.'. Enter this code to verify your phone number.'
                )
            )->toArray();

        }catch(\Exception $e){
            $response = false;
        }

        return $response;

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
            'country_code' => 'required|numeric',
            'mobile_number' => 'required|numeric',
            'otp' => 'required|max:4'
        ],[
            'required' => 'Please enter :attribute.'
        ]);

        if ($validator->fails())
            return response()->json(['status_code'=> $this->errorStatus, 'message'=> $validator->errors()->first(), 'data' => null]);

        $check = User::where('mobile_number', $request->input('mobile_number'))->where('country_code', $request->input('country_code'))->first();

        if ($check) {
            $user = User::where('mobile_number', $request->input('mobile_number'))->where('country_code', $request->input('country_code'))->where('otp', $request->input('otp'))->first();

            if ($user) {
                $user->otp = '';
                $user->mobile_number_verified = 1;
                $user->save();

                DB::table('oauth_access_tokens')
                    ->where('user_id', $user->id)
                    ->update([
                        'revoked' => 1
                    ]);

                $token = $user->createToken($user->name)->accessToken;
                $data = Self::getAllListData($user->id);

                if($user->type == 'patient'){

                    $userDetails =  User::where('users.id', $user->id)->join('patients_profiles', 'users.id', 'user_id')->first(); 
                    if($userDetails->profile_image == null)
                        $userDetails->profile_image = 'default.png';
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
                    if($userDetails['profile_image'] == null)
                        $userDetails['profile_image'] = 'default.png';
                    $userDetails['service_in'] = DB::table('caregiver_attributes')
                                ->select('county_areas.id','county_areas.area')
                                ->join('county_areas', 'county_areas.id','caregiver_attributes.value')
                                ->where('caregiver_id', '=', $userDetails->id)
                                ->where('type', '=', 'service_area')->get();
                    $success['token'] =  $token;
                    $success['userDetails'] =  $userDetails;
                    $success['service_area'] =  $data['county'];
                } 

                return response()->json(['status_code' => $this->successStatus, 'message' => 'Otp verified.', 'data'=> $success]);
            } else {
                return response()->json(['status_code' => 400, 'message' => 'Incorrect OTP.', 'data'=> null]);
            }
        } else {
            return response()->json(['status_code' => 400, 'message' => 'Please enter registered mobile number.', 'data'=> null]);
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
            'country_code' =>'required|numeric',
            'mobile_number' => 'required|numeric',
            'type' => ['required', Rule::in(['caregiver', 'patient'])]
        ]);

        if ($validator->fails())
            return response()->json(['status_code'=> $this->errorStatus, 'message'=> $validator->errors()->first(), 'data' => null]);

            $input = $request->input();
            $user = User::where('mobile_number', $input['mobile_number'])->where('country_code', $input['country_code'])->where('type' , $input['type'])->first();
        if ($user) {
            if ($user->is_blocked) {
                return response()->json(['status_code' => 999, 'message' => 'Your account is blocked by admin. Please contact to admin: admin@gmail.com.', 'data' => null]);
            } else {
                DB::table('oauth_access_tokens')
                    ->where('user_id', $user->id)
                    ->update([
                        'revoked' => 1
                    ]);
                    $input['otp'] = rand(1000,9999);

                    $user->otp = $input['otp'];
                    $user->save();
               
                    $data = Self::sendTwilioOTP($input['mobile_number'], $input['country_code'], $input['otp']); 
                return response()->json(['status_code' => $this->successStatus, 'message' => 'Please verify the mobile number to proceed. Otp, send it to your registered mobile number.', 'data' => '']);
            } 
        } else {
            return response()->json(['status_code' => $this->errorStatus, 'message' => 'You have entered invalid credentials.', 'data' => null]);
        }
    }

    /**
     * Resend OTP api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_code' => 'required|numeric',
            'mobile_number' => 'required|numeric',
        ]);

        if ($validator->fails())
            return response()->json(['status_code'=> $this->errorStatus, 'message'=> $validator->errors()->first(), 'data' => null]);
        $input = $request->input();
        $user = User::where('mobile_number', $request->input('mobile_number'))->where('country_code', $request->input('country_code'))->first();

        if ($user) {

            $input['otp'] = rand(1000,9999);

            $user->otp = $input['otp'];
            $user->save();
       
            $data = Self::sendTwilioOTP($input['mobile_number'], $input['country_code'], $input['otp']); 

            return response()->json(['status_code' => 200, 'message' => 'Please verify the mobile number to proceed. Otp, send it to your registered mobile number.', 'data'=> null]);
            
        } else {
            return response()->json(['status_code' => 400, 'message' => 'Please enter registered mobile number.', 'data'=> null]);
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
            if($user['profile_image'] == null)
                $user['profile_image'] = 'default.png';
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
        $data = Self::getAllListData($user->id);

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
            if($userDetails['profile_image'] == null)
                $userDetails['profile_image'] = 'default.png';
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
    public function getAllListData($userId)
    {
        $success['county'] = Countyareas::select('id','area')->where('area', '!=' ,'0')->where('is_area_blocked', '=', '1')->orderBy('area', 'ASC')->get();

        $success['services'] = DB::table('services')->select('id', 'title', 'description', 'service_image')->where('is_blocked', '=', '0')->orderBy('title', 'asc')->get();

        $success['diagnosis'] = Diagnose::select('id', 'title')->where('is_blocked',0)->orderBy('title', 'asc')->get();

        $success['relations'] = Relation::pluck('title');

        $success['user_added_relations'] = UserRelation::select('user_relations.*', 'relations.title')->join('relations' , 'relation_id' , 'relations.id')->where('user_id', $userId)->get();

        return $success;
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

}
