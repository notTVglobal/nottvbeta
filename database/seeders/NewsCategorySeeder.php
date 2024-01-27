<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $categories = [
          ['name' => 'World News', 'description' => 'Covering international events, global politics, and significant developments across various countries.'],
          ['name' => 'National News', 'description' => 'Focused on news and events specific to Canada, including national politics, policies, and major national occurrences.'],
          ['name' => 'Local News', 'description' => 'News pertinent to specific cities, towns, or regions, covering local government, community events, and regional issues.'],
          ['name' => 'Politics', 'description' => 'Encompassing political news, elections, government policies, and political party activities both at the national and local levels.'],
          ['name' => 'Business and Economy', 'description' => 'Covering financial markets, economic policies, business developments, corporate news, and economic trends.'],
          ['name' => 'Technology', 'description' => 'Focused on advancements in technology, tech industry news, gadget releases, and the impact of technology in various sectors.'],
          ['name' => 'Health', 'description' => 'Covering health-related news, medical advancements, healthcare policies, and public health issues.'],
          ['name' => 'Science', 'description' => 'Focused on scientific discoveries, research updates, space exploration, and significant breakthroughs in various scientific fields.'],
          ['name' => 'Environment', 'description' => 'Covering environmental issues, climate change, conservation efforts, and sustainable living practices.'],
          ['name' => 'Education', 'description' => 'News related to educational policies, school events, academic research, and developments in the education sector.'],
          ['name' => 'Sports', 'description' => 'Covering all types of sports news, events, athlete profiles, and sports-related controversies.'],
          ['name' => 'Entertainment', 'description' => 'News from the world of movies, television, music, celebrity gossip, and cultural events.'],
          ['name' => 'Arts and Culture', 'description' => 'Focused on the arts, literature, cultural events, exhibitions, and performances.'],
          ['name' => 'Lifestyle', 'description' => 'Covering topics like fashion, travel, food, and personal well-being.'],
          ['name' => 'Opinion', 'description' => 'Featuring editorials, opinion pieces, analysis, and commentary on a variety of topics.'],
          ['name' => 'Crime and Justice', 'description' => 'Reporting on criminal cases, law enforcement, legal issues, and court proceedings.'],
          ['name' => 'Human Interest', 'description' => 'Stories focusing on individuals or events with emotional appeal, highlighting personal experiences and human resilience.'],
          ['name' => 'Weather', 'description' => 'Reporting on weather forecasts, climate phenomena, and significant weather-related events.'],
          ['name' => 'Transportation', 'description' => 'News related to transportation systems, infrastructure developments, and issues affecting travel and commuting.'],
      ];

      $this->addTimestamps($categories);
      DB::table('news_categories')->insert($categories);
    }

  private function addTimestamps(array &$items)
  {
    $timestamp = now();
    foreach ($items as &$item) {
      $item['created_at'] = $timestamp;
      $item['updated_at'] = $timestamp;
    }
  }
}
