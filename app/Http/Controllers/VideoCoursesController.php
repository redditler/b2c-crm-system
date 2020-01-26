<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Video;
use App\VideoCategory;
use App\VideoView;
use App\User;
use App\UserGroups;
use App\UserRoles;
use DB;

class VideoCoursesController extends Controller
{

	public static $uploadsPath = '/mnt/media';

	public static function parseDate($date)
    {
        if($date === null){
            return '';
        }
        if($date->isToday()){
            return 'сегодня, '.date('G:i:s', $date->timestamp);
        }elseif($date->isYesterday()){
            return 'накануне, '.date('G:i:s', $date->timestamp);
        }else{
            return date('d.m.Y, H:i:s', $date->timestamp);
        }
    }

    public function manage(Request $request)
    {
    	if(!empty($request->manage_id)){
    		$checkVideo = Video::where('id', $request->manage_id)->get();
    		if($checkVideo->count()>0){
	    		if($request->remove_flag == "true"){
					unlink(self::$uploadsPath.'/'.$checkVideo->first()->video_file);
					Video::where('id', $request->manage_id)->delete();
					$return['state'] = 'ok';
    			}else{
					if(empty($request->video_title)){
						$returnFails[] = 'Не указан заголовок загружаемого видео';
					}
					if(empty($request->video_description)){
						$returnFails[] = 'Не указано описание загружаемого видео';
					}
					if(!isset($returnFails)){
						if(empty($request->video_groups)){
							$groupAccess = 'any';
						}else{
							if(count($request->video_groups)>0){
								foreach($request->video_groups as $thisGroup){
									if($thisGroup == "false"){
										$groupAccess = 'any';
										break;
									}
									$groupAccess[] = $thisGroup;
								}
							}
						}
						if(empty($request->video_users)){
							$usersAccess = 'any';
						}else{
							if(count($request->video_users)>0){
								foreach($request->video_users as $thisUser){
									if($thisUser == "false"){
										$usersAccess = 'any';
										break;
									}
									$usersAccess[] = $thisUser;
								}
							}
						}
						if($groupAccess != "any"){
							$groupAccess = json_encode($groupAccess);
						}
						if($usersAccess != "any"){
							$usersAccess = json_encode($usersAccess);
						}
						Video::where('id', $request->manage_id)->update(
							[
								'video_title'		=> $request->video_title,
								'video_description'	=> $request->video_description,
								'category'			=> $request->video_category,
								'updated_at'		=> date("Y-m-d H:i:s", time()),
								'visible_groups'	=> $groupAccess,
								'visible_users'		=> $usersAccess
							]
						);
						$return['state'] = 'ok';
					}else{
						$return = [
							'state'		=> 'fail',
							'reason'	=> $returnFails
						];
					}
    			}
    		}
    	}
    	if(!isset($return)){
	    	$return['state'] = 'fail';
	    }
    	return response()->json($return);
    }
    
	public function index()
	{
		$systemUsers = User::select(['id', 'name', 'title'])
			->where('fired', 1)
			->where('id', '!=', Auth::user()->id)
			->orderBy('name', 'asc')
			->get();
		$userGroups = UserGroups::where('id', '!=', 3)->get();
		$userRoles = UserRoles::where('id', '>', 0)->get();
		foreach($systemUsers as &$currentUser){
			$parseName = explode(' ', $currentUser->name);
			$currentUser->name = $parseName[0].' '.mb_substr($parseName[1], 0, 1).'. '.mb_substr($parseName[2], 0, 1).'.'.(strlen($currentUser->title)>0 ? ' ('.$currentUser->title.')' : '');
		}
		$getVideos = Video::getAccessibleVideos();
		foreach($getVideos as &$thisCategory){
			foreach($thisCategory['videos'] as &$thisVideo){
				$thisVideo->url = asset('media/'.$thisVideo->video_file);
				$thisVideo->created_at_string = self::parseDate($thisVideo->created_at);
				$getUploader = User::where('id', $thisVideo->uploaded_by)->get();
				if($getUploader->count()>0){
					$thisVideo->uploader = $getUploader->first()->name;
				}else{
					$thisVideo->uploader = 'Пользователь не найден';
				}
			}
		}
		return view('videoCoursesMain')
			->with('availableUsers', 	$systemUsers)
			->with('userGroups', 		$userGroups)
			->with('userRoles',			$userRoles)
			->with('videos', 			$getVideos)
			->with('categories', 		VideoCategory::select(['id', 'category_title'])->orderBy('category_title', 'asc')->get());
	}

