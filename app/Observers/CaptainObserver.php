<?php
declare (strict_types = 1);
namespace App\Observers;
use App\Models\Captain;
use Illuminate\Support\Facades\Auth;
class CaptainObserver {
    public function created(Captain $captain): void {
        $captain->profile()->create([]);
        $captain->car()->create([]);
        $captain->invite()->create([
            'captain_id' => $captain->id,
            'type' => 'caption',
            'code_invite'=> str_replace(' ', '_', $captain->name) . generateRandom(3),
            'data' => date('Y-m-d'),
        ]);
    }
}
