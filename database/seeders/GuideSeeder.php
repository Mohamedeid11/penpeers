<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Guide;
use Illuminate\Database\Seeder;

class GuideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Guide::truncate();

        $guides = [
            [
                "name" => "What does the service cost? And how many books can I create?",
                "explanation" => "Authors and co-authors can register on PenPeers Platform for 15 days free trial, then subscribe to one of our plans: 100USD Annually or 250USD Triennially for unlimited number of books."
            ],
            [
                "name" => "How can authors download their completed books?",
                "explanation" => "Completed books can be downloaded as PDF file."
            ],
            [
                "name" => "Who is the PenPeers platform for?",
                "explanation" => "PenPeers is designed for anyone who wants to co-author a book. An existing group can join the platform to use its tools to co-ordinate the project, or an author with an idea can use the service to find suitable co-authors."
            ],
            [
                "name" => "What subject areas can my book cover?",
                "explanation" => "The service is suitable for any book that is suitable for co-authorship in a wide variety of genres, including history and science, technology, mystery, romance and science fiction. The subject matter must not violate the service’s terms."
            ],
            [
                "name" => "How do I find suitable co-authors for a writing project?",
                "explanation" => "You can pick from the authors page on PenPeers or you can invite other co-authors by choosing non-registered on PenPeers .  For more info, please check the user guide ."
            ],
            [
                "name" => "How can I find a writing project to join?",
                "explanation" => "Search in the Books section for open projects of interest. To join a writing project, register as a user, sign in and make a request to the project owner."
            ],
            [
                "name" => "What languages are supported?",
                "explanation" => "The PenPeer terms allow for books in Arabic and English. Other languages will be supported in the future."
            ],
            [
                "name" => "How can I get professional help to complete a book?",
                "explanation" => "If you need professional help, use the Consult form to request the skills required, and PenPeers will find suitable candidates. A skills marketplace will be added to the service soon."
            ],
            [
                "question" => "What happens when the author's subscription expires ?",
                "answer" => "When the subscription expires, the author has a 2 months grace period that he can work with his existing books but he can't create any new books. After this period, his account will be hold"
            ],
            [
                "question" => "How do I learn more about the editor?",
                "answer" => "To learn more about the editor click <a
                    href=\"../public/editor-guide.pdf\" target='_blank' style='color: #ce7852; text-decoration: underline;'>here</a>"
            ],
            [
                "question" => "How can I get a public access to any completed book on PenPeers?",
                "answer" => "You can send buying request to the book author, for more details check the user guide"
            ]
        ];

        Guide::insert($guides);
    }
}
