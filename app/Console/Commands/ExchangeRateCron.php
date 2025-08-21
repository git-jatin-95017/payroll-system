<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExchangeRate;

class ExchangeRateCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Exchange Rates';

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
     * @return int
     */
    public function handle()
    {
        $startDate = date('Y-m-d');
        $nextdate = strtotime($startDate);
        $dateFinal = strtotime("+7 day", $nextdate);
        $endDate = date('Y-m-d', $dateFinal);

        // set API Endpoint and access key (and any options of your choice)

        $endpoint = 'live';

        $access_key = 'df990ab0e9fc0c0468d72082bb60dde7';

        $url = 'https://api.currencylayer.com/'.$endpoint.'?access_key='.$access_key.'& currencies = USD,GBP,EUR & start_date = '.$startDate.' & end_date = '. $endDate;

        $result = file_get_contents($url);

        $exchangeRates = json_decode($result, true);

        if (count($exchangeRates['quotes']) > 0) {
            ExchangeRate::truncate();
            foreach($exchangeRates['quotes'] as $key => $value) {
                $currency = str_replace('USD', '', $key);
                ExchangeRate::create([
                    'currency' => $currency,
                    'rate' =>$value,
                    'date' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }
}
