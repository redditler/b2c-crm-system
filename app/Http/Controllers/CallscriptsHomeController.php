<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\CallscriptsCallLog as CallLog;
use App\CallscriptsQuestions as Questions;
use App\CallscriptsTopics as Topics;
use App\CallscriptsImprovements as Improve;
use App\CallscriptsNoticements as Noticements;

class CallscriptsHomeController extends Controller
{

	public function getQuestion(Request $request)
	{
		/*
			Здесь же происходит сохранение ответа на прошлый вопрос
		*/
		$getNextQuestion = Questions::where('topic', '=', $request->input('topic'));
		if($request->has('next_id')){
			$getNextQuestion = $getNextQuestion->where('id', $request->input('next_id'));
			# Сохранение ответа на поставленный вопрос
			$updateQuery = CallLog::where('call_id', $request->call_id)
				->where('question_id', $request->parent_id)
				->update(
					[
						'variant_id'	=> $request->answered,
						'updated_at'	=> date("Y-m-d H:i:s", time())
					]
				);
		}else{
            $beginTopic = true;
			$getNextQuestion = $getNextQuestion->where('parent_id', '=', $request->input('parent_id'));
		}
		$returnQuestion = [];
		if($getNextQuestion->count()>0){
			# Запись вопроса в рамках текущего диалога
			$insertedQuestion = CallLog::insertGetId(
				[
					'call_id'		=> $request->call_id,
					'operateur_id'	=> Auth::user()->id,
					'question_id'	=> $getNextQuestion->first()->id,
					'variant_id'	=> 0,
					'created_at'	=> date("Y-m-d H:i:s", time())
				]
			);
			$returnQuestion = [
				'has_response' 	=> true,
				'id'			=> $getNextQuestion->first()->id,
				'question_text'	=> $getNextQuestion->first()->question_text,
                'instructions'  => $getNextQuestion->first()->instructions,
				'question_title'=> $getNextQuestion->first()->question_title,
				'variants'		=> json_decode($getNextQuestion->first()->variants),
				'occurence_id'	=> $insertedQuestion,
				'request'		=> $request->toArray()
			];
            if(isset($beginTopic)){
                $getQuickQuestions = Questions::where('topic', $request->input('topic'))->where('parent_id', -2)->where('type', 3)->get();
                if($getQuickQuestions->count()>0){
                    foreach($getQuickQuestions as $thisQQ){
                        $quickQuestions[] = [
                            'id'        => $thisQQ->id,
                            'title'     => $thisQQ->question_title
                        ];
                    }
                }
                if(isset($quickQuestions)){
                    $returnQuestion['quick'] = $quickQuestions;
                }
            }
		}else{
			/*
				В базу тоже нужно записать, что это тупиковая ветка, т.к. позже мы будем расследовать причины сего
			*/
			$insertedEndpoint = CallLog::insertGetId(
				[
					'call_id'		=> $request->call_id,
					'operateur_id'	=> Auth::user()->id,
					'question_id'	=> 0,
					'variant_id'	=> 0,
					'created_at'	=> date("Y-m-d H:i:s", time())
				]
			);
			$returnQuestion = [
				'has_response'	=> false,
				'occurence_id'	=> $insertedEndpoint,
				'request'		=> $request->toArray()
			];
		}
		return response()->json($returnQuestion);
	}

    public function getNoticements(Request $request)
    {
        $getNoticements = Noticements::buildHierarchy(($request->has('topic') ? $request->topic : 'any'));
        return response()->json($getNoticements);
    }

    public function begin()
    {
        if(Auth::user()->role_id == 1){
        	$getCallTopics = Topics::get();
            $improvementsQ = Improve::count();
            $logLength = CallLog::count();
        }else{
            $getCallTopics = Topics::where('is_publicated', 1)->get();
            $improvementsQ = 0;
            $logLength = 0;
        }
    	return view('callscriptsHome')
    		->with('topics', $getCallTopics)
            ->with('improvementsQ', $improvementsQ)
            ->with('logLength', $logLength);
    }

    public function removeAnswer(Request $request)
    {
    	if(($request->occurence_id>0) and ($request->call_id>0)){
    		CallLog::where('call_id', $request->call_id)
    			->where('id', $request->occurence_id)
    			->update(
    				[
    					'is_removed' => 1
    				]
    			);
    		$return['state'] = 'ok';
    	}else{
    		$return['state'] = 'fail';
    	}
    	return response()->json($return);
    }

    public function describeFailure(Request $request)
    {
    	if(($request->occurence_id>0) and ($request->call_id>0)){
    		if(strlen($request->description)>0){
    			CallLog::where('call_id', $request->call_id)
    			->where('id', $request->occurence_id)
    			->update(
    				[
    					'fail_description' => $request->description
    				]
    			);
    			$return['state'] = 'ok';
    		}else{
    			$return['state'] = 'fail';
    		}
    	}else{
    		$return['state'] = 'fail';
    	}
    	return response()->json($return);
    }

    public function improveQuestion (Request $request)
    {
    	if(($request->question_id>0) and (strlen($request->question_text)>0)){
    		# Проверка на существование вопроса
    		$checkQuestion = Questions::where('id', $request->question_id)->get();
    		if($checkQuestion->count()>0){
    			if($checkQuestion->first()->question_text != $request->question_text){
    				Improve::insert(
    					[
    						'operateur_id'	=> Auth::user()->id,
    						'question_id'	=> $request->question_id,
    						'improved_text'	=> $request->question_text,
    						'created_at'	=> date("Y-m-d H:i:s", time())
    					]
    				);
    				$return['state'] = 'ok';
    			}else{
    				# Текст вопроса не изменился
    				$return = [
    					'state'			=> 'fail', 
    					'description'	=> 'Текст не был изменен в сравнении с существующим'
    				];
    			}
    		}else{
    			$return = [
					'state'			=> 'fail', 
					'description'	=> 'Вопрос, предлагаемый к улучшению, не существует'
				];
    		}
    	}else{
    		$return = [
				'state'			=> 'fail', 
				'description'	=> 'Заполнены не все обязательные поля'
			];
    	}
    	return response()->json($return);
    }

}
