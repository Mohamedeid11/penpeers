<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookEdition;
use App\Models\BookPublishRequest;
use App\Models\BookRole;
use App\Models\Genre;
use App\Models\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use PhpParser\Node\NullableType;


class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $books = [
            "2" => [
                "title" => "Our World Our Life",
                "description" => "In this scientifically informed account of the changes in nature over the last century,
                 award-winning broadcaster and natural historian David Attenborough shares a lifetime of wisdom and a hopeful vision for the future.
                 Goodreads Choice Award Winner for Best Science & Technology Book of the Year*",
                "genre_id" => Genre::where('name' , "History")->first()->id,
                "front_cover" => "defaults/1.jpg",
                "back_cover" => "defaults/back.png",
            ],
            "3" => [
                "title" => "War of Dragon",
                "description" => "The shadows of evil sweep down across the peaceful land of Argonath as the Masters prepare to unleash dread monstrosities on the world,
                    and only Relkin and dragon Bazil Broketail stand between the forces of darkness and Argonath's survival.",
                "genre_id" => Genre::where('name' , "Science Fiction")->first()->id,
                "front_cover" => "defaults/5.jpg",
                "back_cover" => "defaults/back.png",
            ],
            "4" => [
                "title" => "Art Of Illustrator",
                "description" => "Expand your graphics toolkit and delve into the complexity of Adobe Illustrator with the practical and time-tested techniques, tips,
                    and tricks of an Adobe Certified Expert, featureing all new content or Illustrator CS6",
                "genre_id" => Genre::where('name' , "History")->first()->id,
                "front_cover" => "defaults/8.jpg",
                "back_cover" => "defaults/back.png",
            ],
            "5" => [
                "title" => "The Wild Beauty",
                "description" => "Praise for Wild Beauty: “No one does magical realism quite like McLemore, and this third novel, laced with slow-burning suspense,
                        folklore, romance, and spun together with exquisite, luxuriant prose, proves it. .",
                "genre_id" => Genre::where('name' , "Science Fiction")->first()->id,
                "front_cover" => "defaults/The-Wild-Beuaty-Front.jpg",
                "back_cover" => "defaults/The-Wild-Beuaty-Back.jpg",
            ],
            "6" => [
                "title" => "Mystery of Universe",
                "description" => "Engaging storybook-style descriptions that explain key discoveries about the universe.
                 More to Explore Once you've discovered The Mysteries of the Universe, dive into the companion titles from this series from DK Books!",
                "genre_id" => Genre::where('name' , "Mystery")->first()->id,
                "front_cover" => "defaults/Mistry-of-Univers-Front.jpg",
                "back_cover" => "defaults/Mistry-of-Univers-Back.jpg",
            ],
            "7" => [
                "title" => "Magazine",
                "description" => "Magazine is the treasured photographic magazine that chronicled the 20th Century. It now lives on at LIFE.com, the largest,
                        most amazing collection of professional photography on the internet.",
                "genre_id" => Genre::where('name' , "Art")->first()->id,
                "front_cover" => "defaults/Magazine-Front.jpg",
                "back_cover" => "defaults/Magazine-Back.jpg",
            ],
            "8" => [
                "title" => "Living In The Light",
                "description" => "Living in the Lightis a comprehensive map to growth, fulfillment, and consciousness. As we grapple with personal, national,
                    and global challenges on many fronts, this classic work is timelier than ever.",
                "genre_id" => Genre::where('name' , "Romance")->first()->id,
                "front_cover" => "defaults/Living-In-The-Light-Front.jpg",
                "back_cover" => "defaults/Living-In-The-Light-Back.jpg",
            ],
            "9" => [
                "title" => "Memorise",
                "description" => "I would call this a discover - and- memorise strategy for generating adaptive novel behav- iors .
                    Obtaining new functionalities by a discover - and- memorise strategy should be compared to the more fa- miliar way of obtaining novel .",
                "genre_id" => Genre::where('name' , "Mystery")->first()->id,
                "front_cover" => "defaults/4.jpg",
                "back_cover" => "defaults/back.png",
            ],
            "10" => [
                "title" => "حياة الصحراء",
                "description" => "إن الصورة التقليدية الراسخة في عقل أبناء المدن الحديثة عن «حياة الصحراء »،
                هي العيش في الخيام والتنقل بالإبل، ضمن حدود غير واضحة، تراعى فقط التحالفات القائمة بين القبائل. وقد ترحل القبيلة ،وتترك موقعها مع ماشيتها في مواسم
                ،فتهيم في أرجاء الصحراء بحثاً عن العشب والمياه،وعن المكان الأفضل لرعى الأغنام التي يشرب حليبها ويصنع منه الجبن،
                 ومن صوفها ينسج الأبسطة وأقمشة الخيام ،فإنها ثروته.
                 وبدلاً من تأثيث منزل دائم ومكتمل بكل اللوازم مثلما في المدن",
                "genre_id" => Genre::where('name' , "Literature")->first()->id,
                "front_cover" => "defaults/Desert-Life-Front.jpg",
                "back_cover" => "defaults/Desert-Life-Back.jpg",
            ],
            "11" => [
                "title" => "Realistic Drawing Secrets",
                "description" => "This is the book that can teach anyone to draw (yes, even you!) If you're not getting the kind of true-to-life results you want in your drawings (or if you can't even draw a straight line), Carrie and Rick Parks can help.",
                "genre_id" => Genre::where('name' , "Art")->first()->id,
                "front_cover" => "defaults/drawing-front.png",
                "back_cover" => "defaults/back.png",
            ],
            "12" => [
                "title" => "Newspaper",
                "description" => "Forerunners of the modern newspaper include the Acta diurna (“daily acts”) of ancient Rome—posted announcements of political
                    and social events—and manuscript newsletters circulated in the late Middle Ages by various international traders, among them the Fugger family of Augsburg.",
                "genre_id" => Genre::where('name' , "Sport")->first()->id,
                "front_cover" => "defaults/Newspaper-Front.jpg",
                "back_cover" => "defaults/Newspaper-Back.jpg",
            ],
            "13" => [
                "title" => "The Declaration of Faith",
                "description" => Str::random(300),
                "genre_id" => Genre::where('name' , "History")->first()->id,
                "front_cover" => "defaults/The-Declaration-of-Faith.png",
                "back_cover" => "defaults/The-Declaration-of-Faith.png",
            ],
            "14" => [
                "title" => "Mazha Thulli",
                "description" => "SONGS Mazha Thulli Thulli 2. Hemandhathin 3. Ormayundoo , 4. Pooviyil Mayangum - SEDE 15151 SATHYAVAN SAVITHRI P. C. Sree Vardhini Productions ,
                        55 Usman Road Madras - 17 35 mm Col : 3998.34 Me : 146 Min : C.C. No.",
                "genre_id" => Genre::where('name' , "Science Fiction")->first()->id,
                "front_cover" => "defaults/Mazha-Thulli-Front.jpg",
                "back_cover" => "defaults/Mazha-Thulli-Back.jpg",
            ],
            "15" => [
                "title" => "OliO",
                "description" => "AN OLIO OF VERSE . . IN A LIBRARY . TREAD softly here , as ye would tread In presence of the honored dead , With reverent step and low - bowed head .
                    Speak low - as low as ye would speak Before some saint of grandeur meek , Whose favor .",
                "genre_id" => Genre::where('name' , "Children's Literature")->first()->id,
                "front_cover" => "defaults/7.jpg",
                "back_cover" => "defaults/back.png",
            ],
            "16" => [
                "title" => "New World for Children",
                "description" => "n “The Cartographers,” the main character works for a company that creates and sells virtual memories,
                       while struggling to maintain a real-world relationship sabotaged by an addiction to his own creations.",
                "genre_id" => Genre::where('name' , "History")->first()->id,
                "front_cover" => "defaults/9.jpg",
                "back_cover" => "defaults/back.png",
            ],
            "17" => [
                "title" => "Blue in the water",
                "description" => "With watercolors gorgeous enough to wade in by award-winning artist Meilo So and playful,
                    moving poems by Kate Coombs, Water Sings Blue evokes the beauty and power, the depth and mystery,
                     and the endless resonance of the sea.",
                "genre_id" => Genre::where('name' , "Science Fiction")->first()->id,
                "front_cover" => "defaults/2.jpg",
                "back_cover" => "defaults/back.png",
            ],
            "18" => [
                "title" => "Animals Life",
                "description" => "More than three thousand full-color photographs, accompanied by detailed captions, sidebars, and explanations,
                    provide a definitive look at the animal kingdom, in a volume, organized by behavioral traits, that examines every aspect of animal behavior,
                    including courtship rituals, family relationships, hunting techniques, feeding habits, and defense mechanisms.",
                "genre_id" => Genre::where('name' , "Mystery")->first()->id,
                "front_cover" => "defaults/3.jpg",
                "back_cover" => "defaults/back.png",
            ],
            "19" => [
                "title" => "Moon Light Shadow",
                "description" => "A dangerous discovery in the Malaysian Jungle coincides with a desperate journey that culminates in death and destruction at the hands of psychotic criminals.
                    This fast moving plot twists and turns right through to the final page.",
                "genre_id" => Genre::where('name' , "Literature")->first()->id,
                "front_cover" => "defaults/6.jpg",
                "back_cover" => "defaults/back.png",
            ],
            "20" => [
                "title" => "Alone Walker",
                "description" => "Total value of land alone and irrigation system , estimated by Secretary Total bonded indebtedness ,
                    including all bonds authorized . Last assessed value land alone for purposes of taxation ..
                     Tax rate per $ 100 of assessed valuation .",
                "genre_id" => Genre::where('name' , "Art")->first()->id,
                "front_cover" => "defaults/10.jpg",
                "back_cover" => "defaults/back.png",
            ],
            "21" => [
                "title" => "The Hunter House",
                "description" => "Seven years ago the Hunter House Academy for Girls was closed down after the horrific
                    murder of a popular teacher and the disappearance of the school's 'golden girl', Melissa Somerville.",
                "genre_id" => Genre::where('name' , "Mystery")->first()->id,
                "front_cover" => "defaults/6.jpg",
                "back_cover" => "defaults/back.png",
            ],
            "22" => [
                "title" => "Kids World",
                "description" => "An encyclopedic guide to music for young people features biographies of musical superstars throughout history;
                        musical awards; music festivals; music for stage, screen, and television; famous people and their favorite music; and more.",
                "genre_id" => Genre::where('name' , "Children's Literature")->first()->id,
                "front_cover" => "defaults/Kids-Front.jpg",
                "back_cover" => "defaults/Kids-Back.jpg",
            ],
            "1" => [
                "title" => "HAPPY FAMILY",
                "description" => "Happy Families making piece in the world.
                 Originally the book is written by talented writers, this summary to the social study of the family was designed
                  both for students of sociology and for students of related subjects requiring familiarity with a similar approach.
                   It is therefore written in language as simple as possible; technical terms are only introduced when indispensable
                    and are always defined. While the book is focused on Arabic and Indian family systems, the author believed these
                    are intelligible only when placed in a wider context, and so the first part is concerned with kinship, marriage
                    and the family in general. She does not attempt to provide a descriptive account of all the empirical studies
                     available but concentrates on what he considers the chief theoretical problems. In consequence this book is
                     argumentative and critical in approach, and never strays far from the central issues of sociological theory;
                      it is, therefore, of value to both students of sociology and to others interested in the perspective which
                       the discipline can give to the study of the family.",
                "genre_id" => Genre::where('name' , "Romance")->first()->id,
                "front_cover" => "defaults/Family-Front.png",
                "back_cover" => "defaults/Family-Back.png",
            ],
        ];
        $completed_books = array("Our World Our Life"
        ,"War of Dragon"
        ,"Art Of Illustrator"
        ,"The Wild Beauty"
        ,"Mystery of Universe"
        ,"Magazine"
        ,"Living In The Light"
        ,"Memorise"
        ,"حياة الصحراء"
        ,"Realistic Drawing Secrets"
        );

        foreach ($books as $index => $book) {

            if (in_array($book['title'] , $completed_books) ) {

                $completed = 1;
                $status = true;

            }else{

                $completed = 0;
                $status = false;
            }
            Book::factory()->create([
                'title' => $book['title'],
                'description' => $book['description'],
                'front_cover' => $book['front_cover'],
                'back_cover' => $book['back_cover'],
                'genre_id' => $book['genre_id'],
//                'genre_id' => Genre::inRandomOrder()->first()->id,
                'language' => $book['title'] == "حياة الصحراء" ? 'ar' : 'en',
                'popular' => true,
                'visibility' => 'public',
                'slug' => SlugService::createSlug(Book::class, 'slug', $book['title']),
                'completed' => $book['title'] == "HAPPY FAMILY" ? 1 : $completed,
                'editing_status_changed_at' => ($completed == 1) ?Carbon::now()->format('Y-m-d') : Null,
                'status' => ($completed == 1) ? $status : false,
                'status_changed_at' => ($completed == true) ?Carbon::now()->format('Y-m-d') : Null,
            ]);
        }

        Book::factory()->count(10)->create();

        foreach (Book::all() as $book) {
            $known_users = [ 'mohamed' , 'islam' , 'nahla' , 'penpeers' , 'ali' ];

            $mohamed_books = ["HAPPY FAMILY","Our World Our Life", "War of Dragon", "Art Of Illustrator", "The Wild Beauty"];
            $penpeers_books = ["Mystery of Universe", "Magazine", "Living In The Light"];

            if (in_array($book->title, $mohamed_books)) {
                $lead_author_id = User::where('username', 'mohamed')->first()->id;

                if ($book->title ==  "Our World Our Life" || $book->title == "War of Dragon" || $book->title == "The Wild Beauty"){

                    $book->participants()->attach(User::whereIn('username', ["ali" , "nahla"])->pluck('id'),
                        [
                            'book_role_id' => BookRole::where(['name' => 'co_author'])->first()->id,
                            'status' => 1,
                            'answered_at' => Carbon::now()->addMinutes(60)
                        ]
                    );

                }elseif($book->title ==  "Art Of Illustrator"){

                    $book->participants()->attach(User::whereIn('username', ["ali" , "islam"])->pluck('id'),
                        [
                            'book_role_id' => BookRole::where(['name' => 'co_author'])->first()->id,
                            'status' => 1,
                            'answered_at' => Carbon::now()->addMinutes(60)
                        ]
                    );
                }elseif($book->title ==  "HAPPY FAMILY"){

                    $book->participants()->attach(User::whereIn('username',[ 'islam' , 'nahla' , 'penpeers' , 'ali' ,'denis'] )->pluck('id'),
                        [
                            'book_role_id' => BookRole::where(['name' => 'co_author'])->first()->id,
                            'status' => 1,
                            'answered_at' => Carbon::now()->addMinutes(60)
                        ]
                    );
                }

            } elseif (in_array($book->title, $penpeers_books)) {
                if ($book->title ==  "Living In The Light"){
                    $lead_author_id = User::where('username', 'penpeers')->first()->id;
                    $book->participants()->attach(User::whereIn('username', ["islam", "mohamed"])->pluck('id'),
                        [
                            'book_role_id' => BookRole::where(['name' => 'co_author'])->first()->id,
                            'status' => 1,
                            'answered_at' => Carbon::now()->addMinutes(60)
                        ]
                    );

                }else {

                    $lead_author_id = User::where('username', 'penpeers')->first()->id;
                    $book->participants()->attach(User::whereIn('username', ["nahla", "mohamed"])->pluck('id'),
                        [
                            'book_role_id' => BookRole::where(['name' => 'co_author'])->first()->id,
                            'status' => 1,
                            'answered_at' => Carbon::now()->addMinutes(60)
                        ]
                    );
                }
            } elseif ($book->title == "Memorise") {

                $lead_author_id = User::where('username', 'nahla')->first()->id;
                $book->participants()->attach(User::whereIn('username', ["islam", "penpeers"])->pluck('id'),
                    [
                        'book_role_id' => BookRole::where(['name' => 'co_author'])->first()->id,
                        'status' => 1,
                        'answered_at' => Carbon::now()->addMinutes(60)
                    ]
                );

            } elseif ($book->title == "حياة الصحراء") {

                $lead_author_id = User::where('username', 'islam')->first()->id;
                $book->participants()->attach(User::whereIn('username' , ["nahla", "mohamed"])->pluck('id'),
                    [
                        'book_role_id' => BookRole::where(['name' => 'co_author'])->first()->id,
                        'status' => 1 ,
                        'answered_at' =>  Carbon::now()->addMinutes(60)
                    ]
                );

            } elseif ($book->title == "Realistic Drawing Secrets") {

                $lead_author_id = User::where('username', 'ali')->first()->id;

                $book->participants()->attach(User::whereIn('username' , ["penpeers", "mohamed"])->pluck('id'),
                    [
                        'book_role_id' => BookRole::where(['name' => 'co_author'])->first()->id,
                        'status' => 1 ,
                        'answered_at' =>  Carbon::now()->addMinutes(60)
                    ]
                );

            }else{
                $lead_author_id =  User::whereIn('username' , $known_users)->inRandomOrder()->first()->id;

                $rand_status = array_rand([ 0 , 1 , 2 ]);

                $book->participants()->attach(User::whereIn('username' , $known_users)->inRandomOrder()->where('id', '!=',  $lead_author_id)->limit(2)->pluck('id'),
                    [
                        'book_role_id' => BookRole::where(['name' => 'co_author'])->first()->id,
                        'status' => $rand_status ,
                        'answered_at' => ($rand_status == 0) ? Null : Carbon::now()->addMinutes(60)
                    ]
                );
            }


            $book->participants()->attach($lead_author_id,
                ['book_role_id' => BookRole::where(['name' => 'lead_author'])->first()->id, 'status' => 1]
            );

            BookEdition::factory()->create([
                'book_id' => $book->id,
                'edition_number' => 1,
                'original_price' => 100,
                'discount_price' => 50,
                'publication_date' => date('Y-m-d'),
                'status' => 1,
                'status_changed_at' => date('Y-m-d'),
                'is_hidden' => 1,
                'is_hidden_changed_at' => date('Y-m-d')
            ]);

            // BookEdition::factory()->create([
            //     'book_id' => $book->id,
            //     'edition_number' => 2,
            //     'original_price' => 200,
            //     'discount_price' => 100
            // ]);
        }
    }
}
