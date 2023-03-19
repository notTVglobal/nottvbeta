<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShowCategory;

class ShowCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShowCategory::create([
            'name' => 'News',
            'description' => 'Newscasts, Newsbreaks, Headlines. Programs reporting on local, regional, national and international events. Such programs may include weather reports, sportscasts, community news, and other related features or segments contained within "News Programs"',
        ]);
        ShowCategory::create([
            'name' => 'Talk',
            'description' => 'Programs on various topics that include analysis or discussion, for example, talk or panel shows, consumer affairs or reviews, newsmagazines and documentaries that do not fall under "Long-form Documentary". This category excludes programs presenting information primarily for entertainment value. This category excludes programs presenting information primarily for entertainment value. "Docutainment" programs, gossip or entertainment talk shows fall more appropriately under "Reality". Lifestyle magazine shows generally fall under "Education".',
        ]);
        ShowCategory::create([
            'name' => 'Documentary',
            'description' => 'Long-form Documentary. Original works of non-fiction, primarily designed to inform but may also educate and entertain, providing an in-depth critical analysis of a specific subject or point of view over the course of at least 22 minutes. These programs shall not be used as commercial vehicles. Further, programs that fall under the category "Reality" do not qualify as documentary.',
        ]);
        ShowCategory::create([
            'name' => 'Actuality',
            'description' => 'Programs focusing on the coverage of conferences, political conventions, opening/closing of events (including awards dinners) and political debates, as well as programs of a non-entertainment nature intended to raise funds.',
        ]);
        ShowCategory::create([
            'name' => 'Spirituality',
            'description' => 'Programs dealing primarily with (i.e. more that 50%) spirituality, spiritual teachings, religion and religious teachings, as well as discussions of the human spiritual condition.',
        ]);
        ShowCategory::create([
            'name' => 'Education',
            'description' => 'Programs presenting detailed information related to a wide variety of topics and used by the viewer primarily to acquire knowledge. The programs can be related to established curricula. All programs targeted at pre-schoolers (ages 2-5) except those that are primarily comprised of drama. Programs presenting information on recreation, hobby and skill development, recreational sports and outdoor activities, travel and leisure, employment opportunities, and talk shows of an informative ("how-to") nature.',
        ]);
        ShowCategory::create([
            'name' => 'Sports',
            'description' => 'Programs of live or live-to-tape sports events and competitions including coverage of professional and amateur tournaments. The category also includes programs reviewing and analysing professional or amateur competitive sports events/teams (i.e. pre- and post-game shows, magazine shows, scripted sports, call-in and talk shows, etc.)',
        ]);
        ShowCategory::create([
            'name' => 'Drama',
            'description' => 'Entertainment productions of a fictional nature, including dramatisations of real events. They must be comprised primarily of (i.e. more than 50%) dramatic performances. On-going dramatic series, Specials, mini-series, and made-for-TV feature films. Animated television programs and films (excludes computer graphic productions without story lines). Other drama, including, but not limited to, readings, narratives, improvisations, tapes/films of live theatre not developed specifically for television, experimental shorts, video clips, continuous action animation (e.g. puppet shows)',
        ]);
        ShowCategory::create([
            'name' => 'Comedy',
            'description' => 'On-going comedy series (sitcoms). Programs of comedy sketches, improvisations, unscripted works, stand-up comedy',
        ]);
        ShowCategory::create([
            'name' => 'Music',
            'description' => 'Programs consisting primarily (i.e. more than 50%) of music videos and in some cases including a host and other programming elements. Programs comprised primarily (i.e. more than 50%) of live or pre-recorded performances of music. The performance portion excludes videoclips, voice-overs or musical performances used as background.',
        ]);
        ShowCategory::create([
            'name' => 'Dance',
            'description' => 'Programs comprised primarily (i.e. more than 50%) of live or pre-recorded performances of dance. The performance portion excludes videoclips, voice-overs or musical performances used as background.',
        ]);
        ShowCategory::create([
            'name' => 'Variety',
            'description' => 'Programs containing primarily (i.e. more than 50%) performances of mixed character (e.g. not exclusively music or comedy performances) consisting of a number of individual acts such as singing, dancing, acrobatic exhibitions, comedy sketches, monologues, magic, etc.',
        ]);
        ShowCategory::create([
            'name' => 'Games',
            'description' => 'Programs featuring games of skill and chance as well as quizzes.',
        ]);
        ShowCategory::create([
            'name' => 'Entertainment',
            'description' => 'Programs primarily about the world of entertainment and its people. These programs include celebrity profiles that may use promotional footage, talk or interview shows, award shows, galas and tributes. They also include entertainment-oriented magazine shows; fund-raising shows which include entertainers (i.e. telethons); human interest programs consisting of live or live-to-tape footage without significant portions devoted to in-depth analysis or interpretation; and coverage of community events such as carnivals, festivals, parades and fashion shows.',
        ]);
        ShowCategory::create([
            'name' => 'Reality',
            'description' => 'Programs that present unscripted dramatic or humorous situations, document actual events and typically feature ordinary people instead of professional actors. This type of programming involves passively following individuals as they go about their daily personal and professional activities. Though unscripted, this programming may be directed and may resemble a soap opera – hence the popular references to “docusoaps” and “docudramas.” Though this type of programming may be factual, it lacks or has very minimal amounts of in-depth critical analysis of a specific subject or point of view that is the key defining element of Long-form documentary programming.',
        ]);
        ShowCategory::create([
            'name' => 'Interstitials',
            'description' => 'Programs with a running time of less than 5 minutes, exclusive of advertising and other interstitial material, consisting of material that can be described under any of the other categories (except PSA, Promotional and Filler)',
        ]);
        ShowCategory::create([
            'name' => 'PSAs',
            'description' => 'Messages of less than 5 minutes duration intended to educate the audience about issues of public concern, encourage public support and awareness of a worthy cause, or promote the work of a non-profit group or organization dedicated to enhancing the quality of life in local communities or in society or the world at large. These include community billboards. These messages are not intended to sell or promote goods or commercial services. No payment is exchanged between broadcasters and producers for the broadcast of these messages.',
        ]);
        ShowCategory::create([
            'name' => 'Promotional',
            'description' => 'Programming exceeding 12 minutes in length that combines information and/or entertainment with the sale or promotion of goods or services into a virtually indistinguishable whole. This category includes videos and films of any length produced by individuals, groups and businesses for public relations, recruitment, etc.',
        ]);
        ShowCategory::create([
            'name' => 'Filler',
            'description' => 'Programming, in no case longer than 30 minutes in duration, the purpose of which is to fill in the time between the presentation of the major programs broadcast by the licensed pay services and those specialty services authorized to distribute filler programming, and includes material that promotes the programs or services provided by the licensee.',
        ]);
    }
}
