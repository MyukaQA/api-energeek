<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Skill::insert([
            [
                'name' => 'PHP',
            ],
            [
                'name' => 'PosgreSQL',
            ],
            [
                'name' => 'API (JSON, REST)',
            ],
            [
                'name' => 'Version Control System (Gitlab, Github)',
            ],
        ]);
    }
}
