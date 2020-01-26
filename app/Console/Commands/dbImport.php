<?php

namespace App\Console\Commands;

use App\Contact;
use App\ContactPhones;
use App\Field;
use App\Fields_promo;
use App\Leed;
use App\LeedIps;
use App\Promo;
use App\Prop;
use App\Prop_promo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class dbImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:dbImport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer information from the old database information on orders to the new database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            DB::beginTransaction();

            $contacts = $this->contacts();
            foreach ($contacts as $contact) {
                $var = new contact();
                $var->fio = $contact['leed_name'];
                $var->region_id = $contact['leed_region_id'];
                $var->email = $contact['userEmail'];
                $var->save();

                $lastId = $var->id;

                $varPhone = new ContactPhones();
                $varPhone->contact_id = $lastId;
                $varPhone->phone = $contact['leed_phone'];
                $varPhone->save();
            }

            $leeds = $this->leedAndPromo();
            foreach ($leeds as $leed) {
                $varLeed = new Leed();
                $varLeed->leed_name = $leed->leed_name;
                $varLeed->leed_phone = $leed->leed_phone;
                $varLeed->leed_region_id = $leed->leed_region_id;
                $varLeed->status_id = $leed->status_id;
                $varLeed->label_id = $leed->label_id;
                $varLeed->comment = $leed->comment;
                $varLeed->save();

                $lastId = $varLeed->id;

                if ($leed->status_id == 10) {
                    $varPromo = new Promo();
                    $varPromo->leed_id = $lastId;
                    $varPromo->promo_code = $leed->promo_code;
                    $varPromo->promo_discount = $leed->promo_discount;
                    $varPromo->promo_phone = $leed->leed_phone;
                    $varPromo->promo_email = $leed->userEmail;
                    $varPromo->save();
                }

                $varIp = new LeedIps();
                $varIp->leed_id = $lastId;
                $varIp->ip = $leed->leed_ip;
                $varIp->client_ip = $leed->leed_ip;
                $varIp->save();
            }

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();
        }

    }

    public function contacts()
    {
        $phone = [];
        foreach ($this->leedAndPromo() as $value) {
            $var = [];
            $var['leed_name'] = $value->leed_name;
            $var['leed_phone'] = $value->leed_phone;
            $var['leed_region_id'] = $value->leed_region_id;
            $var['userEmail'] = $value->userEmail;
            $phone[] = $var;
        }
        $ids = array_column($phone, 'leed_phone');
        $ids = array_unique($ids);
        $phone = array_filter($phone, function ($key, $value) use ($ids) {
            return in_array($value, array_keys($ids));
        }, ARRAY_FILTER_USE_BOTH);

        return $phone;
    }

    public function leedAndPromo()
    {
        $all = [];
        foreach ($this->promoLeeds() as $promoLeed) {
            $all[] = $promoLeed;
        }
        foreach ($this->leeds() as $leed) {
            $all[] = $leed;
        }

        return $all;

    }

    public function leeds()
    {
        $fields = Field::all();
        $leeds = [];

        foreach ($fields as $field) {
            $fieldStatus = 4;
            $fieldLable = 5;
            $fieldComment = '';
            $props = prop::query()->where('field_id', '=', $field->id)->get();
            foreach ($props as $prop) {
                $fieldStatus = $this->statusLeeds($prop->status) ? $this->statusLeeds($prop->status) : 4;
                $fieldLable = $this->lableLeeds($prop->label) ? $this->lableLeeds($prop->label) : 5;
                $fieldComment = $prop->comment;
            }
            $var = new \stdClass();
            $var->leed_name = $field->field_1;
            $var->leed_phone = $this->phoneContact($field->field_2);
            $var->leed_region_id = $field->region_id ? $field->region_id : 13;
            $var->status_id = $fieldStatus;
            $var->userEmail = $field->field_3 ? (substr_count($field->field_3, '@') == 1 ? $field->field_3 : '') : '';
            $var->label_id = $fieldLable;
            $var->leed_ip = $field->field_7 ? (substr_count($field->field_7, '.') == 3 ? $field->field_7 : '0.0.0.0') : '0.0.0.0';
            $var->comment = $fieldComment;

            $leeds[] = $var;


        }
        return $leeds;
    }

    public function promoLeeds()
    {
        $fieldsPromos = Fields_promo::all();
        $promos = [];

        foreach ($fieldsPromos as $fieldsPromo) {
            $fieldLable = 5;
            $fieldComment = '';
            $propPromos = Prop_promo::query()->where('field_id_promo', '=', $fieldsPromo->id)->get();
            foreach ($propPromos as $propPromo) {
                $fieldLable = $propPromo->label == 'На просчет popup' ? 3 : 5;
                $fieldComment = $propPromo->comment;
            }

            $var = new \stdClass();
            $var->leed_name = $fieldsPromo->field_1_promo;
            $var->leed_phone = $this->phoneContact($fieldsPromo->field_2_promo);
            $var->leed_region_id = $fieldsPromo->region_id ? $fieldsPromo->region_id : 13;
            $var->status_id = 10;
            $var->userEmail = $fieldsPromo->field_7_promo ? (substr_count($fieldsPromo->field_7_promo, '@') == 1 ? $fieldsPromo->field_7_promo : '') : '';
            $var->label_id = $fieldLable;
            $var->leed_ip = substr_count($fieldsPromo->field_6_promo, '.') == 3 ? $fieldsPromo->field_6_promo : $fieldsPromo->field_8_promo;
            $var->comment = $fieldComment;
            $var->promo_code = $fieldsPromo->field_4_promo;
            $var->promo_discount = $fieldsPromo->field_3_promo;

            $promos[] = $var;

        }
        return $promos;
    }

    public function statusLeeds($var)
    {
        $result = '';
        switch ($var) {
            case 'done_call':
                $result = 3;
                break;
            case 'double':
                $result = 4;
                break;
            case 'new':
                $result = 5;
                break;
            case 'not_done_call':
                $result = 6;
                break;
            default:
                $result = 4;
        }

        return $result;
    }

    public function lableLeeds($var)
    {
        $result = '';
        switch ($var) {
            case 'Заявка на просчет':
                $result = 3;
                break;
            case 'Заказ обратного звонка':
                $result = 1;
                break;
            case 'Заявка на замер':
                $result = 2;
                break;
            default:
                $result = 5;
        }

        return $result;
    }

    public function leddIp($var)
    {
        $result = '';
        if (!preg_match('#^(?:(?:25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(?:25[0-5]|2[0-4]\d|[01]?\d\d?)$#', $var)) {
            $result = '0.0.0.0';
        } else {
            $result = $var;
        }
        return $result;
    }

    public function phoneContact($var)
    {
        $var = trim($var, '+');
        $var = str_replace(' ', '', $var);
        $var = str_replace('-', '', $var);
        $var = str_replace('(', '', $var);
        $var = str_replace(')', '', $var);
        $var = substr($var, -10);
        $var = preg_match('#[0-9]{7,10}$#', $var) ? $var : '';

        return $var;
    }

}
