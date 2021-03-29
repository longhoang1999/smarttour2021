<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
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
use Carbon\CarbonInterval;
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
    // public function loadmore($type)
    // {
	   //  if($type == "login")
	   //  {
	   //      if(Session::has('website_language') && Session::get('website_language') == "vi")
	   //      {
	   //          $user = Auth::user();
	   //          $route = Route::where('to_id_user',$user->us_id)->get();
	   //          session()->put('route',$route);
	   //          $lang = Language::where("language","vn")->get();
	   //          foreach ($lang as $value) {
	   //              $des = Destination::select('de_image','de_duration','de_link','de_map')->where("de_remove",$value->des_id)->first();
	   //              $value["de_image"] = $des->de_image;
	   //              $value["de_duration"] = $des->de_duration;
	   //              $value["de_link"] = $des->de_link;
	   //              $value["de_map"] = $des->de_map;
	   //          }
	   //          $shareTour = ShareTour::inRandomOrder()->limit(9)->get();
	   //      }
	   //      else
	   //      {
	   //          $user = Auth::user();
	   //          $route = Route::where('to_id_user',$user->us_id)->get();
	   //          session()->put('route',$route);
	   //          $lang = Language::where("language","en")->get();
	   //          foreach ($lang as $value) {
	   //              $des = Destination::select('de_image','de_duration','de_link','de_map')->where("de_remove",$value->des_id)->first();
	   //              $value["de_image"] = $des->de_image;
	   //              $value["de_duration"] = $des->de_duration;
	   //              $value["de_link"] = $des->de_link;
	   //              $value["de_map"] = $des->de_map;
	   //          }
	   //          $shareTour = ShareTour::inRandomOrder()->limit(9)->get();
	   //      }
    //     	return view('dashboard',['fullName'=>$user->us_fullName,'des'=>$lang,'shareTour'=>$shareTour,'replace'=>'replace']);
    //     }
    //    	else if($type == "notlogin")
    //    	{
    //    		if(Session::has('website_language') && Session::get('website_language') == "vi")
    //    		{
    //    			$lang = Language::where("language","vn")->get();
	   //          foreach ($lang as $value) {
	   //              $des = Destination::select('de_image','de_duration','de_link','de_map')->where("de_remove",$value->des_id)->first();
	   //              $value["de_image"] = $des->de_image;
	   //              $value["de_duration"] = $des->de_duration;
	   //              $value["de_link"] = $des->de_link;
	   //              $value["de_map"] = $des->de_map;
	   //          }
	   //          $shareTour = ShareTour::inRandomOrder()->limit(9)->get();
    //    		}
    //    		else
    //    		{
    //    			$lang = Language::where("language","en")->get();
	   //          foreach ($lang as $value) {
	   //              $des = Destination::select('de_image','de_duration','de_link','de_map')->where("de_remove",$value->des_id)->first();
	   //              $value["de_image"] = $des->de_image;
	   //              $value["de_duration"] = $des->de_duration;
	   //              $value["de_link"] = $des->de_link;
	   //              $value["de_map"] = $des->de_map;
	   //          }
	   //          $shareTour = ShareTour::inRandomOrder()->limit(9)->get();
    //    		}
    //    		return view("generalinterface",['des'=>$lang,'shareTour'=>$shareTour,'replace'=>'replace']);
    //    	}
    // }
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
        return view('recommend_tour',['shareId'=>$shareId,
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
    public function searchTour()
    {
        // find total share tour
        $votes_total_time = ShareTour::get();
        foreach ($votes_total_time as $key =>$sharetour) {
            $route = Route::where("to_id",$sharetour->sh_to_id)->first();
            if(intval(Carbon::parse($route->to_endtime)->day) - intval(Carbon::parse($route->to_starttime)->day) == 0)
                $votes_total_time->forget($key);
        }
        $votes_over = ShareTour::where("number_star",">=","8")->get()->count();
        $votes_number = ShareTour::where("numberReviews",">=","2")->get()->count();
        $allShareTour = ShareTour::get();
        $i=0;
        $array = array();
        foreach ($allShareTour as $sharetour) {
            $array = Arr::add($array, $i ,$sharetour->sh_to_id);
            $i++;
        }
        $findThisMon = Route::whereIn("to_id",$array)->whereMonth("to_startDay",Carbon::now()->month)->get()->count();

        if(Session::has('website_language') && Session::get('website_language') == "vi")
        {
            $lang = Language::select('de_name','des_id')->where("language","vn")->get();
        }
        else
        {
            $lang = Language::select('de_name','des_id')->where("language","en")->get();
        }
        return view('sharetour.searchtour',[
            'votes_over' => $votes_over,
            'votes_number' => $votes_number,
            'thismonth' => $findThisMon,
            'votes_total_time' => $votes_total_time->count(),
            'lang' => $lang
        ]);
    }
    // div_1
    public function searchTourTable()
    {
        $votes_over = ShareTour::where("number_star",">=","8")->get();
        return DataTables::of($votes_over)
            ->addColumn(
                'stt',
                function ($votes_over) {
                    $stt = "";
                    return $stt;
                }
            )
            ->addColumn(
                'tourName',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    $tourName = $route->to_name;
                    return $tourName;
                }
            )
            ->addColumn(
                'startLocat',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    if($route->to_startLocat == "")
                    {
                        $startLocat = '<span class="badge badge-warning">Not available</span>';
                    }
                    else
                    {
                        $des = Destination::where("de_remove",$route->to_startLocat)->first();
                        $startLocat = '<i class="fas fa-street-view" style="color:#e74949;"></i> '.$des->de_name;
                    }
                    return $startLocat;
                }
            )
            ->addColumn(
                'rating',
                function ($votes_over) {
                    $rating = $votes_over->number_star.' <i class="fas fa-star text-warning"></i>';
                    return $rating;
                }
            )
            ->addColumn(
                'votes',
                function ($votes_over) {
                    $votes = $votes_over->numberReviews.' votes';
                    return $votes;
                }
            )
            ->addColumn(
                'totalTime',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    $start_time = Carbon::parse($route->to_starttime);
                    $finish_time = Carbon::parse($route->to_endtime);
                    $totalTime_seconds = $finish_time->diffInSeconds($start_time);
                    //convert second to H:i:s
                    $dt = Carbon::now();
                    $days = $dt->diffInDays($dt->copy()->addSeconds($totalTime_seconds));
                    $hours = $dt->diffInHours($dt->copy()->addSeconds($totalTime_seconds)->subDays($days));
                    $minutes = $dt->diffInMinutes($dt->copy()->addSeconds($totalTime_seconds)->subDays($days)->subHours($hours));
                    return $totalTime = CarbonInterval::days($days)->hours($hours)->minutes($minutes)->forHumans();
                }
            )
            ->addColumn(
                'detailPlace',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
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
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                            }
                            else
                            {
                                $desName = Language::select('de_name')->where("language","en")->where("des_id",$value)->first();
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                            }
                        }
                        else if($checkDes->de_default == "1")
                        {
                            $Detail= $Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$checkDes->de_name.'<br>';
                        }
                    }
                    return $Detail;
                }
            )
            ->rawColumns(['stt','tourName','startLocat','detailPlace','rating','votes','totalTime'])
            ->make(true);
    }
    //div_2
    public function searchMostVotes()
    {
        $votes_over = ShareTour::where("numberReviews",">=","2")->get();
        return DataTables::of($votes_over)
            ->addColumn(
                'stt',
                function ($votes_over) {
                    $stt = "";
                    return $stt;
                }
            )
            ->addColumn(
                'tourName',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    $tourName = $route->to_name;
                    return $tourName;
                }
            )
            ->addColumn(
                'startLocat',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    if($route->to_startLocat == "")
                    {
                        $startLocat = '<span class="badge badge-warning">Not available</span>';
                    }
                    else
                    {
                        $des = Destination::where("de_remove",$route->to_startLocat)->first();
                        $startLocat = '<i class="fas fa-street-view" style="color:#e74949;"></i> '.$des->de_name;
                    }
                    return $startLocat;
                }
            )
            ->addColumn(
                'rating',
                function ($votes_over) {
                    $rating = $votes_over->number_star.' <i class="fas fa-star text-warning"></i>';
                    return $rating;
                }
            )
            ->addColumn(
                'votes',
                function ($votes_over) {
                    $votes = $votes_over->numberReviews.' votes';
                    return $votes;
                }
            )
            ->addColumn(
                'totalTime',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    $start_time = Carbon::parse($route->to_starttime);
                    $finish_time = Carbon::parse($route->to_endtime);
                    $totalTime_seconds = $finish_time->diffInSeconds($start_time);
                    //convert second to H:i:s
                    $dt = Carbon::now();
                    $days = $dt->diffInDays($dt->copy()->addSeconds($totalTime_seconds));
                    $hours = $dt->diffInHours($dt->copy()->addSeconds($totalTime_seconds)->subDays($days));
                    $minutes = $dt->diffInMinutes($dt->copy()->addSeconds($totalTime_seconds)->subDays($days)->subHours($hours));
                    return $totalTime = CarbonInterval::days($days)->hours($hours)->minutes($minutes)->forHumans();
                }
            )
            ->addColumn(
                'detailPlace',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
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
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                            }
                            else
                            {
                                $desName = Language::select('de_name')->where("language","en")->where("des_id",$value)->first();
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                            }
                        }
                        else if($checkDes->de_default == "1")
                        {
                            $Detail= $Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$checkDes->de_name.'<br>';
                        }
                    }
                    return $Detail;
                }
            )
            ->rawColumns(['stt','tourName','startLocat','detailPlace','rating','votes','totalTime'])
            ->make(true);
    }
    // div_3
    public function searchThisMonth()
    {
        $votes_over = ShareTour::get();
        foreach ($votes_over as $key => $value) {
            $checkRoute = Route::where("to_id",$value->sh_to_id)->first();
            $month = date("m", strtotime($checkRoute->to_startDay)); 
            if($month != Carbon::now()->month)
            {
                // xóa phần tử khỏi model eloquent
                $votes_over->forget($key);
            }
        }
        return DataTables::of($votes_over)
            ->addColumn(
                'stt',
                function ($votes_over) {
                    $stt = "";
                    return $stt;
                }
            )
            ->addColumn(
                'tourName',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    $tourName = $route->to_name;
                    return $tourName;
                }
            )
            ->addColumn(
                'startLocat',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    if($route->to_startLocat == "")
                    {
                        $startLocat = '<span class="badge badge-warning">Not available</span>';
                    }
                    else
                    {
                        $des = Destination::where("de_remove",$route->to_startLocat)->first();
                        $startLocat = '<i class="fas fa-street-view" style="color:#e74949;"></i> '.$des->de_name;
                    }
                    return $startLocat;
                }
            )
            ->addColumn(
                'rating',
                function ($votes_over) {
                    $rating = $votes_over->number_star.' <i class="fas fa-star text-warning"></i>';
                    return $rating;
                }
            )
            ->addColumn(
                'votes',
                function ($votes_over) {
                    $votes = $votes_over->numberReviews.' votes';
                    return $votes;
                }
            )
            ->addColumn(
                'totalTime',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    $start_time = Carbon::parse($route->to_starttime);
                    $finish_time = Carbon::parse($route->to_endtime);
                    $totalTime_seconds = $finish_time->diffInSeconds($start_time);
                    //convert second to H:i:s
                    $dt = Carbon::now();
                    $days = $dt->diffInDays($dt->copy()->addSeconds($totalTime_seconds));
                    $hours = $dt->diffInHours($dt->copy()->addSeconds($totalTime_seconds)->subDays($days));
                    $minutes = $dt->diffInMinutes($dt->copy()->addSeconds($totalTime_seconds)->subDays($days)->subHours($hours));
                    return $totalTime = CarbonInterval::days($days)->hours($hours)->minutes($minutes)->forHumans();
                }
            )
            ->addColumn(
                'detailPlace',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
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
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                            }
                            else
                            {
                                $desName = Language::select('de_name')->where("language","en")->where("des_id",$value)->first();
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                            }
                        }
                        else if($checkDes->de_default == "1")
                        {
                            $Detail= $Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$checkDes->de_name.'<br>';
                        }
                    }
                    return $Detail;
                }
            )
            ->rawColumns(['stt','tourName','startLocat','detailPlace','rating','votes','totalTime'])
            ->make(true);
    }
    //div_4
    public function searchForHighTotal()
    {
        $votes_over = ShareTour::get();
        foreach ($votes_over as $key =>$sharetour) {
            $route = Route::where("to_id",$sharetour->sh_to_id)->first();
            if(intval(Carbon::parse($route->to_endtime)->day) - intval(Carbon::parse($route->to_starttime)->day) == 0)
                $votes_over->forget($key);
        }
        return DataTables::of($votes_over)
            ->addColumn(
                'stt',
                function ($votes_over) {
                    $stt = "";
                    return $stt;
                }
            )
            ->addColumn(
                'tourName',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    $tourName = $route->to_name;
                    return $tourName;
                }
            )
            ->addColumn(
                'startLocat',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    if($route->to_startLocat == "")
                    {
                        $startLocat = '<span class="badge badge-warning">Not available</span>';
                    }
                    else
                    {
                        $des = Destination::where("de_remove",$route->to_startLocat)->first();
                        $startLocat = '<i class="fas fa-street-view" style="color:#e74949;"></i> '.$des->de_name;
                    }
                    return $startLocat;
                }
            )
            ->addColumn(
                'rating',
                function ($votes_over) {
                    $rating = $votes_over->number_star.' <i class="fas fa-star text-warning"></i>';
                    return $rating;
                }
            )
            ->addColumn(
                'votes',
                function ($votes_over) {
                    $votes = $votes_over->numberReviews.' votes';
                    return $votes;
                }
            )
            ->addColumn(
                'totalTime',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    $start_time = Carbon::parse($route->to_starttime);
                    $finish_time = Carbon::parse($route->to_endtime);
                    $totalTime_seconds = $finish_time->diffInSeconds($start_time);
                    //convert second to H:i:s
                    $dt = Carbon::now();
                    $days = $dt->diffInDays($dt->copy()->addSeconds($totalTime_seconds));
                    $hours = $dt->diffInHours($dt->copy()->addSeconds($totalTime_seconds)->subDays($days));
                    $minutes = $dt->diffInMinutes($dt->copy()->addSeconds($totalTime_seconds)->subDays($days)->subHours($hours));
                    return $totalTime = CarbonInterval::days($days)->hours($hours)->minutes($minutes)->forHumans();
                }
            )
            ->addColumn(
                'detailPlace',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
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
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                            }
                            else
                            {
                                $desName = Language::select('de_name')->where("language","en")->where("des_id",$value)->first();
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                            }
                        }
                        else if($checkDes->de_default == "1")
                        {
                            $Detail= $Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$checkDes->de_name.'<br>';
                        }
                    }
                    return $Detail;
                }
            )
            ->rawColumns(['stt','tourName','startLocat','detailPlace','rating','votes','totalTime'])
            ->make(true);
    }
    //max total
    public function searchMaxTotal()
    {
        $votes_over = ShareTour::get();
        foreach ($votes_over as $key =>$sharetour) {
            $route = Route::where("to_id",$sharetour->sh_to_id)->first();
            $sharetour['minutes'] = Carbon::parse($route->to_endtime)->diffInMinutes(Carbon::parse($route->to_starttime));
        }
        $votes_over = $votes_over->sortBy('minutes');
        return DataTables::of($votes_over)
            ->addColumn(
                'stt',
                function ($votes_over) {
                    $stt = "";
                    return $stt;
                }
            )
            ->addColumn(
                'tourName',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    $tourName = $route->to_name;
                    return $tourName;
                }
            )
            ->addColumn(
                'startLocat',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    if($route->to_startLocat == "")
                    {
                        $startLocat = '<span class="badge badge-warning">Not available</span>';
                    }
                    else
                    {
                        $des = Destination::where("de_remove",$route->to_startLocat)->first();
                        $startLocat = '<i class="fas fa-street-view" style="color:#e74949;"></i> '.$des->de_name;
                    }
                    return $startLocat;
                }
            )
            ->addColumn(
                'rating',
                function ($votes_over) {
                    $rating = $votes_over->number_star.' <i class="fas fa-star text-warning"></i>';
                    return $rating;
                }
            )
            ->addColumn(
                'votes',
                function ($votes_over) {
                    $votes = $votes_over->numberReviews.' votes';
                    return $votes;
                }
            )
            ->addColumn(
                'totalTime',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    $start_time = Carbon::parse($route->to_starttime);
                    $finish_time = Carbon::parse($route->to_endtime);
                    $totalTime_seconds = $finish_time->diffInSeconds($start_time);
                    //convert second to H:i:s
                    $dt = Carbon::now();
                    $days = $dt->diffInDays($dt->copy()->addSeconds($totalTime_seconds));
                    $hours = $dt->diffInHours($dt->copy()->addSeconds($totalTime_seconds)->subDays($days));
                    $minutes = $dt->diffInMinutes($dt->copy()->addSeconds($totalTime_seconds)->subDays($days)->subHours($hours));
                    return $totalTime = CarbonInterval::days($days)->hours($hours)->minutes($minutes)->forHumans();
                }
            )
            ->addColumn(
                'detailPlace',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
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
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                            }
                            else
                            {
                                $desName = Language::select('de_name')->where("language","en")->where("des_id",$value)->first();
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                            }
                        }
                        else if($checkDes->de_default == "1")
                        {
                            $Detail= $Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$checkDes->de_name.'<br>';
                        }
                    }
                    return $Detail;
                }
            )
            ->rawColumns(['stt','tourName','startLocat','detailPlace','rating','votes','totalTime'])
            ->make(true);
    }
    //min total
    public function searchMinTotal()
    {
        $votes_over = ShareTour::get();
        foreach ($votes_over as $key =>$sharetour) {
            $route = Route::where("to_id",$sharetour->sh_to_id)->first();
            $sharetour['minutes'] = Carbon::parse($route->to_endtime)->diffInMinutes(Carbon::parse($route->to_starttime));
        }
        $votes_over = $votes_over->sortBy('minutes',SORT_REGULAR, true);
        return DataTables::of($votes_over)
            ->addColumn(
                'stt',
                function ($votes_over) {
                    $stt = "";
                    return $stt;
                }
            )
            ->addColumn(
                'tourName',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    $tourName = $route->to_name;
                    return $tourName;
                }
            )
            ->addColumn(
                'startLocat',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    if($route->to_startLocat == "")
                    {
                        $startLocat = '<span class="badge badge-warning">Not available</span>';
                    }
                    else
                    {
                        $des = Destination::where("de_remove",$route->to_startLocat)->first();
                        $startLocat = '<i class="fas fa-street-view" style="color:#e74949;"></i> '.$des->de_name;
                    }
                    return $startLocat;
                }
            )
            ->addColumn(
                'rating',
                function ($votes_over) {
                    $rating = $votes_over->number_star.' <i class="fas fa-star text-warning"></i>';
                    return $rating;
                }
            )
            ->addColumn(
                'votes',
                function ($votes_over) {
                    $votes = $votes_over->numberReviews.' votes';
                    return $votes;
                }
            )
            ->addColumn(
                'totalTime',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    $start_time = Carbon::parse($route->to_starttime);
                    $finish_time = Carbon::parse($route->to_endtime);
                    $totalTime_seconds = $finish_time->diffInSeconds($start_time);
                    //convert second to H:i:s
                    $dt = Carbon::now();
                    $days = $dt->diffInDays($dt->copy()->addSeconds($totalTime_seconds));
                    $hours = $dt->diffInHours($dt->copy()->addSeconds($totalTime_seconds)->subDays($days));
                    $minutes = $dt->diffInMinutes($dt->copy()->addSeconds($totalTime_seconds)->subDays($days)->subHours($hours));
                    return $totalTime = CarbonInterval::days($days)->hours($hours)->minutes($minutes)->forHumans();
                }
            )
            ->addColumn(
                'detailPlace',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
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
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                            }
                            else
                            {
                                $desName = Language::select('de_name')->where("language","en")->where("des_id",$value)->first();
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                            }
                        }
                        else if($checkDes->de_default == "1")
                        {
                            $Detail= $Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$checkDes->de_name.'<br>';
                        }
                    }
                    return $Detail;
                }
            )
            ->rawColumns(['stt','tourName','startLocat','detailPlace','rating','votes','totalTime'])
            ->make(true);
    }
    //last month
    public function searchLastMonth()
    {
        $votes_over = ShareTour::get();
        foreach ($votes_over as $key => $value) {
            $checkRoute = Route::where("to_id",$value->sh_to_id)->first();
            $month = date("m", strtotime($checkRoute->to_startDay)); 
            if($month != Carbon::now()->month-1)
            {
                // xóa phần tử khỏi model eloquent
                $votes_over->forget($key);
            }
        }
        return DataTables::of($votes_over)
            ->addColumn(
                'stt',
                function ($votes_over) {
                    $stt = "";
                    return $stt;
                }
            )
            ->addColumn(
                'tourName',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    $tourName = $route->to_name;
                    return $tourName;
                }
            )
            ->addColumn(
                'startLocat',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    if($route->to_startLocat == "")
                    {
                        $startLocat = '<span class="badge badge-warning">Not available</span>';
                    }
                    else
                    {
                        $des = Destination::where("de_remove",$route->to_startLocat)->first();
                        $startLocat = '<i class="fas fa-street-view" style="color:#e74949;"></i> '.$des->de_name;
                    }
                    return $startLocat;
                }
            )
            ->addColumn(
                'rating',
                function ($votes_over) {
                    $rating = $votes_over->number_star.' <i class="fas fa-star text-warning"></i>';
                    return $rating;
                }
            )
            ->addColumn(
                'votes',
                function ($votes_over) {
                    $votes = $votes_over->numberReviews.' votes';
                    return $votes;
                }
            )
            ->addColumn(
                'totalTime',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    $start_time = Carbon::parse($route->to_starttime);
                    $finish_time = Carbon::parse($route->to_endtime);
                    $totalTime_seconds = $finish_time->diffInSeconds($start_time);
                    //convert second to H:i:s
                    $dt = Carbon::now();
                    $days = $dt->diffInDays($dt->copy()->addSeconds($totalTime_seconds));
                    $hours = $dt->diffInHours($dt->copy()->addSeconds($totalTime_seconds)->subDays($days));
                    $minutes = $dt->diffInMinutes($dt->copy()->addSeconds($totalTime_seconds)->subDays($days)->subHours($hours));
                    return $totalTime = CarbonInterval::days($days)->hours($hours)->minutes($minutes)->forHumans();
                }
            )
            ->addColumn(
                'detailPlace',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
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
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                            }
                            else
                            {
                                $desName = Language::select('de_name')->where("language","en")->where("des_id",$value)->first();
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                            }
                        }
                        else if($checkDes->de_default == "1")
                        {
                            $Detail= $Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$checkDes->de_name.'<br>';
                        }
                    }
                    return $Detail;
                }
            )
            ->rawColumns(['stt','tourName','startLocat','detailPlace','rating','votes','totalTime'])
            ->make(true);
    }
    // tour you shared
    public function searchTourYouShared()
    {
        $votes_over = ShareTour::get();
        foreach ($votes_over as $key => $value) {
            $checkRoute = Route::where("to_id",$value->sh_to_id)->first();
            if($checkRoute->to_id_user != Auth::user()->us_id)
            {
                // xóa phần tử khỏi model eloquent
                $votes_over->forget($key);
            }
        }
        return DataTables::of($votes_over)
            ->addColumn(
                'stt',
                function ($votes_over) {
                    $stt = "";
                    return $stt;
                }
            )
            ->addColumn(
                'tourName',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    $tourName = $route->to_name;
                    return $tourName;
                }
            )
            ->addColumn(
                'startLocat',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    if($route->to_startLocat == "")
                    {
                        $startLocat = '<span class="badge badge-warning">Not available</span>';
                    }
                    else
                    {
                        $des = Destination::where("de_remove",$route->to_startLocat)->first();
                        $startLocat = '<i class="fas fa-street-view" style="color:#e74949;"></i> '.$des->de_name;
                    }
                    return $startLocat;
                }
            )
            ->addColumn(
                'rating',
                function ($votes_over) {
                    $rating = $votes_over->number_star.' <i class="fas fa-star text-warning"></i>';
                    return $rating;
                }
            )
            ->addColumn(
                'votes',
                function ($votes_over) {
                    $votes = $votes_over->numberReviews.' votes';
                    return $votes;
                }
            )
            ->addColumn(
                'totalTime',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    $start_time = Carbon::parse($route->to_starttime);
                    $finish_time = Carbon::parse($route->to_endtime);
                    $totalTime_seconds = $finish_time->diffInSeconds($start_time);
                    //convert second to H:i:s
                    $dt = Carbon::now();
                    $days = $dt->diffInDays($dt->copy()->addSeconds($totalTime_seconds));
                    $hours = $dt->diffInHours($dt->copy()->addSeconds($totalTime_seconds)->subDays($days));
                    $minutes = $dt->diffInMinutes($dt->copy()->addSeconds($totalTime_seconds)->subDays($days)->subHours($hours));
                    return $totalTime = CarbonInterval::days($days)->hours($hours)->minutes($minutes)->forHumans();
                }
            )
            ->addColumn(
                'detailPlace',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
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
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                            }
                            else
                            {
                                $desName = Language::select('de_name')->where("language","en")->where("des_id",$value)->first();
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                            }
                        }
                        else if($checkDes->de_default == "1")
                        {
                            $Detail= $Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$checkDes->de_name.'<br>';
                        }
                    }
                    return $Detail;
                }
            )
            ->rawColumns(['stt','tourName','startLocat','detailPlace','rating','votes','totalTime'])
            ->make(true);
    }
    public function searchAnyMonth($date)
    {
        $monthYear = explode("-", $date);
        $monthRequest = $monthYear[1];
        $yearRequest = $monthYear[0];

        $votes_over = ShareTour::get();
        foreach ($votes_over as $key => $value) {
            $checkRoute = Route::where("to_id",$value->sh_to_id)->first();
            $month = date("m", strtotime($checkRoute->to_startDay)); 
            $year = date("Y", strtotime($checkRoute->to_startDay)); 
            if($month != $monthRequest || $year != $yearRequest)
            {
                // xóa phần tử khỏi model eloquent
                $votes_over->forget($key);
            }
        }
        return DataTables::of($votes_over)
            ->addColumn(
                'stt',
                function ($votes_over) {
                    $stt = "";
                    return $stt;
                }
            )
            ->addColumn(
                'tourName',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    $tourName = $route->to_name;
                    return $tourName;
                }
            )
            ->addColumn(
                'startLocat',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    if($route->to_startLocat == "")
                    {
                        $startLocat = '<span class="badge badge-warning">Not available</span>';
                    }
                    else
                    {
                        $des = Destination::where("de_remove",$route->to_startLocat)->first();
                        $startLocat = '<i class="fas fa-street-view" style="color:#e74949;"></i> '.$des->de_name;
                    }
                    return $startLocat;
                }
            )
            ->addColumn(
                'rating',
                function ($votes_over) {
                    $rating = $votes_over->number_star.' <i class="fas fa-star text-warning"></i>';
                    return $rating;
                }
            )
            ->addColumn(
                'votes',
                function ($votes_over) {
                    $votes = $votes_over->numberReviews.' votes';
                    return $votes;
                }
            )
            ->addColumn(
                'totalTime',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    $start_time = Carbon::parse($route->to_starttime);
                    $finish_time = Carbon::parse($route->to_endtime);
                    $totalTime_seconds = $finish_time->diffInSeconds($start_time);
                    //convert second to H:i:s
                    $dt = Carbon::now();
                    $days = $dt->diffInDays($dt->copy()->addSeconds($totalTime_seconds));
                    $hours = $dt->diffInHours($dt->copy()->addSeconds($totalTime_seconds)->subDays($days));
                    $minutes = $dt->diffInMinutes($dt->copy()->addSeconds($totalTime_seconds)->subDays($days)->subHours($hours));
                    return $totalTime = CarbonInterval::days($days)->hours($hours)->minutes($minutes)->forHumans();
                }
            )
            ->addColumn(
                'detailPlace',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
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
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                            }
                            else
                            {
                                $desName = Language::select('de_name')->where("language","en")->where("des_id",$value)->first();
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                            }
                        }
                        else if($checkDes->de_default == "1")
                        {
                            $Detail= $Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$checkDes->de_name.'<br>';
                        }
                    }
                    return $Detail;
                }
            )
            ->rawColumns(['stt','tourName','startLocat','detailPlace','rating','votes','totalTime'])
            ->make(true);
    }

    public function takeDetailRoute(Request $req)
    {
        $sharetour = ShareTour::where("sh_id",$req->idShareTour)->first();
        $route = Route::where("to_id",$sharetour->sh_to_id)->first();
        // img + label
        $arrayImg = array();
        $arrayLabel = array();
        if($sharetour->image != "")
        {
            $imageStartLocat = array(asset($sharetour->image));
            array_push($arrayImg,$imageStartLocat);
            array_push($arrayLabel,array($route->to_name));
        }
        else
        {
            $imageStartLocat = array(asset('imgPlace/empty.png'));
            array_push($arrayImg,$imageStartLocat);
            array_push($arrayLabel,array($route->to_name));
        }
        // label
        $pieces = explode("|", $route->to_des);
        $array = array();
        for ($i=0; $i < count($pieces)-1; $i++) {
            $array = Arr::add($array, $i ,$pieces[$i]);
        }
        foreach ($array as $ar) {
            $findDes = Destination::where("de_remove",$ar)->first();
            if($findDes->de_default=="0")
            {
                if($findDes->de_image!= "")
                {
                    $imageLocation = array(asset($findDes->de_image));
                    array_push($arrayImg,$imageLocation);
                    if(Session::has('website_language') && Session::get('website_language') == "vi")
                    {
                        $lang = Language::where("des_id",$findDes->de_remove)->where("language","vn")->first();
                        array_push($arrayLabel,array($lang->de_name));
                    }
                    else
                    {
                        $lang = Language::where("des_id",$findDes->de_remove)->where("language","en")->first();
                        array_push($arrayLabel,array($lang->de_name));
                    }
                }
                else
                {
                    $imageLocation = array(asset('imgPlace/empty.png'));
                    array_push($arrayImg,$imageLocation);
                    if(Session::has('website_language') && Session::get('website_language') == "vi")
                    {
                        $lang = Language::where("des_id",$findDes->de_remove)->where("language","vn")->first();
                        array_push($arrayLabel,array($lang->de_name));
                    }
                    else
                    {
                        $lang = Language::where("des_id",$findDes->de_remove)->where("language","en")->first();
                        array_push($arrayLabel,array($lang->de_name));
                    }
                }
            }
        }
        // your votes
        if(Auth::check())
        {
            $findVotes = Uservotes::where("us_id",Auth::user()->us_id)->where("sh_id",$sharetour->sh_id)->first();
            if(!empty($findVotes))
                $your_votes =  $findVotes->vote_number. ' <i class="fas fa-star text-warning"></i>';
            else
                $your_votes = '<span class="badge badge-warning">Not available</span>';
        }
        else $your_votes = "";
        // other
        $avgRating = $sharetour->number_star. '<i class="fas fa-star text-warning"></i>';
        $number_rate = $sharetour->numberReviews. ' votes';
        // start locat
        if($route->to_startLocat != "")
        {
            $des = Destination::select("de_name")->where("de_remove",$route->to_startLocat)->first();
            $startLocat = '<span data-id="'.$route->to_startLocat.'"><i class="fas fa-street-view" style="color:#e74949;"></i> '.$des->de_name.'</span>';
        }
        else
            $startLocat = '<span class="badge badge-warning">Not available</span>';
        $link_view_tour = route('share.viewSharetour',[$route->to_id,$sharetour->sh_id]);

        return [$arrayImg,
            $arrayLabel,
            $route->to_name,
            $your_votes,
            $sharetour->content,
            $avgRating,
            $number_rate,
            $startLocat,
            $this->takeDetail($array),
            date('d/m/Y h:i a', strtotime($route->to_starttime)),
            date('d/m/Y h:i a', strtotime($route->to_endtime)),
            date('d/m/Y', strtotime($route->to_startDay)),
            $link_view_tour,
            Carbon::parse($route->to_endtime)->diffInMinutes(Carbon::parse($route->to_starttime))
        ];
    }
    public function takeDetail($array)
    {
        $Detail = "";
        foreach ($array as $value)
        {
            $checkDes = Destination::where("de_remove",$value)->first();
            if($checkDes->de_default == "0")
            {
                if(Session::has('website_language') && Session::get('website_language') == "vi")
                {
                    $desName = Language::select('de_name','des_id')->where("language","vn")->where("des_id",$value)->first();
                    $Detail=$Detail.'<span data-id="'.$desName->des_id.'"><i class="fas fa-street-view" style="color:#e74949;"></i> '.$desName->de_name.'</span>';
                }
                else
                {
                    $desName = Language::select('de_name','des_id')->where("language","en")->where("des_id",$value)->first();
                    $Detail=$Detail.'<span data-id="'.$desName->des_id.'"><i class="fas fa-street-view" style="color:#e74949;"></i> '.$desName->de_name.'</span>';
                }
            }
            else if($checkDes->de_default == "1")
            {
                $Detail=$Detail.'<span data-id="'.$checkDes->de_remove.'"><i class="fas fa-street-view" style="color:#e74949;"></i> '.$checkDes->de_name.'</span>';
            }
        }
        return $Detail;
    }
    public function selectPlaceForType(Request $req)
    {
        if($req->type == "All")
        {
            if(Session::has('website_language') && Session::get('website_language') == "vi")
            {
                $des = Language::select('des_id','de_name')->where("language","vn")->get();
                foreach ($des as $value) {
                    $value['de_remove'] = $value['des_id'];
                }
            }
            else
            {
                $des = Language::select('des_id','de_name')->where("language","en")->get();
                foreach ($des as $value) {
                    $value['de_remove'] = $value['des_id'];
                }
            }
        }
        else
        {
            if(Session::has('website_language') && Session::get('website_language') == "vi")
            {
                $des = Destination::select('de_remove')->where("de_default","0")->where("de_type",$req->type)->get();
                foreach ($des as $value) {
                    $lang = Language::select('de_name')->where("language","vn")->where("des_id",$value->de_remove)->first();
                    $value['de_name'] = $lang->de_name;
                }
            }
            else
            {
                $des = Destination::select('de_remove')->where("de_default","0")->where("de_type",$req->type)->get();
                foreach ($des as $value) {
                    $lang = Language::select('de_name')->where("language","en")->where("des_id",$value->de_remove)->first();
                    $value['de_name'] = $lang->de_name;
                }
            }
        }
        $arrayId = array();
        $arrayName = array();
        foreach ($des as $de) {
            array_push($arrayId, $de->de_remove);
            array_push($arrayName, $de->de_name);
        }
        return [$arrayId,$arrayName];
    }
    public function selectTourForPlace(Request $req)
    {
        //return $req->listIdSearch;
        //$sharetour = ShareTour::get();
        $route = DB::table('tour')
            ->rightJoin('sharetour', 'sharetour.sh_to_id', '=', 'tour.to_id')
            ->get();
        $saveListId = array();
        foreach ($route as $value) {
            $pieces = explode("|", $value->to_des);
            $array = array();
            for ($i=0; $i < count($pieces)-1; $i++) {
                $array = Arr::add($array, $i ,$pieces[$i]);
            }
            if(count(array_intersect($req->listIdSearch, $array)) == count($req->listIdSearch))
            {
                array_push($saveListId, $value->to_id);
            }
        }
        return $saveListId;
    }
    public function searchListPlace($arrayToid)
    {
        $array = explode(',', $arrayToid);
        $votes_over = ShareTour::whereIn("sh_to_id",$array)->get();
        return DataTables::of($votes_over)
            ->addColumn(
                'stt',
                function ($votes_over) {
                    $stt = "";
                    return $stt;
                }
            )
            ->addColumn(
                'tourName',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    $tourName = $route->to_name;
                    return $tourName;
                }
            )
            ->addColumn(
                'startLocat',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    if($route->to_startLocat == "")
                    {
                        $startLocat = '<span class="badge badge-warning">Not available</span>';
                    }
                    else
                    {
                        $des = Destination::where("de_remove",$route->to_startLocat)->first();
                        $startLocat = '<i class="fas fa-street-view" style="color:#e74949;"></i> '.$des->de_name;
                    }
                    return $startLocat;
                }
            )
            ->addColumn(
                'rating',
                function ($votes_over) {
                    $rating = $votes_over->number_star.' <i class="fas fa-star text-warning"></i>';
                    return $rating;
                }
            )
            ->addColumn(
                'votes',
                function ($votes_over) {
                    $votes = $votes_over->numberReviews.' votes';
                    return $votes;
                }
            )
            ->addColumn(
                'totalTime',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
                    $start_time = Carbon::parse($route->to_starttime);
                    $finish_time = Carbon::parse($route->to_endtime);
                    $totalTime_seconds = $finish_time->diffInSeconds($start_time);
                    //convert second to H:i:s
                    $dt = Carbon::now();
                    $days = $dt->diffInDays($dt->copy()->addSeconds($totalTime_seconds));
                    $hours = $dt->diffInHours($dt->copy()->addSeconds($totalTime_seconds)->subDays($days));
                    $minutes = $dt->diffInMinutes($dt->copy()->addSeconds($totalTime_seconds)->subDays($days)->subHours($hours));
                    return $totalTime = CarbonInterval::days($days)->hours($hours)->minutes($minutes)->forHumans();
                }
            )
            ->addColumn(
                'detailPlace',
                function ($votes_over) {
                    $route = Route::where("to_id",$votes_over->sh_to_id)->first();
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
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                            }
                            else
                            {
                                $desName = Language::select('de_name')->where("language","en")->where("des_id",$value)->first();
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                            }
                        }
                        else if($checkDes->de_default == "1")
                        {
                            $Detail= $Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$checkDes->de_name.'<br>';
                        }
                    }
                    return $Detail;
                }
            )
            ->rawColumns(['stt','tourName','startLocat','detailPlace','rating','votes','totalTime'])
            ->make(true);
    }
    public function tourhistory()
    {
        return view('sharetour.tourhistory');
    }
    public function showtourhistory()
    {
        $route = Route::where("to_id_user",Auth::user()->us_id)->get();
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
                        $startLocat = '<i class="fas fa-street-view" style="color:#e74949;"></i> '.$des->de_name;
                    }
                    return $startLocat;
                }
            )
            ->addColumn(
                'totalTime',
                function ($route) {
                    $start_time = Carbon::parse($route->to_starttime);
                    $finish_time = Carbon::parse($route->to_endtime);
                    $totalTime_seconds = $finish_time->diffInSeconds($start_time);
                    //convert second to H:i:s
                    $dt = Carbon::now();
                    $days = $dt->diffInDays($dt->copy()->addSeconds($totalTime_seconds));
                    $hours = $dt->diffInHours($dt->copy()->addSeconds($totalTime_seconds)->subDays($days));
                    $minutes = $dt->diffInMinutes($dt->copy()->addSeconds($totalTime_seconds)->subDays($days)->subHours($hours));
                    return $totalTime = CarbonInterval::days($days)->hours($hours)->minutes($minutes)->forHumans();
                }
            )
            ->addColumn(
                'detailPlace',
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
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                            }
                            else
                            {
                                $desName = Language::select('de_name')->where("language","en")->where("des_id",$value)->first();
                                $Detail=$Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$desName->de_name.'<br>';
                            }
                        }
                        else if($checkDes->de_default == "1")
                        {
                            $Detail= $Detail.'<i class="fas fa-street-view" style="color:#e74949;"></i>'.$checkDes->de_name.'<br>';
                        }
                    }
                    return $Detail;
                }
            )
            ->rawColumns(['stt','startLocat','detailPlace','totalTime'])
            ->make(true);
    }
    public function takeDetailTour(Request $req)
    {
        $route = Route::where("to_id",$req->idTour)->first();
        // img + label
        $arrayImg = array();
        $arrayLabel = array();
        // label
        $pieces = explode("|", $route->to_des);
        $array = array();
        for ($i=0; $i < count($pieces)-1; $i++) {
            $array = Arr::add($array, $i ,$pieces[$i]);
        }
        foreach ($array as $ar) {
            $findDes = Destination::where("de_remove",$ar)->first();
            if($findDes->de_default=="0")
            {
                if($findDes->de_image!= "")
                {
                    $imageLocation = array(asset($findDes->de_image));
                    array_push($arrayImg,$imageLocation);
                    if(Session::has('website_language') && Session::get('website_language') == "vi")
                    {
                        $lang = Language::where("des_id",$findDes->de_remove)->where("language","vn")->first();
                        array_push($arrayLabel,array($lang->de_name));
                    }
                    else
                    {
                        $lang = Language::where("des_id",$findDes->de_remove)->where("language","en")->first();
                        array_push($arrayLabel,array($lang->de_name));
                    }
                }
                else
                {
                    $imageLocation = array(asset('imgPlace/empty.png'));
                    array_push($arrayImg,$imageLocation);
                    if(Session::has('website_language') && Session::get('website_language') == "vi")
                    {
                        $lang = Language::where("des_id",$findDes->de_remove)->where("language","vn")->first();
                        array_push($arrayLabel,array($lang->de_name));
                    }
                    else
                    {
                        $lang = Language::where("des_id",$findDes->de_remove)->where("language","en")->first();
                        array_push($arrayLabel,array($lang->de_name));
                    }
                }
            }
        }
        // start locat
        if($route->to_startLocat != "")
        {
            $des = Destination::select("de_name")->where("de_remove",$route->to_startLocat)->first();
            $startLocat = '<span data-id="'.$route->to_startLocat.'"><i class="fas fa-street-view" style="color:#e74949;"></i> '.$des->de_name.'</span>';
        }
        else
            $startLocat = '<span class="badge badge-warning">Not available</span>';
        $link_view_tour = route('user.editTour',[$route->to_id]);

        return [$arrayImg,
            $arrayLabel,
            $route->to_name,
            $startLocat,
            $this->takeDetail($array),
            date('d/m/Y h:i a', strtotime($route->to_starttime)),
            date('d/m/Y h:i a', strtotime($route->to_endtime)),
            date('d/m/Y', strtotime($route->to_startDay)),
            $link_view_tour,
            Carbon::parse($route->to_endtime)->diffInMinutes(Carbon::parse($route->to_starttime))
        ];
    }
}
