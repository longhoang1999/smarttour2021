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
use App\Models\Language;
use App\Models\Path;
use App\Models\ShareTour;

use PHPMailer;
use Session;
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
                        $position = '<span class="badge badge-warning">'.trans("admin.User").'</span>';
                    }
                    else if($allAccount->us_type == "1")
                        $position = '<span class="badge badge-primary">'.trans("admin.Admin").'</span>';
                    return $position;
                }
            )
            ->addColumn(
                'actions',
                function ($allAccount) {
                    $actions = '<button type="button" data-id="'.$allAccount->us_id.'"  class="btn btn-info btn-sm btn-block" data-toggle="modal" data-target="#modalDetail">
                          '.trans("admin.Detail").'
                        </button>';
                    if($allAccount->us_type != "1")
                    {
                        $actions = $actions.'<button type="button" data-id="'.$allAccount->us_id.'" class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#modalDelete">
                              '.trans("admin.Delete").'
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
                    $action = '<button class="btn btn-sm btn-info btn-block" data-id="'.$feedback->fb_id.'" data-toggle="modal" data-target="#exampleModal">'.trans("admin.Detail").'</button>';
                    $action = $action.'<button class="btn btn-sm btn-success btn-block" data-id="'.$feedback->fb_id.'" data-toggle="modal" data-target="#modalAnswer">'.trans("admin.Replytofeedback").'</button>';
                    return $action;
                }
            )
            ->rawColumns(['stt','email','fullName','action'])
            ->make(true);
    }
    public function getEmail(Request $req)
    {
        $email = User::where("us_id",$req->recipient)->first();
        if($email->us_checkEmail != "")
        {
            $status = "false";
        }
        else $status = "true";
        return [$email->us_email,$status];
    }
    public function sendFeedback(Request $req)
    {
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
        $mail->addAddress($req->emailRecipient, 'User');
        $mail->Subject = 'Email reply to your feedback!';
        $str='
        <h2>Email reply to your feedback!</h2>
        <small>(This is an automated message. Please do not reply)</small>
        <h4>Title email: '.$req->titleEmail.'</h4>
        <p>Content email: '.$req->content.'</p>
        ';
        $mail->Body = $str;
        if($mail->send())
        {
            return back()->with("success","Email successfully sent");
        }
        else
        {
            return back()->with("error","Cannot send email");
        }
        
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
        $destination = Language::where("language","en")->get();
            foreach ($destination as $value) {
                $des = Destination::select('de_remove','de_lat','de_lng','de_duration')->where("de_remove",$value->des_id)->first();
                $value["de_remove"] = $des->de_remove;
                $value["de_lat"] = $des->de_lat;
                $value["de_lng"] = $des->de_lng;
                $value["de_duration"] = $des->de_duration;
            }
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
                    $actions = '<button class="btn btn-block btn-info btn-sm" data-remove="'.$destination->de_remove.'" data-toggle="modal" data-target="#modalDetail">'.trans("admin.Detail").'</button>';
                    return $actions;
                }
            )
            ->rawColumns(['stt','duration','actions'])
            ->make(true);
    }
    public function showDestinationVN ()
    {
        $destination = Language::where("language","vn")->get();
            foreach ($destination as $value) {
                $des = Destination::select('de_remove','de_lat','de_lng','de_duration')->where("de_remove",$value->des_id)->first();
                $value["de_remove"] = $des->de_remove;
                $value["de_lat"] = $des->de_lat;
                $value["de_lng"] = $des->de_lng;
                $value["de_duration"] = $des->de_duration;
            }
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
                    $actions = '<button class="btn btn-block btn-info btn-sm" data-remove="'.$destination->de_remove.'" data-toggle="modal" data-target="#modalDetail">'.trans("admin.Detail").'</button>';
                    return $actions;
                }
            )
            ->rawColumns(['stt','duration','actions'])
            ->make(true);
    }
    public function showDestinationEditVN ()
    {
        $destination = Language::where("language","vn")->get();
            foreach ($destination as $value) {
                $des = Destination::select('de_remove','de_lat','de_lng','de_duration')->where("de_remove",$value->des_id)->first();
                $value["de_remove"] = $des->de_remove;
                $value["de_lat"] = $des->de_lat;
                $value["de_lng"] = $des->de_lng;
                $value["de_duration"] = $des->de_duration;
            }
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
                    $actions = '<button class="btn btn-block btn-info btn-sm" data-remove="'.$destination->de_remove.'" data-toggle="modal" data-target="#modalDetail">'.trans("admin.Detail").'</button>';
                    $actions = $actions.'<button class="btn btn-block btn-danger btn-sm" data-remove="'.$destination->de_remove.'" data-toggle="modal" data-target="#modalEdit">'.trans("admin.Edit").'</button>';
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
    	$destination->de_name = $req->de_name_vn;
    	$destination->de_lat = $req->de_lat;
    	$destination->de_lng = $req->de_lng;
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

        $lang_vn = new Language();
        $lang_vn->des_id = $randomletter;
        $lang_vn->language = "vn";
        $lang_vn->de_name = $req->de_name_vn;
        $lang_vn->de_description = $req->de_description_vn;
        $lang_vn->de_shortdes = $req->de_shortdes_vn;
        $lang_vn->save();
        
        $lang_en = new Language();
        $lang_en->des_id = $randomletter;
        $lang_en->language = "en";
        $lang_en->de_name = $req->de_name_en;
        $lang_en->de_description = $req->de_description_en;
        $lang_en->de_shortdes = $req->de_shortdes_en;
        $lang_en->save();
        

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
        $destination = Language::where("language","en")->get();
            foreach ($destination as $value) {
                $des = Destination::select('de_remove','de_lat','de_lng','de_duration')->where("de_remove",$value->des_id)->first();
                $value["de_remove"] = $des->de_remove;
                $value["de_lat"] = $des->de_lat;
                $value["de_lng"] = $des->de_lng;
                $value["de_duration"] = $des->de_duration;
            }
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
                    $actions = '<button class="btn btn-block btn-info btn-sm" data-remove="'.$destination->de_remove.'" data-toggle="modal" data-target="#modalDetail">'.trans("admin.Detail").'</button>';
                    $actions = $actions.'<button class="btn btn-block btn-danger btn-sm" data-remove="'.$destination->de_remove.'" data-toggle="modal" data-target="#modalDelete">'.trans("admin.Remove").'</button>';
                    return $actions;
                }
            )
            ->rawColumns(['stt','duration','actions'])
            ->make(true);
    }
    public function showDestinationRemoveVN()
    {
        $destination = Language::where("language","vn")->get();
            foreach ($destination as $value) {
                $des = Destination::select('de_remove','de_lat','de_lng','de_duration')->where("de_remove",$value->des_id)->first();
                $value["de_remove"] = $des->de_remove;
                $value["de_lat"] = $des->de_lat;
                $value["de_lng"] = $des->de_lng;
                $value["de_duration"] = $des->de_duration;
            }
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
                    $actions = '<button class="btn btn-block btn-info btn-sm" data-remove="'.$destination->de_remove.'" data-toggle="modal" data-target="#modalDetail">'.trans("admin.Detail").'</button>';
                    $actions = $actions.'<button class="btn btn-block btn-danger btn-sm" data-remove="'.$destination->de_remove.'" data-toggle="modal" data-target="#modalDelete">'.trans("admin.Remove").'</button>';
                    return $actions;
                }
            )
            ->rawColumns(['stt','duration','actions'])
            ->make(true);
    }
    public function showDetail(Request $req,$remove,$lang)
    {
        if($lang == "en")
        {
            $des = Language::where("language","en")->where("des_id",$remove)->first();
            $de = Destination::where("de_remove",$remove)->first();
            if(!empty($des) && !empty($de))
            {
                if($de->de_image != "")
                {
                    $image = asset($de->de_image);
                }
                else $image="";
                $duration = floatval($de->de_duration)/60/60;
                return [$des->de_name,$de->de_lng,$de->de_lat,$des->de_description,$des->de_shortdes,$duration,$de->de_link,$de->de_map,$image];
            }
            else
            {
                return "Can not find data";
            }
            
        }
        else if($lang == "vn")
        {
            $des = Language::where("language","vn")->where("des_id",$remove)->first();
            $de = Destination::where("de_remove",$remove)->first();
            if(!empty($des) && !empty($de))
            {
                if($de->de_image != "")
                {
                    $image = asset($de->de_image);
                }
                else $image="";
                $duration = floatval($de->de_duration)/60/60;
                return [$des->de_name,$de->de_lng,$de->de_lat,$des->de_description,$des->de_shortdes,$duration,$de->de_link,$de->de_map,$image];
            }
            else
            {
                return "Can not find data";
            }
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
        $lang = Language::where("des_id",$remove)->get();
        foreach ($lang as $value) {
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
        $destination = Language::where("language","en")->get();
            foreach ($destination as $value) {
                $des = Destination::select('de_remove','de_lat','de_lng','de_duration')->where("de_remove",$value->des_id)->first();
                $value["de_remove"] = $des->de_remove;
                $value["de_lat"] = $des->de_lat;
                $value["de_lng"] = $des->de_lng;
                $value["de_duration"] = $des->de_duration;
            }
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
                    $actions = '<button class="btn btn-block btn-info btn-sm" data-remove="'.$destination->de_remove.'" data-toggle="modal" data-target="#modalDetail">'.trans("admin.Detail").'</button>';
                    $actions = $actions.'<button class="btn btn-block btn-danger btn-sm" data-remove="'.$destination->de_remove.'" data-toggle="modal" data-target="#modalEdit">'.trans("admin.Edit").'</button>';
                    return $actions;
                }
            )
            ->rawColumns(['stt','duration','actions'])
            ->make(true);
    }
    public function showDetailEdit($remove,$lang)
    {   
        if($lang == "en")
        {
            $des = Language::where("language","en")->where("des_id",$remove)->first();
            $de = Destination::where("de_remove",$remove)->first();
            if(!empty($des) && !empty($de))
            {
                if($de->de_image != "")
                {
                    $image = asset($de->de_image);
                }
                else $image="";
                $duration = floatval($de->de_duration)/60/60;
                return [$des->de_name,$de->de_lng,$de->de_lat,$des->de_description,$des->de_shortdes,$duration,$de->de_link,$de->de_map,$image];
            }
            else
            {
                return "Can not find data";
            }
            
        }
        else if($lang == "vn")
        {
            $des = Language::where("language","vn")->where("des_id",$remove)->first();
            $de = Destination::where("de_remove",$remove)->first();
            if(!empty($des) && !empty($de))
            {
                if($de->de_image != "")
                {
                    $image = asset($de->de_image);
                }
                else $image="";
                $duration = floatval($de->de_duration)/60/60;
                return [$des->de_name,$de->de_lng,$de->de_lat,$des->de_description,$des->de_shortdes,$duration,$de->de_link,$de->de_map,$image];
            }
            else
            {
                return "Can not find data";
            }
        }
    }
    public function formEditPlace(Request $req,$remove,$lang)
    {
        if($lang == "en")
        {
            $des = Language::where("language","en")->where("des_id",$remove)->first();
            $de = Destination::where("de_remove",$remove)->first();
            if(!empty($des) && !empty($de))
            {
                $des->de_name=$req->placeName;
                $des->de_description=$req->description;
                $des->de_shortdes=$req->shortdes;
                $des->save();
                $de->de_duration= floatval($req->duration)*60*60;
                $de->de_lat=$req->latitude_edit;
                $de->de_lng=$req->longitude_edit;
                $de->de_map=$req->link_edit;
                $de->de_link=$req->de_link;
                if($req->file('image'))
                {
                    $image = $req->file('image');
                    File::delete(public_path($de->de_image));
                    $picName = time().'.'.$image->getClientOriginalExtension();
                    $image->move(public_path('imgPlace/'), $picName);
                    $de->de_image='imgPlace/'.$picName;
                }
                $de->save();
            }
        }
        else if($lang == "vn")
        {
            $des = Language::where("language","vn")->where("des_id",$remove)->first();
            $de = Destination::where("de_remove",$remove)->first();
            if(!empty($des) && !empty($de))
            {
                $des->de_name=$req->placeName;
                $des->de_description=$req->description;
                $des->de_shortdes=$req->shortdes;
                $des->save();
                $de->de_name = $req->placeName;
                $de->de_duration= floatval($req->duration)*60*60;
                $de->de_lat=$req->latitude_edit;
                $de->de_lng=$req->longitude_edit;
                $de->de_map=$req->link_edit;
                $de->de_link=$req->de_link;
                if($req->file('image'))
                {
                    $image = $req->file('image');
                    File::delete(public_path($de->de_image));
                    $picName = time().'.'.$image->getClientOriginalExtension();
                    $image->move(public_path('imgPlace/'), $picName);
                    $de->de_image='imgPlace/'.$picName;
                }
                $de->save();
            }
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
                'startLocat',
                function ($route) {
                    if($route->to_startLocat == "")
                    {
                        $startLocat = '<span class="badge badge-warning">Not available</span>';
                    }
                    else
                    {
                        $des = Destination::where("de_remove",$route->to_startLocat)->first();
                        $startLocat = $des->de_name;
                    }
                    return $startLocat;
                }
            )

            ->addColumn(
                'Detail',
                function ($route) {
                    $pieces = explode("|", $route->to_des);
                    $array = array();
                    for ($i=0; $i < count($pieces)-1; $i++) {
                        $array = Arr::add($array, $i ,$pieces[$i]);
                    }
                    $Detail = "";
                    foreach ($array as $value) {
                        $checkDes = Destination::where("de_remove",$value)->first();
                        if($checkDes->de_default == "0")
                        {
                            if(Session::has('website_language') && Session::get('website_language') == "vi")
                            {
                                $desName = Language::select('de_name')->where("language","vn")->where("des_id",$value)->first();
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name;
                            }
                            else
                            {
                                $desName = Language::select('de_name')->where("language","en")->where("des_id",$value)->first();
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name;
                            }
                        }
                        else if($checkDes->de_default == "1")
                        {
                            $Detail= $Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$checkDes->de_name;
                        }
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
                        $startTime = date('h:i:s a', strtotime($route->to_starttime));
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
                        $endTime = date('h:i:s a', strtotime($route->to_endtime));
                    }
                    return $endTime;
                }
            )  
            ->addColumn(
                'actions',
                function ($route) {
                    $actions = '<button class="btn btn-block btn-info btn-sm" data-id="'.$route->to_id.'" data-toggle="modal" data-target="#modalDetail">'.trans("admin.Detail").'</button>';
                    $actions =$actions.'<a href="'.route('admin.editTour',$route->to_id).'" target="_blank" class="btn btn-block btn-danger btn-sm">'.trans("admin.EditTour").'</a>';
                    return $actions;
                }
            )
            ->rawColumns(['stt','startLocat','Detail','startTime','endTime','actions'])
            ->make(true);
    }
    public function showAllRouteRating()
    {
        $share = ShareTour::get();
        return DataTables::of($share)
            ->addColumn(
                'stt',
                function ($share) {
                    $stt = "";
                    return $stt;
                }
            )
            ->addColumn(
                'tourName',
                function ($share) {
                    $tour = Route::where("to_id",$share->sh_to_id)->first();
                    $tourName = $tour->to_name;
                    return $tourName;
                }
            )
            ->addColumn(
                'Detail',
                function ($share) {
                    $route = Route::where("to_id",$share->sh_to_id)->first();
                    $pieces = explode("|", $route->to_des);
                    $array = array();
                    for ($i=0; $i < count($pieces)-1; $i++) {
                        $array = Arr::add($array, $i ,$pieces[$i]);
                    }
                    $Detail = "";
                    foreach ($array as $value) {
                        $checkDes = Destination::where("de_remove",$value)->first();
                        if($checkDes->de_default == "0")
                        {
                            if(Session::has('website_language') && Session::get('website_language') == "vi")
                            {
                                $desName = Language::select('de_name')->where("language","vn")->where("des_id",$value)->first();
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name;
                            }
                            else
                            {
                                $desName = Language::select('de_name')->where("language","en")->where("des_id",$value)->first();
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name;
                            }
                        }
                        else if($checkDes->de_default == "1")
                        {
                            $Detail= $Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$checkDes->de_name;
                        }
                    }
                    return $Detail;
                }
            )
            ->addColumn(
                'avg',
                function ($share) {
                    $avg = $share->number_star.' <i class="fas fa-star text-warning"></i>';
                    return $avg;
                }
            )
            ->addColumn(
                'actions',
                function ($share) {
                    $route = Route::where("to_id",$share->sh_to_id)->first();
                    $actions = '<button class="btn btn-block btn-info btn-sm" data-id="'.$route->to_id.'" data-toggle="modal" data-target="#modalDetail2">'.trans("admin.Detail").'</button>';
                    $actions = $actions.'<button class="btn btn-block btn-danger btn-sm" data-id="'.$share->sh_id.'" data-toggle="modal" data-target="#modalDelete">'.trans("admin.Remove").'</button>';
                    return $actions;
                }
            )
            ->rawColumns(['stt','tourName','Detail','avg','actions'])
            ->make(true);
    }
    public function sharetourDelete($id)
    {
        $shareTour = ShareTour::where("sh_id",$id)->first();
        File::delete(public_path($shareTour->image));
        $shareTour->delete();
        return back()->with("success","you have successfully deleted");
    }
    public function routeDetail(Request $req)
    {
        $route = Route::where("to_id",$req->recipient)->first();
        $user = User::where("us_id",$route->to_id_user)->first();

        $creator = $user->us_fullName;
        $tourName = $route->to_name;
        if($route->to_startLocat != "")
        {
            $des = Destination::where("de_remove",$route->to_startLocat)->first();
            $startLocat = $des->de_name;
        }
        else $startLocat = '<span class="badge badge-warning">'.trans("admin.Notavailable").'</span>';

        $pieces_2 = explode("|", $route->to_des);
        $array = array();
        for ($i=0; $i < count($pieces_2)-1; $i++) {
            $array = Arr::add($array, $i ,$pieces_2[$i]);
        }
        $Detail = "";

        foreach ($array as $value) {
            $desCheck = Destination::where("de_remove",$value)->first();
            if($desCheck->de_default == "0")
            {
                if(Session::has('website_language') && Session::get('website_language') == "vi")
                {
                    $desName = Language::select('de_name')->where("language","vn")->where("des_id",$value)->first();
                    $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                }
                else
                {
                    $desName = Language::select('de_name')->where("language","en")->where("des_id",$value)->first();
                    $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                }
            }
            else if($desCheck->de_default == "1")
            {
                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desCheck->de_name.'<br>';
            }
        }
        
        if($route->to_comback == "0")
        {
            $comeBack = '<span class="badge badge-warning">'.trans("admin.Noreturn").'</span>';
        }
        else if($route->to_comback == "1")
        {
            $comeBack = '<span class="badge badge-success">'.trans("admin.Havecomeback").'</span>';
        }
        if($route->to_optimized == "0")
        {
            $Optimized = '<span class="badge badge-warning">'.trans("admin.Notoptimal").'</span>';
        }
        else if($route->to_optimized == "1")
        {
            $Optimized = '<span class="badge badge-success">'.trans("admin.Optimizedforduration").'</span>';
        }
        else if($route->to_optimized == "2")
        {
            $Optimized = '<span class="badge badge-success">'.trans("admin.Optimizedforcost").'</span>';
        }
        $starttime = date('h:i:s a', strtotime($route->to_starttime));
        if($route->to_endtime != "")
            $endtime = date('h:i:s a', strtotime($route->to_endtime));
        else
            $endtime = '<span class="badge badge-success">'.trans("admin.Thereisnoendtime").'</span>';
        return [$creator,$tourName,$startLocat,$Detail,$starttime,$endtime,$comeBack,$Optimized];
    }
    public function routeDetail2(Request $req)
    {
        $route = Route::where("to_id",$req->recipient)->first();
        $user = User::where("us_id",$route->to_id_user)->first();

        $creator = $user->us_fullName;
        $tourName = $route->to_name;
        if($route->to_startLocat != "")
        {
            $des = Destination::where("de_remove",$route->to_startLocat)->first();
            $startLocat = $des->de_name;
        }
        else $startLocat = '<span class="badge badge-warning">'.trans("admin.Notavailable").'</span>';

        $pieces_2 = explode("|", $route->to_des);
        $array = array();
        for ($i=0; $i < count($pieces_2)-1; $i++) {
            $array = Arr::add($array, $i ,$pieces_2[$i]);
        }
        $Detail = "";

        foreach ($array as $value) {
            $desCheck = Destination::where("de_remove",$value)->first();
            if($desCheck->de_default == "0")
            {
                if(Session::has('website_language') && Session::get('website_language') == "vi")
                {
                    $desName = Language::select('de_name')->where("language","vn")->where("des_id",$value)->first();
                    $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                }
                else
                {
                    $desName = Language::select('de_name')->where("language","en")->where("des_id",$value)->first();
                    $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                }
            }
            else if($desCheck->de_default == "1")
            {
                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desCheck->de_name.'<br>';
            }
        }
        
        if($route->to_comback == "0")
        {
            $comeBack = '<span class="badge badge-warning">'.trans("admin.Noreturn").'</span>';
        }
        else if($route->to_comback == "1")
        {
            $comeBack = '<span class="badge badge-success">'.trans("admin.Havecomeback").'</span>';
        }
        if($route->to_optimized == "0")
        {
            $Optimized = '<span class="badge badge-warning">'.trans("admin.Notoptimal").'</span>';
        }
        else if($route->to_optimized == "1")
        {
            $Optimized = '<span class="badge badge-success">'.trans("admin.Optimizedforduration").'</span>';
        }
        else if($route->to_optimized == "2")
        {
            $Optimized = '<span class="badge badge-success">'.trans("admin.Optimizedforcost").'</span>';
        }
        $starttime = date('h:i:s a', strtotime($route->to_starttime));
        if($route->to_endtime != "")
            $endtime = date('h:i:s a', strtotime($route->to_endtime));
        else
            $endtime = '<span class="badge badge-success">'.trans("admin.Thereisnoendtime").'</span>';

        $share = ShareTour::where("sh_to_id",$route->to_id)->first();
        if($share->image == "")
        {
            $imageShare = "";
        }
        else  $imageShare = asset($share->image);
        return [$creator,$tourName,$startLocat,$Detail,$starttime,$endtime,$comeBack,$Optimized,$share->content,$share->number_star,$imageShare];
    }
    public function editTour($id)
    {
        $route = Route::where("to_id",$id)->first();
        // treo
        $pieces_2 = explode("|", $route->to_des);
        $array = array();
        for ($i=0; $i < count($pieces_2)-1; $i++) {
            $array = Arr::add($array, $i ,$pieces_2[$i]);
        }
        $latlng_new = array();
        $dename_new = array();
        $placeId_new = array();
        $duration_new = array();
        $j = 0;
        foreach ($array as $value) {
            $desCheck = Destination::where("de_remove",$value)->first();
            if($desCheck->de_default == "1")
            {
                $latlng = (object)array('lat' => $desCheck->de_lat, 'lng' => $desCheck->de_lng);
                $latlng_new = Arr::add($latlng_new, $j ,$latlng);
                $dename_new = Arr::add($dename_new, $j ,$desCheck->de_name);
                $placeId_new = Arr::add($placeId_new, $j ,$desCheck->de_remove);
                $duration_new = Arr::add($duration_new, $j ,$desCheck->de_duration);
                $j++;
            }
        }
        if($route->to_startLocat != "")        
        {
            $des = Destination::where("de_remove",$route->to_startLocat)->first();
            $latlng_start = (object)array('lat' => $des->de_lat, 'lng' => $des->de_lng);
            $dename_start = $des->de_name;
            $placeId_start =  $des->de_remove;
            $duration_start = $des->de_duration;
        }
        else
        {
            $latlng_start = "";
            $dename_start = "";
            $placeId_start =  "";
            $duration_start = "";
        }
        $user = Auth::user();
        return view('admin.edittour',['us_fullName'=>$user->us_fullName,
            'startLocat'=>$route->to_startLocat,
            'to_des'=>$route->to_des,
            'to_starttime'=>$route->to_starttime,
            'to_endtime'=>$route->to_endtime,
            'to_comback'=>$route->to_comback,
            'to_optimized'=>$route->to_optimized,
            'id'=>$id,
            'latlng_new' => $latlng_new,
            'dename_new' => $dename_new,
            'placeId_new' => $placeId_new,
            'duration_new' => $duration_new,
            'latlng_start' => $latlng_start,
            'dename_start' => $dename_start,
            'placeId_start' => $placeId_start,
            'duration_start' => $duration_start,
        ]);
    }
    public function editRoute(Request $req,$id)
    {
        // xóa cũ
        $idRoute = $id;
        $route = Route::where("to_id",$id)->first();
        if($route->to_startLocat != "")
        {
            $des = Destination::where("de_remove",$route->to_startLocat)->first();
            $des->delete();
        }
        $pieces = explode("|", $route->to_des);
        $array = array();
        for ($i=0; $i < count($pieces)-1; $i++) {
            $array = Arr::add($array, $i ,$pieces[$i]);
        }
        foreach ($array as $value) {
            $checkDes = Destination::where("de_remove",$value)->first();
            if($checkDes->de_default == "1")
            {
                $checkDes->delete();
            }
        }
        $route->delete();
        // thêm mới
        $user = Auth::user();
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
        $new_route = new Route();
        $new_route->to_id_user = $user->us_id;
        $new_route->to_starttime = $req->timeStart;
        $new_route->to_endtime = $req->timeEnd;
        $new_route->to_comback = $req->to_comback;
        $new_route->to_optimized = $req->to_optimized;
        $new_route->to_name = $req->nameTour;
        $new_route->to_startDay = date('Y-m-d', strtotime(Carbon::now()));
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
        $new_route->to_des = $desId;
        if(!empty($req->val))
        {
            $new_route->to_startLocat = $req->val['de_id'];
        }
        $new_route->save();
    }
}
