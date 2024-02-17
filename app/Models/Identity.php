<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\OAuthUserInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class Identity extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function findOrCreate(OAuthUserInterface $user, $userId = null) {
        $identity = Identity::where('oauth_id', $user->getId())
                ->where('oauth_provider', $user->getProvider())
                ->first();

        if(!$identity) {
            return Identity::create([
                'oauth_id' => $user->getId(),
                'oauth_provider' => $user->getProvider(),
                'user_id' => $userId ?? Auth::id(),
            ]);
        }

        //Check for identity mismatch
        if($identity->user_id != $userId) {
            throw new \Exception('Identity mismatch');
        }

        return $identity;
    }
}
