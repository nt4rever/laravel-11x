<?php

namespace Tests;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

trait AuthenticatedUser
{
    public function setUpUser(array $attributes = [])
    {
        $this->logout();

        $this->user = User::factory()->create($attributes);

        $this->login();

        return $this;
    }

    public function login()
    {
        Sanctum::actingAs(
            $this->user,
            ['*']
        );
        // $this->actingAs($this->user);
    }

    public function logout()
    {
        $this->user = null;
    }
}
