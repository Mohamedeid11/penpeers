<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get(base_path()."/database/assets/countries.json");
        $data = new Collection(json_decode($json, true));
        $countries = [];
        foreach ($data as $country){
            array_push($countries, [
                "name"=>$country['name_en'],
                "nationality"=>$country['name_en'],
                "key"=>$country['countryKey'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

        }
        DB::table('countries')->insert($countries);
    }
}
