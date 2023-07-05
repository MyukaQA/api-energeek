<?php

namespace Database\Seeders;

use App\Models\Job;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Job::insert([
            [
                'name' => 'Frontend Web Programmer',
            ],
            [
                'name' => 'Fullstack Web Programmer',
            ],
            [
                'name' => 'Quality Control',
            ],
        ]);
    }
}