	public function detailed($category_id)
	{
		$systemUsers = User::select(['id', 'name', 'title'])
			->where('fired', 1)
			->where('id', '!=', Auth::user()->id)
			->orderBy('name', 'asc')
			->get();
		$userGroups = UserGroups::where('id', '!=', 3)->get();
		$userRoles = UserRoles::where('id', '>', 0)->get();
		foreach($systemUsers as &$currentUser){
			$parseName = explode(' ', $currentUser->name);
			$currentUser->name = $parseName[0].' '.mb_substr($parseName[1], 0, 1).'. '.mb_substr($parseName[2], 0, 1).'.'.
				(strlen($currentUser->title)>0 ? ' ('.$currentUser->title.')' : '');
		}
		$getVideos = Video::getAccessibleVideos('detailed', $category_id);
		foreach($getVideos as &$thisVideo){
			$thisVideo->url = asset('media/'.$thisVideo->video_file);
			$thisVideo->created_at_string = self::parseDate($thisVideo->created_at);
			$getUploader = User::where('id', $thisVideo->uploaded_by)->get();
			if($getUploader->count()>0){
				$thisVideo->uploader = $getUploader->first()->name;
			}else{
				$thisVideo->uploader = 'Пользователь не найден';
			}
		}
		$getCategory = VideoCategory::where('id', $category_id)->get();
		return view('videoCoursesDetailed')
			->with('availableUsers', 	$systemUsers)
			->with('userGroups', 		$userGroups)
			->with('userRoles',			$userRoles)
			->with('videos', 			$getVideos)
			->with('category', 			$getCategory->first())
			->with('categories', 		VideoCategory::select(['id', 'category_title'])->orderBy('category_title', 'asc')->get());
	}

	public function proxy($video_id)
	{
		/*
			Этот метод не будет использоваться после внедрения статичной отдачи, но остается для обратной совместимости
		*/
		if(file_exists(self::$uploadsPath.'/'.$video_id)){
			header('Content-Type: video/mp4');
			header("Content-Length: ".filesize(self::$uploadsPath.'/'.$video_id));
			readfile(self::$uploadsPath.'/'.$video_id);
		}else{
			abort(404);
		}
		exit();
	}

	public function getViews($video_id=false)
	{
		$cachedTitles = [];
		$getViewedVideos = VideoView::orderBy('viewed_at', 'desc');
		if($video_id){
			$getViewedVideos = $getViewedVideos->where('video_id', $video_id);
		}
		$getViewedVideos = $getViewedVideos->paginate(100);
		if($getViewedVideos->count()>0){
			foreach($getViewedVideos as &$thisViewedVideo){
				if(isset($cachedTitles[$thisViewedVideo->video_id])){
					$thisViewedVideo->title = $cachedTitles[$thisViewedVideo->video_id];
				}else{
					$getVideoTitle = Video::select('video_title')->where('id', $thisViewedVideo->video_id)->get();
					if($getVideoTitle->count()>0){
						$thisViewedVideo->title = $getVideoTitle->first()->video_title;
					}else{
						$thisViewedVideo->title = 'Видео не существует';
					}
					$cachedTitles[$thisViewedVideo->video_id] = $thisViewedVideo->title;
				}
				$getUser = User::select('name')->where('id', $thisViewedVideo->user_id)->get();
				if($getUser->count()>0){
					$thisViewedVideo->operateur = $getUser->first()->name;
				}else{
					$thisViewedVideo->operateur = 'Неведомый гость';
				}
				$thisViewedVideo->viewed_at = date('d.m.Y, H:i:s', $thisViewedVideo->viewed_at);
			}
		}
		return view('videoCoursesViews')
			->with('viewedVideos', $getViewedVideos)
			->with('categories', 		VideoCategory::select(['id', 'category_title'])->orderBy('category_title', 'asc')->get());
	}

	public function setViewed($video_id)
	{
		$checkVideo = Video::where('id', $video_id)->get();
		if($checkVideo->count()>0){
			$return['state'] = 'ok';
			Video::where('id', $video_id)
				->update(
					[
						'views' => $checkVideo->first()->views+1
					]
				);
			VideoView::insert(
				[
					'video_id'	=> $video_id,
					'user_id'	=> Auth::user()->id,
					'viewed_at'	=> time()
				]
			);
		}else{
			$return['state'] = 'fail';
		}
		return response()->json($return);
	}

	public function categoriesIndex(Request $request)
	{
		$getCategories = VideoCategory::orderBy('category_title', 'asc')->get();
		foreach($getCategories as &$thisCategory){
			$thisCategory->videos_count = Video::select(DB::raw('COUNT(0) as `count`'))->where('category', $thisCategory->id)->get()->first()->count;
			$videoViews = Video::select(DB::raw('SUM(`views`) as `views_summary`'))->where('category', $thisCategory->id)->get()->first()->views_summary;
			$thisCategory->video_views = ($videoViews ? $videoViews : 0);
		}
		return view('videoCoursesCategories')
			->with('categories', $getCategories);
	}

