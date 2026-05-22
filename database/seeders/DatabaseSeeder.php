<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserPhone;
use App\Models\Member;
use App\Models\Trainer;
use App\Models\Gym;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\FeedbackGym;
use App\Models\FeedbackTrainer;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a Member
        $memberUser = User::create([
            'name' => 'John Doe',
            'email' => 'member@fitsphere.test',
            'password' => Hash::make('password'),
            'role' => 'member'
        ]);
        UserPhone::create(['user_id' => $memberUser->id, 'phone' => '+15551234567']);
        $member1 = Member::create([
            'user_id' => $memberUser->id,
            'birth_date' => '1995-05-15',
            'fitness_goal' => 'Build muscle and lose fat',
            'weight' => 85.5,
            'height' => 180
        ]);

        $memberUser2 = User::create([
            'name' => 'Alice Smith',
            'email' => 'alice@fitsphere.test',
            'password' => Hash::make('password'),
            'role' => 'member'
        ]);
        UserPhone::create(['user_id' => $memberUser2->id, 'phone' => '+15559876543']);
        Member::create([
            'user_id' => $memberUser2->id,
            'birth_date' => '1992-08-20',
            'fitness_goal' => 'Improve cardiovascular health',
            'weight' => 65.0,
            'height' => 165
        ]);

        // 2. Create Trainers
        $trainerData = [
            ['name' => 'Sarah Chen', 'spec' => 'Yoga & Mindfulness', 'price' => 55, 'rating' => 5, 'location' => 'Los Angeles, CA', 'clients' => 46, 'sessions' => 1500, 'exp' => 6],
            ['name' => 'Elena Vance', 'spec' => 'Pilates & Rehab', 'price' => 65, 'rating' => 4, 'location' => 'New York, NY', 'clients' => 30, 'sessions' => 1200, 'exp' => 8],
            ['name' => 'Maya Gupta', 'spec' => 'Functional & Kettlebell', 'price' => 50, 'rating' => 5, 'location' => 'Austin, TX', 'clients' => 55, 'sessions' => 2000, 'exp' => 5],
        ];

        $trainers = [];
        foreach ($trainerData as $idx => $tData) {
            $tUser = User::create([
                'name' => $tData['name'],
                'email' => "trainer{$idx}@fitsphere.test",
                'password' => Hash::make('password'),
                'role' => 'trainer'
            ]);
            UserPhone::create(['user_id' => $tUser->id, 'phone' => "+1555000100{$idx}"]);
            $trainer = Trainer::create([
                'user_id' => $tUser->id,
                'experience_years' => $tData['exp'],
                'bio' => "Expert in {$tData['spec']} focusing on mobility and structural integrity.",
                'birth_date' => '1985-11-10',
                'specialization' => $tData['spec'],
                'price_per_month' => $tData['price'],
                'location' => $tData['location'],
                'certifications' => ['ACE Certified', 'NASM Performance Enhancement'],
                'total_sessions' => $tData['sessions'],
                'active_clients' => $tData['clients'],
            ]);
            $trainers[] = $trainer;

            // Add Feedback for Trainer
            FeedbackTrainer::create([
                'member_id' => $member1->id,
                'trainer_id' => $trainer->id,
                'rating' => $tData['rating'],
                'comment' => 'Great trainer, highly recommended!'
            ]);
        }

        // 3. Create Gyms
        $gymData = [
            ['name' => 'FitSphere Elite', 'city' => 'Cairo', 'street' => 'Downtown', 'price' => 300, 'rating' => 5, 'features' => ['24/7 ACCESS', 'PERSONAL TRAINING', 'SAUNA']],
            ['name' => 'PowerHouse Gym', 'city' => 'Cairo', 'street' => 'Zamalek', 'price' => 250, 'rating' => 4, 'features' => ['MMA AREA', 'POOL', 'CAFE']],
            ['name' => 'Urban Iron', 'city' => 'Cairo', 'street' => 'Maadi', 'price' => 200, 'rating' => 5, 'features' => ['CROSSFIT', 'DUMBBELLS UP TO 100KG', 'RECOVERY ZONE']],
        ];

        $gyms = [];
        foreach ($gymData as $idx => $gData) {
            $gUser = User::create([
                'name' => $gData['name'],
                'email' => "gym{$idx}@fitsphere.test",
                'password' => Hash::make('password'),
                'role' => 'gym'
            ]);
            UserPhone::create(['user_id' => $gUser->id, 'phone' => "+1555200100{$idx}"]);
            $gym = Gym::create([
                'user_id' => $gUser->id,
                'city' => $gData['city'],
                'street_name' => $gData['street'],
                'description' => "Welcome to {$gData['name']}, the best place to train.",
                'closed_days' => 'None',
                'manager_name' => 'Admin',
                'manager_email' => "admin@gym{$idx}.test",
                'features' => $gData['features'],
                'price_per_session' => $gData['price']
            ]);
            $gyms[] = $gym;

            // Add Feedback for Gym
            FeedbackGym::create([
                'member_id' => $member1->id,
                'gym_id' => $gym->id,
                'rating' => $gData['rating'],
                'comment' => 'Amazing facility with great equipment!'
            ]);
        }

        // 4. Create Product Categories
        $catSupplements = ProductCategory::create(['name' => 'Supplements']);
        $catGear = ProductCategory::create(['name' => 'Gear']);
        $catApparel = ProductCategory::create(['name' => 'Apparel']);

        // 5. Create Products
        Product::create([
            'category_id' => $catSupplements->id,
            'name' => 'HydroWhey Protein Isolate',
            'product_price' => 54.99,
            'stock' => 100,
            'description' => 'Ultra-pure protein with rapid absorption for maximum muscle recovery.',
        ]);
        
        Product::create([
            'category_id' => $catSupplements->id,
            'name' => 'Ignition Pre-Workout',
            'product_price' => 34.99,
            'stock' => 50,
            'description' => 'Explosive energy and focus for your toughest workouts.',
        ]);

        Product::create([
            'category_id' => $catApparel->id,
            'name' => 'Apex Training Tee',
            'product_price' => 38.00,
            'stock' => 200,
            'description' => 'Moisture-wicking fabric with 4-way stretch for unrestricted movement.',
        ]);

        Product::create([
            'category_id' => $catGear->id,
            'name' => 'Pro-Grip Band Set',
            'product_price' => 24.99,
            'stock' => 75,
            'description' => '5 resistance levels ranging from light to extra-heavy with carry bag.',
        ]);
        
        Product::create([
            'category_id' => $catGear->id,
            'name' => 'Hybrid Shaker Pro',
            'product_price' => 14.99,
            'stock' => 150,
            'description' => 'Leak-proof shaker bottle with integrated supplement storage.',
        ]);
    }
}
