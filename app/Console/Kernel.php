<?php

namespace App\Console;

use App\Http\Controllers\PriceController;
use App\Models\Price;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            //$prices = Price::where(['created' => 0, 'all' => 0])->get();
            $prices = Price::where(['created' => 0,'all'=>0])->get();
            foreach ($prices as $item) {
                Log::info('Testing scheduler: ' . date("d/m/Y h:i:sa"));
                $j = PriceController::createProductInPriceList($item->id, 0, 'xml');

                $item->created = 1;
                $item->save();
                Log::info('Testing scheduler: ' . date("d/m/Y h:i:sa"));
            }
            $all_xml = Price::where(['id' => 1])->first();
            if ($all_xml->created == 0) {
                PriceController::createProductInPriceList(1, 1);
                $all_xml->created = 1;
                $all_xml->save();
            }
            $all_xls = Price::where(['id' => 2])->first();
            if ($all_xls->created == 0) {
                PriceController::createProductInPriceList(2, 1);
                $all_xls->created = 1;
                $all_xls->save();
            }
        })->everyTenMinutes();

        $schedule->call(function () {
            PriceController::generatePrice();
        })->dailyAt('02:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
