<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AuthenticatedUser;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, AuthenticatedUser;

    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }
}
