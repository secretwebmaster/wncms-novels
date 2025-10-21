<?php

namespace Secretwebmaster\WncmsNovels\Console\Commands;

use Illuminate\Console\Command;
use Faker\Factory as Faker;

class GenerateDemoNovels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Usage:
     * php artisan wncms-novels:generate {count=5} {--min=3} {--max=5}
     */
    protected $signature = 'wncms-novels:generate {count=5} {--min=3} {--max=5}';

    /**
     * The console command description.
     */
    protected $description = 'Generate demo novels and chapters using Faker in English';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $count = (int) $this->argument('count');
        $minChapterCount = (int) $this->option('min');
        $maxChapterCount = (int) $this->option('max');

        $this->generateNovels($count, $minChapterCount, $maxChapterCount);
    }

    /**
     * Generate demo novels and chapters.
     */
    protected function generateNovels(int $count, int $minChapterCount = 3, int $maxChapterCount = 5): void
    {
        $faker = Faker::create('en_US');

        $novelModel   = wncms()->package('wncms-novels')->model('novel');
        $chapterModel = wncms()->package('wncms-novels')->model('chapter');

        for ($i = 1; $i <= $count; $i++) {
            $title = $faker->catchPhrase();
            $slug  = wncms()->getUniqueSlug('novels');

            $novel = $novelModel::create([
                'title'         => $title,
                'slug'          => $slug,
                'description'   => $faker->realTextBetween(100, 150),
                'status'        => 'published',
                'series_status' => $faker->randomElement([0, 1]),
                'published_at'  => now(),
                'expired_at'    => null,
                'author'        => $faker->name,
            ]);

            $chapterCount = rand($minChapterCount, $maxChapterCount);

            for ($j = 1; $j <= $chapterCount; $j++) {
                $chapterTitle = 'Chapter ' . $j . ': ' . $faker->catchPhrase();
                $chapterSlug  = wncms()->getUniqueSlug('novels');

                $paragraphs = [];
                for ($p = 0; $p < rand(5, 9); $p++) {
                    $paragraphs[] = $faker->paragraph(rand(5, 8));
                }

                $content = implode("\n\n", $paragraphs);

                $chapterModel::create([
                    'novel_id'     => $novel->id,
                    'title'        => $chapterTitle,
                    'slug'         => $chapterSlug,
                    'content'      => $content,
                    'status'       => 'published',
                    'number'       => $j,
                    'published_at' => now(),
                    'expired_at'   => null,
                    'author'       => $novel->author,
                ]);
            }

            $novel->update(['chapter_count' => $chapterCount]);
            $this->info("Created novel: {$novel->title} ({$chapterCount} chapters)");
        }
    }
}
