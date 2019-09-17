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
use App\User;
use App\Helper;
use App\FcmUser;
use App\PatientProfile;
use App\Caregiver;
use Image;
use Validator;
use DB;

class UserController extends Controller
{
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
            'password' => 'required|min:6',
            'type' => ['required', Rule::in(['caregiver', 'patient'])],
        ]);

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
            return response()->json(['status_code' => $this->successStatus, 'message' => 'You are successfully registered.', 'data'=> null]);
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

            $email = $request->input('email');
            $password = $request->input('password');

        if (Auth::attempt(['email' => $email, 'password' => $password])) {

            $user = Auth::user();

            if ($user->is_blocked) {
                return response()->json(['status_code' => 999, 'message' => 'Your account is blocked by admin. Please contact to admin.', 'data' => null]);
            } else {
                DB::table('oauth_access_tokens')
                    ->where('user_id', $user->id)
                    ->update([
                        'revoked' => 1
                    ]);
                $success['token'] =  $user->createToken($user->name)->accessToken;
                $success['name'] =  $user->name;
                $success['email'] = $user->email;

                return response()->json(['status_code' => $this->successStatus, 'message' => '', 'data' => $success]);
            }
        } else {
            return response()->json(['status_code' => $this->errorStatus, 'message' => 'Invalid Credentials.', 'data' => null]);
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
        ]);

        $userDetails = User::where('email', $request->input('email'))->first();
        if ($userDetails) {
            User::where('email', $request->input('email'))->update(['otp' => rand(1000,9999)]);
            $user = User::where('email', $request->input('email'))->first();
            Mail::to($request->input('email'))->send(new ForgotPassword($user));

            return response()->json(['status_code' => $this->successStatus , 'message' => 'Your One Time Password has been sent to your mail.', 'data' => null]);
        } else {
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'Unauthorized.', 'data' => null]);
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
                $otp->save();

                DB::table('oauth_access_tokens')
                    ->where('user_id', $otp->id)
                    ->update([
                        'revoked' => 1
                    ]);

                return response()->json(['status_code' => $this->successStatus, 'message' => 'Otp Verified.', 'data'=> null]);
            } else {
                return response()->json(['status_code' => 400, 'message' => 'Incorrect Otp.', 'data'=> null]);
            }
        } else {
            return response()->json(['status_code' => 400, 'message' => 'Please enter registered email Id.', 'data'=> null]);
        }
    }

    /**
     * change password api
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
                return response()->json(['status_code' => 999, 'message' => 'Your account is blocked by admin. Please contact to admin.', 'data' => null]);
            } else {
                User::where('email', $request->input('email'))->update(['password' => Hash::make($request->input('password'))]);
                return response()->json(['status_code' => $this->successStatus, 'message' => 'Password changed successfully.', 'data' => null]);
            }
        } else {
            return response()->json(['status_code' => 400 , 'message' => 'Unauthorized', 'data' => null]);
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
