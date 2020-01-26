<?php
namespace App\Http\Controllers;

use App\Connect1C\StockPoints;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StockPointsController extends Controller
{
    public function index()
    {
        $salons = StructDistsController::getSalon();
        $kodSalons = [];
        foreach ($salons as $key => $salon){
            $kodSalons[$salon['code_kb']] = $salon;
        }

        $accountMovement = self::accountMovement();

        return view('test', [
            'moneys' => json_encode($accountMovement),
            'salon' => json_encode($kodSalons)
        ]);
    }

    public function sumSalonPay()
    {
        $accountMovement = self::accountMovement();
        $salonNameAll = StructDistsController::getSalon();
        $salonName = [];
        foreach ($salonNameAll as $item){
            $salonName[$item['code_kb']] = $item;
        }

        $sumSalonPay = [];
        $sum = [];
        foreach ($accountMovement as $account){
            $sumSalonPay[$account['code_kb']][$account['date_pay']][] =$account;
            $sum[$account['code_kb']][$account['date_pay']] = 0;
        }


        foreach ($accountMovement as $account){
            $sum[$account['code_kb']][$account['date_pay']] += $account['sum_pay'];
        }

        foreach ($sum as $key => $item){
            echo '<pre>';
            echo $salonName[$key]['name'].'<br/>';
            $sum = 0;
            foreach ($item as $k => $value){
                if ($k >= Carbon::make('01-01-2019')->format('Y-m-d') && $k <= Carbon::make('31-01-2019')->format('Y-m-d')){
                    echo $k.': '.$value.'<br/>';
                    $sum +=$value;
                }
            }
            echo 'SUM: '.$sum.'<br/>';

            echo '</pre>';
        }

        return '123';
    }

    static public function accountMovement()
    {
        $salons = StructDistsController::index();
        $accountMovement = StockPoints::where(function ($q) use ($salons){
            foreach ($salons as $salon){
                $q->orWhere('code_kb',$salon['code_kb']);
            }
        })->orderBy('code_kb', 'desc')->get()->toArray();

        return $accountMovement;
    }


}
