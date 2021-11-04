<?php

namespace Database\Seeders;

use App\Models\StaticPage;
use Illuminate\Database\Seeder;

class StaticPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        factory(StaticPage::class)->create([
//            'title'   => 'Home',
//            'slug'    => 'home',
//            'content' => $this->readTemplate('home-secondary-banner.html'),
//            'layout'  => 'home',
//        ]);
//
//        factory(StaticPage::class)->create([
//            'title'         => 'About Us',
//            'slug'          => 'about-us',
//            'content'       => $this->readTemplate('about-us.html'),
//            'layout'        => 'default',
//            'youtube_video' => 'https://www.youtube-nocookie.com/embed/5kyZd7gvI0A',
//        ]);
//
//        factory(StaticPage::class)->create([
//            'title'   => 'Products',
//            'slug'    => 'products',
//            'content' => $this->readTemplate('products.html'),
//            'layout'  => 'products',
//        ]);
//
//        factory(StaticPage::class)->create([
//            'title'   => 'Contact Us',
//            'slug'    => 'contact-us',
//            'content' => $this->readTemplate('contact-us.html'),
//            'layout'  => 'contacts',
//        ]);
    }

    /**
     * Read the HTML template content.
     *
     * @param string $fileName
     *
     * @return string
     */
    protected function readTemplate(string $fileName): string
    {
        return file_get_contents(base_path('public/_templates/'.$fileName));
    }
}
