<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Destination;
use App\Models\TypePlace;
use App\Models\Langtype;
use App\Models\RatingPlace;
use Illuminate\Support\Arr;
use Session;
use DB;
class MapDirectController {
	public $routresult = array();
	public $total = 0;
	public $durordis;
	public $origin;
	public $isback;
	public $choosendur;
	public $dello;
	public function showmap(){
		if(Session::has('website_language') && Session::get('website_language') == "vi")
        {
            $de = Language::where("language","vn")->get();
            foreach ($de as $value) {
                $des = Destination::select('de_remove','de_lat','de_lng','de_link','de_duration','de_cost','de_image','de_tag')->where("de_remove",$value->des_id)->first();
                $value["de_id"] = $des->de_remove;
                $value["de_lat"] = $des->de_lat;
                $value["de_lng"] = $des->de_lng;
                $value["de_link"] = $des->de_link;
                $value["de_duration"] = $des->de_duration;
                $value["de_cost"] = $des->de_cost;
                $value["de_tag"] = $des->de_tag;
                if($des->de_image != "")
                	$value["de_image"] = asset($des->de_image);
            }
        }
        else
        {
            $de = Language::where("language","en")->get();
            foreach ($de as $value) {
                $des = Destination::select('de_remove','de_lat','de_lng','de_link','de_duration','de_cost','de_image','de_tag')->where("de_remove",$value->des_id)->first();
                $value["de_id"] = $des->de_remove;
                $value["de_lat"] = $des->de_lat;
                $value["de_lng"] = $des->de_lng;
                $value["de_link"] = $des->de_link;
                $value["de_duration"] = $des->de_duration;
                $value["de_cost"] = $des->de_cost;
                $value["de_tag"] = $des->de_tag;
                if($des->de_image != "")
                	$value["de_image"] = asset($des->de_image);
            }
        }
		$destination  = array();
		foreach ($de as $value) {
			$latlng = array('lat' => $value->de_lat, 'lng' => $value->de_lng);
			$array_detag = array();
			if($value->de_tag != null)
			{
		        $pieces = explode("|", $value->de_tag);
		        for ($i=0; $i < count($pieces)-1; $i++) {
		            $array_detag = Arr::add($array_detag, $i ,$pieces[$i]);
		        }
			}
			// ratingplace
			$array_ratez_votes = array();
			$findRatingPlace = RatingPlace::where("ra_des_id",$value->des_id)->get();
			$sumStar = 0;
			foreach ($findRatingPlace as $RatingPlace) {
				$sumStar = $sumStar + $RatingPlace->ra_votes;
			}
			if($findRatingPlace->count() > 0)
			{
				$avg = $sumStar / $findRatingPlace->count();
				array_push($array_ratez_votes, floatval(number_format((float)$avg, 1, '.', '')), $findRatingPlace->count());
			}
			else
			{
				array_push($array_ratez_votes, 0 ,$findRatingPlace->count());
			}
			//
			$tmp  = (object) array('de_name' => $value->de_name,'location' =>$latlng,'de_duration'=>$value->de_duration,'de_link'=>$value->de_link,'de_description'=>$value->de_description,'de_cost'=>$value->de_cost,'de_img'=>$value->de_image,'de_shortdes'=>$value->de_shortdes,'de_tag'=>$array_detag,'rate_votes'=>$array_ratez_votes);
			$des =   [$value->de_id,$tmp];
			array_push($destination,$des);
		}
		// thẻ select 2
		$typePlace = array();
		$totalType = TypePlace::where("status","0")->count();
		$type = TypePlace::select("id")->where("status","0")->get();

		for($i = 0; $i < $totalType; $i++)
		{
			if(Session::has('website_language') && Session::get('website_language') == "vi")
				$langType = Langtype::where("type_id",$type[$i]->id)->where("language","vn")->first();
			else
				$langType = Langtype::where("type_id",$type[$i]->id)->where("language","en")->first();
			${"info_type" . $i} = array('id'=>$i,'text'=>$langType->nametype,'children' => $this->findTypePlace($type[$i]->id));
			array_push($typePlace,${"info_type" . $i});
		}
		return [$destination,$typePlace];
	}
	public function findTypePlace($type)
	{
	    $des = Destination::where("de_default","0")->where("de_type",$type)->get();
	    $linePlace = array();
	    foreach ($des as $value) {
	    	if(Session::has('website_language') && Session::get('website_language') == "vi")
	    	{
	    		$lang = Language::where("language","vn")->where("des_id",$value->de_remove)->first();
	    		$idAndPlace = array('id' => $lang->des_id, 'text' => $lang->de_name);
	    		array_push($linePlace,$idAndPlace);
	    	}
	    	else
	    	{
	    		$lang = Language::where("language","en")->where("des_id",$value->de_remove)->first();
	    		$idAndPlace = array('id' => $lang->des_id, 'text' => $lang->de_name);
	    		array_push($linePlace,$idAndPlace);
	    	}
	    }
	    return $linePlace;
	}
	public function calroute($arr){
		$tmptotal = 0;
		$tmparr = array();
		// array_unshift($tmparr,$this->origin);
		if($this->dello == 0 ){
		//calculating from time 
			if($this->durordis ==1){
				for($i = 0; $i <count($arr); $i++){
					$value = DB::table('destination')
							->where('de_id',$arr[$i])
							->select('de_duration')
							->get();
					if($i!=0){
						$path = DB::table('path')
							 ->where('pa_de_start',$arr[$i-1])
							 ->where('pa_de_end',$arr[$i])
							 ->select('pa_duration')
							 ->get();
						$tmptotal += $path[0]->pa_duration;
					} 
					$tmptotal += $value[0]->de_duration;
				}
				if($this->isback){
					$path = DB::table('path')
							 ->where('pa_de_start',$arr[count($arr)-1])
							 ->where('pa_de_end',$arr[0])
							 ->select('pa_duration')
							 ->get();
					$tmptotal += $path[0]->pa_duration;
				}
				if(empty($this->routresult)){
					$this->routresult = $arr;
					$this->total = $tmptotal;
				} elseif ($tmptotal < $this->total) {
					$this->routresult = $arr;
					$this->total = $tmptotal;
				}
			} else {
				for($i = 0; $i <count($arr)-1; $i++){
					$path = DB::table('path')
							 ->where('pa_de_start',$arr[$i])
							 ->where('pa_de_end',$arr[$i+1])
							 ->select('pa_distance')
							 ->get();
					$tmptotal += $path[0]->pa_distance;
				}
				if(empty($this->routresult)){
					$this->routresult = $arr;
					$this->total = $tmptotal;
				} elseif ($tmptotal < $this->total) {
					$this->routresult = $arr;
					$this->total = $tmptotal;
				}
			}
		} else {
			if($this->durordis == 1){
				$i=0;
				while($i!=-1&&$i < count($arr)){
					array_push($tmparr,$arr[$i]);
					$value = DB::table('destination')
							->where('de_id',$arr[$i])
							->select('de_duration')
							->get();
					if($i!=0){
						$path = DB::table('path')
							 ->where('pa_de_start',$arr[$i-1])
							 ->where('pa_de_end',$arr[$i])
							 ->select('pa_duration')
							 ->get();
						$tmptotal += $path[0]->pa_duration;
					} 
					$tmptotal += $value[0]->de_duration;
					if($this->isback&&$i!=0){
						$path = DB::table('path')
							 ->where('pa_de_start',$arr[$i-1])
							 ->where('pa_de_end',$arr[$i])
							 ->select('pa_duration')
							 ->get();
						$tmptotal += $path[0]->pa_duration;
					}
					$i++;
					if($tmptotal<$this->choosendur){
						if(empty($this->routresult)){
							$this->routresult = $tmparr;
							$this->total = $tmptotal;
						} elseif (count($tmparr) >=count($this->routresult)) {
							if(count($tmparr) >count($this->routresult)){
                $this->routresult = $tmparr;
								$this->total = $tmptotal;
              }else if($tmptotal <= $this->total){
                 $this->routresult = $tmparr;
								$this->total = $tmptotal;
              }
            }
					} else {
						$i=-1;
					}				
				}
			}	
		}
	}
			
