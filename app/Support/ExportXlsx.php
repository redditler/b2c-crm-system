<?php
/**
 * Created by PhpStorm.
 * User: pomazan_rn
 * Date: 28.08.2019
 * Time: 10:37
 */

namespace App\Support;


use App\ContactNew;
use App\ContactPhones;
use App\Leed;
use App\Support\LeadFilter\LeadFilterRender;
use App\Support\UserRole\SelectRole;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExportXlsx
{
    public static function getExportFile($request, $callFunc)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        self::$callFunc($sheet, $request);
        $filename = 'client.Xls';

        header('Content-Disposition: attachment; filename=' . $filename );
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');

    }

    public static function contactXls($sheet, $request)
    {
        $contacts = ContactNew::query()
            ->whereBetween('created_at', [$request->ContactDateFromXls.' 00:00:00', $request->ContactDateToXLS.' 23:59:59'])
            ->where(function ($q) use ($request){
                if (!is_null($request->clientRegionIdXls[0])){
                    foreach (explode(',', $request->clientRegionIdXls[0]) as $value){
                        $q->orWhere('region_id', $value);
                    }
                }
            })
            ->where(function ($q) use ($request){
                if (!is_null($request->qualityId[0])){
                    foreach (explode(',', $request->qualityId[0]) as $value){
                        $q->orWhere('contact_quality_id', $value);
                    }
                }
            })
            ->where(function ($q) use ($request){
                if (!is_null($request->client_user_idXls[0])){
                    foreach (explode(',', $request->client_user_idXls[0]) as $value){
                        $q->orWhere('user_id', $value);
                    }
                }
            })
            ->where(function ($q) use ($request){
                if ( $request->contactPhoneXls) {
                    foreach (ContactPhones::getIdLikePhone($request->contactPhoneXls) as $phone) {
                        $q->orWhere('id', $phone->contact_id);
                    }
                }
            })
            ->with('group')
            ->with('regions')
            ->with('manager')
            ->with('contactQuality')
            ->with('contactSources')
            ->with('contactPhones')
            ->orderBy('id', 'desc')
            ->get();


        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'ФИО');
        $sheet->setCellValue('C1', 'Телефон');
        $sheet->setCellValue('D1', 'Регион');
        $sheet->setCellValue('E1', 'Адрес');
        $sheet->setCellValue('F1', 'Ведущий мененджер');
        $sheet->setCellValue('G1', 'Квалификация');
        $sheet->setCellValue('H1', 'Пол');
        $sheet->setCellValue('I1', 'Источник клиента');
        $sheet->setCellValue('J1', 'Дата создания');
        $sheet->getStyle('J')->getNumberFormat()->setFormatCode('DD.MM.YY');

        $val = 2;

        foreach ($contacts as $contact){
            $sheet->setCellValue('A'.$val, $contact->id);
            $sheet->setCellValue('B'.$val, $contact->fio);
            $sheet->setCellValue('C'.$val,  $contact->contactPhones ? $contact->contactPhones[0]->phone : 'Телефон не найден');
            $sheet->setCellValue('D'.$val, $contact->regions ? $contact->regions->name : 'Регион не установлен');
            $sheet->setCellValue('E'.$val, $contact->city ? $contact->city : 'Адрес не установлен');
            $sheet->setCellValue('F'.$val, $contact->manager ? $contact->manager->name : 'Менеджер не назначен');
            $sheet->setCellValue('G'.$val, $contact->contactQuality ? $contact->contactQuality->title : 'Квалификация не указана');
            $sheet->setCellValue('H'.$val, $contact->gender ? 'М' : (is_null($contact->gender) ? 'Пол не указан' : 'Ж'));
            $sheet->setCellValue('I'.$val, $contact->contactSources ? $contact->contactSources->name : 'Источник не указан');
            $sheet->setCellValue('J'.$val, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($contact->created_at->format('d.m.Y')));
            $val++;
        }

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);

        return $sheet;
    }

    public static function leadXls($sheet, $request)
    {
//        dd($request->all());
        $requestRes =self::arrayRepairs($request);

        $user = Auth::user();
        $filter = LeadFilterRender::chooseFilterMethod($requestRes) ?? false;
        $children = SelectRole::selectRole($user);

        $leads = Leed::query()
            ->whereRaw("(`contact_id`,`id`) IN (SELECT `contact_id`, MAX(id) FROM leeds GROUP BY `contact_id`)")
            ->where('leed_type_id', 1)
            ->where('rejected_lead', 0)
            ->whereBetween('created_at', [$request->leadDateFrom.' 00:00:00', $request->leadDateTo.' 23:59:59'])
            ->where(function ($q) use ($children, $user) {
                foreach ($children->getUserChildren() as $child) {

                    $q->orWhere('user_id', $child->id);
                }
                if ($user->group_id == 1 || $user->group_id == 2 || $user->group_id == 3) {
                    foreach ($children->getUserRegion() as $region) {
                        $q->orWhere('leed_region_id', $region->id)->where('user_id', 0);
                    }
                }
            })
            ->where(function ($q) use ($requestRes) {
                if ($requestRes->leadStatusId) {
                        $q->orWhere('status_id', $requestRes->leadStatusId);
                }
            })
            ->where(function ($q) use ($filter) {
                if ($filter) {
                    foreach ($filter as $value) {
                        $q->orWhere('user_id', $value->id);//@TODO
                        if ($value->group_id == 1 || $value->group_id == 1){
                            $q->orWhere('user_id', 0)->where('leed_region_id', $value->branch->region_id);
                        }
                    }
                }
            })
            ->orderBy('id', 'desc')
            ->get();
//       dd($leads);

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'ФИО');
        $sheet->setCellValue('C1', 'Телефон');
        $sheet->setCellValue('D1', 'Регион');
        $sheet->setCellValue('E1', 'Статутс');
        $sheet->setCellValue('F1', 'Ведущий мененджер');
        $sheet->setCellValue('G1', 'Дата создания');
        $sheet->getStyle('G')->getNumberFormat()->setFormatCode('DD.MM.YY');

        $val = 2;
        foreach ($leads as $lead){
            $sheet->setCellValue('A'.$val, $lead->id);
            $sheet->setCellValue('B'.$val, $lead->leed_name);
            $sheet->setCellValue('C'.$val, $lead->leed_phone);
            $sheet->setCellValue('D'.$val, $lead->region ? $lead->region->name : 'Регион не установлен');
            $sheet->setCellValue('E'.$val, $lead->status ? $lead->status->name : 'Статус не установлен');
            $sheet->setCellValue('F'.$val, $lead->manager ? $lead->manager->name : 'Менеджер не назначен');
            $sheet->setCellValue('G'.$val, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($lead->created_at->format('d.m.Y')));
            $val++;
        }


        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);

        return $sheet;
    }


    public static function arrayRepairs($request)
    {
        $requestRes = new \stdClass();
        $requestRes->leadDateFrom = $request->leadDateFrom;
        $requestRes->leadDateTo = $request->leadDateTo;
        $requestRes->group_id = $request->group_id;
        $requestRes->regionManager_id = $request->regionManager_id ? explode(',',  $request->regionManager_id) : null;
        $requestRes->salon_id = $request->salon_id ? explode(',',  $request->salon_id) : null;
        $requestRes->user_id = $request->user_id ? explode(',',  $request->user_id) : null;
        $requestRes->leadStatusId = $request->leadStatusId;
        return $requestRes;
    }
}