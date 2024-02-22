<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Faq::truncate();

        $faqs = [
            [
                "question" => "What does the service cost?",
                "answer" => "Authors and co-authors can register in the PenPeers Platform for free."
            ],
            [
                "question" => "How can authors download their completed books?",
                "answer" => "Completed ebooks can be sold on PenPeers or any ebook platform including Amazon Kindle and Google Books."
            ],
            [
                "question" => "Will PenPeers print and publish my book?",
                "answer" => "PenPeers does not print or publish physical books, only ebooks. However, using the Consult skillsbase, PenPeers can guide authors in how to use print-on-demand services and link up with publishers."
            ],
            [
                "question" => "Who is the PenPeers platform for?",
                "answer" => "PenPeers is designed for anyone who wants to co-author a book. An existing group can join the platform to use its tools to co-ordinate the project, or an author with an idea can use the service to find suitable co-authors."
            ],
            [
                "question" => "What subject areas can my book cover?",
                "answer" => "The service is suitable for any book that is suitable for co-authorship in a wide variety of genres, including academic and scientific, courseware, travel guides, history, collections of poetry and short stories. The subject matter must not violate the service’s terms."
            ],
            [
                "question" => "How do I find suitable co-authors for a writing project?",
                "answer" => "You can pick from the authors page on PenPeers or you can invite other co-authors by choosing non-registered on PenPeers . For more info, please check the user guide ."
            ],
            [
                "question" => "How can I find a writing project to join?",
                "answer" => "Search in the Books section for open projects of interest. To join a writing project, register as a user, sign in and make a request to the project owner."
            ],
            [
                "question" => "What languages are supported?",
                "answer" => "The PenPeer terms allow for books in Arabic, English, French, German and Spanish. Other languages will be supported in the future."
            ],
            [
                "question" => "How can I get professional help to complete a book?",
                "answer" => "If you need professional help, use the Consult form to request the skills required, and PenPeers will find suitable candidates. A skills marketplace will be added to the service soon."
            ],
            [
                "question" => "What happens when the author's subscription expires ?",
                "answer" => "When the subscription expires, the author has a 2 months grace period that he can work with his existing books but he can't create any new books. After this period, his account will be hold"
            ]
        ];

        Faq::insert($faqs);
    }
}

