<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CallscriptsNoticements extends Model
{

    protected $table = 'cs_noticements';

    public static function buildHierarchy($visiblity='any')
    {
        if($visiblity == "all"){
            $getNoticements= self::orderBy('parent_id', 'asc')->get();
        }else{
        	$getNoticements = self::where('visiblity', $visiblity)
        		->orWhere('visiblity', 'like', $visiblity.',%')
        		->orWhere('visiblity', 'like', '%,'.$visiblity)
        		->orWhere('visiblity', 'like', '%,'.$visiblity.',%')
                ->orWhere('visiblity', 'any')
                ->orWhere('visiblity', 'like', 'any,%')
                ->orWhere('visiblity', 'like', '%,any')
                ->orWhere('visiblity', 'like', '%,any,%')
        		->orderBy('parent_id', 'asc')
        		->get();
        }
    	if($getNoticements->count()>0){
    		$return = [];
    		foreach($getNoticements as $currentNoticement){
    			$return[($currentNoticement->parent_id == -1 ? 0 : $currentNoticement->parent_id)][] = [
    				'id'		=> $currentNoticement->id,
    				'parent_id'	=> ($currentNoticement->parent_id == -1 ? 0 : $currentNoticement->parent_id),
    				'title'		=> $currentNoticement->title, 
    				'title'     => str_replace('"', '{quot}', $currentNoticement->title), 
                    'text'      => str_replace('"', '{quot}', $currentNoticement->noticement_text),
                    'visiblity' => $currentNoticement->visiblity
    			];
    		}
    		return $return;
    	}else{
    		return [];
    	}
    }

    public static function getUniqueCategories()
    {
        $getUnique = self::whereNull('noticement_text')->get();
        if($getUnique->count()>0){
            $return = [];
            foreach($getUnique as $thisUnique){
                $return[$thisUnique->id] = $thisUnique->title;
            }
            return $return;
        }else{
            return [];
        }
    }

}
