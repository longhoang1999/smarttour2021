<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\Route;
use App\Models\Feedback;
use App\Models\Destination;
use App\Models\Path;
use PHPMailer;

use Illuminate\Support\Arr;
use Carbon\Carbon;
use View;


class AdminController extends Controller
{
    public function dashboard()
    {
    	$user = Auth::user();
        return view('admin.dashboard',['us_fullName'=>$user->us_fullName]);
    }
    public function showAllAccount()
    {
    	$allAccount=User::get();
        return DataTables::of($allAccount)
            ->addColumn(
                'stt',
                function ($allAccount) {
                    $stt="";
                    return $stt;
                }
            )
            ->addColumn(
                'position',
                function ($allAccount) {
                    if($allAccount->us_type == "0")
                    {
                        $position = '<span class="badge badge-warning">User</span>';
                    }
                    else if($allAccount->us_type == "1")
                        $position = '<span class="badge badge-primary">Admin</span>';
                    return $position;
                }
            )
            ->addColumn(
                'actions',
                function ($allAccount) {
                    $actions = '<button type="button" data-id="'.$allAccount->us_id.'"  class="btn btn-info btn-sm btn-block" data-toggle="modal" data-target="#modalDetail">
                          Detail
                        </button>';
                    if($allAccount->us_type != "1")
                    {
                        $actions = $actions.'<button type="button" data-id="'.$allAccount->us_id.'" class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#modalDelete">
                              Delete
                            </button>';
                    }
                    return $actions;
                }
            )
            ->rawColumns(['stt','position','actions'])
            ->make(true);
    }
    public function deleteAcc($id)
    {
        $feedback = Feedback::where('fb_us_id',$id)->get();
        foreach ($feedback as $value) {
            $value->delete();
        }
        $tour = Route::where("to_id_user",$id)->get();
        foreach ($tour as $value) {
            $value->delete();
        }
        $user = User::where("us_id",$id)->first();
        File::deleteDirectory(public_path('uploadUsers/'.$user->us_code));
        $user->delete();
        return back()->with("status","You have successfully deleted this account");
    }
    public function feedback()
    {
    	$user = Auth::user();
        return view('admin.feedback',['us_fullName'=>$user->us_fullName]);
    }
    public function showAllFeedback()
    {
    	$feedback = Feedback::get();
        return DataTables::of($feedback)
            ->addColumn(
                'stt',
                function ($feedback) {
                    $stt="";
                    return $stt;
                }
            )
            ->addColumn(
                'email',
                function ($feedback) {
                    $user = User::select('us_email')->where('us_id',$feedback->fb_us_id)->first();
                    return $user->us_email;
                }
            )
            ->addColumn(
                'fullName',
                function ($feedback) {
                    $user = User::select('us_fullName')->where('us_id',$feedback->fb_us_id)->first();
                    return $user->us_fullName;
                }
            )
            ->addColumn(
                'action',
                function ($feedback) {
                    $action = '<button class="btn btn-sm btn-info btn-block" data-id="'.$feedback->fb_id.'" data-toggle="modal" data-target="#exampleModal">Detail</button>';
                    return $action;
                }
            )
            ->rawColumns(['stt','email','fullName','action'])
            ->make(true);
    }
    public function detaiFeedback(Request $req,$id)
    {
    	$feedback = Feedback::where('fb_id',$id)->first();
    	$user = User::where('us_id',$feedback->fb_us_id)->first();
    	return [$user->us_email,$user->us_fullName,$feedback->content,$feedback->star];
    }
    public function addPlace()
    {
    	$user = Auth::user();
        return view('admin.addplace',['us_fullName'=>$user->us_fullName]);
    }
    public function showDestination()
    {
    	$destination = Destination::get();
        return DataTables::of($destination)
        	->addColumn(
                'stt',
                function ($destination) {
                    $stt = "";
                    return $stt;
                }
            )
            ->addColumn(
                'duration',
                function ($destination) {
                    $duration = floatval($destination->de_duration)/60/60;
                    return $duration;
                }
            )
            ->addColumn(
                'actions',
                function ($destination) {
                    $actions = '<button class="btn btn-block btn-info btn-sm" data-remove="'.$destination->de_remove.'" data-toggle="modal" data-target="#modalDetail">Detail</button>';
                    return $actions;
                }
            )
            ->rawColumns(['stt','duration','actions'])
            ->make(true);
    }
    public function postaddPlace(Request $req)
    {
    	$randomletter = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789QAZXSWEDCRFVTGBYHNUJMIKLPO"), 0, 27);
    	$destination = new Destination();
    	$destination->de_id = $randomletter;
    	$destination->de_remove = $randomletter;
    	$destination->de_name = $req->de_name;
    	$destination->de_lat = $req->de_lat;
    	$destination->de_lng = $req->de_lng;
    	$destination->de_description = $req->de_description;
    	$destination->de_shortdes = $req->de_shortdes;
        $destination->de_map = $req->de_map;
    	$destination->de_link = $req->de_link;
    	$destination->de_duration = floatval($req->de_duration)*60*60;
        //img
        if($req->file('de_image'))
        {
            $image = $req->file('de_image');
            //File::delete(public_path($user->us_image));
            $picName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('imgPlace/'), $picName);
            $destination->de_image='imgPlace/'.$picName;
        }
    	$destination->save();

