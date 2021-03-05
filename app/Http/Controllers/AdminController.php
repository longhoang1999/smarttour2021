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
    	$allAccount=User::where('us_type',"0")->get();
        return DataTables::of($allAccount)
            ->addColumn(
                'stt',
                function ($allAccount) {
                    $stt="";
                    return $stt;
                }
            )
            
            ->rawColumns(['stt'])
            ->make(true);
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
    		$duration = floatval($des->de_duration)/60/60;
    		return [$des->de_name,$des->de_lng,$des->de_lat,$des->de_description,$des->de_shortdes,$duration,$des->de_link];
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
    		return [$des->de_name,$des->de_lng,$des->de_lat,$des->de_description,$des->de_shortdes,$duration,$des->de_link];
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
    
}
