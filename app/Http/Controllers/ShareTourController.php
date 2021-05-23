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
use App\Models\TypePlace;
use App\Models\Langtype;
use App\Models\Comment;

use Session;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;


class ShareTourController extends Controller
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
    public function temporaryImg(Request $req)
    {
        // reset
        $user = Auth::user();
        $array = $this->cutArrray($user->temporary_photo);
        foreach ($array as $value) {
            File::delete(public_path('temporary_Img/'.$value));
        }
        $user->temporary_photo = "";
        
        $files = $req->file('input_file_img');
        $arrNameFile = array();
        foreach ($files as $file) {
            $picName = time().rand(10,1000).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('temporary_Img'), $picName);
            $user->temporary_photo = $user->temporary_photo.$picName."|";
            array_push($arrNameFile, $picName);
        }
        $user->save();
        return $arrNameFile;
    }
    public function temporaryDeleteImg(Request $req)
    {
        $user = Auth::user();
        $array = $this->cutArrray($user->temporary_photo);
        $listImg = "";
        foreach ($array as $value) {
            if($value == $req->nameImg)
                File::delete(public_path('temporary_Img/'.$value));
            else
                $listImg = $listImg.$value."|";
        }
        $user->temporary_photo = $listImg;
        $user->save();
        return;
    }
    public function addcomment($idShare,Request $req)
    {
        if($req->numberStar != "")
        {
            $findUserVotes = Uservotes::where("sh_id",$idShare)->where("us_id",Auth::user()->us_id)->first();
            if($findUserVotes)
            {
                $findUserVotes->vote_number = $req->numberStar;
                $findUserVotes->save();
                $userVotesId = $findUserVotes->id;
            }
            else
            {
                $userVotes = new Uservotes();
                $userVotes->sh_id = $idShare;
                $userVotes->us_id = Auth::user()->us_id;
                $userVotes->vote_number = $req->numberStar;
                $userVotes->save();
                $userVotesId = $userVotes->id;
            }
            $allVote = Uservotes::where("sh_id",$idShare)->get();
            $all_user_votes = $allVote->count();
            $allStart = "0";
            foreach ($allVote as $value) {
                $allStart += floatval($value->vote_number);
            }
            $sharetour = ShareTour::where("sh_id",$idShare)->first();
            $sharetour->number_star = floatval($allStart/$all_user_votes);
            $sharetour->numberReviews = $all_user_votes;
            $sharetour->save();
            if($req->content_rating != "")
            {
                $addComment = new Comment();
                $addComment->id_user_votes = $userVotesId;
                $addComment->co_content = $req->content_rating;
                if(Auth::user()->temporary_photo != "")
                {
                    $addComment->co_image = Auth::user()->temporary_photo;
                    $arrayImg = $this->cutArrray(Auth::user()->temporary_photo);
                    foreach ($arrayImg as $file) {
                        File::move(public_path('temporary_Img/'.$file), public_path('uploadUsers/'.Auth::user()->us_code.'/'.$file));
                    }
                    Auth::user()->temporary_photo = null;
                    Auth::user()->save();
                }
                $addComment->save();
            }
        }
        return back();
    }
    public function viewtour($shareId)
    {
        if(Auth()->check())
        {
            // reset temporary photo
            $array_temporary = $this->cutArrray(Auth::user()->temporary_photo);
            foreach ($array_temporary as $value) {
                File::delete(public_path('temporary_Img/'.$value));
            }
            Auth::user()->temporary_photo = null;
            Auth::user()->save();
            // gắn tour đã xem
            $check = 1;
            foreach ($this->cutArrray(Auth()->user()->tour_seen) as $value) {
                if($value ==$shareId)
                    $check = 2;
            }
            if($check == 1)
            {
                Auth()->user()->tour_seen = Auth()->user()->tour_seen.$shareId."|";
                Auth()->user()->save();
            }
        }
        $shareTour = ShareTour::where('sh_id','<>',$shareId)->orderBy('numberReviews', 'DESC')->limit(10)->get();
        $share = ShareTour::where("sh_id",$shareId)->first();
        $tour = Route::where("to_id",$share->sh_to_id)->first();
        $creatorName = User::select('us_fullName')->where("us_id",$tour->to_id_user)->first();
        $userVotes = Uservotes::where('sh_id',$share->sh_id)->orderBy('dateCreated', 'DESC')->get();
        $array = array();
        foreach ($userVotes as $value) {
            array_push($array, $value->id);
        }
        $findComment = Comment::whereIn("id_user_votes",$array)->orderBy('co_date_created', 'DESC')->paginate(10);
        $fiveStar = Uservotes::where('sh_id',$share->sh_id)->where("vote_number","5")->count();
        $fourStar = Uservotes::where('sh_id',$share->sh_id)->where("vote_number","4")->count();
        $threeStar = Uservotes::where('sh_id',$share->sh_id)->where("vote_number","3")->count();
        $towStar = Uservotes::where('sh_id',$share->sh_id)->where("vote_number","2")->count();
        $oneStar = Uservotes::where('sh_id',$share->sh_id)->where("vote_number","1")->count();
        return view('sharetour.sharetour',[
            'share'=>$share,
            'shareTour'=>$shareTour,
            'creatorName'=>$creatorName->us_fullName,
            'totalVotes' => $userVotes->count(),
            'fiveStar' => $fiveStar,'fourStar' => $fourStar,'threeStar' => $threeStar,'towStar' => $towStar,'oneStar' => $oneStar,
            'userVotes' => $userVotes,
            'findComment' => $findComment,
            'shareId' => $shareId,
            'typeComment' => 'all',
            'array_user_like' => $this->cutArrray($tour->user_like)
        ]);
    }
    public function choseComment(Request $req)
    {
        $shareId = $req->share_tour_id;
        if($req->chose_comment == "1")
        {
            return redirect()->route('viewtour',$shareId);
        }
        else if($req->chose_comment == "2")
        {
            return redirect()->route('viewtourUserComment',[$shareId]);
        }
    }
    public function viewtourUserComment($shareId)
    {
        // viewtour again
        $shareTour = ShareTour::where('sh_id','<>',$shareId)->orderBy('numberReviews', 'DESC')->limit(10)->get();
        $share = ShareTour::where("sh_id",$shareId)->first();
        $tour = Route::where("to_id",$share->sh_to_id)->first();
        $creatorName = User::select('us_fullName')->where("us_id",$tour->to_id_user)->first();
        $userVotes = Uservotes::where('us_id',Auth::user()->us_id)->where('sh_id',$share->sh_id)->orderBy('dateCreated', 'DESC')->get();
        $array = array();
        foreach ($userVotes as $value) {
            array_push($array, $value->id);
        }
        $findComment = Comment::whereIn("id_user_votes",$array)->orderBy('co_date_created', 'DESC')->paginate(10);
        $fiveStar = Uservotes::where('sh_id',$share->sh_id)->where("vote_number","5")->count();
        $fourStar = Uservotes::where('sh_id',$share->sh_id)->where("vote_number","4")->count();
        $threeStar = Uservotes::where('sh_id',$share->sh_id)->where("vote_number","3")->count();
        $towStar = Uservotes::where('sh_id',$share->sh_id)->where("vote_number","2")->count();
        $oneStar = Uservotes::where('sh_id',$share->sh_id)->where("vote_number","1")->count();
        return view('sharetour.sharetour',[
            'share'=>$share,
            'shareTour'=>$shareTour,
            'creatorName'=>$creatorName->us_fullName,
            'totalVotes' => $userVotes->count(),
            'fiveStar' => $fiveStar,'fourStar' => $fourStar,'threeStar' => $threeStar,'towStar' => $towStar,'oneStar' => $oneStar,
            'userVotes' => $userVotes,
            'findComment' => $findComment,
            'shareId' => $shareId,
            'typeComment' => 'user_login',
            'array_user_like' => $this->cutArrray($tour->user_like)
        ]);
    }
    public function changeLikeTour(Request $req)
    {
        $sharetour = ShareTour::where("sh_id",$req->shareId)->first();
        $tour = Route::where("to_id",$sharetour->sh_to_id)->first();
        $array_user_like = $this->cutArrray($tour->user_like);
        $check = 1;
        // unlike
        foreach ($array_user_like as $key => $value) {
            if(Auth::user()->us_id == $value)
            {
                $check = 2;
                unset($array_user_like[$key]);
            }
        }
        // like
        if($check == 1)
        {
            array_push($array_user_like, Auth::user()->us_id);
        }
        $user_like = "";
        foreach ($array_user_like as $value) {
            $user_like = $user_like.$value."|";
        }
        $tour->user_like = $user_like;
        $tour->save();
        return [$check,count($array_user_like)];
    }
    public function deleteComment($idComment)
    {
        $findComment = Comment::where("co_id",$idComment)->first();
        if($findComment->co_image != null)
        {
            $findUserVotes = Uservotes::select("us_id")->where("id",$findComment->id_user_votes)->first();
            $findUser = User::select("us_code")->where("us_id",$findUserVotes->us_id)->first();
            $array = $this->cutArrray($findComment->co_image);
            foreach ($array as $value) {
                File::delete(public_path('uploadUsers/'.$findUser->us_code.'/'.$value));
            }
        }
        $findComment->delete();
        return back()->with("success","Xóa thành công!");
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
                $typePlace = TypePlace::where("id",$des->de_type)->first();
                $langType = Langtype::select('nametype')->where("language","vn")->where("type_id",$typePlace->id)->first();
	        	$de_lat = $des->de_lat;
	        	$de_lng = $des->de_lng;
	        	$de_name = $lang->de_name;
	        	$short = $lang->de_shortdes;
	        	$description = $lang->de_description;
                $type = $langType->nametype;
	        }
	        else
	        {
	        	$lang = Language::where("language","en")->where("des_id",$req->des_id)->first();
	        	$des = Destination::where("de_remove",$req->des_id)->first();
                $typePlace = TypePlace::where("id",$des->de_type)->first();
                $langType = Langtype::select('nametype')->where("language","en")->where("type_id",$typePlace->id)->first();
	        	$de_lat = $des->de_lat;
	        	$de_lng = $des->de_lng;
	        	$de_name = $lang->de_name;
	        	$short = $lang->de_shortdes;
	        	$description = $lang->de_description;
                $type = $langType->nametype;
	        }
	    }
	    else
	    {
            $typePlace = TypePlace::where("id",$checkDes->de_type)->first();
            if(Session::has('website_language') && Session::get('website_language') == "vi")
                $langType = Langtype::select('nametype')->where("language","vn")->where("type_id",$typePlace->id)->first();
            else
                $langType = Langtype::select('nametype')->where("language","en")->where("type_id",$typePlace->id)->first();
	    	$de_lat = $checkDes->de_lat;
        	$de_lng = $checkDes->de_lng;
        	$de_name = $checkDes->de_name;
        	$short = $checkDes->de_shortdes;
        	$description = $checkDes->de_description;
            $type = $langType->nametype;
	    }
	    if($checkDes->de_image != "")
	    {
	    	$image = asset($checkDes->de_image);
	    }
	    else
	    {
	    	$image="";
	    }
        $linkDetail = route('showDetailPlace',$req->des_id);
	    return [$de_lat,$de_lng,$de_name,$image,$short,$description,$checkDes->de_duration,$checkDes->de_map,$checkDes->de_link,$type,$linkDetail];
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
        $array = $this->cutArrray($route->to_des);
        $latlng_new = array();
        $dename_new = array();
        $placeId_new = array();
        $duration_new = array();
        $description_new = array();
        $cost_new = array();
        $j = 0;
        foreach ($array as $value) {
            $desCheck = Destination::where("de_remove",$value)->first();
            if($desCheck->de_default == "1")
            {
                $latlng = (object)array('lat' => $desCheck->de_lat, 'lng' => $desCheck->de_lng);
                $latlng_new = Arr::add($latlng_new, $j ,$latlng);
                $dename_new = Arr::add($dename_new, $j ,$desCheck->de_name);
                $placeId_new = Arr::add($placeId_new, $j ,$desCheck->de_remove);
                if($desCheck->de_description != "")
                {
                    $description_new = Arr::add($description_new, $j ,$desCheck->de_description);
                }
                else
                {
                    $description_new = Arr::add($description_new, $j ,"");
                }
                $j++;
            }
        }
        $pieces_3 = explode("|", $route->to_duration);
        for ($i=0; $i < count($pieces_3)-1; $i++) {
            $duration_new = Arr::add($duration_new, $i ,$pieces_3[$i]);
        }
        //cost
        $pieces_4 = explode("|", $route->to_cost);
        for ($i=0; $i < count($pieces_4)-1; $i++) {
            $cost_new = Arr::add($cost_new, $i ,$pieces_4[$i]);
        }
        if($route->to_startLocat != "")        
        {
            $des = Destination::where("de_remove",$route->to_startLocat)->first();
            $latlng_start = (object)array('lat' => $des->de_lat, 'lng' => $des->de_lng);
            $dename_start = $des->de_name;
            $placeId_start =  $des->de_remove;
            $duration_start = $des->de_duration;
            $description_start = $des->de_description;
            $cost_start = $des->de_cost;
        }
        else
        {
            $latlng_start = "";
            $dename_start = "";
            $placeId_start =  "";
            $duration_start = "";
            $cost_start = "";
            $description_start = "";
        }
        //$user = Auth::user();
        return view('recommend_tour',[
            'shareId'=>$shareId,
            'startLocat'=>$route->to_startLocat,
            'to_des'=>$route->to_des,
            'to_starttime'=>$route->to_starttime,
            'to_endtime'=>$route->to_endtime,
            'to_comback'=>$route->to_comback,
            'to_optimized'=>$route->to_optimized,
            'to_currency'=>$route->to_currency,
            'id'=>$id,
            'latlng_new' => $latlng_new,
            'dename_new' => $dename_new,
            'placeId_new' => $placeId_new,
            'duration_new' => $duration_new,
            'description_new' => $description_new,
            'cost_new' => $cost_new,
            'latlng_start' => $latlng_start,
            'dename_start' => $dename_start,
            'placeId_start' => $placeId_start,
            'cost_start' => $cost_start,
            'duration_start' => $duration_start,
            'description_start' => $description_start,
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
        $votes_over = ShareTour::where("number_star",">=","4")->get()->count();
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
        $allTypePlace = TypePlace::where('status','<>','1')->get();
        if(Session::has('website_language') && Session::get('website_language') == "vi")
        {
            foreach ($allTypePlace as $value) {
                $findNameType = Langtype::where('type_id',$value->id)->where("language","vn")->first();
                $value['nameType'] = $findNameType->nametype;
            }
        }
        else
        {
            foreach ($allTypePlace as $value) {
                $findNameType = Langtype::where('type_id',$value->id)->where("language","en")->first();
                $value['nameType'] = $findNameType->nametype;
            }
        }
        return view('sharetour.searchtour',[
            'votes_over' => $votes_over,
            'votes_number' => $votes_number,
            'thismonth' => $findThisMon,
            'votes_total_time' => $votes_total_time->count(),
            'lang' => $lang,
            'allTypePlace' => $allTypePlace
        ]);
    }
    //search for name
    public function searchTourName($idShareTour)
    {
        $votes_over = ShareTour::where("sh_id",$idShareTour)->orderBy('number_star', 'DESC')->get();
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
                'evaluate',
                function ($votes_over) {
                    $evaluate = $this->converStar($votes_over->number_star);
                    return $evaluate.' <br>-'.$votes_over->numberReviews.' votes';
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
                    $array = $this->cutArrray($route->to_des);
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
            ->rawColumns(['stt','tourName','startLocat','detailPlace','evaluate','totalTime'])
            ->make(true);
    }
    public function converStar($num)
    {
        $num = floatval($num);
        $roundnum = round(($num+0.5)*2)/2 -0.5;
        $arr = array();
        for($i =5; $i>0; $i--){
            for( $j = 1; $j>=0;$j = $j-0.5){     
                if(($roundnum - $j)>=0 ){
                    $roundnum -= $j;
                    array_push($arr, $j);
                    break;
                }
            }
        }
        $starStsing = '';
        foreach ($arr as $value) {
            if($value == 1)
                $starStsing = $starStsing.'<i class="fas fa-star text-warning"></i>';
            else if($value == 0)
                $starStsing = $starStsing.'<i class="far fa-star text-warning"></i>';
            else if($value == 0.5)
                $starStsing = $starStsing.'<i class="fas fa-star-half-alt text-warning"></i>';
        }
        return $starStsing;
    }
    // div_1
    public function searchTourTable()
    {
        $votes_over = ShareTour::where("number_star",">=","4")->orderBy('number_star', 'DESC')->get();
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
                'evaluate',
                function ($votes_over) {
                    $evaluate = $this->converStar($votes_over->number_star);
                    return $evaluate.' <br>-'.$votes_over->numberReviews.' votes';
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
                    $array = $this->cutArrray($route->to_des);
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
            ->rawColumns(['stt','tourName','startLocat','detailPlace','evaluate','totalTime'])
            ->make(true);
    }
    //div_2
    public function searchMostVotes()
    {
        $votes_over = ShareTour::where("numberReviews",">=","2")->orderBy('number_star', 'DESC')->get();
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
                'evaluate',
                function ($votes_over) {
                    $evaluate = $this->converStar($votes_over->number_star);
                    return $evaluate.' <br>-'.$votes_over->numberReviews.' votes';
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
                    $array = $this->cutArrray($route->to_des);
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
            ->rawColumns(['stt','tourName','startLocat','detailPlace','evaluate','totalTime'])
            ->make(true);
    }
    // div_3
    public function searchThisMonth()
    {
        $votes_over = ShareTour::orderBy('number_star', 'DESC')->get();
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
                'evaluate',
                function ($votes_over) {
                    $evaluate = $this->converStar($votes_over->number_star);
                    return $evaluate.' <br>-'.$votes_over->numberReviews.' votes';
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
                    $array = $this->cutArrray($route->to_des);
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
            ->rawColumns(['stt','tourName','startLocat','detailPlace','evaluate','totalTime'])
            ->make(true);
    }
    //div_4
    public function searchForHighTotal()
    {
        $votes_over = ShareTour::orderBy('number_star', 'DESC')->get();
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
                'evaluate',
                function ($votes_over) {
                    $evaluate = $this->converStar($votes_over->number_star);
                    return $evaluate.' <br>-'.$votes_over->numberReviews.' votes';
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
                    $array = $this->cutArrray($route->to_des);
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
            ->rawColumns(['stt','tourName','startLocat','detailPlace','evaluate','totalTime'])
            ->make(true);
    }
    //max total
    public function searchMaxTotal()
    {
        $votes_over = ShareTour::orderBy('number_star', 'DESC')->get();
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
                'evaluate',
                function ($votes_over) {
                    $evaluate = $this->converStar($votes_over->number_star);
                    return $evaluate.' <br>-'.$votes_over->numberReviews.' votes';
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
                    $array = $this->cutArrray($route->to_des);
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
            ->rawColumns(['stt','tourName','startLocat','detailPlace','evaluate','totalTime'])
            ->make(true);
    }
    //min total
    public function searchMinTotal()
    {
        $votes_over = ShareTour::orderBy('number_star', 'DESC')->get();
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
                'evaluate',
                function ($votes_over) {
                    $evaluate = $this->converStar($votes_over->number_star);
                    return $evaluate.' <br>-'.$votes_over->numberReviews.' votes';
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
                    $array = $this->cutArrray($route->to_des);
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
            ->rawColumns(['stt','tourName','startLocat','detailPlace','evaluate','totalTime'])
            ->make(true);
    }
    //last month
    public function searchLastMonth()
    {
        $votes_over = ShareTour::orderBy('number_star', 'DESC')->get();
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
                'evaluate',
                function ($votes_over) {
                    $evaluate = $this->converStar($votes_over->number_star);
                    return $evaluate.' <br>-'.$votes_over->numberReviews.' votes';
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
                    $array = $this->cutArrray($route->to_des);
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
            ->rawColumns(['stt','tourName','startLocat','detailPlace','evaluate','totalTime'])
            ->make(true);
    }
    // tour you shared
    public function searchTourYouShared()
    {
        $votes_over = ShareTour::orderBy('number_star', 'DESC')->get();
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
                'evaluate',
                function ($votes_over) {
                    $evaluate = $this->converStar($votes_over->number_star);
                    return $evaluate.' <br>-'.$votes_over->numberReviews.' votes';
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
                    $array = $this->cutArrray($route->to_des);
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
            ->rawColumns(['stt','tourName','startLocat','detailPlace','evaluate','totalTime'])
            ->make(true);
    }
    public function searchAnyMonth($date)
    {
        $monthYear = explode("-", $date);
        $monthRequest = $monthYear[1];
        $yearRequest = $monthYear[0];

        $votes_over = ShareTour::orderBy('number_star', 'DESC')->get();
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
                'evaluate',
                function ($votes_over) {
                    $evaluate = $this->converStar($votes_over->number_star);
                    return $evaluate.' <br>-'.$votes_over->numberReviews.' votes';
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
                    $array = $this->cutArrray($route->to_des);
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
            ->rawColumns(['stt','tourName','startLocat','detailPlace','evaluate','totalTime'])
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
        $array = $this->cutArrray($route->to_des);
        //cost total
        
        if(Session::has('website_language') && Session::get('website_language') == "vi")
        {
            $pieces_cost = explode("|", $route->to_cost);
            $totalCost = 0;
            for ($i=0; $i < count($pieces_cost)-1; $i++) {
                $totalCost = $totalCost + intval($pieces_cost[$i]);
            }
            if($route->to_currency == "2")
                $totalCost = $totalCost*23000;
            $totalCost = $totalCost." VNĐ";
        }
        else
        {
            $pieces_cost = explode("|", $route->to_cost);
            $totalCost = 0;
            for ($i=0; $i < count($pieces_cost)-1; $i++) {
                $totalCost = $totalCost + intval($pieces_cost[$i]);
            }
            if($route->to_currency == "1")
                $totalCost = round($totalCost/23000, 2);
            $totalCost = $totalCost." USD";
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
        $your_votes = "";
        // your votes
        if(Auth::check())
        {
            $findVotes = Uservotes::where("us_id",Auth::user()->us_id)->where("sh_id",$sharetour->sh_id)->first();
            if(!empty($findVotes))
                for ($i=1; $i <= 5; $i++) { 
                    if($i <= $findVotes->vote_number)
                    {
                        $your_votes = $your_votes.' <i class="fas fa-star text-warning"></i>';
                    }
                }
            else
                $your_votes = '<span class="badge badge-warning">Not available</span>';
        }
        // other
        $evaluate = $this->converStar($sharetour->number_star).' -'.$sharetour->numberReviews.' votes</span>';
        // ignore 
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
        $link_detail_tour = route('viewtour',$sharetour->sh_id);
        //tour_creator
        $find_tour_creator = User::select('us_fullName')->where("us_id",$route->to_id_user)->first();
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
            $link_detail_tour,
            Carbon::parse($route->to_endtime)->diffInMinutes(Carbon::parse($route->to_starttime)),
            $sharetour->sh_id,
            $totalCost,
            $find_tour_creator->us_fullName,
            $evaluate
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
            $array = $this->cutArrray($value->to_des);
            if(count(array_intersect($req->listIdSearch, $array)) == count($req->listIdSearch))
            {
                array_push($saveListId, $value->to_id);
            }
        }
        return $saveListId;
    }
    public function selectTourForCost(Request $req)
    {
        $route = DB::table('tour')->rightJoin('sharetour', 'sharetour.sh_to_id', '=', 'tour.to_id')->get();
        if($req->currency == "VNĐ")
        {
            foreach($route as $key => $ro)
            {
                $pieces = explode("|", $ro->to_cost);
                $totalCost = 0;
                if($ro->to_currency == "1")
                {
                    for ($i=0; $i < count($pieces)-1; $i++) {
                        $totalCost = $totalCost+intval($pieces[$i]);
                    }
                    if(!isset($req->maximum))
                    {
                        if($totalCost < $req->minimum )
                            $route->forget($key);
                    }
                    else
                    {
                        //sss
                        if($totalCost < $req->minimum || $totalCost > $req->maximum)
                            $route->forget($key);
                    }
                }
                else if($ro->to_currency == "2")
                {
                    for ($i=0; $i < count($pieces)-1; $i++) {
                        $totalCost = $totalCost+intval($pieces[$i]);
                    }
                    $totalCost = $totalCost * 23000;
                    if(!isset($req->maximum))
                    {
                        if($totalCost < $req->minimum )
                            $route->forget($key);
                    }
                    else
                    {
                        if($totalCost < $req->minimum || $totalCost > $req->maximum)
                            $route->forget($key);
                    }
                }
            }
        }
        else if($req->currency == "USD")
        {
            foreach($route as $key => $ro)
            {
                $pieces = explode("|", $ro->to_cost);
                $totalCost = 0;
                if($ro->to_currency == "1")
                {
                    for ($i=0; $i < count($pieces)-1; $i++) {
                        $totalCost = $totalCost+intval($pieces[$i]);
                    }
                    $totalCost = $totalCost / 23000;
                    if(!isset($req->maximum))
                    {
                        if($totalCost < $req->minimum )
                            $route->forget($key);
                    }
                    else
                    {
                        if($totalCost < $req->minimum || $totalCost > $req->maximum)
                            $route->forget($key);
                    }
                }
                else if($ro->to_currency == "2")
                {
                    for ($i=0; $i < count($pieces)-1; $i++) {
                        $totalCost = $totalCost+intval($pieces[$i]);
                    }
                    if(!isset($req->maximum))
                    {
                        $route->forget($key);
                    }
                    else
                    {
                        if($totalCost < $req->minimum || $totalCost > $req->maximum)
                            $route->forget($key);
                    }
                }
            }
        }
        $saveListId = array();
        foreach ($route as $value) {
            array_push($saveListId, $value->to_id);
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
                'evaluate',
                function ($votes_over) {
                    $evaluate = $this->converStar($votes_over->number_star);
                    return $evaluate.' <br>-'.$votes_over->numberReviews.' votes';
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
                    $array = $this->cutArrray($route->to_des);
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
            ->rawColumns(['stt','tourName','startLocat','detailPlace','evaluate','totalTime'])
            ->make(true);
    }
    public function tourhistory()
    {
        return view('sharetour.tourhistory');
    }
    public function tourUserLike()
    {
        return view('sharetour.touruserlike');
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
                'Star',
                function ($route) {
                    $findShare = ShareTour::where("sh_to_id",$route->to_id)->first();
                    if(empty($findShare))
                    {
                        return $this->converStar($route->to_star);
                    }
                    else
                    {
                        return $this->converStar($findShare->number_star);
                    }
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
                    $array = $this->cutArrray($route->to_des);
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
            ->rawColumns(['stt','startLocat','detailPlace','totalTime','Star'])
            ->make(true);
    }
    public function showtourlike()
    {
        $allRoute = Route::get();
        $listTour = array();
        foreach($allRoute as $route)
        {
            $array_user_like = $this->cutArrray($route->user_like);
            foreach($array_user_like as $arr)
            {
                if($arr == Auth::user()->us_id)
                {
                    array_push($listTour, $route->to_id);
                }
            }
        }
        $route = Route::whereIn("to_id",$listTour)->get();
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
                'Star',
                function ($route) {
                    $findShare = ShareTour::where("sh_to_id",$route->to_id)->first();
                    if(empty($findShare))
                    {
                        return $this->converStar($route->to_star);
                    }
                    else
                    {
                        return $this->converStar($findShare->number_star);
                    }
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
                    $array = $this->cutArrray($route->to_des);
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
            ->rawColumns(['stt','startLocat','detailPlace','totalTime','Star'])
            ->make(true);
    }
    public function takeDetailTour(Request $req,$status)
    {
        $route = Route::where("to_id",$req->idTour)->first();
        // img + label
        $arrayImg = array();
        $arrayLabel = array();
        // label
        $array = $this->cutArrray($route->to_des);
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
        if($req->status == "haveshare")
        {
            $findShare = ShareTour::where("sh_to_id",$route->to_id)->first();
            $link_view_tour = route('viewtour',$findShare->sh_id);
        }
        else if($req->status == "noshare")
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
    public function getinforTouredit(Request $req)
    {
        $findTour = Route::where("to_id",$req->routeId)->first();
        $findShare = ShareTour::where("sh_to_id",$req->routeId)->first();
        if(empty($findShare))
            return [$findTour->to_name,$findTour->to_star,$share = "no"];
        else
        {
            if($findShare->image == "")
                $image = "";
            else
                $image = asset($findShare->image);
            return [$findTour->to_name,$findTour->to_star,$share = "yes",$findShare->content,$image];
        }
    }
    public function voteUser(Request $req)
    {
        $findUserVotes = Uservotes::where("sh_id",$req->shareId)->where("us_id",Auth::user()->us_id)->first();
        if(empty($findUserVotes))
            return [$status = "no"];
        else
            return [$status = "yes",$findUserVotes->vote_number];
    }
}