	public function arr_permute($items, $perms = array()) {// Array permutations
			if (empty($items)) { 
				$this->calroute($perms);
			} else {
				for ($i = 0; $i < count($items); $i++) {
					 $newitems = $items;
					 $newperms = $perms;
					 list($foo) = array_splice($newitems, $i, 1);
					 array_unshift($newperms, $foo);
					$this->arr_permute($newitems, $newperms);
				 }
			}
	}

	public function processroute(Request $req){
		$this->durordis = $req->durordis;
		$this->isback = $req->isback;
		$this->choosendur = $req->choosendur;
		$this->dello = $req->dello;
	
		// var_dump($this->choosendur == 'NaN');
		// return
		// $this->origin = $req->data[0];
		// $arr = $req->data;
		// unset($arr[0]);
		// $arr = $arr;

		//Check time alert 
		$tmptotal = 0;
		if($this->choosendur != 'NaN' && $this->dello == 0 ){	
			for($i = 0; $i <count($req->locatsList); $i++){
				$value = DB::table('destination')
						->where('de_id',$req->locatsList[$i])
						->select('de_duration')
						->get();
				if($i!=0){
					$path = DB::table('path')
						 ->where('pa_de_start',$req->locatsList[$i-1])
						 ->where('pa_de_end',$req->locatsList[$i])
						 ->select('pa_duration')
						 ->get();
					$tmptotal += $path[0]->pa_duration;
				} 
				$tmptotal += $value[0]->de_duration;
			}
			if($this->isback){
				$path = DB::table('path')
						 ->where('pa_de_start',$req->locatsList[count($req->locatsList)-1])
						 ->where('pa_de_end',$req->locatsList[0])
						 ->select('pa_duration')
						 ->get();
				$tmptotal += $path[0]->pa_duration;
			}
			if((int)$tmptotal > (int)$this->choosendur){
				return $tmptotal;
			}
		}
		

		$this->arr_permute($req->locatsList);
		return $this->routresult;
	}

