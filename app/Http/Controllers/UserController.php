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
use App\Models\ShareTour;
use App\Models\Language;
use App\Models\Uservotes;
use Session;
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
        if(Auth::check())
        {
            return redirect()->route("user.dashboard");
        }
        else
        {
            if(Session::has('website_language') && Session::get('website_language') == "vi")
            {
                $lang = Language::where("language","vn")->get();
                foreach ($lang as $value) {
                    $des = Destination::select('de_image','de_duration','de_link','de_map')->where("de_remove",$value->des_id)->first();
                    $value["de_image"] = $des->de_image;
                    $value["de_duration"] = $des->de_duration;
                    $value["de_link"] = $des->de_link;
                    $value["de_map"] = $des->de_map;
                }
                $shareTour = ShareTour::orderBy('numberReviews', 'DESC')->limit(9)->get();
                return view("generalinterface",['des'=>$lang,'shareTour'=>$shareTour]);
            }
            else
            {
                $lang = Language::where("language","en")->get();
                foreach ($lang as $value) {
                    $des = Destination::select('de_image','de_duration','de_link','de_map')->where("de_remove",$value->des_id)->first();
                    $value["de_image"] = $des->de_image;
                    $value["de_duration"] = $des->de_duration;
                    $value["de_link"] = $des->de_link;
                    $value["de_map"] = $des->de_map;
                }
                $shareTour = ShareTour::orderBy('numberReviews', 'DESC')->limit(9)->get();
                return view("generalinterface",['des'=>$lang,'shareTour'=>$shareTour]);
            }
        }
    }
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
                //$route = Route::where('to_id_user',$user->us_id)->get();
                return redirect()->route('user.dashboard');
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
        if(Session::has('website_language') && Session::get('website_language') == "vi")
        {
            $user = Auth::user();
            $route = Route::where('to_id_user',$user->us_id)->get();
            session()->put('route',$route);
            $lang = Language::where("language","vn")->get();
            foreach ($lang as $value) {
                $des = Destination::select('de_image','de_duration','de_link','de_map')->where("de_remove",$value->des_id)->first();
                $value["de_image"] = $des->de_image;
                $value["de_duration"] = $des->de_duration;
                $value["de_link"] = $des->de_link;
                $value["de_map"] = $des->de_map;
            }
            $shareTour = ShareTour::orderBy('numberReviews', 'DESC')->limit(9)->get();
            return view('dashboard',['fullName'=>$user->us_fullName,'des'=>$lang,'shareTour'=>$shareTour]);
        }
        else
        {
            $user = Auth::user();
            $route = Route::where('to_id_user',$user->us_id)->get();
            session()->put('route',$route);
            $lang = Language::where("language","en")->get();
            foreach ($lang as $value) {
                $des = Destination::select('de_image','de_duration','de_link','de_map')->where("de_remove",$value->des_id)->first();
                $value["de_image"] = $des->de_image;
                $value["de_duration"] = $des->de_duration;
                $value["de_link"] = $des->de_link;
                $value["de_map"] = $des->de_map;
            }
            $shareTour = ShareTour::orderBy('number_star', 'DESC')->limit(9)->get();
            return view('dashboard',['fullName'=>$user->us_fullName,'des'=>$lang,'shareTour'=>$shareTour]);
        }
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
        if(!empty($req->val))
        {
            $des = new Destination();
            $des->de_id = $req->val['de_id'];
            $des->de_remove = $req->val['de_id'];
            $latlng = explode("|", $req->val['location']);
            $des->de_lat = $latlng[0];
            $des->de_lng = $latlng[1];
            $des->de_name = $req->val['de_name'];
            $des->de_duration = $req->val['de_duration'];
            $des->de_map = 'http://www.google.com/maps/place/'.$latlng[0].','.$latlng[1];
            $des->de_default = "1";
            $des->save();
        }
        $user = Auth::user();
        $route = new Route();
        $route->to_id_user = $user->us_id;
        $route->to_starttime = $req->timeStart;
        $route->to_endtime = $req->timeEnd;
        $route->to_comback = $req->to_comback;
        $route->to_optimized = $req->to_optimized;
        $route->to_name = $req->nameTour;
        $route->to_startDay = date('Y-m-d', strtotime(Carbon::now()));
        $i=0;
        $desId = "";
        foreach ($req->tmparr as  $value) {
            if(empty($value['de_default']))
            {
                $desId = $desId.$value['de_id']."|";
                $i++;
            }
            else if($value['de_default'] == "1")
            {
                $desNewPlace = new Destination();
                $desNewPlace->de_id = $value['de_id'];
                $desNewPlace->de_remove = $value['de_id'];
                $latlng = explode("|", $value['location']);
                $desNewPlace->de_lat = $latlng[0];
                $desNewPlace->de_lng = $latlng[1];
                $desNewPlace->de_name = $value['de_name'];
                $desNewPlace->de_duration = $value['de_duration'];
                $desNewPlace->de_map = 'http://www.google.com/maps/place/'.$latlng[0].','.$latlng[1];
                $desNewPlace->de_default = "1";
                $desNewPlace->save();
                $desId = $desId.$value['de_id']."|";
                $i++;
            }
        }
        $route->to_des = $desId;
        if(!empty($req->val))
        {
            $route->to_startLocat = $req->val['de_id'];
        }
        $route->save();
        return $route->to_id;
    }
    public function shareTour(Request $req)
    {
        $share = new ShareTour();
        $share->sh_to_id = $req->ro_id;
        $share->number_star = $req->star;
        $share->content = $req->content;
        $share->numberReviews = "1";
        if($req->file('image_tour'))
        {
            $image = $req->file('image_tour');
            $picName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('img_ShareTour'), $picName);
            $share->image='img_ShareTour/'.$picName;
        }
        $share->save();

        $uservotes = new Uservotes();
        $uservotes->sh_id = $share->sh_id;
        $uservotes->us_id = Auth::user()->us_id;
        $uservotes->vote_number = $req->star;
        $uservotes->save();
        return redirect()->route('user.editTour',$req->ro_id);
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
        $route = Route::where('to_id',$req->inputLink)->first();
        $array = array();
        $pieces = explode("|", $route->to_des);
        for ($i=0; $i < count($pieces)-1; $i++) {
            $array = Arr::add($array, $i ,$pieces[$i]);
        }
        $array_2 = array();$label = array();
        foreach ($array as $value) {
            $desCheck = Destination::where("de_remove",$value)->first();
            if($desCheck->de_default == "1")
            {
                $lang = $desCheck;
            }
            else
            {
                if(Session::has('website_language') && Session::get('website_language') == "vi")
                {
                    $lang = Language::where("language","vn")->where("des_id",$value)->first();
                    $de = Destination::
                    select('de_id','de_lat','de_lng','de_duration','de_link')
                    ->where('de_remove',$value)
                    ->first();
                    $lang["de_lat"] = $de->de_lat;
                    $lang["de_lng"] = $de->de_lng;
                    $lang["de_link"] = $de->de_link;
                    $lang["de_duration"] = $de->de_duration;
                }
                else
                {
                    $lang = Language::where("language","en")->where("des_id",$value)->first();
                    $de = Destination::
                    select('de_id','de_lat','de_lng','de_duration','de_link')
                    ->where('de_remove',$value)
                    ->first();
                    $lang["de_lat"] = $de->de_lat;
                    $lang["de_lng"] = $de->de_lng;
                    $lang["de_link"] = $de->de_link;
                    $lang["de_duration"] = $de->de_duration;
                }
            }
            $latlng = (object)array('lat' => $lang->de_lat, 'lng' => $lang->de_lng);
            array_push($array_2,$latlng);
            $labelName = $lang->de_name;
            array_push($label,$labelName);
        }
        //start locat
        $nameStartLocat ="";
        if($route->to_startLocat != "")
        {
            $start = Destination::where("de_remove",$route->to_startLocat)->first();
            $objStartLocat = (object)array('lat' => $start->de_lat, 'lng' => $start->de_lng);
            $nameStartLocat = $start->de_name;
        }
        else $objStartLocat = "";
        //name start locat
        if($route->to_comback == 1)
        {
            if($route->to_startLocat != "")
            {
                $start_2 = Destination::where("de_remove",$route->to_startLocat)->first();
                $objStartLocat_2 = (object)array('lat' => $start_2->de_lat, 'lng' => $start_2->de_lng);
                array_push($label,$start_2->de_name);
                array_push($array_2,$objStartLocat_2);
            }
            else
            {
                array_push($label,$label[0]);
                array_push($array_2,$array_2[0]);
            }
        }
        return [$array_2,$label,$objStartLocat,$nameStartLocat];
    }
}