        $array = array();
        $array = Arr::add($array, 0 ,$randomletter);
        $allDes = Destination::where('de_id','<>',$randomletter)->get();
        $i=1;
        foreach ($allDes as $value) {
            $array = Arr::add($array, $i ,$value->de_remove);
            $i++;
        }
    	return $array;
    }
    public function getLatLng(Request $req)
    {
        $latLngArray = array();
        foreach ($req->array as  $value) {
            $check  = Destination::where('de_remove',$value)->first();
            $latlng = (object)array('lat' => $check->de_lat, 'lng' => $check->de_lng);
            array_push($latLngArray,$latlng);
        }
        return $latLngArray;
    }
    public function checkPlace(Request $req)
    {
    	// $url = "https://www.google.com/maps/place/C%E1%BB%ADa+h%C3%A0ng+Biti's+-+CH+C%E1%BA%A7u+Gi%E1%BA%A5y/@21.0353488,105.7880971,17z/data=!4m5!3m4!1s0x3135ab4818ee7bbb:0x6078971c0a2ae4e6!8m2!3d21.0356562!4d105.7924557?hl=vi-VN";
    	$url = $req->inputLink;
    	$getPlace = "";
    	//cắt từ place/
    	$str1 = substr($url, 34);
    	//trả về vị trí của / đầu tiên
    	$pos = strpos($str1, "/");
    	//lấy đoạn trước /@
    	$str2 = substr($str1, 0 ,$pos);
    	//cắt thành mảng theo dấu +
    	$pieces = explode("+", $str2);
    	foreach ($pieces as  $value) {
    		$str3 = "";$str4 = "";$str5 = "";$dem1 = 0;$dem2 = 0;
    		//lấy 2 chữ cái sau dấu % gán $str3
    		for ($i=0; $i < strlen($value); $i++) { 
    			if($value[$i] == "%")
    				$str3 = $str3.substr($value, (int)($i+1) , 2);
    		}
    		//giảm mã hex ra UTF-8
    		$pack = pack('H*', $str3);
    		//lấy các chữ cái đầu tiên đến vị trí % đầu tiên gán $str4
    		for ($i=0; $i < strlen($value); $i++) { 
    			if($value[$i] == "%")
    			{
    				$str4 = $str4.substr($value, 0 ,(int)$i);
    				break;
    			}
    		}
    		//đếm số dấu %
    		for ($i=0; $i < strlen($value); $i++) { 
    			if($value[$i] == "%")
    			{
    				$dem1 = $dem1+1;
    			}
    		}
    		//tìm dấu % cuối cùng-> cắt các ký tự cuối cùng (sau dấu % 2 ký tự)
    		for ($i=0; $i < strlen($value); $i++) { 
    			if($value[$i] == "%")
    			{
    				$dem2 = $dem2 + 1;
    				if($dem2 == $dem1 && $dem1!=0)
	    			{
	    				$str5 = $str5.substr($value, (int)($i+3));
	    			}
    			}
    		}
    		//nếu chữ đc mã hóa
    		if($str4.$pack.$str5 != "")
    		{
    			$value = $str4.$pack.$str5." ";
    		}
    		//nếu chữ k đc mã hóa
    		else
    		{
    			$value = $value." ";
    		}
    		$getPlace = $getPlace.$value;
    	}
    	return $getPlace;
    }
    public function editPlace()
    {
    	$user = Auth::user();
        return view('admin.editplace',['us_fullName'=>$user->us_fullName]);
    }
    public function removePlace()
    {
    	$user = Auth::user();
        return view('admin.removeplace',['us_fullName'=>$user->us_fullName]);
    }
    public function showDestinationRemove()
    {
    	$destination = Destination::get();
        return DataTables::of($destination)
        	->addColumn(
                'stt',
                function ($destination) {
                    $stt = "";
                    return $stt;
                }
            )
            ->addColumn(
                'duration',
                function ($destination) {
                    $duration = floatval($destination->de_duration)/60/60;
                    return $duration;
                }
            )
            ->addColumn(
                'actions',
                function ($destination) {
                    $actions = '<button class="btn btn-block btn-info btn-sm" data-remove="'.$destination->de_remove.'" data-toggle="modal" data-target="#modalDetail">Detail</button>';
                    $actions = $actions.'<button class="btn btn-block btn-danger btn-sm" data-remove="'.$destination->de_remove.'" data-toggle="modal" data-target="#modalDelete">Remove</button>';
                    return $actions;
                }
            )
            ->rawColumns(['stt','duration','actions'])
            ->make(true);
    }
    public function showDetail(Request $req,$remove)
    {
    	$des = Destination::where("de_remove",$remove)->first();
    	if(!empty($des))
    	{
            if($des->de_image != "")
            {
                $image = asset($des->de_image);
            }
            else $image="";
    		$duration = floatval($des->de_duration)/60/60;
    		return [$des->de_name,$des->de_lng,$des->de_lat,$des->de_description,$des->de_shortdes,$duration,$des->de_link,$des->de_map,$image];
    	}
    	else
    	{
    		return "Can not find data";
    	}
    }
    public function placeDelete($remove)
    {
        $path_start = Path::where('pa_de_start',$remove)->get();
        foreach ($path_start as $value) {
            $value->delete();
        }
        $path_end = Path::where('pa_de_end',$remove)->get();
        foreach ($path_end as $value) {
            $value->delete();
        }

    	$des = Destination::where("de_remove",$remove)->first();
    	if(!empty($des))
    	{
            File::delete(public_path($des->de_image));
    		$des->delete();
    	}
    	return redirect()->route("admin.removePlace")->with("status","You have successfully deleted the place");
    }
    public function showDestinationEdit()
    {
    	$destination = Destination::get();
        return DataTables::of($destination)
        	->addColumn(
                'stt',
                function ($destination) {
                    $stt = "";
                    return $stt;
                }
            )
            ->addColumn(
                'duration',
                function ($destination) {
                    $duration = floatval($destination->de_duration)/60/60;
                    return $duration;
                }
            )
            ->addColumn(
                'actions',
                function ($destination) {
                    $actions = '<button class="btn btn-block btn-info btn-sm" data-remove="'.$destination->de_remove.'" data-toggle="modal" data-target="#modalDetail">Detail</button>';
                    $actions = $actions.'<button class="btn btn-block btn-danger btn-sm" data-remove="'.$destination->de_remove.'" data-toggle="modal" data-target="#modalEdit">Edit</button>';
                    return $actions;
                }
            )
            ->rawColumns(['stt','duration','actions'])
            ->make(true);
    }
    public function showDetailEdit($remove)
    {
    	$des = Destination::where("de_remove",$remove)->first();
    	if(!empty($des))
    	{
    		$duration = floatval($des->de_duration)/60/60;
    		return [$des->de_name,$des->de_lng,$des->de_lat,$des->de_description,$des->de_shortdes,$duration,$des->de_link,$des->de_map];
    	}
    	else
    	{
    		return "Can not find data";
    	}
    }
    public function formEditPlace(Request $req,$remove)
    {
    	$des = Destination::where("de_remove",$remove)->first();
    	if(!empty($des))
    	{
    		$des->de_name=$req->placeName;
    		$des->de_description=$req->description;
    		$des->de_shortdes=$req->shortdes;
    		$des->de_duration= floatval($req->duration)*60*60;
    		$des->save();
    	}
    	return back()->with("status","You have successfully corrected");
    }
    public function generalInfor()
    {
    	$user = Auth::user();
    	$totalAcc = User::where("us_type","0")->count();
    	$totalDes = Destination::count();
    	$totalFeedback = Feedback::count();
    	$avgStar = Feedback::avg('star');
        return view('admin.generalinfor',['us_fullName'=>$user->us_fullName,'totalAcc'=>$totalAcc,'totalDes'=>$totalDes,'totalFeedback'=>$totalFeedback,'avgStar'=>$avgStar]);
    }
    public function updatePath(Request $req)
    {
        foreach ($req->input as  $value) {
            $path = new Path();
            $path->pa_de_start = $value['pa_de_start'];
            $path->pa_de_end = $value['pa_de_end'];
            $path->pa_distance = $value['pa_distance'];
            $path->pa_duration = $value['pa_duration'];
            $path->save();
        }
        return "UploadOk";
    }

    public function checkUserAdmin(Request $req)
    {
        $user = User::where("us_id",$req->id)->first();
        if($user->us_image == "")
            $status = false;
        else
            $status = true;
        return [asset($user->us_image),$user->us_email,$user->us_fullName,$user->us_gender,$user->us_age,$status,$user->us_checkEmail,$user->us_type];
    }
    public function addaccount(Request $req)
    {
        $this->validate($req,[
            'us_email'=>'required|email',
            'us_password'=>'required|min:0|max:32',
            'us_confirm'=>'required|min:0|max:32',
            'us_fullname' => 'required',
            'us_image' => 'mimes:jpg,png'
        ],[
            'us_email.required' => 'Bạn phải nhập email',
            'us_email.email' => 'Sai cấu chúc email',
            'us_password.required' => "Bạn phải nhập mật khẩu",
            'us_password.min' => "Mật khẩu quá ngắn",
            'us_password.max' => "Mật khẩu quá dài",
            'us_confirm.required' => "Bạn phải nhập xác nhận mật khẩu",
            'us_confirm.min' => "Xác nhận mật khẩu quá ngắn",
            'us_confirm.max' => "Xác nhận mật khẩu quá dài",
            'us_fullname.required' => 'Bạn phải nhập full name',
            'us_image.mimes' => 'Sai cấu trúc file ảnh'
        ]);
        if($req->us_password == $req->us_confirm)
        {
            $userCheck = User::where("us_email",$req->us_email)->first();
            if(empty($userCheck))
            {
                $user = new User();
                $user->us_email = $req->us_email;
                $user->us_password = Hash::make($req->us_password);
                $user->us_fullName = $req->us_fullname;
                $user->us_gender = $req->us_gender;
                $user->us_age = $req->us_age;
                $user->us_type = $req->us_type;
                // code
                $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $user->us_code = substr(str_shuffle($permitted_chars), 0, 20); 
                $path = public_path().'/uploadUsers/' . $user->us_code;
                File::makeDirectory( $path,0777,true);
                $user->save();
                if($req->file('us_image'))
                {
                    $image = $req->file('us_image');
                    $picName = time().'.'.$image->getClientOriginalExtension();
                    $image->move(public_path('uploadUsers/'.$user->us_code), $picName);
                    $user->us_image='uploadUsers/'.$user->us_code.'/'.$picName;
                    $user->save();
                }
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
                return back()->with("status","Create Account Success");
            }
            else
            {
                return back()->with("error","Email address already exists");
            }
        }
        else
        {
            return back()->with("error","Confirm password wrong");
        }
    }
    public function changeLanguage(Request $req)
    {
        if($req->lang == "vi")
        {
            \Session::put('website_language', 'vi');
        }
        if($req->lang == "en")
        {
            \Session::put('website_language', 'en');
        }
        return back();
    }
    public function history()
    {
        $user = Auth::user();
        return view('admin.history',['us_fullName'=>$user->us_fullName]);
    }
    public function showAllRoute()
    {
        $route = Route::get();
        return DataTables::of($route)
            ->addColumn(
                'stt',
                function ($route) {
                    $stt = "";
                    return $stt;
                }
            )
            ->addColumn(
                'fullName',
                function ($route) {
                    $fullName = User::select('us_fullName')->where("us_id",$route->to_id_user)->first();
                    return $fullName->us_fullName;
                }
            )
            ->addColumn(
                'startLocat',
                function ($route) {
                    if($route->to_startLocat == "")
                    {
                        $startLocat = '<span class="badge badge-warning">Not available</span>';
                    }
                    else
                    {
                        $startLocat = $route->to_startLocat;
                    }
                    return $startLocat;
                }
            )
            ->addColumn(
                'Detail',
                function ($route) {
                    $pieces = explode("-", $route->to_des);
                    $array = array();
                    for ($i=0; $i < count($pieces)-1; $i++) {
                        $array = Arr::add($array, $i ,$pieces[$i]);
                    }
                    $Detail = "";
                    foreach ($array as $value) {
                        $desName = Destination::select('de_name')->where("de_remove",$value)->first();
                        $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name;
                    }
                    return $Detail;
                }
            )
            ->addColumn(
                'startTime',
                function ($route) {
                    if($route->to_starttime == "")
                    {
                        $startTime ='<span class="badge badge-warning">Not available</span>';
                    }
                    else
                    {
                        $startTime = $route->to_starttime;
                    }
                    return $startTime;
                }
            )
            ->addColumn(
                'endTime',
                function ($route) {
                    if($route->to_endtime == "")
                    {
                        $endTime ='<span class="badge badge-warning">Not available</span>';
                    }
                    else
                    {
                        $endTime = $route->to_endtime;
                    }
                    return $endTime;
                }
            )  
            ->addColumn(
                'actions',
                function ($route) {
                    $actions = '<a href="'.route('admin.editTour',$route->to_id).'" target="_blank" class="btn btn-block btn-danger btn-sm">Edit Tour</a>';
                    return $actions;
                }
            )
            ->rawColumns(['stt','fullName','startLocat','Detail','startTime','endTime','actions'])
            ->make(true);
    }
    public function editTour($id)
    {
        $route = Route::where("to_id",$id)->first();
        //echo $route->startLocat;
        $user = Auth::user();
        return view('admin.edittour',['us_fullName'=>$user->us_fullName,'startLocat'=>$route->to_startLocat,'to_des'=>$route->to_des,'to_starttime'=>$route->to_starttime,'to_endtime'=>$route->to_endtime,'to_comback'=>$route->to_comback,'to_optimized'=>$route->to_optimized,'id'=>$id]);
    }
    public function editRoute(Request $req,$id)
    {
        $user = Auth::user();
        $route = Route::where("to_id",$id)->first();
        $route->to_id_user = $user->us_id;
        $i=0;
        $des = "";
        foreach ($req->locatsList as  $value) {
            $des = $des.$req->locatsList[$i]."-";
            $i++;
        }
        $route->to_des = $des;
        $route->to_starttime = $req->timeStart;
        $route->to_endtime = $req->timeEnd;
        $route->to_comback = $req->to_comback;
        $route->to_optimized = $req->to_optimized;
        $route->to_name = $req->nameTour;

        if($req->coordinates != "")
        {
            $route->to_startLocat = $req->coordinates;
        }
        $route->to_startDay = date('Y-m-d', strtotime(Carbon::now()));
        $route->save();
    }
}
