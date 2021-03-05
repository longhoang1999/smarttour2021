<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Models\Route;
use App\Models\Feedback;
use App\Models\User;
use App\Models\Destination;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use PHPMailer;




class UserController extends Controller
{
    public function langVN(Request $req)
    {
        \Session::put('website_language', 'vi');
        return true;
    }
    public function langEN(Request $req)
    {
        \Session::put('website_language', 'en');
        return true;
    }
    public function login()
    {
        $des = Destination::select('de_name','de_image','de_description','de_shortdes','de_duration','de_link')->get();
        return view("generalinterface",['des'=>$des]);
    }
    // public function viewlogin()
    // {
    //     return view("login");
    // }
    public function postLogin(Request $req)
    {
    	$this->validate($req,[
    		'us_email'=>'required|email',
    		'us_password'=>'required|min:0|max:32',
    	],[
    		'us_email.required' => 'Bạn phải nhập email',
    		'us_email.email' => 'Sai cấu chúc email',
    		'us_password.required' => "Bạn phải nhập mật khẩu",
    		'us_password.min' => "Mật khẩu quá ngắn",
    		'us_password.max' => "Mật khẩu quá dài"
    	]);
    	if(Auth::attempt(['us_email'=>$req->us_email,'us_password'=>$req->us_password]))
    	{
            $user = Auth::user();
            if($user->us_type == "0")
            {
                $route = Route::where('ro_id_user',$user->us_id)->get();
                return redirect()->route('user.dashboard')->with("route",$route);
            }
            else
            {
                return redirect()->route('admin.generalInfor');
            }
    	}
    	else
    	{
    		return back()->with("error","Đăng nhập không thành công");
    	}
    	
    }
    public function dashboard()
    {
        $user = Auth::user();
        $route = Route::where('ro_id_user',$user->us_id)->get();
        session()->put('route',$route);
        $des = Destination::select('de_name','de_image','de_description','de_shortdes','de_duration','de_link')->get();
        return view('dashboard',['fullName'=>$user->us_fullName,'des'=>$des]);
    }
    public function logout(){
        Auth::logout();
        session()->flush();
        return redirect()->route('login');
    }
    public function feedback(Request $req)
    {
        $user = Auth::user();
        $feedback = new Feedback();
        $feedback->fb_us_id = $user->us_id;
        $feedback->star = $req->star;
        $feedback->content = $req->feedback;
        $feedback->dateCreated = date('Y-m-d', strtotime(Carbon::now()));
        $feedback->save();
        return back()->with("notification","You have submitted your response successfully");
    }
    public function saveTour(Request $req)
    {
        $user = Auth::user();
        $route = new Route();
        $route->ro_id_user = $user->us_id;
        $i=1;
        foreach ($req->locatsList as  $value) {
            $ro_route = "ro_route_".$i;
            $route->$ro_route = $req->locatsList[$i-1];
            $i++;
        }
        $route->dateCreated = date('Y-m-d', strtotime(Carbon::now()));
        $route->save();
    }
    
