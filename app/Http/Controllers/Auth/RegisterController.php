<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Mail;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
    * Save user.
    *
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $status = 'fail';
        $errors = $userMsg = $redirectUrl = '';
        $rules    = array(
            'name'     => 'required',
            'email'     => 'required|email',
            'password'     => 'required'
        );
        $messages = array(
            'name.required'    => 'Please enter name',
            'email.required'    => 'Please enter email',
            'password.required'    => 'Please enter password'
        );
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails())
        {
            return json_encode(['errors'=> $validator->errors()->getMessages(),'code'=>422,'status'=>'fail','userMsg'=>'']);
        }
        else
        {
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $user = User::create($input);
//            $to = $user->email;
//            $companyAdmin = "Admin";
//            $companyAdminEmail = "admin@admin.com";
//            $subject = 'New Registration';
//            $url = route('verify-email',[hash('sha256',$user->id)]);
//            $link = "<a href=$url>Verify Email</a>";
//            $msg = "Click here this link and verify email $link";
//
//            $headers  = 'MIME-Version: 1.0' . "\r\n";
//            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";          
//            $headers .= "From: ".$companyAdmin."<".$companyAdminEmail.">";
//            mail($to,$subject,$msg,$headers);
                
            $status = "success";
            $userMsg = 'Please verify your email before login';
            $redirectUrl = route('login');
            $resp = array('status' => $status,'errors'=>$errors,'redirect' => $redirectUrl,'userMsg'=> $userMsg);
            return response()->json($resp);
        }
    }
    
    
    /**
     * verify your email.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function verifyEmail($id)
    {
        $data['email_verified_at'] = date("Y-m-d H:i:s");
        User::whereraw('SHA2(id,256) = "'.$id.'"')
            ->update($data);
        return redirect(route('login'));
    }
    
    /**
     * verify your email.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkUser(Request $request)
    {
        $user = User::where('email',$request->input('email'))
                ->whereNotNull('email_verified_at')
            ->first();
        if(sizeof((array)$user) > 0){
            //            $to = $user->email;
//            $companyAdmin = "Admin";
//            $companyAdminEmail = "admin@admin.com";
//            $subject = 'Forgot Password';
//            $url = route('forgot-password',[hash('sha256',$user->id)]);
//            $link = "<a href=$url>Click Here</a>";
//            $msg = "Click here this link and change password $link";
//
//            $headers  = 'MIME-Version: 1.0' . "\r\n";
//            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";          
//            $headers .= "From: ".$companyAdmin."<".$companyAdminEmail.">";
//            mail($to,$subject,$msg,$headers);
            $status = "success";
            $userMsg = 'Email is verified and we send reset link on your register email.';
            $redirectUrl = route('forgot');
            $errors = '';
        } else {
            $status = "fail";
            $userMsg = 'Email is not verified';
            $redirectUrl = '';
            $errors = '';
        }
            $resp = array('status' => $status,'errors'=>$errors,'redirect' => $redirectUrl,'userMsg'=> $userMsg);
            return response()->json($resp);
    }
    
    /**
     * verify your email.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function forgotForm($id)
    {
        $user = User::whereraw('SHA2(id,256) = "'.$id.'"')
            ->first();
        return view('reset',compact('user'));
    }
    
    
    /**
    * Reset user password.
    *
    * @return \Illuminate\Http\Response
    */
    public function resetPassword(Request $request)
    {
        $status = 'fail';
        $errors = $userMsg = $redirectUrl = '';
        $rules    = array(
            'password'     => 'required'
        );
        $messages = array(
            'password.required'    => 'Please enter password'
        );
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails())
        {
            return json_encode(['errors'=> $validator->errors()->getMessages(),'code'=>422,'status'=>'fail','userMsg'=>'']);
        }
        else
        {
            $user = new \stdClass();
            $user->password =  Hash::make($request->input('password'));
            User::where('id',$request->input('Uid'))->update((array)$user);
//            $to = $user->email;
//            $companyAdmin = "Admin";
//            $companyAdminEmail = "admin@admin.com";
//            $subject = 'New Registration';
//            $url = route('verify-email',[hash('sha256',$user->id)]);
//            $link = "<a href=$url>Verify Email</a>";
//            $msg = "Click here this link and verify email $link";
//
//            $headers  = 'MIME-Version: 1.0' . "\r\n";
//            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";          
//            $headers .= "From: ".$companyAdmin."<".$companyAdminEmail.">";
//            mail($to,$subject,$msg,$headers);
                
            $status = "success";
            $userMsg = 'Password update successfully';
            $redirectUrl = route('login');
            $resp = array('status' => $status,'errors'=>$errors,'redirect' => $redirectUrl,'userMsg'=> $userMsg);
            return response()->json($resp);
        }
    }
}
