<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Candidate;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Best Computer Science Student',
                'description' => 'Awarded to the most outstanding computer science student who has shown exceptional academic performance and practical skills.',
                'sort_order' => 1,
                'candidates' => [
                    ['name' => 'Adebayo Johnson', 'bio' => '400-level Computer Science student with a 4.8 GPA. Active in ACM chapter and open-source projects.'],
                    ['name' => 'Fatima Bello', 'bio' => '300-level student with multiple hackathon wins. Leads the university coding club.'],
                    ['name' => 'Chinedu Okafor', 'bio' => 'Final year student specializing in AI/ML. Published two research papers.'],
                    ['name' => 'Amina Ibrahim', 'bio' => '400-level student with outstanding academic records. Google Developer Student Lead.'],
                ],
            ],
            [
                'name' => 'Most Popular Student',
                'description' => 'Recognizing the most well-known and loved student across the department.',
                'sort_order' => 2,
                'candidates' => [
                    ['name' => 'Oluwaseun Adeyemi', 'bio' => 'Known for organizing campus events and bringing students together.'],
                    ['name' => 'Grace Eze', 'bio' => 'SUG Senate member and social media influencer with over 10k followers.'],
                    ['name' => 'Musa Abdullahi', 'bio' => 'Sports captain and class representative. Always ready to help.'],
                    ['name' => 'Blessing Okoro', 'bio' => 'Event coordinator and founder of the campus volunteer network.'],
                ],
            ],
            [
                'name' => 'Best Project of the Year',
                'description' => 'For the most innovative and impactful student project completed this academic year.',
                'sort_order' => 3,
                'candidates' => [
                    ['name' => 'Team Alpha - Smart Campus App', 'bio' => 'A mobile app that digitizes campus services including hostel booking, result checking, and event management.'],
                    ['name' => 'Team Beta - AgriTech Solution', 'bio' => 'IoT-based farming solution that monitors soil conditions and automates irrigation.'],
                    ['name' => 'Team Gamma - EduBridge', 'bio' => 'An AI-powered tutoring platform that connects students with peer tutors.'],
                ],
            ],
            [
                'name' => 'Best NACOS Executive',
                'description' => 'Recognizing the NACOS executive member who has made the most impact this tenure.',
                'sort_order' => 4,
                'candidates' => [
                    ['name' => 'David Ogundimu', 'bio' => 'NACOS President who spearheaded the coding bootcamp initiative.'],
                    ['name' => 'Sandra Nwachukwu', 'bio' => 'Vice President who organized the annual tech conference.'],
                    ['name' => 'Ibrahim Yusuf', 'bio' => 'General Secretary who maintained excellent documentation and communication.'],
                    ['name' => 'Chioma Agu', 'bio' => 'Financial Secretary who managed funds transparently and grew the treasury.'],
                ],
            ],
            [
                'name' => 'Most Innovative Student',
                'description' => 'For the student who has demonstrated exceptional creativity and innovation in technology.',
                'sort_order' => 5,
                'candidates' => [
                    ['name' => 'Emeka Nnamdi', 'bio' => 'Built a blockchain-based voting system for student elections.'],
                    ['name' => 'Halima Sani', 'bio' => 'Developed a sign language to text converter using computer vision.'],
                    ['name' => 'Tunde Bakare', 'bio' => 'Created an open-source framework for rapid API development in PHP.'],
                ],
            ],
            [
                'name' => 'Best Dressed Student',
                'description' => 'For the student with the most impressive and consistent fashion sense on campus.',
                'sort_order' => 6,
                'candidates' => [
                    ['name' => 'Kemi Afolabi', 'bio' => 'Fashion enthusiast known for unique Ankara and modern fusion styles.'],
                    ['name' => 'Daniel Obi', 'bio' => 'Always spotted in sharp suits and trendy streetwear.'],
                    ['name' => 'Zainab Mohammed', 'bio' => 'Modest fashion icon who runs a popular style blog.'],
                    ['name' => 'Victor Emenike', 'bio' => 'Sneakerhead and fashion influencer with a growing brand.'],
                ],
            ],
        ];

        foreach ($categories as $catData) {
            $candidates = $catData['candidates'];
            unset($catData['candidates']);

            $category = Category::create($catData);

            foreach ($candidates as $candData) {
                $candData['category_id'] = $category->id;
                $candData['vote_count'] = rand(5, 150); // Random seed votes
                Candidate::create($candData);
            }
        }
    }
}
