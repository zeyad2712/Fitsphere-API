<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Muscle;
use App\Models\VideoCategory;
use App\Models\Video;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Muscles
        $muscleFullBody = Muscle::firstOrCreate(['name' => 'Full Body']);
        $muscleChest = Muscle::firstOrCreate(['name' => 'Chest']);
        $muscleBack = Muscle::firstOrCreate(['name' => 'Back']);
        $muscleArms = Muscle::firstOrCreate(['name' => 'Arms']);
        $muscleAbs = Muscle::firstOrCreate(['name' => 'Abs']);
        $muscleCore = Muscle::firstOrCreate(['name' => 'Core']);
        $muscleLegs = Muscle::firstOrCreate(['name' => 'Legs']);
        $muscleGlutes = Muscle::firstOrCreate(['name' => 'Glutes']);
        $muscleHips = Muscle::firstOrCreate(['name' => 'Hips']);
        $muscleLowerBack = Muscle::firstOrCreate(['name' => 'Lower Back']);
        $muscleJoints = Muscle::firstOrCreate(['name' => 'Joints']);
        $muscleShoulders = Muscle::firstOrCreate(['name' => 'Shoulders']);

        // 2. Create Categories
        $catWorkout = VideoCategory::firstOrCreate(['name' => 'Workout']);
        $catRecovery = VideoCategory::firstOrCreate(['name' => 'Recovery']);

        // 3. Create Videos - Workouts
        $workouts = [
            ['title' => 'Full Body HIIT Blast', 'url' => 'https://www.youtube.com/embed/ml6cT4AZdqI', 'duration' => 900, 'desc' => 'A high-intensity interval training session designed to torch calories and build overall endurance.', 'muscle_id' => $muscleFullBody->id],
            ['title' => 'Upper Body Strength Building', 'url' => 'https://www.youtube.com/embed/ml6cT4AZdqI', 'duration' => 1200, 'desc' => 'Focus on building strength and definition in your chest, back, and arms.', 'muscle_id' => $muscleChest->id],
            ['title' => 'Intense Core Crusher', 'url' => 'https://www.youtube.com/embed/ml6cT4AZdqI', 'duration' => 600, 'desc' => 'Develop a strong and stable core with this intense circuit.', 'muscle_id' => $muscleCore->id],
            ['title' => 'Lower Body Power', 'url' => 'https://www.youtube.com/embed/ml6cT4AZdqI', 'duration' => 1500, 'desc' => 'Build explosive power and strength in your legs and glutes.', 'muscle_id' => $muscleLegs->id],
            ['title' => 'Back & Biceps Focus', 'url' => 'https://www.youtube.com/embed/ml6cT4AZdqI', 'duration' => 1800, 'desc' => 'Targeted workout for pulling muscles to improve posture and back strength.', 'muscle_id' => $muscleBack->id],
        ];

        foreach ($workouts as $w) {
            Video::create([
                'category_id' => $catWorkout->id,
                'title' => $w['title'],
                'url' => $w['url'],
                'duration' => $w['duration'],
                'description' => $w['desc'],
                'muscle_id' => $w['muscle_id'],
            ]);
        }

        // 4. Create Videos - Recovery
        $recoveries = [
            ['title' => 'Post-Workout Deep Stretching', 'url' => 'https://www.youtube.com/embed/ml6cT4AZdqI', 'duration' => 900, 'desc' => 'Essential passive stretches to aid long-term recovery and flexibility.', 'muscle_id' => $muscleFullBody->id],
            ['title' => 'Yoga for Muscle Relief', 'url' => 'https://www.youtube.com/embed/ml6cT4AZdqI', 'duration' => 1800, 'desc' => 'Gentle restorative yoga flows explicitly aimed at reducing muscle tension.', 'muscle_id' => $muscleLowerBack->id],
            ['title' => 'Active Recovery Mobility Flow', 'url' => 'https://www.youtube.com/embed/ml6cT4AZdqI', 'duration' => 1200, 'desc' => 'Light, low-impact movements designed to keep your joints healthy.', 'muscle_id' => $muscleJoints->id],
            ['title' => 'Shoulder & Neck Release', 'url' => 'https://www.youtube.com/embed/ml6cT4AZdqI', 'duration' => 600, 'desc' => 'Quick routine to relieve tension stored in the upper body.', 'muscle_id' => $muscleShoulders->id],
            ['title' => 'Hip Mobility Routine', 'url' => 'https://www.youtube.com/embed/ml6cT4AZdqI', 'duration' => 900, 'desc' => 'Open up tight hips to improve squat depth and overall lower body function.', 'muscle_id' => $muscleHips->id],
        ];

        foreach ($recoveries as $r) {
            Video::create([
                'category_id' => $catRecovery->id,
                'title' => $r['title'],
                'url' => $r['url'],
                'duration' => $r['duration'],
                'description' => $r['desc'],
                'muscle_id' => $r['muscle_id'],
            ]);
        }
    }
}
