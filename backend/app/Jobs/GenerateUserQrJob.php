<?php

namespace App\Jobs;

use App\Models\Player;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Support\Formatters\AddressFormatter;

class GenerateUserQrJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Player $player)
    {
    }

    public function handle(): void
    {
        if (empty($this->player->address)) {
            return;
        }

        $address = json_decode(json_encode($this->player->address), true);

        $data = implode(', ', array_filter([
            $address['street'] ?? null,
            $address['city'] ?? null,
            $address['province'] ?? null,
            $address['postal_code'] ?? null,
            'Canada',
        ]));

        if (!$data) {
            return;
        }

        $googleMapsUrl = 'https://maps.google.com/?q=' . urlencode($data);

        $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/';
        $response = Http::get($qrApiUrl, [
            'size' => '200x200',
            'data' => $googleMapsUrl,
        ]);

        if ($response->successful()) {
            $filename = $this->player->hash . '.png';
            Storage::disk('public')->put("qrcodes/{$filename}", $response->body());
            $this->player->update(['qr_code_path' => "qrcodes/{$filename}"]);
        }
    }


}
