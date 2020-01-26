<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CallscriptsQuestions as Questions;

class CallscriptsTopics extends Model
{
    protected $table = 'cs_topics';

    public static function getAssociated($topic=-1)
    {
    	if($topic == -1){
    		$getTopics = self::get();
    	}else{
    		$getTopics = self::where('id', '=', $topic)->get();
    	}
    	if($getTopics->count()>0){
    		$topicsData = $getTopics->toArray();
    		foreach($topicsData as $thisTopicIdx=>$currentTopic){
	    		$currentTopicQuestions = Questions::where('topic', '=', $currentTopic['id'])->get();
	    		if($currentTopicQuestions->count()>0){
	    			$topicsData[$thisTopicIdx]['questions'] = $currentTopicQuestions->toArray();
	    		}else{
	    			$topicsData[$thisTopicIdx]['questions'] = [];
	    		}
	    	}
    		return $topicsData;
    	}else{
    		return [];
    	}
    }
}
