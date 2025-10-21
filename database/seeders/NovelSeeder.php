<?php

namespace Secretwebmaster\WncmsNovels\Database\Seeders;

use Illuminate\Database\Seeder;

class NovelSeeder extends Seeder
{
    public function run(): void
    {
        $this->call('wncms-novels:generate', [
            'count' => 5,
            '--min' => 3,
            '--max' => 6,
        ]);
    }
}
