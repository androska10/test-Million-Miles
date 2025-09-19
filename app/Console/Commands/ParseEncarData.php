<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ParsingService;
use Illuminate\Support\Facades\Log;

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
        Log::info('🚀 Starting ENCAR data parsing...'); 

        try {
            $saved = $parser->parseAll();
            Log::info("✅ Successfully saved {$saved} cars."); 
        } catch (\Exception $e) {
            Log::error('❌ Error: ' . $e->getMessage()); 
            return 1;
        }
    }
}