    public function register(Request $req)
    {
        $this->validate($req,[
            'email'=>'required|email',
            'password'=>'required|min:0|max:32',
            'confirm' =>'required|min:0|max:32',
            'fullname' => 'required',
            'age' => 'required',
        ],[
            'email.required'=>'Bạn chưa nhập email',
            'email.email'=>'Sai cấu chúc email',
            'password.required'=>'Bạn chưa nhập password',
            'password.min'=>'Password không được nhỏ hơn 0 kí tự',
            'password.max'=>'Password không được lớn hơn 32 kí tự',
            'confirm.required'=>'Bạn chưa nhập password',
            'confirm.min'=>'Password không được nhỏ hơn 0 kí tự',
            'confirm.max'=>'Password không được lớn hơn 32 kí tự',
            'fullname.required' =>"Bạn chưa nhập họ tên",
            'age.required' => "Bạn chưa nhập tuổi"
        ]);
        if($req->password != $req->confirm)
        {
            return back()->with("error","Xác nhận mật khẩu sai");
        }
        else
        {
            $checkEmail = User::where("us_email",$req->email)->first();
            if(empty($checkEmail))
            {
                $user = new User();
                $user->us_email = $req->email;
                $user->us_password  = Hash::make($req->password);
                $user->us_fullName = $req->fullname;
                $user->us_gender = $req->gender;
                $user->us_age = $req->age;
                $user->us_type = "0";
                $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $user->us_code = substr(str_shuffle($permitted_chars), 0, 20); 
                $path = public_path().'/uploadUsers/' . $user->us_code;
                File::makeDirectory( $path,0777,true);
                $user->save();
                //gửi mail
                require_once '../app/Providers/PHPMailer/PHPMailerAutoload.php'; 
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->SMTPSecure = 'tls';
                $mail->SMTPAuth = true;
                
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
                $mail->CharSet = 'UTF-8';
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->Username = 'longhoanghai8499@gmail.com';
                $mail->Password = 'shikatori142922188aA';
                $mail->isHTML(true);
                $mail->setFrom('system@gmail.com', 'Tour Advce System');
                $mail->addAddress($user->us_email, 'User');
                $mail->Subject = 'The message confirms you have successfully registered!';

                $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $maso= substr(str_shuffle($permitted_chars), 0, 6);
                $user->us_checkEmail = $maso;
                $user->save();
                $str='
                <h2>The message confirms you have successfully registered!</h2>
                <small>(This is an automated message. Please do not reply)</small>
                <h4>To verify your email please click the button below</h4>
                <p>Email information: '.$user->us_email.'</p>
                <p>Full name information: '.$user->us_fullName.'</p>
                <p>Verification: <a href="'.route('checkEmail',['id' => $user->us_id,'key'=>$user->us_checkEmail]).'" style="background: #9a46f5;color: white;padding: .5rem 1rem;text-decoration:none;cursor:pointer;border-radius:15px">Verification</a> </p>
                ';
                $mail->Body = $str;
                $mail->send();
                return back()->with("success","Đăng ký thành công");
            }
            else
            {
                return back()->with("error","Tài khoản email đã tồn tại");
            }
        }
    }
    public function checkEmail($id,$key)
    {
        $user = User::where("us_id",$id)->first();
        if(!empty($user))
        {
            if($user->us_checkEmail != "")
            {
                if($user->us_checkEmail == $key)
                {
                    $user->us_checkEmail = "";
                    $user->save();
                    return view("error.status",['status'=>'success']);
                }
                else
                {
                    return view("error.status",['status'=>'wrongKey']);
                }
            }
            else
            {
                return view("error.status",['status'=>'authenticated']);
            }
        }
        else
        {
            return response()->view('error.404');
        }
    }
    public function checkForgot(Request $req)
    {
        $user = User::where("us_email",$req->input)->first();
        if(!empty($user))
        {
            return "true";
        }
        else
        {
            return "false";
        }
    }
    public function senkey(Request $req)
    {
        $user = User::where("us_email",$req->input)->first();
        if(!empty($user))
        {
            //gửi mail
            require_once '../app/Providers/PHPMailer/PHPMailerAutoload.php'; 
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth = true;
            
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->CharSet = 'UTF-8';
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->Username = 'longhoanghai8499@gmail.com';
            $mail->Password = 'shikatori142922188aA';
            $mail->isHTML(true);
            $mail->setFrom('system@gmail.com', 'Tour Advce System');
            $mail->addAddress($user->us_email, 'User');
            $mail->Subject = 'Confirmation message of retrieving your password!';

            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $maso= substr(str_shuffle($permitted_chars), 0, 10);
            $user->us_forgot = $maso;
            $user->save();
            $str='
            <h2>Confirmation message of retrieving your password!</h2>
            <small>(This is an automated message. Please do not reply)</small>
            <h4>To verify your email please enter the code below. Note: do not share the code with anyone</h4>
            <p>Email information: '.$user->us_email.'</p>
            <p>Full name information: '.$user->us_fullName.'</p>
            <p>Key: '.$user->us_forgot.' </p>
            ';
            $mail->Body = $str;
            if($mail->send())
            {
                return "true";
            }
            else
            {
                return "false";
            }
        }
        else
        {
            return "false";
        }
        
    }
    public function checkkey(Request $req)
    {
        $user = User::where("us_email",$req->email)->first();
        if(!empty($user))
        {
            if($user->us_forgot == $req->input)
            {
                $user->us_password = Hash::make($user->us_email);
                $user->us_forgot ="";
                $user->save();
                return "true";
            }
            else
            {
               return "false"; 
            }
        }
        else
        {
            return "false";
        }
    }
    public function editInfo(Request $req)
    {
        $this->validate($req,[
            'newpass'=>'min:0|max:32',
            'confirmpass'=>'min:0|max:32',
            'file' => 'mimes:jpeg,png',
            'oldpass' => 'min:0|max:32',
        ],[
            'oldpass.min'=>'Password không được nhỏ hơn 0 kí tự',
            'oldpass.max'=>'Password không được lớn hơn 32 kí tự',
            'newpass.min'=>'Password không được nhỏ hơn 0 kí tự',
            'newpass.max'=>'Password không được lớn hơn 32 kí tự',
            'confirmpass.min'=>'Password không được nhỏ hơn 0 kí tự',
            'confirmpass.max'=>'Password không được lớn hơn 32 kí tự',
            'file.mimes' => 'Bạn chọn sai loại file'
        ]);
        $user = Auth::user();
        if($req->file('file'))
        {
            $image = $req->file('file');
            File::delete(public_path($user->us_image));
            $picName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('uploadUsers/'.$user->us_code), $picName);
            $user->us_image='uploadUsers/'.$user->us_code.'/'.$picName;
        }
        $user->us_fullName = $req->fullName;
        $user->us_gender = $req->gender;
        $user->us_age = $req->age;
        $user->save();
        // Change password
        if($req->newpass != "" || $req->confirmpass != "" || $req->oldpass)
        {
            $checkPass = Hash::check($req->oldpass,$user->us_password);
            if($checkPass)
            {
                if($req->newpass != "" && $req->confirmpass!= "")
                {
                    if($req->newpass == $req->confirmpass)
                    {
                        $user->us_password = Hash::make($req->newpass);
                        $user->save();
                        return redirect()->route('logout');
                    }
                    else return back()->with("success","Verify password is not correct");
                }
                else return back()->with("success","Enter missing information for the password change function");
            }
            else
            {
                return back()->with("success","Password change failed");
            }
        }
        return back()->with("success","Information is successfully edited");
    }
    public function checkUser(Request $req)
    {
        $user = Auth::user();
        if($user->us_image == "")
            $status = false;
        else
            $status = true;
        return [asset($user->us_image),$user->us_email,$user->us_fullName,$user->us_gender,$user->us_age,$status,$user->us_checkEmail];
    }
    //hàm treo
    public function checkTour(Request $req)
    {
        $route = Route::where('ro_id',$req->inputLink)->first();
        $array = array();
        if($route->ro_route_1 != "")
            $array = Arr::add($array, 1 ,$route->ro_route_1);
        if($route->ro_route_2 != "")
            $array = Arr::add($array, 2 ,$route->ro_route_2);
        if($route->ro_route_3 != "")
            $array = Arr::add($array, 3 ,$route->ro_route_3);
        if($route->ro_route_4 != "")
            $array = Arr::add($array, 4 ,$route->ro_route_4);
        if($route->ro_route_5 != "")
            $array = Arr::add($array, 5 ,$route->ro_route_5);

        $de = Destination::
            select('de_id','de_name','de_lat','de_lng','de_duration','de_link','de_description')
            ->whereIn('de_id',$array)
            ->get();
        $destination  = array();
        foreach ($de as $value) {
            $latlng = (object)array('lat' => $value->de_lat, 'lng' => $value->de_lng);
            array_push($destination,$latlng);
        }
        //label
        $label = array();
        foreach ($de as $value) {
            $labelName = (object)array('label' => $value->de_name);
            array_push($label,$labelName);
        }
        return [$destination,$label];
    }
}
