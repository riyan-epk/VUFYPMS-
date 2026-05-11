<?php

namespace Database\Seeders;

use App\Models\ProjectDomain;
use Illuminate\Database\Seeder;

class ProjectDomainSeeder extends Seeder
{
    public function run(): void
    {
        $domains = [
            ['name' => 'Artificial Intelligence', 'description' => 'Machine learning, deep learning, neural networks, and AI-driven applications.'],
            ['name' => 'Web Development', 'description' => 'Front-end, back-end, and full-stack web applications using modern frameworks.'],
            ['name' => 'Mobile Application Development', 'description' => 'Android, iOS, and cross-platform mobile app development.'],
            ['name' => 'Database Systems', 'description' => 'Database design, optimization, data warehousing, and big data solutions.'],
            ['name' => 'Cybersecurity', 'description' => 'Network security, ethical hacking, encryption, and secure software development.'],
            ['name' => 'Internet of Things (IoT)', 'description' => 'Smart devices, embedded systems, sensors, and connected hardware solutions.'],
            ['name' => 'Cloud Computing', 'description' => 'Cloud-based applications, microservices, serverless computing, and DevOps.'],
            ['name' => 'Data Science & Analytics', 'description' => 'Data mining, visualization, statistical analysis, and business intelligence.'],
            ['name' => 'Software Engineering', 'description' => 'SDLC, agile methodologies, testing, software architecture, and design patterns.'],
            ['name' => 'Human-Computer Interaction', 'description' => 'UI/UX design, accessibility, usability testing, and interaction design.'],
            ['name' => 'Blockchain Technology', 'description' => 'Distributed ledger, smart contracts, cryptocurrency, and decentralized apps.'],
            ['name' => 'Computer Vision', 'description' => 'Image processing, object detection, facial recognition, and video analysis.'],
        ];

        foreach ($domains as $domain) {
            ProjectDomain::firstOrCreate(['name' => $domain['name']], array_merge($domain, ['is_active' => true]));
        }
    }
}
