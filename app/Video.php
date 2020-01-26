<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\VideoCategory;

class Video extends Model
{
    protected $table = 'video_courses';

    public static function getAccessibleVideos($type='index', $category=false)
    {
        if($type == "index"){
            $buildResult = [];
            $getCategories = VideoCategory::orderBy('category_title', 'asc')->get();
            foreach($getCategories as &$thisCategory){
                $getCatVideos = self::where('category', $thisCategory->id)
                    ->where(function($whereQuery){
                        $whereQuery->where('uploaded_by', Auth::user()->id)
                            ->orWhere(function($query){
                                $query->where(function($inQuery){
                                    $inQuery
                                        ->where('visible_groups', 'like', '%"group:'.Auth::user()->group_id.'"%')
                                        ->orWhere('visible_groups', 'like', '%"role:'.Auth::user()->role_id.'"%');
                                })
                                ->where(function($inQuery){
                                    $inQuery
                                        ->where('visible_users', 'like', '%"'.Auth::user()->id.'"%')
                                        ->orWhere('visible_users', '=', 'any');
                                });
                            })->orWhere(function($query){
                                $query
                                    ->where('visible_users', 'like', '%"'.Auth::user()->id.'"%')
                                    ->where('visible_groups', '=', 'any');
                            })->orWhere(function($query){
                                $query
                                    ->where('visible_users', '=', 'any')
                                    ->where('visible_groups', '=', 'any');
                            });
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
                if($getCatVideos->count()>0){
                    $buildResult[$thisCategory->id] = [
                        'title'     => $thisCategory->category_title,
                        'videos'    => $getCatVideos
                    ];
                }else{
                    unset($thisCategory);
                }
            }
            return $buildResult;
        }elseif($type == "detailed"){
            return self::where('category', $category)
                ->where(function($whereQuery){
                    $whereQuery->where('uploaded_by', Auth::user()->id)
                        ->orWhere(function($query){
                            $query->where(function($inQuery){
                                $inQuery
                                    ->where('visible_groups', 'like', '%"group:'.Auth::user()->group_id.'"%')
                                    ->orWhere('visible_groups', 'like', '%"role:'.Auth::user()->role_id.'"%');
                            })
                            ->where(function($inQuery){
                                $inQuery
                                    ->where('visible_users', 'like', '%"'.Auth::user()->id.'"%')
                                    ->orWhere('visible_users', '=', 'any');
                            });
                        })->orWhere(function($query){
                            $query
                                ->where('visible_users', 'like', '%"'.Auth::user()->id.'"%')
                                ->where('visible_groups', '=', 'any');
                        })->orWhere(function($query){
                            $query
                                ->where('visible_users', '=', 'any')
                                ->where('visible_groups', '=', 'any');
                        });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(9);
        }
    }
}
