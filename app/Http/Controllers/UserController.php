<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
use App\Models\RatingPlace;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use PHPMailer;
use Socialite;
use Redirect;
use Cookie;


class UserController extends Controller
{
    public function cutArrray($string)
    {
        $pieces = explode("|", $string);
        $array = array();
        for ($i=0; $i < count($pieces)-1; $i++) {
            $array = Arr::add($array, $i ,$pieces[$i]);
        }
        return $array;
    }
    //fb + google
    public function getInfor($social)
    {
        return Socialite::driver($social)->redirect();
    }
    // login by FB + google
    public function checkInfor($social)
    {
        $info = Socialite::driver($social)->user();
        $user = User::where('provider',$social)->where('provider_user_id',$info->getId())->first();
        if($user)
        {
            if($user->us_lock == "1")
            {
                return redirect()->route('login')->with("error","Tài khoản của bạn đang bị khóa");
            }
            else
            {
                if($this->updatedAccFB($user,$info,$social) == "ok")
                    return redirect()->route('user.dashboard');
                else
                    return redirect()->route('login')->with("error","Email đã đăng ký, vui lòng chọn tài khoản khác");
            }
        }
        else
        {
            if($this->createdAccFB($info,$social) == "ok")
                return redirect()->route('user.dashboard');
            else
                return redirect()->route('login')->with("error","Email đã đăng ký, vui lòng chọn tài khoản khác");
        }
    }
    public function updatedAccFB($user,$info,$social)
    {
        //updated
        $checkEmail = User::where("us_id","<>",$user->us_id)->where("us_email",$info->getEmail())->first();
        if(empty($checkEmail))
        {
            $socialUpdated =  $social;
            $user->us_email = $info->getEmail();
            $user->us_fullName = $info->getName();
            $user->provider_user_id = $info->getId();
            $user->provider = $socialUpdated;                
            if($info->avatar_original != null)
            {
                File::delete(public_path($user->us_image));
                // $extension = pathinfo($info->avatar_original, PATHINFO_EXTENSION);
                $extension = 'jpg';
                $picName = time().'.'.$extension;
                $file = file_get_contents($info->avatar_original);
                $save = file_put_contents('uploadUsers/'.$user->us_code.'/'.$picName, $file);
                if($save)
                {
                    $user->us_image='uploadUsers/'.$user->us_code.'/'.$picName;
                }
            }
            $user->save();
            Auth::login($user);
            return $status = "ok";
        }
        else{
            return $status = "exist";
        }
    }
    public function createdAccFB($info,$social)
    {
        $checkEmail = User::where("us_email",$info->getEmail())->first();
        if(empty($checkEmail))
        {
            // created
            $userSocial = new User();
            $socialCreated =  $social;
            $userSocial->us_email = $info->getEmail();
            $userSocial->us_password = Hash::make($socialCreated);
            $userSocial->us_fullName = $info->getName();
            $userSocial->provider_user_id = $info->getId();
            $userSocial->provider = $socialCreated;
            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $userSocial->us_code = substr(str_shuffle($permitted_chars), 0, 20); 
            $path = public_path().'/uploadUsers/' . $userSocial->us_code;
            File::makeDirectory( $path,0777,true);
            if($info->avatar_original != null)
            {
                // $extension = pathinfo($info->avatar_original, PATHINFO_EXTENSION);
                $extension = 'jpg';
                $picName = time().'.'.$extension;
                $file = file_get_contents($info->avatar_original);
                $save = file_put_contents('uploadUsers/'.$userSocial->us_code.'/'.$picName, $file);
                if($save)
                {
                    $userSocial->us_image='uploadUsers/'.$userSocial->us_code.'/'.$picName;
                }
            }
            $userSocial->save();
            Auth::login($userSocial);
            return $status = "ok";
        }
        else
        {
            return $status = "exist";
        }
    }   

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
    public function login(Request $req)
    {
        if(!Auth::check())
        {
            $cookieEmail = $req->cookie('email');
            $cookiePassword = $req->cookie('password');
            if(isset($cookieEmail) && isset($cookiePassword))
            {
                $findUser = User::where("us_email",$cookieEmail)->where("provider",null)->first();
                if(!empty($findUser) && $findUser->us_lock=="0")
                {
                    Auth::attempt(['us_email'=>$cookieEmail,'us_password'=>$cookiePassword]);
                }
                else
                {
                    \Cookie::queue(\Cookie::forget('email'));
                    \Cookie::queue(\Cookie::forget('password'));
                }
            }
        }
        $shareTour = ShareTour::orderBy('numberReviews', 'DESC')->limit(10)->get();
        if(Auth::check())
        {
            $user = Auth::user();
            $route = Route::where('to_id_user',$user->us_id)->orderBy('to_startDay', 'desc')->get();
            if($user->tour_seen != null)
            {
                $arrayTourSeen = $this->cutArrray($user->tour_seen); 
                $reverseArray = array_reverse($arrayTourSeen, false);
                $resultArr = array();
                foreach($reverseArray as $key => $value)
                {
                    if($key <= 10)
                        array_push($resultArr, $value);
                }
            }    
            else
                $resultArr = array();
            session()->put('route',$route);
            return view('user.dashboard',['fullName'=>$user->us_fullName,'shareTour'=>$shareTour,'arrayTourSeen'=>$resultArr]);
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
                        if($user->provider != null)
                        {
                            Auth::logout();
                            return [$result = "fail login"];
                        }
                        else
                        {
                            if($user->us_type == "0")
                            {
                                return [$position = "user",$user->us_id,$user->us_fullName];
                            }
                            else
                            {
                                return [$position = "admin",$user->us_id,$user->us_fullName];
                            }
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
                        if($user->provider != null)
                        {
                            Auth::logout();
                            return back()->with("error","Đăng nhập không thành công");
                        }
                        else
                        {
                            if($user->us_type == "0")
                            {
                                return redirect()->route('user.dashboard')
                                    ->withCookie(cookie('email', $req->us_email, 20160))
                                    ->withCookie(cookie('password', $req->us_password, 20160));
                            }
                            else
                            {
                                return redirect()->route('admin.generalInfor')
                                    ->withCookie(cookie('email', $req->us_email, 20160))
                                    ->withCookie(cookie('password', $req->us_password, 20160));
                            }
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
    public function searchTourSmart(Request $req)
    {
        $result = DB::select("select sharetour.sh_id,sharetour.image,tour.to_name FROM tour,sharetour WHERE sharetour.sh_to_id=tour.to_id and tour.to_name like '%".$req->key."%' ORDER BY RAND() limit 5");
        return $result;
    }
    
    public function showDetailPlace($idplace)
    {
        $de = Destination::
        select('de_lat','de_lng','de_duration','de_link','de_map','de_image','de_type','de_child_img','de_tag')
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
        $array = array();
        if($de->de_child_img != null)
        {
            $pieces = explode("|", $de->de_child_img);
            for ($i=0; $i < count($pieces)-1; $i++) {
                $array = Arr::add($array, $i ,$pieces[$i]);
            }
        }
        $lang["de_lat"] = $de->de_lat;
        $lang["de_lng"] = $de->de_lng;
        $lang["de_link"] = $de->de_link;
        $lang["de_image"] = $de->de_image;
        $lang["de_map"] = $de->de_map;
        $lang["de_duration"] = $de->de_duration;  
        $lang["nametype"] = $langplace->nametype;
        //tag
        if($de->de_tag != null)
            $arrTag = $this->cutArrray($de->de_tag);
        // rating place
        $findRating = RatingPlace::where("ra_des_id",$idplace)->orderBy('ra_date_created', 'DESC')->get();
        $sumVotes = 0;
        $oneVote = 0;$twoVote = 0;$threeVote = 0;$fourVote = 0;$fiveVote = 0;
        foreach ($findRating as $value) {
            $sumVotes = $sumVotes + $value->ra_votes;
            if($value->ra_votes == "1")
                $oneVote = $$oneVote + 1;
            else if($value->ra_votes == "2")
                $twoVote = $twoVote + 1;
            else if($value->ra_votes == "3")
                $threeVote = $threeVote + 1;
            else if($value->ra_votes == "4")
                $fourVote = $fourVote + 1;
            else if($value->ra_votes == "5")
                $fiveVote = $fiveVote + 1;
        }
        if(count($findRating) != 0)
            $svgVotes = $sumVotes/count($findRating);
        else
            $svgVotes = 0;
        if(Auth::check())
        {
            // reset temporary photo
            $user = Auth::user();
            $array_temporary = $this->cutArrray($user->temporary_photo);
            foreach ($array_temporary as $value) {
                File::delete(public_path('temporary_Img/'.$value));
            }
            $user->temporary_photo = null;
            $user->save();

            $findRatingUsLogin = RatingPlace::where("ra_us_id",$user->us_id)->where("ra_des_id",$idplace)->first();
            return view('user.showplace',[
                'idplace' => $idplace,
                'fullName'=>$user->us_fullName,
                'lang'=>$lang,
                'array'=>$array,
                'findRating' => $findRating,
                'findRatingUsLogin' => $findRatingUsLogin,
                'svgVotes' => $svgVotes,
                'oneVote' => $oneVote,'twoVote' => $twoVote,'threeVote' => $threeVote,'fourVote' => $fourVote,'fiveVote' => $fiveVote,
                'arrTag' => $arrTag
            ]);
        }
        else
        {
            return view('user.showplace',[
                'idplace' => $idplace,
                'lang'=>$lang,
                'array'=>$array,
                'findRating' => $findRating,
                'svgVotes' => $svgVotes,
                'oneVote' => $oneVote,'twoVote' => $twoVote,'threeVote' => $threeVote,'fourVote' => $fourVote,'fiveVote' => $fiveVote,
                'arrTag' => $arrTag
            ]);
        }
        
    }
    public function temporaryCopyImg(Request $req)
    {
        // reset
        $user = Auth::user();
        // $files = $req->file('input_file_img');
        $array = $this->cutArrray($user->temporary_photo);
        foreach ($array as $value) {
            File::delete(public_path('temporary_Img/'.$value));
        }
        $findRating =  RatingPlace::where("ra_id",$req->ra_id)->first();
        if($findRating->ra_images != "")
        {
            Auth::user()->temporary_photo = $findRating->ra_images;
            Auth::user()->save();
            $arrayImg = $this->cutArrray(Auth::user()->temporary_photo);
            foreach ($arrayImg as $file) {
                File::copy(public_path('uploadUsers/'.Auth::user()->us_code.'/'.$file),public_path('temporary_Img/'.$file));
            }
        }
    }
    public function updateRating(Request $req,$ra_id)
    {
        $findRating = RatingPlace::where("ra_id",$ra_id)->first();
        $findRating->ra_votes = $req->numberStar;
        $findRating->ra_content = $req->content_rating;
        $findRating->ra_date_created = Carbon::now();
        if($findRating->ra_images != null)
        {
            $arrayOldImg = $this->cutArrray($findRating->ra_images);
            foreach($arrayOldImg as $ar)
                File::delete(public_path('uploadUsers/'.Auth::user()->us_code.'/'.$ar));
        }
        if(Auth::user()->temporary_photo != "")
        {
            $findRating->ra_images = Auth::user()->temporary_photo;
            $arrayImg = $this->cutArrray(Auth::user()->temporary_photo);
            foreach ($arrayImg as $file) {
                File::move(public_path('temporary_Img/'.$file), public_path('uploadUsers/'.Auth::user()->us_code.'/'.$file));
            }
            Auth::user()->temporary_photo = null;
            Auth::user()->save();
        }
        $findRating->save();
        return back()->with("success","Chỉnh sửa đánh giá thành công!");
    }
    public function addRating(Request $req,$idplace)
    {
        $newRating = new RatingPlace();
        $newRating->ra_votes = $req->numberStar;
        $newRating->ra_us_id = Auth::user()->us_id;
        $newRating->ra_des_id = $idplace;
        $newRating->ra_content = $req->content_rating;
        $newRating->ra_date_created = Carbon::now();
        if(Auth::user()->temporary_photo != "")
        {
            $newRating->ra_images = Auth::user()->temporary_photo;
            $arrayImg = $this->cutArrray(Auth::user()->temporary_photo);
            foreach ($arrayImg as $file) {
                File::move(public_path('temporary_Img/'.$file), public_path('uploadUsers/'.Auth::user()->us_code.'/'.$file));
            }
            Auth::user()->temporary_photo = null;
            Auth::user()->save();
        }
        $newRating->save();
        return back()->with("success","Đánh giá thành công!");
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
        \Cookie::queue(\Cookie::forget('email'));
        \Cookie::queue(\Cookie::forget('password'));
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
    public function duplicate(Request $req)
    {
        $allTour = Route::where("to_id_user",Auth::user()->us_id)->get();
        $desId = "";
        $i=0;
        foreach ($req->tmparr as  $value) {
            $desId = $desId.$value['de_id']."|";
            $i++;
        }
        $duplicateTour = array();
        foreach ($allTour as $tour) {
            if($tour->to_des == $desId)
            {
                $infoTour = (object) array('idTour' => $tour->to_id,'nameTour' => $tour->to_name);
                array_push($duplicateTour, $infoTour);
            }
        }
        return $duplicateTour;
    }
    public function saveTour(Request $req)
    {
        if(!empty($req->val))
        {
            $finddes = Destination::where('de_id',$req->val['de_id'])->first();
            if(empty($finddes))
            {
                $des = new Destination();
                $des->de_id = $req->val['de_id'];
                $des->de_remove = $req->val['de_id'];
                $latlng = explode("|", $req->val['location']);
                $des->de_lat = $latlng[0];
                $des->de_lng = $latlng[1];
                $des->de_name = $req->val['de_name'];
                $des->de_duration = $req->val['de_duration'];
                $des->de_cost = $req->val['de_cost'];
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
        $cost = "";
        foreach ($req->tmparr as  $value) {
            if($value['de_default'] == "0")
            {
                $desId = $desId.$value['de_id']."|";
                $i++;
            }
            else if($value['de_default'] == "1")
            {
                $finddesNewPlace = Destination::where('de_id',$value['de_id'])->first();
                if(empty($finddesNewPlace))
                {
                    $desNewPlace = new Destination();
                    $desNewPlace->de_id = $value['de_id'];
                    $desNewPlace->de_remove = $value['de_id'];
                    $latlng = explode("|", $value['location']);
                    $desNewPlace->de_lat = $latlng[0];
                    $desNewPlace->de_lng = $latlng[1];
                    $desNewPlace->de_name = $value['de_name'];
                    $desNewPlace->de_duration = $value['de_duration'];
                    $desNewPlace->de_cost = $value['de_cost'];
                    $desNewPlace->de_map = 'http://www.google.com/maps/place/'.$latlng[0].','.$latlng[1];
                    $desNewPlace->de_default = "1";
                    // plus
                    $findType = TypePlace::select("id","totalPlace")->where("status","1")->first();
                    $findType->totalPlace = intval($findType->totalPlace) + 1;
                    $findType->save();
                    //find type place has de_default
                    $desNewPlace->de_type = $findType->id;
                    $desNewPlace->save();
                }
                $desId = $desId.$value['de_id']."|";
                $i++;
            }
            $duration = $duration.$value['de_duration']."|";
            $cost = $cost.$value['de_cost']."|";
        }
        $route->to_des = $desId;
        $route->to_duration = $duration;
        $route->to_cost = $cost;
        if($req->currency=="VNĐ")
            $route->to_currency = "1";
        else if($req->currency=="USD")
            $route->to_currency = "2";
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
                        $mail->Password = 'shikatori8499';
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
                    $mail->Password = 'shikatori8499';
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
        $user = User::where("us_email",$req->input)->where('provider_user_id', null)->first();
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
            $mail->Password = 'shikatori8499';
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
