<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Destination;
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
                $des = Destination::select('de_remove','de_lat','de_lng','de_link','de_duration')->where("de_remove",$value->des_id)->first();
                $value["de_id"] = $des->de_remove;
                $value["de_lat"] = $des->de_lat;
                $value["de_lng"] = $des->de_lng;
                $value["de_link"] = $des->de_link;
                $value["de_duration"] = $des->de_duration;
            }
        }
        else
        {
            $de = Language::where("language","en")->get();
            foreach ($de as $value) {
                $des = Destination::select('de_remove','de_lat','de_lng','de_link','de_duration')->where("de_remove",$value->des_id)->first();
                $value["de_id"] = $des->de_remove;
                $value["de_lat"] = $des->de_lat;
                $value["de_lng"] = $des->de_lng;
                $value["de_link"] = $des->de_link;
                $value["de_duration"] = $des->de_duration;
            }
        }
		$destination  = array();
		foreach ($de as $value) {
			$latlng = array('lat' => $value->de_lat, 'lng' => $value->de_lng);
			$des  = (object) array('place_id'=> $value ->de_id,'de_name' => $value->de_name,'location' =>$latlng,'de_duration'=>$value->de_duration,'de_link'=>$value->de_link,'de_description'=>$value->de_description);
			array_push($destination,$des);
		}
		return $destination;
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