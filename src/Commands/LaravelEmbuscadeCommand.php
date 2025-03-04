<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Commands;

use Illuminate\Console\Command;

class LaravelEmbuscadeCommand extends Command
{
    public $signature = 'laravel-embuscade';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
