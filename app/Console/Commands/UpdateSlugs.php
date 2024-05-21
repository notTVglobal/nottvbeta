<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slugs:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update slugs for various tables';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->updateShowCategories();
        $this->updateNewsCategories();
        $this->updateNewsProvinces();
        $this->updateNewsCities();

        $this->info('Slugs have been updated successfully.');

        return 0;
    }

    protected function updateShowCategories()
    {
        DB::table('show_categories')->orderBy('id')->chunk(100, function ($categories) {
            foreach ($categories as $category) {
                DB::table('show_categories')
                    ->where('id', $category->id)
                    ->update(['slug' => Str::slug($category->name)]);
            }
        });
    }

    protected function updateNewsCategories()
    {
        DB::table('news_categories')->orderBy('id')->chunk(100, function ($categories) {
            foreach ($categories as $category) {
                DB::table('news_categories')
                    ->where('id', $category->id)
                    ->update(['slug' => Str::slug($category->name)]);
            }
        });
    }

    protected function updateNewsProvinces()
    {
        DB::table('news_provinces')->orderBy('id')->chunk(100, function ($provinces) {
            foreach ($provinces as $province) {
                DB::table('news_provinces')
                    ->where('id', $province->id)
                    ->update(['slug' => Str::slug($province->name)]);
            }
        });
    }

    protected function updateNewsCities()
    {
        DB::table('news_cities')->orderBy('id')->chunk(100, function ($cities) {
            foreach ($cities as $city) {
                $slugBase = Str::slug($city->name);
                $slug = $slugBase;
                $count = 1;

                while (DB::table('news_cities')->where('slug', $slug)->exists()) {
                    $province = DB::table('news_provinces')->where('id', $city->province_id)->first();
                    $slug = $slugBase . '-' . strtolower($province->abbreviation);
                    $count++;
                }

                DB::table('news_cities')
                    ->where('id', $city->id)
                    ->update(['slug' => $slug]);
            }
        });
    }
}
