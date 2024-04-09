<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MovieCategory;

class MovieCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MovieCategory::create([
            'name' => 'Action',
            'description' => 'Movies in the action genre are defined by risk and stakes. While many movies may feature an action sequence, to be appropriately categorized inside the action genre, the bulk of the content must be action-oriented, including fight scenes, stunts, car chases, and general danger.',
        ]);
        MovieCategory::create([
            'name' => 'Animation',
            'description' => 'The animation genre is defined by inanimate objects being manipulated to appear as though they are living. This can be done in many different ways and can incorporate any other genre and sub-genre on this list.',
        ]);
        MovieCategory::create([
            'name' => 'Comedy',
            'description' => 'The comedy genre is defined by events that are intended to make someone laugh, no matter if the story is macabre, droll, or zany. Comedy can be found in most movies, but if the majority of the film is intended to be a comedy you may safely place it in this genre. The best comedy movies range throughout this entire spectrum of humor.',
        ]);
        MovieCategory::create([
            'name' => 'Crime',
            'description' => 'The crime genre deals with both sides of the criminal justice system but does not focus on legislative matters or civil suits and legal actions. The best crime movies often occupy moral gray areas where heroes and villains are much harder to define. Many of Martin Scorsese\'s best movies or Quentin Tarantino\'s movies fall within the crime genre.',
        ]);
        MovieCategory::create([
            'name' => 'Drama',
            'description' => 'The drama genre is defined by conflict and often looks to reality rather than sensationalism. Emotions and intense situations are the focus, but where other genres might use unique or exciting moments to create a feeling, movies in the drama genre focus on common occurrences. Drama is a very broad category and untethered to any era — from movies based on Shakespeare to contemporary narratives.',
        ]);
        MovieCategory::create([
            'name' => 'Experimental',
            'description' => 'The experimental genre is often defined by the idea that the work of art and entertainment does not fit into a particular genre or sub-genre, and is intended as such. Experimental art can completely forego a cohesive narrative in exchange for an emotional response or nothing at all.',
        ]);
        MovieCategory::create([
            'name' => 'Fantasy',
            'description' => 'The fantasy genre is defined by both circumstance and setting inside a fictional universe with an unrealistic set of natural laws. The possibilities of fantasy are nearly endless, but the movies will often be inspired by or incorporate human myths. The genre often adheres to general human psychology and societal behavior while incorporating non-scientific concepts like magic, mythical creatures, and supernatural elements.',
        ]);
        MovieCategory::create([
            'name' => 'Historical',
            'description' => 'The historical genre can be split into two sections. One deals with accurate representations of historical accounts which can include biographies, autobiographies, and memoirs. The other section is made up of fictional movies that are placed inside an accurate depiction of a historical setting. The accuracy of a historical story is measured against historical accounts, not fact, as there can never be a perfectly factual account of any event without first-hand experience.',
        ]);
        MovieCategory::create([
            'name' => 'Horror',
            'description' => 'The horror genre is centered upon depicting terrifying or macabre events for the sake of entertainment. A thriller might tease the possibility of a terrible event, whereas a horror film will deliver all throughout the film. The best horror movies are designed to get the heart pumping and to show us a glimpse of the unknown.',
        ]);
        MovieCategory::create([
            'name' => 'Romance',
            'description' => 'The romance genre is defined by intimate relationships. Sometimes these movies can have a darker twist, but the idea is to lean on the natural conflict derived from the pursuit of intimacy and love.',
        ]);
        MovieCategory::create([
            'name' => 'Science Fiction',
            'description' => 'Science fiction movies are defined by a mixture of speculation and science. While fantasy will explain through or make use of magic and mysticism, science fiction will use the changes and trajectory of technology and science. Science fiction will often incorporate space, biology, energy, time, and any other observable science. Most of James Cameron\'s best movies lean heavily on science fiction.',
        ]);
        MovieCategory::create([
            'name' => 'Thriller',
            'description' => 'A thriller story is mostly about the emotional purpose, which is to elicit strong emotions, mostly dealing with generating suspense and anxiety. No matter what the specific plot, the best thrillers get your heart racing.',
        ]);
        MovieCategory::create([
            'name' => 'Western',
            'description' => 'Westerns are defined by their setting and time period. The story needs to take place in the American West, which begins as far east as Missouri and extends to the Pacific ocean. They’re set during the 19th century, and will often feature horse riding, military expansion, violent and non-violent interaction with Native American tribes, the creation of railways, gunfights, and technology created during the industrial revolution.',
        ]);
        MovieCategory::create([
            'name' => 'Musical',
            'description' => 'Musicals originated as stage plays, but they soon became a favorite for many film directors and have even made their way into television. Musicals can incorporate any other genre, but they incorporate characters who sing songs and perform dance numbers.',
        ]);
        MovieCategory::create([
            'name' => 'War',
            'description' => 'The war genre has a few debatable definitions, but we’re going to try to be as straightforward and impartial as humanly possible. Movies in the war genre center around large scale conflicts between opposing forces inside a universe that shares the same natural laws as our own.',
        ]);
        MovieCategory::create([
            'name' => 'Biopics',
            'description' => 'A movie genre that has been around since the birth of cinema, biopics are a category all their own. Biopics can technically run the gamut of movie genres (Sports movies, War, Westerns, etc.) but they often find their home in dramas. At their core, biopics dramatize real people and real events with varying degrees of verisimilitude.',
        ]);
    }
}
