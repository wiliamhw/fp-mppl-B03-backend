<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'key'   => 'copyright-year',
            'value' => '2020',
            'type'  => 'number',
        ]);

        Setting::create([
            'key'   => 'currency-symbol',
            'value' => 'Rp. ',
            'type'  => 'text',
        ]);

        Setting::create([
            'key'   => 'facebook-url',
            'value' => 'https://facebook.com/',
            'type'  => 'text',
        ]);

        Setting::create([
            'key'   => 'instagram-url',
            'value' => 'https://instagram.com/',
            'type'  => 'text',
        ]);

        Setting::create([
            'key'   => 'office-address',
            'value' => 'Jl. Pejaten Barat 2 No. 3A',
            'type'  => 'textarea',
        ]);

        Setting::create([
            'key'   => 'office-email',
            'value' => 'contact@suitmedia.com',
            'type'  => 'text',
        ]);

        Setting::create([
            'key'   => 'office-fax',
            'value' => '+6221 719 6877',
            'type'  => 'text',
        ]);

        Setting::create([
            'key'   => 'office-telephone',
            'value' => '+6221 719 6877',
            'type'  => 'text',
        ]);

        Setting::create([
            'key'   => 'twitter-url',
            'value' => 'https://twitter.com/',
            'type'  => 'text',
        ]);

        Setting::create([
            'key'   => 'youtube-url',
            'value' => 'https://youtube.com/',
            'type'  => 'text',
        ]);
    }
}
