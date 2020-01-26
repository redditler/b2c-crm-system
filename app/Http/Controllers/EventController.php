<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Event;
use Illuminate\Support\Facades\Auth;
use Calendar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function index()
    {
        return view('organizer.fullcalender');
    }

    public function showEvents(Request $request)
    {
        $data = Event::getEventUser($request);

        $events = [];
        if ($data->count()) {
            foreach ($data as $key => $value) {
                $events[] = \Calendar::event(
                    $value->title,
                    true,
                    new \DateTime($value->start_date),
                    new \DateTime($value->end_date . ' +1 day'),
                    null,
                    [
                        'color' => $value->color_event ?? '#52b152',
                        'url' => $value->id ?? false,
                    ]
                );
            }
        }

        $calendar = \Calendar::addEvents($events);
        return response([$calendar->script()->options, $data]);

    }

    public function setEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'color_event' => 'required|regex:/^#[A-Fa-f0-9]{3,6}$/i',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',

        ], [
            'title.required' => 'Поле "Заголовок задачи" обязательно к заполнению.',
            'start_date.required' => 'Поле "Дата начала выполнения задачи" обязательно к заполнению.',
            'end_date.required' => 'Поле "Дата завершения задачи" обязательно к заполнению.',
            'end_date.after_or_equal' => 'Поле "Дата завершения задачи" не верно установлена дата.',
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->all());
        }

        DB::transaction(function () use ($request) {

            if (Auth::user()->role_id == 3 || Auth::user()->role_id == 3) {

                $event = new Event();
                $event->user_id = Auth::user()->id;;
                $event->user_id_from = Auth::user()->id;
                $event->title = $request->title;
                $event->url = $request->url ? $request->url : null;
                //$event->title = $request->title;
                $event->color_event = $request->color_event;
                $event->start_date = $request->start_date;
                $event->end_date = $request->end_date;
                $event->save();


            } else {
                if ($request->client_user_id) {

                    foreach ($request->client_user_id as $value) {
                        $event = new Event();
                        $event->user_id = $value;
                        $event->user_id_from = Auth::user()->id;
                        $event->title = $request->title;
                        $event->url = $request->url ? $request->url : null;
                        //$event->title = $request->title;
                        $event->color_event = $request->color_event;
                        $event->start_date = $request->start_date;
                        $event->end_date = $request->end_date;
                        $event->save();
                    }
                } else {
                    $event = new Event();
                    $event->user_id = Auth::user()->id;;
                    $event->user_id_from = Auth::user()->id;
                    $event->title = $request->title;
                    $event->url = $request->url ? $request->url : null;
                    //$event->title = $request->title;
                    $event->color_event = $request->color_event;
                    $event->start_date = $request->start_date;
                    $event->end_date = $request->end_date;
                    $event->save();

                }

            }
            return response()->make('OK!');
        });

    }


    public function deleteEvent($id)
    {
        if (Auth::user()->role_id == 1) {
            Event::where('id', $id)->delete();
            return response('Задача удаленна');
        } else {
            if (Event::where('id', $id)->first()->user_id_from == Auth::user()->id) {
                Event::where('id', $id)->delete();
                return response('Задача удаленна');
            } else {
                return response('Задачу может удалить только ее создатель');
            }
        }

    }
}
