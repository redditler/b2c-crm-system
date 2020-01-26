<?php

namespace App\Console\Commands;

use App\Leed;
use App\Promo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LeadTypeReceive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'LeadTypeReceive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks whether lead matches its type and who created it.';

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
        $leads = Leed::all()->toArray();
        $promos = Promo::getPromoKey();

        DB::transaction(function () use ($leads, $promos) {
            foreach ($leads as $lead) {
                if (isset($promos[$lead['id']])) {
                    Leed::where('id', $lead['id'])->update([
                        'leed_type_id' => 2
                    ]);
                }
            }
        });
        DB::transaction(function () use ($leads) {
            foreach ($leads as $lead) {
                if ($lead['cm_id'] != null) {
                    Leed::where('id', $lead['id'])->update([
                        'leed_receive_id' => 2
                    ]);
                }
            }
        });
    }
}
