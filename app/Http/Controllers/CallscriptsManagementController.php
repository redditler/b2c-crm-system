<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\CallscriptsTopics as Topics;
use App\CallscriptsQuestions as Questions;
use App\CallscriptsCallLog as CallLog;
use App\CallscriptsNoticements as Noticements;
use App\CallscriptsImprovements as Improvements;
use App\User as Users;

class CallscriptsManagementController extends Controller
{

    public static function parseDate($date)
    {
        if($date === null){
            return '';
        }
        if($date->isToday()){
            return 'Сегодня, '.date('G:i:s', $date->timestamp);
        }elseif($date->isYesterday()){
            return 'Накануне, '.date('G:i:s', $date->timestamp);
        }else{
            return date('d.m.Y, H:i:s', $date->timestamp);
        }
    }

    public function default($question=false)
    {
        $getQuestions = Topics::getAssociated();
        return view('callscriptsManagement')
            ->with('questionsList', $getQuestions)
            ->with('directedQuestion', $question);
    }

    public function updateQuestion(Request $request)
    {
        if($request->has('remove')){
            Questions::where('id', $request->id)->delete();
            return response()->json(['success' =>  true]);
        }else{
            $variants = [];
            foreach($request->variant_ids as $currentVarIdx=>$currentID){
                $variants[] = [
                    'id'    => $currentID,
                    'type'  => $request->input('variant_types.'.$currentVarIdx),
                    'link'  => $request->input('variant_links.'.$currentVarIdx),
                    'title' => $request->input('variant_titles.'.$currentVarIdx)
                ];
            }
            Questions::where('id', $request->id)
                ->update(
                    [
                        'question_title'=> $request->question_title,
                        'question_text' => $request->question_text,
                        'instructions'  => $request->instructions,
                        'variants'      => json_encode($variants),
                        'updated_at'    => date("Y-m-d H:i:s", time())
                    ]
                );
            return response()->json($request->toArray());
        }
    }

    public function createQuestion(Request $request)
    {
        $variants = [];
        foreach($request->variant_ids as $currentVarIdx=>$currentID){
            $variants[] = [
                'id'    => $currentID,
                'type'  => $request->input('variant_types.'.$currentVarIdx),
                'link'  => $request->input('variant_links.'.$currentVarIdx),
                'title' => $request->input('variant_titles.'.$currentVarIdx)
            ];
        }
        Questions::insert(
            [
                'question_title'=> $request->question_title,
                'question_text' => $request->question_text,
                'instructions'  => $request->instructions,
                'variants'      => json_encode($variants),
                'topic'         => $request->topic,
                'parent_id'     => $request->parent_id,
                'type'          => ($request->has('type') ? $request->type : 2),
                'created_at'    => date("Y-m-d H:i:s", time())
            ]
        );
        return response()->json($request->toArray());
    }

    public function getQuestionData(Request $request)
    {
        $question = Questions::where('id', '=', $request->input('question_id'))->get();
        if($question->count()>0){
            $questionAssoc = Questions::where('topic', $question->first()->topic)->get();
            $return = $question->first()->toArray();
            foreach($questionAssoc as $currentQuestion){
                $return['linked'][$currentQuestion->id] = $currentQuestion->question_text;
            }
        }else{
            $return = [];
        }
        return response()->json($return);
    }

    public function replayDialogue($callID, Request $request)
    {
        $getCallLog = CallLog::where('call_id', $callID)->orderBy('id')->get();
        if($getCallLog->count()>0){
            foreach($getCallLog as $thisCallLine){
                $detailedQuestions[] = $thisCallLine->question_id;
            }
            $operateurName = ($getCallLog->first()->operateur_id>0 ? Users::where('id', $getCallLog->first()->operateur_id)->get()->first()->name : 'Неизвестно');
            if(isset($detailedQuestions)){
                $detailedQuestions = array_unique(array_filter($detailedQuestions));
                $getDetailedQuestions = Questions::whereIn('id', $detailedQuestions)->get();
                if($getDetailedQuestions->count()>0){
                    foreach($getDetailedQuestions as $thisDetailedQuestion){
                        $detailedQuestionsData[$thisDetailedQuestion->id] = $thisDetailedQuestion->toArray();
                        $detailedQuestionsData[$thisDetailedQuestion->id]['question_text'] = explode('{%break%}', $detailedQuestionsData[$thisDetailedQuestion->id]['question_text']);
                        $detailedQuestionsData[$thisDetailedQuestion->id]['variants'] = json_decode($detailedQuestionsData[$thisDetailedQuestion->id]['variants'], 1);
                    }
                }else{
                    $detailedQuestionsData = [];
                }
            }
        }
        return view('callscriptsReplayDialogue')
            ->with('callLog', $getCallLog)
            ->with('questions', $detailedQuestionsData)
            ->with('callID', $callID)
            ->with('operateurName', $operateurName);
    }

