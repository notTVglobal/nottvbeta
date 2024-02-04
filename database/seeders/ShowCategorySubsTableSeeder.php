<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShowCategorySubsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // Example sub-category groups with their respective category names
      $subCategoryGroups = [
          'News' => [
              ['name' => 'Local News', 'description' => 'Coverage of local events, community news, and local weather reports.'],
              ['name' => 'National News', 'description' => 'Comprehensive reporting on national events, policies, and issues, including national weather and sports.'],
              ['name' => 'International News', 'description' => 'Coverage of global events, international politics, and cultural stories from around the world.'],
              ['name' => 'Investigative Reporting', 'description' => 'In-depth reporting that uncovers and investigates complex issues, often involving political, social, or economic matters.'],
              ['name' => 'Technology and Science News', 'description' => 'Latest developments in technology, scientific discoveries, and discussions on their impact on society.'],
              ['name' => 'Health and Wellness News', 'description' => 'News covering health policies, medical advancements, wellness tips, and global health trends.'],
              ['name' => 'Business and Finance News', 'description' => 'Coverage of the business world, financial markets, economic policies, and corporate news.'],
              ['name' => 'Entertainment and Celebrity News', 'description' => 'Latest news from the entertainment industry, including celebrity gossip, movie releases, and cultural events.'],
              ['name' => 'Sports News', 'description' => 'Coverage of sports events, athlete profiles, and analysis of various sports and competitions.'],
              ['name' => 'Environmental News', 'description' => 'News and stories related to environmental issues, climate change, conservation efforts, and sustainability.'],
              ['name' => 'Cultural News', 'description' => 'Coverage of cultural events, traditions, heritage, and arts. This category explores the diverse cultural landscapes and artistic expressions from around the world, highlighting their significance and impact on society.',],
              ['name' => 'Economic News', 'description' => 'Focused reporting on broader economic trends, financial policies, market developments, and global economic issues. This category caters to viewers seeking in-depth analysis of economic conditions and insights into the global economy.',],
              ['name' => 'Legal and Judicial News', 'description' => 'Coverage of major legal cases, judicial decisions, and law-related issues. This category provides analysis and reporting on legal proceedings, courtroom trials, and the implications of judicial decisions on society and individuals.',],
              ['name' => 'Lifestyle News', 'description' => 'Features on lifestyle trends, cultural practices, travel, food, and human interest stories.'],
              ['name' => 'Political Analysis and Commentary', 'description' => 'Expert analysis and opinion pieces on political events, policy discussions, and electoral activities.'],
              ['name' => 'Educational News', 'description' => 'News related to the education sector, policy changes in education, and developments in pedagogy and learning methodologies.'],
              ['name' => 'Weather Reports', 'description' => 'Focused updates and forecasts on local and national weather conditions.'],
              ['name' => 'Consumer Affairs', 'description' => 'News and discussions related to consumer-related issues, product reviews, and consumer advocacy.'],
              ['name' => 'Newsmagazines', 'description' => 'Programs that include a compilation of various news-related features and segments. Location-specific content should be categorized under Local or National News as appropriate.'],
              ['name' => 'Special Event Coverage', 'description' => 'Focused coverage on conferences, political conventions, and major events. Location-specific events should be categorized under Local or National News.'],
              ['name' => 'Emergency Broadcasts', 'description' => 'Immediate news and updates related to emergencies and urgent situations.'],
            // Additional categories as needed
          ],
          'Talk' => [
              [
                  'name' => 'Celebrity Talk Shows',
                  'description' => 'Shows featuring interviews with celebrities, discussions about entertainment industry news, and celebrity lifestyles.',
              ],
              [
                  'name' => 'Political Talk Shows',
                  'description' => 'Programs focusing on political discussions, interviews with politicians, political analysis, and commentary.',
              ],
              [
                  'name' => 'Current Affairs Talk Shows',
                  'description' => 'Shows that discuss and analyze current events and topical issues, often featuring experts or commentators.',
              ],
              [
                  'name' => 'Lifestyle Talk Shows',
                  'description' => 'Programs covering a range of lifestyle topics, including health, wellness, fashion, and home living.',
              ],
              [
                  'name' => 'Science and Technology Talk Shows',
                  'description' => 'Focused discussions on scientific discoveries, technological advancements, and their implications.',
              ],
              [
                  'name' => 'Business and Finance Talk Shows',
                  'description' => 'Shows centered around economic trends, business news, personal finance advice, and interviews with business leaders.',
              ],
              [
                  'name' => 'Sports Talk Shows',
                  'description' => 'Discussions on sports news, game analyses, interviews with athletes and sports personalities.',
              ],
              [
                  'name' => 'Educational Talk Shows',
                  'description' => 'Programs with an educational focus, potentially aligned with academic subjects or offering learning in a talk show format.',
              ],
              [
                  'name' => 'Music Talk Shows',
                  'description' => 'Programs featuring discussions, interviews, and analysis on various music topics, artists, and industry trends, providing insights into the world of music.',
              ],
              [
                  'name' => 'Health and Wellness Talk Shows',
                  'description' => 'Discussions around health topics, wellness trends, medical advancements, and advice from health experts.',
              ],
              [
                  'name' => 'Cultural Talk Shows',
                  'description' => 'Shows exploring cultural themes, arts, literature, and discussions on cultural diversity and societal issues.',
              ],
              [
                  'name' => 'Comedy Talk Shows',
                  'description' => 'Talk shows with a focus on humor, including comedic interviews, sketches, and satirical takes on current events.',
              ],
            // Additional sub-categories as needed
          ],
          'Documentary' => [
              [
                  'name' => 'Historical Documentaries',
                  'description' => 'Exploring significant historical events, figures, and periods, offering in-depth analysis and historical perspectives.',
              ],
              [
                  'name' => 'Biographical Documentaries',
                  'description' => 'Focusing on the life and achievements of influential figures, providing insight into their personal and professional lives.',
              ],
              [
                  'name' => 'Science and Nature Documentaries',
                  'description' => 'Covering topics related to natural sciences, wildlife, and environmental issues, highlighting the beauty and complexity of the natural world.',
              ],
              [
                  'name' => 'Social and Cultural Documentaries',
                  'description' => 'Examining societal and cultural issues, exploring diverse lifestyles, traditions, and social phenomena.',
              ],
              [
                  'name' => 'Political Documentaries',
                  'description' => 'Analyzing political events, policies, and ideologies, offering critical perspectives on political systems and leaders.',
              ],
              [
                  'name' => 'Art and Music Documentaries',
                  'description' => 'Delving into the world of art and music, featuring the works of artists and musicians, and exploring various art forms and musical genres.',
              ],
              [
                  'name' => 'Technology and Innovation Documentaries',
                  'description' => 'Focusing on technological advancements, digital revolutions, and innovative solutions shaping the future.',
              ],
              [
                  'name' => 'Health and Wellness Documentaries',
                  'description' => 'Exploring topics related to health, medicine, wellness practices, and advancements in medical science.',
              ],
              [
                  'name' => 'Economic and Business Documentaries',
                  'description' => 'Analyzing economic trends, business strategies, and the global market, offering insights into the world of commerce and finance.',
              ],
              [
                  'name' => 'Environmental and Sustainability Documentaries',
                  'description' => 'Highlighting environmental challenges, conservation efforts, and sustainable practices to protect our planet.',
              ],
              [
                  'name' => 'Adventure and Exploration Documentaries',
                  'description' => 'Showcasing thrilling adventures, expeditions, and explorations in remote and challenging environments.',
              ],
              [
                  'name' => 'Educational Documentaries',
                  'description' => 'Providing educational content in an engaging documentary format, suitable for learning across various age groups.',
              ],
            // Additional sub-categories as needed
          ],
          'Actuality' => [
              [
                  'name' => 'Conference Coverage',
                  'description' => 'Detailed reporting and coverage of major conferences, including key speeches, panel discussions, and expert commentary.',
              ],
              [
                  'name' => 'Political Conventions',
                  'description' => 'Comprehensive coverage of political conventions, including party gatherings, election strategies, and keynote addresses.',
              ],
              [
                  'name' => 'Event Openings and Closings',
                  'description' => 'Broadcasts focusing on the opening and closing ceremonies of significant cultural, sports, and social events.',
              ],
              [
                  'name' => 'Awards Ceremonies',
                  'description' => 'Coverage of awards dinners and ceremonies, showcasing achievements in various fields like cinema, music, literature, and more.',
              ],
              [
                  'name' => 'Political Debates',
                  'description' => 'Live broadcasts of political debates, offering insights into the perspectives of different political parties and leaders.',
              ],
              [
                  'name' => 'Fundraising Programs',
                  'description' => 'Programs intended to raise funds for non-commercial causes, including charity events, telethons, and public support initiatives.',
              ],
              [
                  'name' => 'Cultural Festivals',
                  'description' => 'Coverage of cultural festivals, highlighting traditional and contemporary cultural expressions and celebrations.',
              ],
              [
                  'name' => 'Sporting Event Coverage',
                  'description' => 'Live broadcasts of significant sporting events, competitions, and related activities.',
              ],
              [
                  'name' => 'Public Announcements',
                  'description' => 'Broadcasts of important public announcements, government updates, and community information.',
              ],
              [
                  'name' => 'Documentary Reportage',
                  'description' => 'Real-time documentation and reportage of significant events, offering an in-depth look at unfolding stories.',
              ],
            // Additional sub-categories as needed
          ],
          'Spirituality' => [
              [
                  'name' => 'Religious Teachings',
                  'description' => 'Programs providing insights into the teachings of various religions, focusing on scriptures, doctrines, and religious practices.',
              ],
              [
                  'name' => 'Interfaith Dialogues',
                  'description' => 'Discussions and dialogues between different religious groups, promoting mutual understanding and respect among various faiths.',
              ],
              [
                  'name' => 'Spiritual Practices',
                  'description' => 'Exploring different spiritual practices and rituals across various religions, and their significance in the lives of followers.',
              ],
              [
                  'name' => 'Meditation and Mindfulness',
                  'description' => 'Programs focusing on meditation, mindfulness, and other spiritual techniques for personal growth and mental well-being.',
              ],
              [
                  'name' => 'Religious History and Documentaries',
                  'description' => 'Documentaries and historical analyses of religions, exploring their origins, evolution, and impact on societies.',
              ],
              [
                  'name' => 'Faith and Society',
                  'description' => 'Examining the role and influence of religion in contemporary society, including discussions on morality, ethics, and social values.',
              ],
              [
                  'name' => 'Religious Events and Festivals',
                  'description' => 'Coverage of major religious events, festivals, and celebrations, highlighting their cultural and spiritual significance.',
              ],
              [
                  'name' => 'Spiritual Wellness',
                  'description' => 'Programs that delve into the connection between spirituality and overall well-being, including mental and emotional health.',
              ],
              [
                  'name' => 'Comparative Religion Studies',
                  'description' => 'Educational content focusing on the comparison of beliefs, practices, and philosophies across different religions.',
              ],
              [
                  'name' => 'Philosophical Discussions',
                  'description' => 'Engaging in philosophical debates and discussions that explore spiritual themes, existential questions, and the human condition.',
              ],
            // Additional sub-categories as needed
          ],
          'Education' => [
              [
                  'name' => 'Early Childhood Education',
                  'description' => 'Programs designed for pre-schoolers, focusing on basic learning, play-based education, and early childhood development.',
              ],
              [
                  'name' => 'K-12 Educational Content',
                  'description' => 'Content aligned with primary and secondary education curricula, covering various academic subjects.',
              ],
              [
                  'name' => 'Higher Education and Research',
                  'description' => 'Programs covering higher education topics, university-level research, and academic discussions.',
              ],
              [
                  'name' => 'Vocational and Skill Training',
                  'description' => 'Educational content focused on vocational training, skill development, and career-oriented learning.',
              ],
              [
                  'name' => 'Lifelong Learning',
                  'description' => 'Programs catering to adult learners, covering topics from personal development to new skill acquisition.',
              ],
              [
                  'name' => 'Hobby and Craft Instruction',
                  'description' => 'Informative content on various hobbies and crafts, providing "how-to" guides and creative ideas.',
              ],
              [
                  'name' => 'Recreational Sports and Fitness',
                  'description' => 'Programs focusing on recreational sports, fitness activities, and promoting a healthy lifestyle.',
              ],
              [
                  'name' => 'Travel and Adventure Learning',
                  'description' => 'Educational content on travel, exploration, and learning about different cultures and environments.',
              ],
              [
                  'name' => 'Nature and Wildlife Education',
                  'description' => 'Programs focusing on wildlife, nature conservation, and environmental education.',
              ],
              [
                  'name' => 'DIY and Home Improvement',
                  'description' => 'Instructional content on do-it-yourself projects, home renovations, and practical home improvement skills.',
              ],
              [
                  'name' => 'Cooking and Culinary Arts',
                  'description' => 'Educational shows about cooking techniques, culinary arts, and exploring various cuisines.',
              ],
              [
                  'name' => 'Art and Music Education',
                  'description' => 'Programs dedicated to teaching and exploring the world of art and music, from techniques to history.',
              ],
            // Additional sub-categories as needed
          ],
          'Sports' => [
              [
                  'name' => 'Professional Sports Coverage',
                  'description' => 'Live or recorded coverage of professional sports events, including games, tournaments, and competitions across various sports disciplines.',
              ],
              [
                  'name' => 'Amateur Sports Coverage',
                  'description' => 'Broadcasts featuring amateur sports events, showcasing local, regional, and national amateur tournaments and competitions.',
              ],
              [
                  'name' => 'Sports Analysis and Commentary',
                  'description' => 'Programs offering in-depth analysis, reviews, and commentary on professional and amateur sports events, including pre-game and post-game shows.',
              ],
              [
                  'name' => 'Sports Magazine Shows',
                  'description' => 'Magazine-style programs covering a range of sports topics, athlete profiles, team news, and sports-related stories.',
              ],
              [
                  'name' => 'Sports Talk',
                  'description' => 'Talk-show format programs focusing on sports discussions, call-ins, and interviews with sports personalities and experts.',
              ],
              [
                  'name' => 'Sports Highlights and Recaps',
                  'description' => 'Programs dedicated to highlighting key moments, recapping games, and summarizing sports events.',
              ],
              [
                  'name' => 'Sports Documentaries',
                  'description' => 'Documentary-style programs exploring sports-related topics, athlete biographies, historical sports events, and significant moments in sports.',
              ],
              [
                  'name' => 'Training and Instructional Sports Programs',
                  'description' => 'Educational content focusing on sports training, coaching, skill development, and instructional guides for various sports.',
              ],
              [
                  'name' => 'Adventure and Extreme Sports',
                  'description' => 'Coverage of adventure and extreme sports events, including unconventional and high-adrenaline sports activities.',
              ],
              [
                  'name' => 'Youth Sports Programs',
                  'description' => 'Programs focusing on youth involvement in sports, covering youth tournaments, competitions, and sports education for young athletes.',
              ],
            // Additional sub-categories as needed
          ],
          'Drama' => [
              [
                  'name' => 'Ongoing Dramatic Series',
                  'description' => 'Serialized television dramas, featuring continuing storylines and character development across episodes and seasons.',
              ],
              [
                  'name' => 'Specials, Mini-series, and Made-for-TV Feature Films',
                  'description' => 'One-off drama specials, limited-run mini-series, and feature-length dramas produced specifically for television.',
              ],
              [
                  'name' => 'Theatrical Feature Films Aired on Television',
                  'description' => 'Full-length dramatic films originally produced for theatrical release, now broadcast on television.',
              ],
              [
                  'name' => 'Animated Television Programs and Films',
                  'description' => 'Dramatic content in animated form, covering a range of styles and themes, excluding computer graphics without storylines.',
              ],
              [
                  'name' => 'Other Drama',
                  'description' => 'Includes readings, narrative productions, adaptations of live theatre, experimental shorts, and other innovative dramatic forms not specifically developed for television.',
              ],
            // Additional sub-categories as needed
          ],
          'Comedy' => [
              [
                  'name' => 'Ongoing Comedy Series (Sitcoms)',
                  'description' => 'Television series focused on humor and comedic situations, often featuring recurring characters in a sitcom format.',
              ],
              [
                  'name' => 'Sketch Comedy Shows',
                  'description' => 'Programs featuring a series of comedy sketches, improvisational scenes, and other short humorous segments.',
              ],
              [
                  'name' => 'Stand-up Comedy',
                  'description' => 'Shows featuring stand-up comedians performing comedic routines and monologues in front of a live audience.',
              ],
              [
                  'name' => 'Improvisational Comedy',
                  'description' => 'Comedy shows where performers create spontaneous and unscripted comedic scenes, often involving audience participation.',
              ],
              [
                  'name' => 'Other Comedy Forms',
                  'description' => 'Includes a variety of other comedic content such as comedic readings, narratives, puppet shows, and experimental comedy formats.',
              ],
            // Additional sub-categories as needed
          ],
          'Music' => [
              [
                  'name' => 'Live Music Performances',
                  'description' => 'Programs featuring live or pre-recorded music performances, including concerts, recitals, and live studio performances.',
              ],
              [
                  'name' => 'Music Video Clips',
                  'description' => 'Short films or videotape productions showcasing individual music selections, typically featuring artistic or thematic visual material.',
              ],
              [
                  'name' => 'Music Video Programs',
                  'description' => 'Television programs consisting primarily of music videos, potentially including elements like hosting, interviews, and behind-the-scenes content.',
              ],

              [
                  'name' => 'Operas and Musical Theatre',
                  'description' => 'Broadcasts of operas, operettas, and musical theatre productions, combining elements of music, drama, and stage performance.',
              ],
            // Additional sub-categories as needed
          ],
          'Dance' => [
              [
                  'name' => 'Ballet and Dance Performances',
                  'description' => 'Programs showcasing ballet and other dance performances, covering various styles from classical to modern dance, emphasizing the artistry and technique of dance.',
              ],
              [
                  'name' => 'Contemporary Dance Shows',
                  'description' => 'Features contemporary dance performances, highlighting innovative choreography and modern dance expressions.',
              ],
              [
                  'name' => 'Cultural and Folk Dance',
                  'description' => 'Programs focusing on traditional, cultural, and folk dance forms from around the world, celebrating heritage and cultural diversity through dance.',
              ],
              [
                  'name' => 'Dance Competitions',
                  'description' => 'Broadcasts of dance competitions, showcasing the skills and talents of dancers in various styles and genres, from ballroom to street dance.',
              ],
              [
                  'name' => 'Choreography and Behind-the-Scenes',
                  'description' => 'Shows focusing on the process of choreography, including behind-the-scenes looks at dance productions, rehearsals, and the creative process.',
              ],
              [
                  'name' => 'Dance Festivals and Events',
                  'description' => 'Coverage of major dance festivals and events, showcasing performances, workshops, and celebrations of dance culture.',
              ],
            // Additional sub-categories as needed
          ],
          'Variety' => [
              [
                  'name' => 'Talent Showcases',
                  'description' => 'Programs featuring a variety of talents, from singing and dancing to unique acts, often involving a competitive element or audience participation.',
              ],
              [
                  'name' => 'Comedy and Sketch Shows',
                  'description' => 'Variety shows primarily focusing on comedy sketches, improvisations, and comedic performances, featuring different comedians and styles.',
              ],
              [
                  'name' => 'Acrobatic and Circus Performances',
                  'description' => 'Shows that include breathtaking acrobatic acts, circus performances, and other physical feats, showcasing skill and artistry.',
              ],
              [
                  'name' => 'Magic and Illusion Shows',
                  'description' => 'Programs featuring magicians and illusionists, displaying a range of magical acts, from close-up magic to grand illusions.',
              ],
              [
                  'name' => 'Musical Variety Shows',
                  'description' => 'Variety programs with a focus on musical performances, featuring different artists and genres, often combined with other entertainment elements.',
              ],
              [
                  'name' => 'Cultural Variety Programs',
                  'description' => 'Shows that present a mix of cultural performances, including traditional music, dance, and other art forms from various cultures.',
              ],
              [
                  'name' => 'Game and Interactive Shows',
                  'description' => 'Variety programs that include interactive elements such as games, audience participation, and live interactive segments.',
              ],
              [
                  'name' => 'Variety Specials and Galas',
                  'description' => 'Special event programs, including award shows, galas, and variety specials, featuring a mix of performances and segments.',
              ],
            // Additional sub-categories as needed
          ],
          'Games' => [
              [
                  'name' => 'Quiz and Trivia Shows',
                  'description' => 'Programs that feature quizzes and trivia competitions, challenging participants\' knowledge on various topics.',

              ],
              [
                  'name' => 'Strategy and Skill-Based Games',
                  'description' => 'Shows focusing on games that require strategic thinking and skill, including both physical and mental challenges.',
              ],
              [
                  'name' => 'Luck and Chance Games',
                  'description' => 'Game shows where outcomes are heavily based on elements of luck and chance, often involving spinning wheels, dice, or random selections.',
              ],
              [
                  'name' => 'Puzzle and Word Games',
                  'description' => 'Programs centered around puzzles, word games, and problem-solving activities, testing participants\' cognitive abilities and quick thinking.',
              ],
              [
                  'name' => 'Family Game Shows',
                  'description' => 'Game shows designed for family participation or viewing, featuring games suitable for a wide range of ages and fostering family interaction.',
              ],
              [
                  'name' => 'Interactive Game Shows',
                  'description' => 'Shows that involve audience participation or interactive elements, allowing viewers to be a part of the game, either in-studio or remotely.',
              ],
              [
                  'name' => 'Reality-Based Competition Shows',
                  'description' => 'Competitive game shows with a format that incorporates elements of reality TV, focusing on participants\' interactions and strategies.',
              ],
              [
                  'name' => 'Celebrity Game Shows',
                  'description' => 'Games featuring celebrity participants, often combining entertainment and humor with the game format.',
              ],
              // Additional sub-categories as needed
          ],
          'Entertainment' => [
              [
                  'name' => 'Celebrity Profiles and Interviews',
                  'description' => 'Programs featuring profiles of celebrities, including interviews, career retrospectives, and promotional content.',
              ],
              [
                  'name' => 'Entertainment Talk and Interview Shows',
                  'description' => 'Talk shows focusing on entertainment topics, interviews with personalities from the world of showbiz, and discussions about current entertainment trends.',
              ],
              [
                  'name' => 'Award Shows and Galas',
                  'description' => 'Coverage of award ceremonies and gala events, showcasing achievements in various entertainment fields, including behind-the-scenes content.',
              ],
              [
                  'name' => 'Entertainment-Oriented Magazine Shows',
                  'description' => 'Magazine-style programs that cover a wide range of entertainment-related topics, from movie releases to celebrity news.',
              ],
              [
                  'name' => 'Fundraising and Telethon Shows',
                  'description' => 'Programs designed to raise funds for various causes, often featuring entertainers and celebrities, with a mix of performances and appeals for donations.',
              ],
              [
                  'name' => 'Human Interest Stories',
                  'description' => 'Features focusing on compelling human interest stories and real-life experiences, often highlighting personal achievements, challenges, and inspirational tales.',
              ],
              [
                  'name' => 'Community Event Coverage',
                  'description' => 'Broadcasts of community events such as carnivals, festivals, parades, and fashion shows, showcasing local culture and community spirit.',
              ],
              [
                  'name' => 'Lifestyle and Entertainment Shows',
                  'description' => 'Programs that blend lifestyle content with entertainment, covering topics like travel, leisure activities, and the arts.',
              ],
            // Additional sub-categories as needed
          ],
          'Reality' => [
              [
                  'name' => 'Docuseries and Docudramas',
                  'description' => 'Series documenting real-life events, focusing on personal stories, professional endeavors, or specific themes, often presented in a dramatic style.',
              ],
              [
                  'name' => 'Reality Competitions',
                  'description' => 'Competitive reality shows where participants engage in challenges or contests, often in a serialized format with eliminations and a final winner.',
              ],
              [
                  'name' => 'Lifestyle Reality Shows',
                  'description' => 'Programs focusing on lifestyle aspects such as home renovation, cooking, fashion, and personal makeovers, featuring real people and their experiences.',
              ],
              [
                  'name' => 'Adventure and Survival Reality Shows',
                  'description' => 'Reality programming centered around adventure, survival skills, and outdoor challenges in various environments.',
              ],
              [
                  'name' => 'Social Experiment Shows',
                  'description' => 'Reality shows based on social experiments or situational tests, exploring human behavior, social interactions, and reactions in specific scenarios.',
              ],
              [
                  'name' => 'Travel and Exploration Reality Shows',
                  'description' => 'Programs documenting travels, explorations, and adventures in different cultures and locations, featuring real-life experiences and discoveries.',
              ],
              [
                  'name' => 'Occupational and Workplace Reality Shows',
                  'description' => 'Reality shows set in specific professional environments, showcasing the day-to-day operations and challenges of various occupations.',
              ],
              [
                  'name' => 'Relationship and Dating Shows',
                  'description' => 'Reality programs focusing on relationships, dating, and romantic connections, often involving unscripted interactions and drama.',
              ],
              [
                  'name' => 'Hidden Camera and Prank Shows',
                  'description' => 'Programs involving hidden camera setups, pranks, and surprise scenarios, capturing spontaneous reactions of unsuspecting individuals.',
              ],
            // Additional sub-categories as needed
          ],
          'Interstitials' => [
              [
                  'name' => 'Short-Form Entertainment',
                  'description' => 'Brief entertainment segments, including quick comedy skits, music clips, or cultural snippets.',
              ],
              [
                  'name' => 'Mini-Featurettes',
                  'description' => 'Short sequences related to larger programs, offering quick insights or additional context to regular programming.',
              ],
              [
                  'name' => 'Quick News Updates',
                  'description' => 'Brief segments delivering rapid updates or essential highlights from the world of news, offering viewers concise and up-to-date information.',
              ],
              [
                  'name' => 'Quick Sports Updates',
                  'description' => 'Short updates focusing on the latest sports news, scores, and key moments, providing sports enthusiasts with quick and current sports information.',
              ],
              [
                  'name' => 'Artistic Shorts',
                  'description' => 'Brief artistic presentations or performances, including animation, poetry readings, or short monologues.',
              ],
            // Additional sub-categories as needed
          ],
          'PSAs' => [
              [
                  'name' => 'Health and Safety Announcements',
                  'description' => 'Messages focused on public health, safety tips, and wellbeing, aiming to educate and inform viewers.',
              ],
              [
                  'name' => 'Community and Social Awareness',
                  'description' => 'Announcements intended to raise awareness about social issues, community events, and non-profit initiatives.',
              ],
              [
                  'name' => 'Environmental and Conservation Messages',
                  'description' => 'PSAs related to environmental protection, conservation efforts, and promoting sustainable practices.',
              ],
              [
                  'name' => 'Civic and Educational Information',
                  'description' => 'Informative messages regarding civic engagement, educational opportunities, and community programs.',
              ],
            // Additional sub-categories as needed
          ],
          'Promotional' => [
              [
                  'name' => 'Infomercials',
                  'description' => 'Extended advertisements combining information and entertainment, usually promoting a product or service.',
              ],
              [
                  'name' => 'Corporate Videos',
                  'description' => 'Content produced by businesses for promotion, PR, recruitment, or other corporate purposes.',
              ],
              [
                  'name' => 'Product Showcases',
                  'description' => 'Programs primarily designed to showcase and promote various products or services.',
              ],
              [
                  'name' => 'Branded Entertainment',
                  'description' => 'Content that integrates commercial products or brands into entertainment segments.',
              ],
            // Additional sub-categories as needed
          ],
          'Filler' => [
              [
                  'name' => 'Program Previews',
                  'description' => 'Short segments used to promote upcoming shows or episodes, providing viewers with a glimpse of future content.',
              ],
              [
                  'name' => 'Behind-the-Scenes',
                  'description' => 'Brief content showing the making of a program, including interviews, production insights, or backstage footage.',
              ],
              [
                  'name' => 'Short Form Content',
                  'description' => 'Quick, engaging segments, such as artistic performances, cultural displays, or mini-documentaries, used to fill programming gaps.',
              ],
              [
                  'name' => 'Service Announcements',
                  'description' => 'Short messages related to the broadcaster’s services, programming schedules, or viewer information.',
              ],
            // Additional sub-categories as needed
          ],

        // ... other category groups
      ];

      foreach ($subCategoryGroups as $categoryName => $subCategories) {
        $categoryId = $this->getCategoryIdByName($categoryName);
        if ($categoryId) {
          $this->addCategoryId($subCategories, $categoryId);
          DB::table('show_category_subs')->insert($subCategories);
        }
      }
    }

  private function getCategoryIdByName($categoryName)
  {
    $category = DB::table('show_categories')->where('name', $categoryName)->first();
    return $category ? $category->id : null;
  }

  private function addCategoryId(&$subCategoryArray, $categoryId)
  {
    foreach ($subCategoryArray as &$subCategory) {
      $subCategory['show_categories_id'] = $categoryId;
    }
  }
}
