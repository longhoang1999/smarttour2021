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
use App\Models\TypePlace;
use App\Models\Langtype;
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
        $shareTour = ShareTour::orderBy('numberReviews', 'DESC')->limit(10)->get();
        if(Auth::check())
        {
            $user = Auth::user();
            $route = Route::where('to_id_user',$user->us_id)->orderBy('to_startDay', 'desc')->get();
            session()->put('route',$route);
            return view('user.dashboard',['fullName'=>$user->us_fullName,'shareTour'=>$shareTour]);
        }
        else
        {
            return view('user.dashboard',['shareTour'=>$shareTour]);
        }
    }
    public function tour()
    {
        $shareTour = ShareTour::orderBy('numberReviews', 'DESC')->limit(6)->get();
        if(Auth::check())
        {
            $user = Auth::user();
            $route = Route::where('to_id_user',$user->us_id)->get();
            session()->put('route',$route); 
            return view('user.tour',['fullName'=>$user->us_fullName,'shareTour'=>$shareTour]);
        }
        else
        {
            return view('user.tour',['shareTour'=>$shareTour]);
        }
    }
    public function place()
    {
        if(Session::has('website_language') && Session::get('website_language') == "vi")
        {
            $lang = Language::where("language","vn")->inRandomOrder()->limit(10)->get();
        }
        else
        {
            $lang = Language::where("language","en")->inRandomOrder()->limit(10)->get();
        }
        foreach ($lang as $value) {
            $des = Destination::select('de_image','de_duration','de_link','de_map')->where("de_remove",$value->des_id)->first();
            $value["de_image"] = $des->de_image;
            $value["de_duration"] = $des->de_duration;
            $value["de_link"] = $des->de_link;
            $value["de_map"] = $des->de_map;
        }

        if(Auth::check())
        {
            $user = Auth::user();
            return view('user.place',['fullName'=>$user->us_fullName,'des'=>$lang]);
        }
        else
        {
            return view('user.place',['des'=>$lang]);
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
        //check modal
        if(isset($req->typeLogin) && $req->typeLogin == "modal")
        {
            $userCheckLock = User::where("us_email",$req->us_email)->first();
            if(!empty($userCheckLock))
            {
                if($userCheckLock->us_lock == "1")
                {
                    return [$result = "lock"];
                }
                else
                {
                    if(Auth::attempt(['us_email'=>$req->us_email,'us_password'=>$req->us_password]))
                    {
                        $user = Auth::user();
                        if($user->us_type == "0")
                        {
                            return [$position = "user",$user->us_id];
                        }
                        else
                        {
                            return [$position = "admin",$user->us_id];
                        }
                    }
                    else
                    {
                        return [$result = "fail login"];
                    }
                }
            }
            else
            {
                return [$result = "fail login"];
            } 
        }
        else
        {
            $userCheckLock = User::where("us_email",$req->us_email)->first();
            if(!empty($userCheckLock))
            {
                if($userCheckLock->us_lock == "1")
                {
                    return back()->with("error","Tài khoản của bạn đang bị khóa");
                }
                else
                {
                    if(Auth::attempt(['us_email'=>$req->us_email,'us_password'=>$req->us_password]))
                    {
                        $user = Auth::user();
                        if($user->us_type == "0")
                        {
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
            }
            else
            {
                return back()->with("error","Đăng nhập không thành công");
            }
        }    
    }
    public function about()
    {
        if(Auth::check())
        {
            $user = Auth::user();
            return view('user.about',['fullName'=>$user->us_fullName]);
        }
        else
        {
            return view('user.about');
        }
    }
    public function viewfeedback()
    {
        if(Auth::check())
        {
            $user = Auth::user();
            return view('user.feedback',['fullName'=>$user->us_fullName]);
        }
        else
        {
            return view('user.feedback');
        }
    }
    public function dashboard()
    {
        $user = Auth::user();
        $route = Route::where('to_id_user',$user->us_id)->get();
        session()->put('route',$route);
        return back()->with('fullName',$user->us_fullName);
    }
    public function searchPlaceSmart(Request $req)
    {
        $result = DB::select("select langplace.des_id,langplace.de_name,destination.de_image FROM langplace,destination WHERE langplace.language='vn' and langplace.de_name like '%".$req->key."%' and langplace.des_id=destination.de_remove ORDER BY RAND() limit 5");
        return $result;
    }
    public function showDetailPlace($idplace)
    {
        $de = Destination::
        select('de_lat','de_lng','de_duration','de_link','de_map','de_image','de_type')
        ->where('de_remove',$idplace)
        ->first();

        if(Session::has('website_language') && Session::get('website_language') == "vi")
        {
            $lang = Language::where("language","vn")->where("des_id",$idplace)->first();
            $findType = TypePlace::where("id",$de->de_type)->first();
            $langplace = Langtype::select("nametype")->where("language","vn")->where("type_id",$findType->id)->first();
        }
        else
        {
            $lang = Language::where("language","en")->where("des_id",$idplace)->first();
            $findType = TypePlace::where("id",$de->de_type)->first();
            $langplace = Langtype::select("nametype")->where("language","en")->where("type_id",$findType->id)->first();
        }
        
        $lang["de_lat"] = $de->de_lat;
        $lang["de_lng"] = $de->de_lng;
        $lang["de_link"] = $de->de_link;
        $lang["de_image"] = $de->de_image;
        $lang["de_map"] = $de->de_map;
        $lang["de_duration"] = $de->de_duration;
        $lang["nametype"] = $langplace->nametype;
        if(Auth::check())
        {
            $user = Auth::user();
            return view('user.showplace',['fullName'=>$user->us_fullName,'lang'=>$lang]);
        }
        else
        {
            return view('user.showplace',['lang'=>$lang]);
        }
        
    }
    public function listPlaceForType($idtype)
    {
        $listPlace = Destination::select('de_remove')->where("de_default","0")->where("de_type",$idtype)->limit(10)->get();
        foreach ($listPlace as $value) {
            if(Session::has('website_language') && Session::get('website_language') == "vi")
            {
                $lang = Language::where("language","vn")->where("des_id",$value->de_remove)->first();
                $value['de_name'] = $lang->de_name;
            }
            else{
                $lang = Language::where("language","en")->where("des_id",$value->de_remove)->first();
                $value['de_name'] = $lang->de_name;
            }
        }   
        $typeName = TypePlace::where("id",$idtype)->first();
        if(Session::has('website_language') && Session::get('website_language') == "vi")
            $langType = Langtype::select("nametype")->where("language","vn")->where("type_id",$typeName->id)->first();
        else
            $langType = Langtype::select("nametype")->where("language","en")->where("type_id",$typeName->id)->first();
        if(Auth::check())
        {
            $user = Auth::user();
            return view('user.listplacetype',['fullName'=>$user->us_fullName,'listPlace'=>$listPlace,'langType'=>$langType->nametype]);
        }
        else
        {
            return view('user.listplacetype',['listPlace'=>$listPlace,'langType'=>$langType->nametype]);
        }
    }
    public function loadPlaceInfo(Request $req)
    {
        if(Session::has('website_language') && Session::get('website_language') == "vi")
            $lang = Language::where("language","vn")->where("des_id",$req->idPlace)->first();
        else
            $lang = Language::where("language","en")->where("des_id",$req->idPlace)->first();
        $findDes = Destination::where("de_remove",$req->idPlace)->first();
        if($findDes->de_image != "")
            $lang['de_image'] = asset($findDes->de_image);
        else $lang['de_image'] = "";
        $lang['de_duration'] = intval($findDes->de_duration)/60/60;
        $lang['de_link'] = $findDes->de_link;
        $lang['de_map'] = $findDes->de_map;
        return $lang; 
    }
    public function logout(){
        Auth::logout();
        session()->flush();
        return redirect()->route('login')->with("success","You have logged out successfully");
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
            // plus
            $findType = TypePlace::select("id","totalPlace")->where("status","1")->first();
            $findType->totalPlace = intval($findType->totalPlace) + 1;
            $findType->save();
            //type des
            $des->de_type = $findType->id;
            $des->save();
        }
        $user = Auth::user();
        $route = new Route();
        $route->to_id_user = $user->us_id;
        $route->to_starttime = Carbon::parse($req->timeStart)->toDateTimeString();
        $route->to_endtime = Carbon::parse($req->timeStart)->addSeconds($req->timeEnd);
        $route->to_comback = $req->to_comback;
        $route->to_optimized = $req->to_optimized;
        $route->to_name = $req->nameTour;
        $route->to_startDay = date('Y-m-d', strtotime(Carbon::now()));
        if(!empty($req->val))
        {
            $route->to_startLocat = $req->val['de_id'];
        }
        $i=0;
        $desId = "";
        $duration = "";
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
                // plus
                $findType = TypePlace::select("id","totalPlace")->where("status","1")->first();
                $findType->totalPlace = intval($findType->totalPlace) + 1;
                $findType->save();
                //find type place has de_default
                $desNewPlace->de_type = $findType->id;
                $desNewPlace->save();
                $desId = $desId.$value['de_id']."|";
                $i++;
            }
            $duration = $duration.$value['de_duration']."|";
        }
        $route->to_des = $desId;
        $route->to_duration = $duration;
        $route->to_star = $req->star;
        $route->save();
        //share tour
        if($req->options == "yes")
        {
            $share = new ShareTour();
            $share->sh_to_id = $route->to_id;
            $share->number_star = $req->star;
            $share->content = $req->recommend;
            $share->numberReviews = "1";
            $share->save();

            $uservotes = new Uservotes();
            $uservotes->sh_id = $share->sh_id;
            $uservotes->us_id = Auth::user()->us_id;
            $uservotes->vote_number = $req->star;
            $uservotes->save();
        }
        if($req->options == "yes")
            $shareId = $share->sh_id;
        else
            $shareId = "";
        return [$req->options,$shareId,$route->to_id,$url = route('user.editTour',$route->to_id)];
    }
    public function saveImgShareTour(Request $req,$idShareTour)
    {
        $findShare = ShareTour::where("sh_id",$idShareTour)->first();
        if($findShare->image != "")
        {
            File::delete(public_path($findShare->image));
        }
        if($req->file('image_tour'))
        {
            $image = $req->file('image_tour');
            $picName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('img_ShareTour'), $picName);
            $findShare->image='img_ShareTour/'.$picName;
            $findShare->save();
        }
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
        if(isset($req->checkmodal) && $req->checkmodal=="modal")
        {
            if($req->email == "" || $req->password == "" || $req->confirm == "" || $req->fullname == "" || $req->age == "")
            {
                return $notification = "If you enter missing information, please review";
            }
            else
            {
                if($req->password != $req->confirm)
                {
                    return $notification = "incorrect password";
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
                        return $notification = "true";
                    }
                    else
                    {
                        return $notification = "Email account already exists";
                    }
                }
            }
        }
        else
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
                        Auth::logout();
                        session()->flush();
                        return redirect()->route('login')->with("success","You have successfully changed your password");
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
    public function viewShareFeedback()
    {
        $feedback = Feedback::where("fb_share","1")->get();
        if(Auth::check())
        {
            $user = Auth::user();
            return view('user.viewfeedback',['fullName'=>$user->us_fullName,'feedback'=>$feedback]);
        }
        else
        {
            return view('user.viewfeedback',['feedback'=>$feedback]);
        }
    }
}
