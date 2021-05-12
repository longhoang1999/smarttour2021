<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(
	['middleware' => ['locale'] ],
	function(){
		Route::get("/","UserController@login")->name("login");
	}
);
//change language
Route::post("langVN","UserController@langVN")->name("user.langVN");
Route::post("langEN","UserController@langEN")->name("user.langEN");

Route::get("checkEmail/{id}/{key}","UserController@checkEmail")->name('checkEmail');
Route::post("checkForgot","UserController@checkForgot")->name('checkForgot');
Route::post("senkey","UserController@senkey")->name('senkey');
Route::post("checkkey","UserController@checkkey")->name('checkkey');
// Route::get("login","UserController@viewlogin")->name("viewlogin");
Route::post("postLogin","UserController@postLogin")->name("postLogin");
Route::post("register","UserController@register")->name("register");
Route::get("check","AdminController@check")->name("check");

//Auth::routes();
Route::group(
	['middleware' => ['locale'] ],
	function(){
		//gronp view
		Route::get("tour","UserController@tour")->name("tour");
		Route::get("place","UserController@place")->name("place");
		Route::get("about","UserController@about")->name("about");
		Route::get("feedback","UserController@viewfeedback")->name("feedback");
		Route::post("searchPlaceSmart","UserController@searchPlaceSmart")->name("searchPlaceSmart");
		Route::post("searchTourSmart","UserController@searchTourSmart")->name("searchTourSmart");
		Route::get("showDetailPlace/{idplace}","UserController@showDetailPlace")->name("showDetailPlace");
		Route::get("listPlaceForType/{idtype}","UserController@listPlaceForType")->name("listPlaceForType");
		Route::post("loadPlaceInfo","UserController@loadPlaceInfo")->name("loadPlaceInfo");
		Route::get("viewShareFeedback","UserController@viewShareFeedback")->name("viewShareFeedback");
		

		Route::get('maps/showmap','MapDirectController@showmap')->name('showmap');
		Route::get('maps/processroute','MapDirectController@processroute')->name('processroute');
		Route::get('maps/updpath','MapDirectController@updpath')->name('updpath');
		Route::get('maps.gettimeline','MapDirectController@gettimeline')->name('gettimeline');
		Route::get('maps',function(){
		    return view('recommend_tour');
		})->name("user.maps");
		Route::get("viewtour/{id}","ShareTourController@viewtour")->name('viewtour');
		Route::post("takeInforPlace","ShareTourController@takeInforPlace")->name('takeInforPlace');
		//Route::get("loadmore/{type}","ShareTourController@loadmore")->name('share.loadmore');
		Route::get("viewSharetour/{routeid}/{shareId}","ShareTourController@viewSharetour")->name('share.viewSharetour');
		Route::get("editTour/{id}","AdminController@editTour")->name('admin.editTour');
		Route::get("searchTour","ShareTourController@searchTour")->name('searchTour');
		Route::get("searchTourTable","ShareTourController@searchTourTable")->name('share.searchTourTable');
		Route::get("searchTourName/{idShareTour}","ShareTourController@searchTourName")->name('share.searchTourName');
		Route::get("searchTourYouShared","ShareTourController@searchTourYouShared")->name('share.searchTourYouShared');
		Route::get("searchMostVotes","ShareTourController@searchMostVotes")->name('share.searchMostVotes');
		Route::get("searchThisMonth","ShareTourController@searchThisMonth")->name('share.searchThisMonth');
		Route::get("searchForHighTotal","ShareTourController@searchForHighTotal")->name('share.searchForHighTotal');
		Route::get("searchMaxTotal","ShareTourController@searchMaxTotal")->name('share.searchMaxTotal');
		Route::get("searchMinTotal","ShareTourController@searchMinTotal")->name('share.searchMinTotal');
		Route::get("searchLastMonth","ShareTourController@searchLastMonth")->name('share.searchLastMonth');
		Route::get("searchAnyMonth/{date}","ShareTourController@searchAnyMonth")->name('share.searchAnyMonth');
		Route::get("takeDetailRoute","ShareTourController@takeDetailRoute")->name('share.takeDetailRoute');
		Route::post("selectPlaceForType","ShareTourController@selectPlaceForType")->name('share.selectPlaceForType');
		Route::post("selectTourForPlace","ShareTourController@selectTourForPlace")->name('share.selectTourForPlace');
		Route::post("selectTourForCost","ShareTourController@selectTourForCost")->name('share.selectTourForCost');
		Route::get("searchListPlace/{array}","ShareTourController@searchListPlace")->name('share.searchListPlace');
	}
);
Route::get('/home', 'HomeController@index')->name('home');
Route::get('logout','UserController@logout')->name('logout');
Route::group(
	['middleware' => ['userLogin','locale'] ],
	function(){
		//user
		Route::get("dashboard","UserController@dashboard")->name("user.dashboard");
		Route::post("feedback","UserController@feedback")->name("user.feedback");
		Route::get("saveTour","UserController@saveTour")->name("user.saveTour");
		Route::post("saveImgShareTour/{idShareTour}","UserController@saveImgShareTour")->name("user.saveImgShareTour");
		Route::post("checkTour","UserController@checkTour")->name("user.checkTour");
		Route::post("checkUser","UserController@checkUser")->name("user.checkUser");
		Route::post("editInfo","UserController@editInfo")->name("user.editInfo");
		Route::get("editTourUser/{id}","AdminController@editTour")->name('user.editTour');
		Route::post("shareTour","UserController@shareTour")->name("user.shareTour");
		Route::post("rating","ShareTourController@rating")->name("user.rating");
		Route::post("getinfor-touredit","ShareTourController@getinforTouredit")->name("user.getinfor-touredit");
		Route::post("editRoute/{id}","AdminController@editRoute")->name("user.editRoute");
		Route::get("tourhistory","ShareTourController@tourhistory")->name("user.tourhistory");
		Route::get("showtourhistory","ShareTourController@showtourhistory")->name('share.showtourhistory');
		Route::post("takeDetailTour","ShareTourController@takeDetailTour")->name('share.takeDetailTour');
		Route::post("voteUser","ShareTourController@voteUser")->name('share.voteUser');
		// check tour duplicate
		Route::post("duplicate","UserController@duplicate")->name("user.duplicate");
	}
);
Route::group(
	['middleware' => ['userLogin','checkAdmin','locale'] ],
	function(){
		//admin
		Route::get("dashboardAdmin","AdminController@dashboard")->name("admin.dashboard");
		Route::get("showAllAccount","AdminController@showAllAccount")->name("admin.showAllAccount");
		Route::get("feedbackAdmin","AdminController@feedback")->name("admin.feedback");
		Route::get("showAllFeedback","AdminController@showAllFeedback")->name("admin.showAllFeedback");
		Route::post("detaiFeedback/{id}","AdminController@detaiFeedback")->name("admin.detaiFeedback");
		//place
		Route::get("addPlace","AdminController@addPlace")->name("admin.addPlace");
		Route::get("showDestination","AdminController@showDestination")->name("admin.showDestination");
		Route::get("showDestinationVN","AdminController@showDestinationVN")->name("admin.showDestinationVN");
		Route::get("showDestinationType/{type}/{lang}","AdminController@showDestinationType")->name("admin.showDestinationType");
		Route::post("postaddPlace","AdminController@postaddPlace")->name("admin.postaddPlace");
		Route::post("checkPlace","AdminController@checkPlace");
		//edit place
		Route::get("editPlace","AdminController@editPlace")->name("admin.editPlace");
		Route::get("showDestinationEditVN","AdminController@showDestinationEditVN")->name("admin.showDestinationEditVN");
		//remove place
		Route::get("removePlace","AdminController@removePlace")->name("admin.removePlace");
		Route::get("showDestinationRemove","AdminController@showDestinationRemove")->name("admin.showDestinationRemove");

		Route::get("showDestinationRemoveType/{type}/{lang}","AdminController@showDestinationRemoveType")->name("admin.showDestinationRemoveType");

		Route::get("showDestinationRemoveVN","AdminController@showDestinationRemoveVN")->name("admin.showDestinationRemoveVN");
		Route::get("showDetail/{remove}/{lang}","AdminController@showDetail")->name("admin.showDetail");
		Route::get("placeDelete/{remove}","AdminController@placeDelete")->name("admin.placeDelete");
		Route::get("showDestinationEdit","AdminController@showDestinationEdit")->name("admin.showDestinationEdit");

		Route::get("showDestinationEditType/{type}/{lang}","AdminController@showDestinationEditType")->name("admin.showDestinationEditType");

		Route::get("showDetailEdit/{remove}/{lang}","AdminController@showDetailEdit")->name("admin.showDetailEdit");
		Route::post("formEditPlace/{remove}/{lang}","AdminController@formEditPlace")->name("admin.formEditPlace");
		//dash board
		Route::get("generalInfor","AdminController@generalInfor")->name('admin.generalInfor');
		Route::post("getLatLng","AdminController@getLatLng")->name("admin.getLatLng");
		Route::get("updatePath","AdminController@updatePath")->name("admin.updatePath");
		//lock user
		Route::get("deleteAcc/{id}","AdminController@deleteAcc")->name("admin.deleteAcc");
		Route::get("unlockAcc/{id}","AdminController@unlockAcc")->name("admin.unlockAcc");
		Route::post("checkUserAdmin","AdminController@checkUserAdmin")->name("admin.checkUserAdmin");
		Route::post("addaccount","AdminController@addaccount")->name("admin.addaccount");
		//language
		Route::post("changeLanguage","AdminController@changeLanguage")->name('admin.changeLanguage');
		Route::get("history","AdminController@history")->name("admin.history");
		Route::get("showAllRoute","AdminController@showAllRoute")->name("admin.showAllRoute");
		Route::get("showAllRouteRating","AdminController@showAllRouteRating")->name("admin.showAllRouteRating");
		
		Route::post("routeDetail","AdminController@routeDetail")->name("admin.routeDetail");
		Route::post("routeDetail2","AdminController@routeDetail2")->name("admin.routeDetail2");

		Route::post("getEmail","AdminController@getEmail")->name("admin.getEmail");
		Route::post("sendFeedback","AdminController@sendFeedback")->name("admin.sendFeedback");
		Route::get("sharetourDelete/{id}","AdminController@sharetourDelete")->name("admin.sharetourDelete");
		Route::get("typePlace","AdminController@typePlace")->name("admin.typePlace");
		Route::get("showtypeplace","AdminController@showtypeplace")->name("admin.showtypeplace");
		Route::get("showtypeplaceVn","AdminController@showtypeplaceVn")->name("admin.showtypeplaceVn");
		Route::post("routeShowType","AdminController@routeShowType")->name("admin.routeShowType");
		Route::post("fixNameType","AdminController@fixNameType")->name("admin.fixNameType");
		Route::post("addtypeplace","AdminController@addtypeplace")->name("admin.addtypeplace");
		Route::post("deleteTypePlace","AdminController@deleteTypePlace")->name("admin.deleteTypePlace");
		Route::post("sharefeedback","AdminController@sharefeedback")->name("admin.sharefeedback");
		
	}
);

