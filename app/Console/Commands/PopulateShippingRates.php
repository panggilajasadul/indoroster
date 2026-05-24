<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ShippingRate;
use Laravolt\Indonesia\Models\City;

class PopulateShippingRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:populate-shipping-rates {--force : Overwrite existing rates}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Populate shipping_rates table with all cities in Indonesia';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cities = City::all();
        $this->info("Found {$cities->count()} cities.");

        $bar = $this->output->createProgressBar($cities->count());
        $bar->start();

        foreach ($cities as $city) {
            if ($this->option('force')) {
                ShippingRate::updateOrCreate(
                    ['city_code' => $city->code],
                    [
                        'shipping_cost' => 180000,
                        'min_order_qty' => 0,
                        'is_active' => true,
                    ]
                );
            } else {
                ShippingRate::firstOrCreate(
                    ['city_code' => $city->code],
                    [
                        'shipping_cost' => 180000,
                        'min_order_qty' => 0,
                        'is_active' => true,
                    ]
                );
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Shipping rates populated successfully.');
    }
}