	public function categoriesManage(Request $request)
	{
		if($request->type == "add"){
			VideoCategory::insert(
				[
					'created_at'		=> date("Y-m-d H:i:s", time()),
					'updated_at'		=> date("Y-m-d H:i:s", time()),
					'category_title'	=> $request->title
				]
			);
		}elseif($request->type == "edit"){
			if($request->remove == "true"){
				if($request->replacement_category == "false"){
					$getVideos = Video::where('category', $request->occasion_id)->get();
					if($getVideos->count()>0){
						foreach($getVideos as $thisVideo){
							unlink(self::$uploadsPath.'/'.$thisVideo->video_file);
							Video::where('id', $thisVideo->id)->delete();
						}
					}
				}else{
					$getCatVideos = Video::where('category', $request->occasion_id)->get();
					if($getCatVideos->count()>0){
						foreach($getCatVideos as $thisCatVideo){
							Video::where('id', $thisCatVideo->id)
								->update(
									[
										'category' => ($request->replacement_category != $request->occasion_id ? $request->replacement_category : -1)
									]
								);
						}
					}
				}
				VideoCategory::where('id', $request->occasion_id)->delete();
			}else{
				VideoCategory::where('id', $request->occasion_id)
					->update(
						[
							'updated_at'		=> date("Y-m-d H:i:s", time()),
							'category_title'	=> $request->title
						]
					);
			}
		}else{
			abort(404);
		}
		return redirect()->route('videocourses.categoriesManage');
	}

	public function upload(Request $request)
	{

		$systemUsers = User::select(['id', 'name'])
			->where('fired', 1)
			->where('id', '!=', Auth::user()->id)
			->orderBy('name', 'asc')
			->get();
		$userGroups = UserGroups::where('id', '!=', 3)->get();
		$userRoles = UserRoles::where('id', '>', 0)->get();
		foreach($systemUsers as &$currentUser){
			$parseName = explode(' ', $currentUser->name);
			$currentUser->name = $parseName[0].' '.mb_substr($parseName[1], 0, 1).'. '.mb_substr($parseName[2], 0, 1).'.'.(strlen($currentUser->title)>0 ? ' ('.$currentUser->title.')' : '');
		}

		$getVideos = Video::getAccessibleVideos();
		foreach($getVideos as &$thisCategory){
			foreach($thisCategory['videos'] as &$thisVideo){
				$thisVideo->url = asset('media/'.$thisVideo->video_file);
				$thisVideo->created_at_string = self::parseDate($thisVideo->created_at);
				$getUploader = User::where('id', $thisVideo->uploaded_by)->get();
				if($getUploader->count()>0){
					$thisVideo->uploader = $getUploader->first()->name;
				}else{
					$thisVideo->uploader = 'Пользователь не найден';
				}
			}
		}

		/*
			Проверка на заполненность формы
		*/
		if(empty($request->video_file)){
			$returnFails[] = 'Не выбрано видео для загрузки';
		}
		if(empty($request->video_title)){
			$returnFails[] = 'Не указан заголовок загружаемого видео';
		}
		if(empty($request->video_description)){
			$returnFails[] = 'Не указано описание загружаемого видео';
		}
		if(isset($returnFails)){
			return view('videoCoursesMain')
				->with('returnFails', 		json_encode($returnFails))
				->with('availableUsers', 	$systemUsers)
				->with('userGroups', 		$userGroups)
				->with('userRoles',			$userRoles)
				->with('videos', 			$getVideos)
				->with('categories', 		VideoCategory::select(['id', 'category_title'])->orderBy('category_title', 'asc')->get());
		}

		/*
			Определение прав доступа
		*/
		if(empty($request->video_groups)){
			$groupAccess = 'any';
		}else{
			if(count($request->video_groups)>0){
				foreach($request->video_groups as $thisGroup){
					if($thisGroup == "false"){
						$groupAccess = 'any';
						break;
					}
					$groupAccess[] = $thisGroup;
				}
			}
		}
		if(empty($request->video_users)){
			$usersAccess = 'any';
		}else{
			if(count($request->video_users)>0){
				foreach($request->video_users as $thisUser){
					if($thisUser == "false"){
						$usersAccess = 'any';
						break;
					}
					$usersAccess[] = $thisUser;
				}
			}
		}
		if($groupAccess != "any"){
			$groupAccess = json_encode($groupAccess);
		}
		if($usersAccess != "any"){
			$usersAccess = json_encode($usersAccess);
		}

		/*
			Проверка на тип загруженного файла
		*/
		$uploadedMime = $request->video_file->getClientMimeType();
		if($uploadedMime != "video/mp4"){
			$returnFails[] = 'Загружаемое видео имеет неподдерживаемый формат';
		}
		if(isset($returnFails)){
			return view('videoCoursesMain')
				->with('returnFails', 		json_encode($returnFails))
				->with('availableUsers', 	$systemUsers)
				->with('userGroups', 		$userGroups)
				->with('userRoles',			$userRoles)
				->with('videos', 			$getVideos);
		}

		$videoFile = md5(time().$request->video_file->getClientOriginalName()).'.mp4';
		$uploadedFile = $request->file('video_file');
		Storage::disk('crm-videos')->putFileAs('', $request->video_file, $videoFile);

		Video::insert(
			[
				'video_title'		=> $request->video_title,
				'video_file'		=> $videoFile,
				'uploaded_by'		=> Auth::user()->id,
				'created_at'		=> date("Y-m-d H:i:s", time()),
				'updated_at'		=> date("Y-m-d H:i:s", time()),
				'video_description'	=> $request->video_description,
				'category'			=> $request->video_category,
				'visible_groups'	=> $groupAccess,
				'visible_users'		=> $usersAccess
			]
		);

		return redirect()->route('videocourses.index');

	}

}
