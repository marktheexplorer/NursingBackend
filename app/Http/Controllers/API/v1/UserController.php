<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\SignupActivate;
use Illuminate\Validation\Rule;
use App\User; 
use App\Helper;
use App\FcmUser;
use Image; 
use Validator;
use DB;

class UserController extends Controller
{
    public $successStatus = 200;

    /** 
     * Register api 
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    {   
        if ($request->input('type') == 'patient') {
            $validator = Validator::make($request->all(), [ 
                'type' => ['required', Rule::in(['caregiver', 'patient'])],
                'name' => 'required', 
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'fcm_reg_id' => 'required|string', 
                'dob' => 'required',
                'gender' => 'required|string',
                'mobile_number' => 'required|numeric|unique:users',
                'profile_image' =>'nullable|url',
            ]);

            if ($validator->fails())
                return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);

            $input = $request->all();

            if($request->has('profile_image') && ($request->input('profile_image') != null)) {
                $path = $input['profile_image'];
                $filename = basename($path); 
                Image::make($path)->save(config('image.user_image_path').$filename);
                $input['profile_image'] = $filename;
            }

            $input['role_id'] = 3; 
            $input['password'] = Hash::make($input['password']);
            $input['email_activation_token'] = str_random(60);
            $input['dob'] = date("Y-m-d", strtotime($input['dob']));
            $user = User::create($input);

            if ($user) {
                $input['user_id'] = $user->id;
                FcmUser::create($input);
                //send otp code
                return response()->json(['status_code' => $this->successStatus, 'message' => 'You are successfully registered.', 'data'=> null]); 
            } else {
                return response()->json(['status_code' => 400, 'message' => 'Unable to register. Please try again.', 'data'=> null]); 
            }          
        } else {
            $validator = Validator::make($request->all(), [ 
                'type' => ['required', Rule::in(['caregiver', 'patient'])],
                'name' => 'required', 
                'email' => 'required|email|unique:users',
                'mobile_number' => 'required|numeric|unique:users',
                'password' => 'required|min:6',
                'fcm_reg_id' => 'required|string', 
                'dob' => 'required',
                'gender' => 'required|string',
            ]);
            
            if ($validator->fails())
                return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);            
            $input = $request->all();
        
            $input['role_id'] = 2; 
            $input['password'] = Hash::make($input['password']);
            $input['email_activation_token'] = str_random(60);
            $input['dob'] = date("Y-m-d", strtotime($input['dob']));
            $user = User::create($input);

            if ($user) {
                $input['user_id'] = $user->id;
                FcmUser::create($input);
                //send otp code
                return response()->json(['status_code' => $this->successStatus, 'message' => 'You are successfully registered.', 'data'=> null]);
            } else {
                return response()->json(['status_code' => $this->errorStatus, 'message' => 'Unable to register. Please try again.', 'data'=> null]); 
            }
        }  
    }

    /** 
     * Send / Resend otp api 
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response 
     */ 
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'username' => 'required',  
        ]);

        if ($validator->fails())
            return response()->json(['status_code'=> $this->errorStatus, 'message'=> $validator->errors()->first(), 'data' => null]);   

        if (is_numeric($request->input('username')))
            $field = 'mobile_number';
        elseif (filter_var($request->input('username'), FILTER_VALIDATE_EMAIL))
            $field = 'email';
        else
            $field = 'email';
    
        $request->merge([$field => $request->input('username')]);

        $user = User::where($field, $request->input('username'))->first();

        if ($user) {
            $user->otp = '4567';
            $user->save();

            if ($field == 'email') {
                if ($user->email_verified != 1) {
                    $user->notify(new SignupActivate($user));
                    return response()->json(['status_code' => $this->errorStatus, 'message' => 'Please verify your email address, link send to your registered email id.', 'data' => null]);
                } else {
                    
                    return response()->json(['status_code' => $this->successStatus, 'message' => 'Otp, send it to your registered email.', 'data'=> null]); 
                }
            } else {
                if ($user->mobile_number_verified != 1) {
                    //send otp to mobile no.
                    return response()->json(['status_code' => 300, 'message' => 'Please verify the mobile number to proceed. Otp, send it to your registered mobile number.', 'data'=> null]);
                } else  {
                    //send otp to mobile no.
                    return response()->json(['status_code' => $this->successStatus, 'message' => 'Otp, send it to your registered mobile number.', 'data'=> null]);
                }
            } 
        } else {
            return response()->json(['status_code' => 400, 'message' => 'No record found.', 'data'=> null]);
        }
    }

    /** 
     * Check otp api 
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response 
     */ 
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'username' => 'required', 
            'otp' => 'required|max:4',
            'type' => ['required', Rule::in([1, 2])],
        ]);

        if ($validator->fails())
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);

        if (is_numeric($request->input('username')))
            $field = 'mobile_number';
        elseif (filter_var($request->input('username'), FILTER_VALIDATE_EMAIL))
            $field = 'email';
        else
            $field = 'email';
    
        $request->merge([$field => $request->input('username')]);       

        $check = User::where($field, $request->input('username'))->first();

        if ($check) { 
            $otp = User::where($field, $request->input('username'))->where('otp', $request->input('otp'))->first();

            if ($otp) {
                $otp->otp = '';
                $otp->mobile_number_verified = 1;
                $otp->save();

                DB::table('oauth_access_tokens')
                    ->where('user_id', $otp->id)
                    ->update([
                        'revoked' => 1
                    ]);
                $success['token'] =  $otp->createToken($otp->name)->accessToken; 
                $success['name'] =  $otp->name;
                $success['email'] = $otp->email;
                $success['mobile_number'] = $otp->mobile_number;
                $success['profile_image'] = $otp->profile_image;
                return response()->json(['status_code' => $this->successStatus, 'message' => 'Otp Verified.', 'data'=> ($request->input('type') == 1) ? $success : null]);
            } else {
                return response()->json(['status_code' => 400, 'message' => 'Incorrect Otp.', 'data'=> null]);
            }
        } else {
            if ($field == 'mobile_number')
                return response()->json(['status_code' => 400, 'message' => 'The mobile number does not register with us.', 'data'=> null]);
            else 
                return response()->json(['status_code' => 400, 'message' => 'The email address does not register with us.', 'data'=> null]);
        }   
    }

    /** 
     * Email verification link api 
     * 
     * @param  token
     * @return \Illuminate\Http\Response 
     */ 
    public function signupActivate($token)
    {
        $user = User::where('email_activation_token', $token)->first();

        if (!$user) {
            flash()->error('The activation link is invalid.');
            return view('verify.email');
        }

        $user->email_verified = true;
        $user->email_activation_token = '';
        $user->save();

        flash()->success('Your account is verified successfully.');
        return view('verify.email');
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
            'username' => 'required',
            'password' => 'required|min:6',
            'fcm_reg_id' => 'required|string' 
        ]);

        if ($validator->fails()) 
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]); 

            $email = $request->input('username');
            $password = $request->input('password');

        if (Auth::attempt(['email' => $email, 'password' => $password])) { 

            $user = Auth::user();
            $input = $request->input(); 
            FcmUser::where('user_id',$user->id)->update(['fcm_reg_id' => $input['fcm_reg_id']]);

            if ($user->is_blocked) {
                return response()->json(['status_code' => 999, 'message' => 'Your account is blocked by admin. Please contact to admin.', 'data' => null]);
            } elseif (!$user->email_verified) {
                $user->notify(new SignupActivate($user));
                return response()->json(['status_code' => 400, 'message' => 'Please verify your email address, link send to your registered email id.', 'data' => null]);
            } else {
                DB::table('oauth_access_tokens')
                    ->where('user_id', $user->id)
                    ->update([
                        'revoked' => 1
                    ]);
                $success['token'] =  $user->createToken($user->name)->accessToken; 
                $success['type'] =  $user->type; 
                $success['name'] =  $user->name;
                $success['email'] = $user->email;
                $success['country_code'] = $user->country_code;
                $success['mobile_number'] = $user->mobile_number;
                $success['profile_image'] = $user->profile_image;

                return response()->json(['status_code' => $this->successStatus, 'message' => '', 'data' => $success]);
            } 
        } else { 
            return response()->json(['status_code' => 400, 'message' => 'Invalid Credentials.', 'data' => null]); 
        } 
    }

    /** 
     * Forgot / Reset password api 
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response 
     */ 
    public function resetPassword(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'username' => 'required', 
            'password' => 'required|min:6',  
        ]);

        if ($validator->fails()) 
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);

        if (is_numeric($request->input('username')))
            $field = 'mobile_number';
        elseif (filter_var($request->input('username'), FILTER_VALIDATE_EMAIL))
            $field = 'email';
        else
            $field = 'email';
    
        $request->merge([$field => $request->input('username')]);

        $user = User::where($field, $request->input('username'))->first(); 
        if ($user) {
            $user->password = Hash::make($request->input('password'));
            $user->save();
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Password reset successfully.', 'data' => null]); 
        } else {
            return response()->json(['status_code' => 400 , 'message' => 'Unauthorized.', 'data' => null]); 
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
                return response()->json(['status_code' => 400 , 'message' => 'Old Password is not correct.', 'data' => null]);
            }
        } else {
            return response()->json(['status_code' => 400 , 'message' => 'Unauthorized', 'data' => null]); 
        }   
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
        if (!empty($user))
            return response()->json(['status_code' => $this->successStatus , 'message' => '', 'data' => $user]); 
        else
            return response()->json(['status_code' => 400 , 'message' => 'Unauthorized', 'data' => null]);
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
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Notification Settings updated successfully.', 'data' => null]); 
        else
            return response()->json(['status_code' => 400 , 'message' => 'Notification Settings cannot be updated. Please try again.', 'data' => null]); 
    }

    /** 
     * edit user profile details api 
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response 
     */ 
    public function editProfileDetails(Request $request) 
    { 
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:30', 
        ]);

        if ($validator->fails()) 
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);

        $input = $request->input();
        $user = Auth::user();
        $user->fill($input);
       
        if ($user->save())
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Profile details updated successfully.', 'data' => $user]); 
        else
            return response()->json(['status_code' => 400 , 'message' => 'Profile details cannot be updated. Please try again!', 'data' => null]); 
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
            return response()->json(['status_code' => 400 , 'message' => 'Unauthorized', 'data' => null]); 
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
            'profile_image' => 'required|image|mimes:jpeg,jpg,png|max:2048', 
        ]);

        if ($validator->fails()) 
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);

        $user = Auth::user();

        $image = $request->file('profile_image');
        $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
        $image->move(config('image.user_image_path'), $input['imagename']);
        $user->profile_image = $input['imagename'];
       
        if ($user->save()) {
            $success['profile_image'] = $user->profile_image;
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Profile image updated successfully.', 'data' => $success]); 
        } else {
            return response()->json(['status_code' => 400 , 'message' => 'Profile image cannot be uploaded. Please try again!', 'data' => null]); 
        }
    }

    /** 
     * get user current location api 
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response 
     */ 
    public function getCurrentLocation(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'current_lat_lng' => 'required|string', 
        ]);

        if ($validator->fails()) 
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);

        $input = $request->input();

        $currentLocation = Helper::geocode($input['current_lat_lng']);

        $input['city'] = $currentLocation['city'];
        $input['state'] = $currentLocation['state'];
        $input['country'] = $currentLocation['country'];

        $user = User::find(Auth::id());

        if ($user->update($input))
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Current location save successfully', 'data' => null]); 
        else 
            return response()->json(['status_code' => 400 , 'message' => 'Current location is not saved. Please try again!', 'data' => null]); 
    }
}
