<?php

namespace App\Jobs;

use App\Models\Player;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GeneratePlayerQrJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Player $player)
    {
    }

    public function handle(): void
    {
        $player = Player::find($this->player->id); // <--- RELOAD FRESCO DO DB
        if (empty($player->address)) {
            return;
        }

        $address = json_decode(json_encode($player->address), true);

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
            $filename = (string) Str::uuid() . '.png';
            Storage::disk('public')->put("qrcodes/{$filename}", $response->body());

            // SALVA usando player "fresco"
            $player->qr_code_path = "qrcodes/{$filename}";
            $player->save(); // <- método mais direto que update()
        }

        \Log::info('GenerateUserQrJob updated path', [
            'player_id' => $player->id,
            'qr_code_path' => $player->qr_code_path,
        ]);
    }
}
