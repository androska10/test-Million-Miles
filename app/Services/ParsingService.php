<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Car;

class ParsingService {

    private string $baseURL = 'https://api.encar.com/search/car/list/general';

    
    protected function fetchData(int $page=1, int $perPage = 50): array
    {
        $start = ($page - 1) * $perPage;

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept' => 'application/json',
                    'Referer' => 'https://www.encar.com/',
                ])
                ->get($this->baseURL, [
                    'q' => '(And.(And.Hidden.N._.CarType.Y.)_.AdType.A.)',
                    'sr' => '|ModifiedDate|0|8',
                    'start' => $start,
                    'length' => $perPage,
                ]);

            if ($response->failed()) {
                throw new \Exception('HTTP ' . $response->status() . ': ' . $response->body());
            }

             $data = $response->json();

            
            $cars = $data['SearchResults'] ?? [];

            Log::info("Fetched " . count($cars) . " cars from page {$page} (start: {$start})");

            return $cars;

        }   catch (\Throwable $e) {
            Log::error('ENCAR API fetch failed: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function saveData (array $items): int
    {
        $count = 0;

        

        foreach ($items as $item) {
            $firstPhoto = $item['Photos'][0]['location'] ?? null;
            if ($firstPhoto) {
               
                $firstPhoto = 'https://ci.encar.com' . $firstPhoto;
            }

            $year = (int) substr((string)($item['Year'] ?? 0), 0, 4);

            Car::updateOrCreate(
                ['external_id' => $item['Id'] ?? null], // ← уникальный ID
                [
                    'brand'       => $item['Manufacturer'] ?? 'Unknown',
                    'model'       => trim(($item['Model'] ?? '') . ' ' . ($item['Badge'] ?? '') . ' ' . ($item['BadgeDetail'] ?? '')),
                    'year'        => $year,
                    'mileage'     => (int) ($item['Mileage'] ?? 0),
                    'price'       => (int) ($item['Price'] ?? 0), 
                    'image_url'   => $firstPhoto,
                    'data'        => $item,
                ]
            );
            $count++;
        }

        Log::info("Saved {$count} cars to DB.");

        return $count;
    }

    public function parseAll (int $maxPage = 5): int 
    {
        $totalSaved = 0;

        for ($page = 1; $page <= $maxPage; $page++) {
            $items = $this->fetchData($page);
            if (empty($items)) break;

            $totalSaved += $this->saveData($items);

            usleep (500000);
        }   

        return $totalSaved;
    }
}