	public function updpath(Request $req){
		$path = $req->data;
		foreach ($path as $value) {
			$isexist = DB::table('path')
					   ->where('pa_de_start',$value['pa_de_start'])
					   ->where('pa_de_end',$value['pa_de_end'])
					   ->doesntExist();
			if($isexist){
				DB::table('path')->insert($value);
			}	
		}
	}
	public function gettimeline(Request $req){
		$parsed = date_parse($req->time);
		$seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
		$timestart = $seconds;
		$id = array_values($req->data);
		$timeline = array();
		array_push($timeline,$timestart);
		for($i = 0; $i < count($id); $i++) {
			$dedur = 	DB::table('destination')
						->where('de_id',$id[$i])
						->select('de_duration')
						->get();
			$dedur = (int)$dedur[0]->de_duration;
			if($i < count($id)-1){
				$tradur = 	DB::table('path')
							->where('pa_de_start',$id[$i])
							->where('pa_de_end',$id[$i+1])
							->select('pa_duration')
							->get();
				$tradur = 	$tradur[0]->pa_duration;
				$tmp = $timeline[count($timeline)-1] + $dedur;
				array_push($timeline, $tmp);
				$tmp = $timeline[count($timeline)-1] + $tradur;
				array_push($timeline, $tmp);
			} else {
				$tmp = $timeline[count($timeline)-1] + $dedur;
				array_push($timeline, $tmp);
			}
		}
		return $timeline;
	}
}