<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Services\LineAuthService;
use App\Adapters\GoogleUser;
use App\Adapters\LineUser;
use App\Contracts\OAuthUserInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\Services\RoleAssignmentService;

class OAuthController extends Controller
{
    
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function redirectToLine()
    {
        return LineAuthService::initiate()->redirect();
    }

    public function loginThroughGoogle() 
    {
        return self::authenticate(GoogleUser::class, function() {
            return Socialite::driver('google')->user();
        });
    }

    public function loginThroughLine() 
    {
        return self::authenticate(LineUser::class, function() {
            return LineAuthService::initiate()->user();
        });
    }

    public function register(Request $request) {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'register_type' => 'required|in:work,hire,both',
            'avatar' => 'nullable|string|url',
        ];
        
        if ($request->input('register_type') !== 'work') {
            $rules['company_name'] = 'required|string|max:255';
        } else {
            $rules['company_name'] = 'nullable|string|max:255';
        }
        
        $validated = $request->validate($rules);

        $user = User::create($validated);
        if($user) {
            RoleAssignmentService::assignRole($user, $request);
            event(new Registered($user));
            Auth::login($user);
        }
        return to_route('dashboard');
    }

    private static function authenticate(string $adapter, callable $userCallback) {
        try {
            if(!class_exists($adapter)) {
                throw new \Exception("Class {$adapter} does not exist.");
            }
            if(!is_subclass_of($adapter, OAuthUserInterface::class)) {
                throw new \Exception("Class {$adapter} does not implement OAuthUserInterface.");
            }

            $user = $userCallback();
            $OAuthUser = new $adapter($user);
            $user = User::fromOAuthUser($OAuthUser);

            if(!$user) {
                session(['oauth_user' => [
                    'name' => $OAuthUser->getName(),
                    'email' => $OAuthUser->getEmail(),
                    'avatar' => $OAuthUser->getAvatar(),
                ]]);
                return to_route('oauth.register',['oauth'=> true]);
            }

            Auth::login($user);
            
        } catch (\Exception $e) {
            if($e->getMessage() === 'Identity mismatch') {
                return to_route('login')->withErrors("This $adapter account is already linked to another user.");
            }
            return to_route('login')->withInput();
        }

        return to_route('dashboard');
    }
}
