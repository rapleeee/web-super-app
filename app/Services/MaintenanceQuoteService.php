<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class MaintenanceQuoteService
{
    /**
     * @return array{text: string, author: string}
     */
    public function randomQuote(): array
    {
        if (app()->environment('testing')) {
            return $this->fallbackQuote();
        }

        return Cache::remember('app_maintenance_quote', now()->addMinutes(15), function (): array {
            return $this->fetchFromApi() ?? $this->fallbackQuote();
        });
    }

    /**
     * @return array{text: string, author: string}|null
     */
    private function fetchFromApi(): ?array
    {
        try {
            $response = Http::acceptJson()
                ->timeout(2)
                ->get('https://zenquotes.io/api/random');
        } catch (\Throwable) {
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        $payload = $response->json();

        if (! is_array($payload) || ! isset($payload[0]) || ! is_array($payload[0])) {
            return null;
        }

        $text = trim((string) ($payload[0]['q'] ?? ''));
        $author = trim((string) ($payload[0]['a'] ?? ''));

        if ($text === '') {
            return null;
        }

        return [
            'text' => $text,
            'author' => $author !== '' ? $author : 'Anonim',
        ];
    }

    /**
     * @return array{text: string, author: string}
     */
    private function fallbackQuote(): array
    {
        $quotes = [
            ['text' => 'Kemajuan kecil yang konsisten akan mengalahkan rencana besar yang tidak dijalankan.', 'author' => 'Unknown'],
            ['text' => 'Kualitas bukan kebetulan, melainkan hasil dari niat yang jelas dan eksekusi yang disiplin.', 'author' => 'Unknown'],
            ['text' => 'Sistem yang baik dibangun dari perbaikan kecil yang dilakukan berulang.', 'author' => 'Unknown'],
            ['text' => 'Fokus pada hal yang bisa dikendalikan, lalu lakukan dengan tuntas.', 'author' => 'Unknown'],
            ['text' => 'Jangan buru-buru terlihat cepat, pastikan dulu arahnya benar.', 'author' => 'Unknown'],
        ];

        return $quotes[array_rand($quotes)];
    }
}