    public function dialoguesList(Request $request)
    {
        if($request->type == "improvements"){
            $improvementsList = Improvements::orderBy('created_at', 'asc')->paginate(10);
            if($improvementsList->count()>0){
                # Получение связанных сущностей и данных о них
                foreach($improvementsList as &$currentLine){
                    $currentLine->created_at_string = self::parseDate($currentLine->created_at);
                    $currentLine->question_data = Questions::where('id', $currentLine->question_id)->get()->first();
                    $currentLine->topic_data = Topics::where('id', $currentLine->question_data->topic)->get()->first();
                    $currentLine->operateur_name = Users::where('id', $currentLine->operateur_id)->get()->first()->name;
                }
            }
            return view('callscriptsImprovementsList')
                ->with('improvements', $improvementsList);
        }else{
            $callogList = CallLog::groupBy('call_id')->orderBy('id', 'desc')->paginate(10);
            foreach($callogList as &$currentValue){
                $currentValue->created_at_string = self::parseDate($currentValue->created_at);
                $currentValue->content = CallLog::where('call_id', $currentValue->call_id)->orderBy('id', 'desc')->get();
                $currentValue->questionsCount = $currentValue->content->count();
                $currentValue->operateur_name = ($currentValue->operateur_id>0 ? Users::where('id', $currentValue->operateur_id)->get()->first()->name : 'Неизвестно');
                $currentValue->dialogueEnd = self::parseDate($currentValue->content->first()->updated_at != null ? $currentValue->content->first()->updated_at : $currentValue->content->first()->created_at);
                if(($currentValue->content->first()->variant_id == 0) and ($currentValue->content->first()->question_id == 0)){
                    $currentValue->dialogueResult = $currentValue->content->get(1)->variant_id;
                }else{
                    $currentValue->dialogueResult = $currentValue->content->first()->variant_id;
                }
                if($currentValue->dialogueResult>0){
                    $getDialogueResult = Questions::where('variants', 'like', '%"'.$currentValue->dialogueResult.'"%')->get();
                    if($getDialogueResult->count()>0){
                        $resolveVariants = json_decode($getDialogueResult->first()->variants, 1);
                        foreach($resolveVariants as $thisVariant){
                            if($thisVariant['id'] == $currentValue->dialogueResult){
                                $currentValue->dialogueResultExplained = $thisVariant['link'];
                            }
                        }
                        if(!isset($currentValue->dialogueResultExplained)){
                            $currentValue->dialogueResultExplained = false;
                        }
                    }else{
                        $currentValue->dialogueResultExplained = false;
                    }
                }
            }
            return view('callscriptsDialoguesList')
                ->with('callogList', $callogList);
        }
    }

