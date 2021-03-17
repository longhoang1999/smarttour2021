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


class ShareTourController extends Controller
{
    public function viewtour($shareId)
    {
        $share = ShareTour::where("sh_id",$shareId)->first();
        return view('sharetour.sharetour',['share'=>$share]);
    }
    public function rating(Request $req)
    {
    	$user = Auth::user();
    	$checkVote = Uservotes::where("us_id",$user->us_id)->where("sh_id",$req->shareId)->first();
    	if(!empty($checkVote))
    	{
    		$checkVote->vote_number = $req->numberStar;
    		$checkVote->save();
    		$allVote = Uservotes::where("sh_id",$req->shareId)->get();
    		$all_user_votes = $allVote->count();
    		$allStart = "0";
    		foreach ($allVote as $value) {
    			$allStart += floatval($value->vote_number);
    		}
    		$sharetour = ShareTour::where("sh_id",$req->shareId)->first();
    		$sharetour->number_star = floatval($allStart/$all_user_votes);
    		$sharetour->numberReviews = $all_user_votes;
    		$sharetour->save();
    	}
    	else
    	{
    		$uservotes = new Uservotes();
    		$uservotes->sh_id = $req->shareId;
    		$uservotes->us_id  = $user->us_id;
    		$uservotes->vote_number = $req->numberStar;
    		$uservotes->save();

    		$allVote = Uservotes::where("sh_id",$req->shareId)->get();
    		$all_user_votes = $allVote->count();
    		$allStart = "0";
    		foreach ($allVote as $value) {
    			$allStart += floatval($value->vote_number);
    		}

    		$sharetour = ShareTour::where("sh_id",$req->shareId)->first();
    		$sharetour->number_star = floatval($allStart/$all_user_votes);
    		$sharetour->numberReviews = $all_user_votes;
    		$sharetour->save();
    	}
    }
    public function  takeInforPlace(Request $req)
    {
        $checkDes = Destination::where("de_remove",$req->des_id)->first();
        if($checkDes->de_default == "0")
        {
	        if(Session::has('website_language') && Session::get('website_language') == "vi")
	        {
	        	$lang = Language::where("language","vn")->where("des_id",$req->des_id)->first();
	        	$des = Destination::where("de_remove",$req->des_id)->first();
	        	$de_lat = $des->de_lat;
	        	$de_lng = $des->de_lng;
	        	$de_name = $lang->de_name;
	        	$short = $lang->de_shortdes;
	        	$description = $lang->de_description;
	        }
	        else
	        {
	        	$lang = Language::where("language","en")->where("des_id",$req->des_id)->first();
	        	$des = Destination::where("de_remove",$req->des_id)->first();
	        	$de_lat = $des->de_lat;
	        	$de_lng = $des->de_lng;
	        	$de_name = $lang->de_name;
	        	$short = $lang->de_shortdes;
	        	$description = $lang->de_description;
	        }
	    }
	    else
	    {
	    	$de_lat = $checkDes->de_lat;
        	$de_lng = $checkDes->de_lng;
        	$de_name = $checkDes->de_name;
        	$short = "";
        	$description = "";
	    }
	    if($checkDes->de_image != "")
	    {
	    	$image = asset($checkDes->de_image);
	    }
	    else
	    {
	    	$image="";
	    }
	    return [$de_lat,$de_lng,$de_name,$image,$short,$description,$checkDes->de_duration,$checkDes->de_map,$checkDes->de_link];
    }
    public function loadmore($type)
    {
	    if($type == "login")
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
	            $shareTour = ShareTour::inRandomOrder()->limit(9)->get();
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
	            $shareTour = ShareTour::inRandomOrder()->limit(9)->get();
	        }
        	return view('dashboard',['fullName'=>$user->us_fullName,'des'=>$lang,'shareTour'=>$shareTour,'replace'=>'replace']);
        }
       	else if($type == "notlogin")
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
	            $shareTour = ShareTour::inRandomOrder()->limit(9)->get();
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
	            $shareTour = ShareTour::inRandomOrder()->limit(9)->get();
       		}
       		return view("generalinterface",['des'=>$lang,'shareTour'=>$shareTour,'replace'=>'replace']);
       	}
    }
    public function viewSharetour($id,$shareId)
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
        //$user = Auth::user();
        return view('admin.edittour',['shareId'=>$shareId,
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
            'justview' => 'justview'
        ]);
    }
}
