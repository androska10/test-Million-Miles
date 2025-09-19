<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ParsingService;

class ParseEncarData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'encar:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse car data from ENCAR';

    /**
     * Execute the console command.
     */
    public function handle(ParsingService $parser)
    {
        $this->info('🚀 Starting ENCAR data parsing...');

        try {
            $saved = $parser->parseAll(5); // 5 страниц
            $this->info("✅ Successfully saved {$saved} cars.");
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }
    }
}
