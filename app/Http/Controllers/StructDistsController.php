<?php
namespace App\Http\Controllers;

use App\Connect1C\StructDists;
use App\UserBranches;
use Illuminate\Http\Request;

class StructDistsController extends Controller
{
    static protected $salons = [
        ['code_kb' => 5414, 'name' => 'Николаев'],
        ['code_kb' => 6356, 'name' => 'Амстор(Запорожье) ТЦ'],
        ['code_kb' => 10517, 'name' => 'Любарского(Днепр)'],
        ['code_kb' => 10588, 'name' => 'Артельная(Днепр)'],
        ['code_kb' => 18197, 'name' => 'Розница online'],
        ['code_kb' => 19082, 'name' => 'Кавалеридзе(Львов)'],
        ['code_kb' => 19862, 'name' => 'Херсон'],
        ['code_kb' => 20963, 'name' => 'Киев'],
        ['code_kb' => 21571, 'name' => 'Надычи(Львов)'],
        ['code_kb' => 21772, 'name' => 'Гагарина(Днепр)'],
        ['code_kb' => 21773, 'name' => 'Слобожанский(Днепр)'],
        ['code_kb' => 22874, 'name' => 'Ровно'],
        ['code_kb' => 23336, 'name' => 'Винница'],
        ['code_kb' => 23500, 'name' => 'Кременчуг'],
        ['code_kb' => 24913, 'name' => 'Мельница(Днепр) ТЦ'],
        ['code_kb' => 10589, 'name' => 'Мелитополь'],
        ['code_kb' => 25248, 'name' => 'Апполо(Днепр) ТЦ'],
        ['code_kb' => 25543, 'name' => 'Караван(Днепр) ТЦ'],
        ['code_kb' => 25544, 'name' => 'Киев(Полтава) ТЦ'],
        ['code_kb' => 25756, 'name' => 'Бердянск'],
        ['code_kb' => 25806, 'name' => 'Южный(Львов) ТЦ'],
        ['code_kb' => 25908, 'name' => 'Аэромолл(Борисполь) ТЦ'],
        ['code_kb' => 26125, 'name' => 'НОВУС(Киев) ТЦ'],
        ['code_kb' => 26235, 'name' => 'Караван(Харьков) ТЦ'],
        ['code_kb' => 26621, 'name' => 'Пастера(Днепр) ТЦ'],
        ['code_kb' => 25127, 'name' => 'Выездной менеджер №1'],
    ];

    static public function index()
    {
        $userBranch = UserBranches::all();

        if (isset($userBranch->first()->code_kb)){
            $salons = $userBranch->toArray();
        }else{
            $salons = self::$salons;
        }
        $salonData = StructDists::where(function ($q) use ($salons) {
            foreach ($salons as $key => $value){
                $q->orWhere('code_kb', $value['code_kb']);
            }
        })->get()->toArray();

        return $salonData;
    }
    static public function getSalon()
    {
        $userBranch = UserBranches::all();

        if (isset($userBranch->first()->code_kb)){
            $salons = $userBranch->toArray();
        }else{
            $salons = self::$salons;
        }
        return $salons;
    }
}
