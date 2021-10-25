<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Auth;
use Mail;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        
    }
    
    

    public function showLoginForm() {
        if (!session()->has('from')) {
            if (url()->previous() != url("/")) {
                session()->put('from', url()->previous());
            }
        }
        return view('login');
    }
    
    public function checkLogin(Request $request){
        $status = 'fail';
        $errors = $userMsg = $redirectUrl = '';
        $rules    = array(
            'email'     => 'required|email',
            'password'  => 'required'
        );
        $messages = array(
            'email.required'    => 'Please enter your email',
            'password.required' => 'Please enter your password'
        );
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails())
        {
            return json_encode(['errors'=> $validator->errors()->getMessages(),'code'=>422,'status'=>'fail','userMsg'=>'']);
        }
        else
        {
            $user_data = array(
                'email' => $request->input('email'),
                'password' => $request->input('password')
            );

            if (Auth::attempt($user_data)) {
                $user = Auth::user();
                if(empty($user->email_verified_at)){
                    $userMsg = 'Please verify email first';
                    $resp = array('status' => $status,'errors'=>$errors,'redirect' => $redirectUrl,'userMsg'=> $userMsg);
                    return response()->json($resp);
                }
                $status = "success";
                $userMsg = 'Welcome '.$user->name;
//                $to = $user->email;
//                $companyAdmin = "Admin";
//                $companyAdminEmail = "admin@admin.com";
//                $subject = 'login Alert';
//                $date = date("Y-m-d H:i:s");
//                $msg = "Hello $user->name,<br>You Login $date";
//
//                $headers  = 'MIME-Version: 1.0' . "\r\n";
//                $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";          
//                $headers .= "From: ".$companyAdmin."<".$companyAdminEmail.">";
//                mail($to,$subject,$msg,$headers);
            } else {
                $userMsg = 'Wrong Login Details';
            }
            $resp = array('status' => $status,'errors'=>$errors,'redirect' => $redirectUrl,'userMsg'=> $userMsg);
            return response()->json($resp);
        }
        
    }
    
    function logout() {
        Auth::logout();
        return redirect('/');
    }
}
