<?php

use Illuminate\Database\Seeder;

class FaqsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('faqs')->delete();

        DB::table('faqs')->insert([
        	[
            	'question' => 'What is Artistize?',
            	'answer' => 'Artistize is a one-stop platform which connects the artists with art lovers and art seekers. It provides a platform to artists to showcase their talent and art seekers get an opportunity to search and find the right talent through Artistize.',
                'faq_order' => '1'
        	],[
            	'question' => 'Who are the people behind Artistize?',
            	'answer' => 'Artistize is the brainchild of Suresh and Deepa. Suresh, a PhD from the University of Cambridge, is an artist by heart. His struggle to find a music teacher for his son gave him the idea about Artistize. Deepa is the Founder of one of the most renowned recruitment companies in India. She is the strategic thinker and motivator behind Artistize. Read more about us and story on how Artistize evolved at ABOUT US',
                'faq_order' => '2'
        	],[
            	'question' => 'What are the differentiators of Artistize?',
            	'answer' => 'Artistize is unique in many ways –
					•	It is the only platform, which provides networking abilities to artists and art seekers.
					•	It is a common platform for artists of different skill-sets all over the world.
					•	It offers multiple opportunities for talent presentation and evaluation, networking, follow-up, talent search, auctioning of art, etc. 
					•	It is backed by a strong team of recognized advisors and experts.',
                'faq_order' => '3'
        	],[
            	'question' => 'Is Artistize free?',
            	'answer' => 'Absolutely. As an artist, you can register on Artistize for free. Go ahead and create your portfolio, network with others, search for jobs and opportunities, showcase your talent – all for free!',
                'faq_order' => '4'
        	],[
            	'question' => ' Do I have to register to use Artistize?',
            	'answer' => 'Yes. While you can browse through the art works of other artists without the need to register, if you really want to experience the platform completely, you need to register. But don’t worry, the registration is completely free! Once you register, you can create your talent showcase, search and apply for various jobs, create or join groups, and interact with other artists. As an art seeker, once registered, you can search for the right talent and contact/hire them directly.',
                'faq_order' => '1'
        	],[
            	'question' => 'Is this portal region specific? Can I register on Artistize?',
            	'answer' => 'Artistize is open for worldwide audience. Go ahead and register free here!',
                'faq_order' => '5'
        	],[
            	'question' => ' I am an Artist. How can I start using Artistize?',
            	'answer' => 'Very simple – first, register yourself free. Once registered, create your talent portfolio by uploading your work – your work could be pictures, text or videos. Once your portfolio is ready, you can start looking for suitable job opportunities, join relevant groups or simply start interacting with other artists!',
                'faq_order' => '6'
        	],
    	]);           
    }
}
