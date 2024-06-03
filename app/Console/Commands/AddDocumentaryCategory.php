<?php

namespace App\Console\Commands;

use App\Models\MovieCategory;
use App\Models\MovieCategorySub;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddDocumentaryCategory extends Command
{
  protected $signature = 'movie:add-documentary-category';
  protected $description = 'Add Documentary category and subcategories to movie_category and movie_category_subs tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      DB::transaction(function () {
        $subCategoryGroups = [
            'Documentary' => [
                ['name' => 'Nature Documentary', 'description' => 'Focuses on wildlife and natural environments, often showcasing ecosystems, wildlife behavior, and environmental issues.'],
                ['name' => 'Social Documentary', 'description' => 'Explores social issues, cultures, and human lifestyles, often highlighting societal challenges, injustices, and diverse ways of life.'],
                ['name' => 'Historical Documentary', 'description' => 'Centers on historical events, figures, and periods, often using archival footage, interviews, and reenactments to depict historical narratives.'],
                ['name' => 'Biographical Documentary', 'description' => 'Tells the life stories of individuals, whether famous personalities or ordinary people with extraordinary experiences.'],
                ['name' => 'Political Documentary', 'description' => 'Examines political issues, events, and figures, often exploring themes of power, governance, and policy-making.'],
                ['name' => 'Science Documentary', 'description' => 'Focuses on scientific topics, discoveries, and explorations, often explaining complex scientific concepts and innovations.'],
                ['name' => 'Music Documentary', 'description' => 'Explores the world of music, including genres, bands, musicians, and the cultural impact of music.'],
                ['name' => 'Art Documentary', 'description' => 'Centers on the visual arts, covering artists, art movements, galleries, and the creative process.'],
                ['name' => 'Sports Documentary', 'description' => 'Focuses on sports, athletes, and sporting events, often highlighting personal journeys, competitions, and the significance of sports.'],
                ['name' => 'Travel Documentary', 'description' => 'Explores different regions of the world, their landscapes, cultures, and customs, often emphasizing the experience of travel and discovery.'],
                ['name' => 'Environmental Documentary', 'description' => 'Examines environmental issues, conservation efforts, and the relationship between humans and the natural world.'],
                ['name' => 'Technology Documentary', 'description' => 'Focuses on technological advancements, digital culture, and the impact of technology on society and daily life.'],
                ['name' => 'Health and Wellness Documentary', 'description' => 'Explores health-related topics, medical research, wellness practices, and issues affecting the healthcare system.'],
                ['name' => 'True Crime Documentary', 'description' => 'Investigates real crime stories, often involving criminal cases, legal battles, and the pursuit of justice.'],
                ['name' => 'War Documentary', 'description' => 'Focuses on aspects of war, including conflicts, soldier experiences, and historical war narratives.'],
                ['name' => 'Cultural Documentary', 'description' => 'Explores cultural phenomena, traditions, and societal changes, often reflecting on human behavior and social dynamics.'],
                ['name' => 'Educational Documentary', 'description' => 'Aimed at providing educational content on various subjects, often used in academic or training contexts.'],
                ['name' => 'Experimental Documentary', 'description' => 'Utilizes experimental filmmaking techniques, often challenging traditional documentary forms and narratives.'],
                ['name' => 'Advocacy Documentary', 'description' => 'Created to advocate for a particular cause or viewpoint, often aiming to raise awareness or inspire action on specific issues.'],
                ['name' => 'Personal Documentary', 'description' => 'Centers on the filmmaker\'s personal stories, experiences, or perspectives, often blending the personal with the universal.'],
                ['name' => 'Crime and Justice Documentary', 'description' => 'Investigates criminal cases, law enforcement, and the justice system.'],
                ['name' => 'Food and Cuisine Documentary', 'description' => 'Focuses on culinary arts, cooking, and the cultural significance of food.'],
                ['name' => 'Art and Design Documentary', 'description' => 'Focuses on visual arts, architecture, and design, exploring creativity and artistic expression.'],
                ['name' => 'Human Interest Documentary', 'description' => 'Focuses on compelling human stories, often highlighting personal struggles and triumphs.'],
                ['name' => 'Lifestyle Documentary', 'description' => 'Covers various aspects of daily living, including fashion, hobbies, and personal development.'],
            ]
        ];
        $categoryDescription = 'Documentaries are non-fictional films intended to document reality, primarily for the purposes of instruction, education, or maintaining a historical record.';
        $categorySlug = 'documentary';
        $categoryName = 'Documentary';
        // Insert the category
        $category = MovieCategory::create([
            'name' => $categoryName,
            'slug' => $categorySlug,
            'description' => $categoryDescription,
        ]);

        // Insert the subcategories
        foreach ($subCategoryGroups['Documentary'] as $subCategory) {
          MovieCategorySub::create([
              'movie_categories_id' => $category->id,
              'name' => $subCategory['name'],
              'description' => $subCategory['description'],
          ]);
        }
      });

      $this->info('Documentary category and subcategories added successfully.');

    }
}
