<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Auth;
use Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
//         $this->middleware('permission:user',['only' => ['create','store','edit','update','destroy']]);
    }
    
    /**
    * User list.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $userId = Auth::user()->id;
        $users = User::select('users.*','roles.name as RoleName')
                ->leftjoin('model_has_roles','model_has_roles.model_id','=','users.id')
                ->leftjoin('roles','roles.id','=','model_has_roles.role_id')
                ->whereNull('users.deleted_at')
                ->where('users.id','!=',$userId)
                ->get();
        return view('users.index',compact('users'));
    }
    
    /**
    * create new user.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('users.create',compact('roles'));
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
            $user->assignRole($request->input('role'));
            
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
            $userMsg = 'User Saved Successfully';
            $redirectUrl = route('create-user');
            $resp = array('status' => $status,'errors'=>$errors,'redirect' => $redirectUrl,'userMsg'=> $userMsg);
            return response()->json($resp);
        }
    }
    
    /**
     * Show User
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::select('users.*','roles.name as RoleName')
                ->leftjoin('model_has_roles','model_has_roles.model_id','=','users.id')
                ->leftjoin('roles','roles.id','=','model_has_roles.role_id')
                ->whereraw('SHA2(users.id,256) = "'.$id.'"')
                ->first();
        return view('users.edit',compact('user','roles'));
    }
    
    /**
    * Edit User and Save
    *
    * @return \Illuminate\Http\Response
    */
    public function edit(Request $request)
    {
        $status = 'fail';
        $errors = $userMsg = $redirectUrl = '';
        $rules    = array(
            'name'     => 'required',
            'email'     => 'required|email'
        );
        $messages = array(
            'name.required'    => 'Please enter name',
            'email.required'    => 'Please enter email'
        );
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails())
        {
            return json_encode(['errors'=> $validator->errors()->getMessages(),'code'=>422,'status'=>'fail','userMsg'=>'']);
        }
        else
        {
            $data = new \stdClass();
            $data->name = ($request->input('name')) ? $request->input('name') : '';
            $data->email = ($request->input('email')) ? $request->input('email') : '';
            if($request->has('password') && !empty($request->input('password'))){
                $data->password = Hash::make($request->input('password'));
            }
            
            $user = User::where('id',$request->input('eid'))->update((array)$data);
            
            $status = "success";
            $userMsg = 'User updated Successfully';
            $redirectUrl = route('user-index');
            $resp = array('status' => $status,'errors'=>$errors,'redirect' => $redirectUrl,'userMsg'=> $userMsg);
            return response()->json($resp);
        }
    }
    
    /**
     * Delete user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        User::select('*')
            ->whereraw('SHA2(id,256) = "'.$id.'"')
            ->delete();
        return redirect(route('user-index'));
    }
   
}
