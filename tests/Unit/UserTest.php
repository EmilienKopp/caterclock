<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Can an unverified user be inserted into the database?
     */
    public function can_create_unverified_user(): void
    {
        $user = User::factory()->unverified()->create();
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }
}