    public function topicsManager(Request $request)
    {
        if($request->has('type')){
            if($request->type == "update"){
                if(($request->has('topic_title')) and ($request->has('topic_description')) and ($request->has('is_publicated')) and ($request->has('topic_id'))){
                    Topics::where('id', $request->topic_id)->update(
                        [
                            'updated_at'            => date("Y-m-d H:i:s", time()),
                            'topic_name'            => $request->topic_title,
                            'topic_description'     => $request->topic_description,
                            'is_publicated'         => ($request->is_publicated == 1 ? 1 : 0)
                        ]
                    );
                    return response()->json(['success' => true, 'error' => 'Topic data was successfully updated']);
                }else{
                    return response()->json(['success' => false, 'error' => 'Required fields is missing']);
                }
            }elseif($request->type == "create"){
                if(($request->has('topic_title')) and ($request->has('topic_description')) and ($request->has('is_publicated'))){
                    $newTopic = Topics::insertGetId(
                        [
                            'created_at'            => date("Y-m-d H:i:s", time()),
                            'topic_name'            => $request->topic_title,
                            'topic_description'     => $request->topic_description,
                            'is_publicated'         => ($request->is_publicated == 1 ? 1 : 0)
                        ]
                    );
                    Questions::insert(
                        [
                            'topic'             => $newTopic,
                            'question_text'     => 'Текст начала диалога',
                            'variants'          => '[]',
                            'parent_id'         => -1,
                            'type'              => 1,
                            'created_at'        => date("Y-m-d H:i:s", time()),
                            'question_title'    => 'Начало диалога',
                            'instructions'      => null
                        ]
                    );
                    return response()->json(['success' => true, 'error' => 'New topic was successfully created']);
                }else{
                    return response()->json(['success' => false, 'error' => 'Required fields is missing']);
                }
            }else{
                return response()->json(['success' => false, 'error' => 'Request type is incorrect']);
            }
        }else{
            return response()->json(['success' => false, 'error' => 'Request type was missing']);
        }
    }

    public function viewImprovement($improvementId)
    {
        $improvement = Improvements::where('id', $improvementId)->get();
        if($improvement->count()>0){
            $improvement = $improvement->first();
            $improvement->original_question = Questions::where('id', $improvement->question_id)->get()->first();
                $improvement->original_question->question_text = explode('{%break%}', $improvement->original_question->question_text);
            $improvement->topic_data = Topics::where('id', $improvement->original_question->topic)->get()->first();
            $improvement->created_at_string = self::parseDate($improvement->created_at);
            $improvement->operateur_name = Users::where('id', $improvement->operateur_id)->get()->first()->name;
            return view('callscriptsSingleImprovement')
                ->with('improvementId', $improvementId)
                ->with('improvement', $improvement);
        }else{
            abort(404);
        }
    }

    public function viewNoticements()
    {
        $getNoticements = Noticements::buildHierarchy('all');
        $getCategories = Noticements::getUniqueCategories();
        $getTopics = Topics::get();
        return view('callscriptsNoticementsList')
            ->with('getNoticements', $getNoticements)
            ->with('getCategories', $getCategories)
            ->with('csTopics', $getTopics);
    }

    public function manageNoticements(Request $request)
    {
        if((!empty($request->edit_id)) and (!empty($request->remove_unit))){
            if($request->remove_unit == "true"){
                $checkNoticement = Noticements::where('id', $request->edit_id)->get();
                if($checkNoticement->count()>0){
                    if(($checkNoticement->first()->noticement_text == null) or ($checkNoticement->first()->noticement_text == "")){
                        Noticements::where('id', $request->edit_id)->delete();
                        Noticements::where('parent_id', $request->edit_id)->update(['parent_id' =>  -1]);
                    }else{
                        Noticements::where('id', $request->edit_id)->delete();
                    }
                }
                return redirect()->route('callscriptsViewNoticements');
            }
        }
        if((!empty($request->title)) and (!empty($request->visiblity)) and (!empty($request->parent_id))){
            if($request->action_type == "update"){
                Noticements::where('id', $request->edit_id)
                    ->update(
                        [
                            'title'             => $request->title,
                            'noticement_text'   => ($request->has('text') ? $request->text : null),
                            'updated_at'        => date("Y-m-d H:i:s", time()),
                            'visiblity'         => implode(',', $request->visiblity),
                            'parent_id'         => $request->parent_id
                        ]
                    );
            }else{
                Noticements::insert(
                        [
                            'title'             => $request->title,
                            'noticement_text'   => ($request->has('text') ? $request->text : null),
                            'created_by'        => Auth::user()->id,
                            'created_at'        => date("Y-m-d H:i:s", time()),
                            'visiblity'         => implode(',', $request->visiblity),
                            'parent_id'         => $request->parent_id
                        ]
                    );
            }
        }
        return redirect()->route('callscriptsViewNoticements');
    }

}
