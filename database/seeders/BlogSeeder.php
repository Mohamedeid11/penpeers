<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Blog::factory()->count(10)->create();

        $feeds = [
           "1" => [
                "title" => "Chemistry breakthrough ",
                "description" => "Advances in chemistry continue to bring new materials into the realm of recyclability,
                 and new work from a team at the University of Michigan has taken aim at one of the most problematic to reuse.
                  The scientists have developed a method of converting waste PVC (polyvinyl chloride) into usable products,
                   opening up some interesting new possibilities when it comes to this traditionally unrecyclable material.
                    PVC sits in the top handful of plastics in terms of production and volume, and is put to use in everything from piping and flooring,
                     to shower curtains and clothes. Its recycling rate in the US, however, sits at zero, with efforts to recycle the material hindered by its toxic contents.",
                "image" => "feeds/blog-1.png",
            ],

            "2" => [
                "title" => "Nations reach 'historic' deal to protect nature",
                "description" => "Nations have agreed to protect a third of the planet for nature by 2030 in a landmark deal aimed at safeguarding biodiversity.
                    There will also be targets for protecting vital ecosystems such as rainforests and wetlands and the rights of indigenous peoples.
                    The agreement at the COP15 UN biodiversity summit in Montreal, Canada, came early on Monday morning.
                    The summit had been moved from China and postponed due to Covid.
                    China, which was in charge of the meeting, brought down the gavel on the deal despite a last minute objection from the Democratic Republic of Congo.",
                "image" => "feeds/2.png",
            ],
            "3" => [
                "title" => "Extinction is not a foregone conclusion",
                "description" => "The wastefulness of extinction is perhaps best highlighted by the near-extinction of the humpback whale.
                    Over-hunting almost wiped out these gigantic creatures, among the largest to ever have lived on the planet, and the humpback population crashed to just 5,000 in 1966.
                    The extraordinary organisms featured above, along with the sustainable engineering designs they have inspired, present a compelling case for why we must preserve biodiversity.
                    The organisms that create the support systems make all life on Earth, including human life, possible: millions of species are at risk, but losing even a single species can have enormous negative consequences for humanity.",
                "image" => "feeds/3.png",
            ],
            "4" => [
                "title" => "Rick And Morty Creator Used Controversial AI Art, Voice Acting In New Shooter",
                "description" => "As the debate around AI “art” continues to rage on sites like ArtStation, Twitter, and Reddit (and likely in the comments below this very article) it’s already being used in commercial projects. Most recently,
                 Justin Roland of Squinch Games and Rick And Morty fame confirmed that his newest project, the comedic first-person shooter High on Life, used a machine-learning algorithm to create poster art and even a vocal performance.",
                "image" => "feeds/4.png",
            ],
            "5" => [
                "title" => "Tor Tried to Hide AI Art on a Book Cover, and It Is a Mess",
                "description" => "A few weeks ago, right here on io9, readers got their first look at the cover for Christopher Paolini’s newest Fractalverse novel, Fractal Noise. Almost immediately,
                    people could see something was off about the artwork used on the cover.
                    With some quick deduction, Twitter users realized that it was AI-generated art, which was originally posted to a stock art site by a user named Ufuk Kaya. It was also arranged by the in-house designer,
                     who is not credited—something unusual for Tor, as the publisher often promotes the art directors and designers of each cover.",
                "image" => "feeds/5.png",
            ],
            "6" => [
                "title" => "Fortnite settles child privacy and trickery claims",
                "description" => 'The Federal Trade Commission (FTC) said the firm duped players with "deceptive interfaces" that could trigger purchases while the game loaded.
                    It also accused it of using "privacy-invasive" default settings.
                    Epic Games blamed "past designs"."No developer creates a game with the intention of ending up here,"
                     the company said. "We accepted this agreement because we want Epic to be at the forefront of consumer protection and provide the best experience for our players."',
                "image" => "feeds/6.png",
            ],
            "7" => [
                "title" => "Virtual Reality Statistics to Know in 2023",
                "description" => "Today’s immersive hardware customers have ever-increasing access to sleeker, more engaging virtual Reality (VR) experiences and marketplace statistics reflect that interest.
                    Over the last couple of years, VR has grown so dramatically users see the technology in all areas of life. Thanks to increasing consumer and enterprise brands,
                     the immersive market is growing with use cases that cover retail, tourism, training, and much more.
                    According to a report by Emerged Research,
                     VR technology will propel the extended reality (XR) market to roughly $1,246.57 billion by 2025. Moreover, the report forecasts the XR marketplace’s CAGR will jump to 24.2 percent in the same year.",
                "image" => "feeds/7.png",
            ],
            "8" => [
                "title" => "Digital libraries",
                "description" => 'Digital libraries are libraries that house digital resources. They are defined as an organization and not a service that provide access to digital works,
                 have a preservation responsibility to provide future access to materials, and provides these items easily and affordably.
                 The definition of a digital library implies that "a digital library uses a variety of software, networking technologies and standards to facilitate access to digital content and data to a designated user community.
                  Access to digital libraries can be influenced by several factors, either individually or together. The most common factors that influence access are: The library\'s content, the characteristics and information needs of the target users,
                   the library\'s digital interface, the goals and objectives of the library\'s organizational structure, and the standards and regulations that govern library use.',
                "image" => "feeds/8.png",
            ],
            "9" => [
                "title" => "Could Space-based Satellites Power Remote Mines?",
                "description" => "Many space-based technologies are still looking for their “killer app” – the thing that they do better than anything else and makes them indispensable to whoever needs to have that app to solve a problem.
                    At this point in the development of humanity, most of those killer apps will involve solving a problem back on Earth. Space-based solar power satellites are certainly one of those technologies.
                     They have the potential to fundamentally transform the energy industry here on Earth. But they need that one “killer app” to get people interested in investing in them.
                      A study from a group of researchers at the Colorado School of Mines looked at one potential use case – powering remote mining sites that aren’t connected to any electric grid. Unfortunately,
                     even at those extremes, solar power satellites aren’t yet economical enough to warrant the investment.",
                "image" => "feeds/9.png",
            ],
            "10" => [
                "title" => "Hubble Views a Star-Studded Cosmic Cloud",
                "description" => "A portion of the open cluster NGC 6530 appears as a roiling wall of smoke studded with stars in this image from the NASA/ESA Hubble Space Telescope.
                    NGC 6530 is a collection of several thousand stars lying around 4,350 light-years from Earth in the constellation Sagittarius. The cluster is set within the larger Lagoon Nebula,
                     a gigantic interstellar cloud of gas and dust. Hubble has previously imaged the Lagoon Nebula several times, including these images released in 2010 and 2011.
                      It is the nebula that gives this image its distinctly smoky appearance; clouds of interstellar gas and dust stretch from one side of the image to the other.
                      Astronomers investigated NGC 6530 using Hubble’s Advanced Camera for Surveys and Wide Field Planetary Camera 2. They scoured the region in the hope of finding new examples of proplyds,.",
                "image" => "feeds/10.png",
            ],

        ];
        foreach ($feeds as $feed)
        {
            Blog::factory()->create([
                "title" => $feed['title'],
                "description" => $feed['description'],
                "image" => $feed['image'],
                "approved" => true,
            ]);
        }



    }
}
