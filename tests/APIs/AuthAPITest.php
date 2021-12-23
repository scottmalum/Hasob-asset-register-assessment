<?php

namespace Tests\APIs;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\ApiTestTrait;

class AuthAPITest extends TestCase
{
    // DatabaseTransactions
    use ApiTestTrait, WithoutMiddleware, RefreshDatabase;

    /**
     * @test
     */
    public function test_register_admin()
    {
        $admin = [
            'email' => 'admin1@gmail.com',
            'password' => 'Test123',
            'password_confirmation' => 'Test123',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '2345666',
        ];

        $this->response = $this->json(
            'POST',
            '/api/v1/auth/register',
            $admin
        );

        $this->assertApiSuccess();
        $this->response->assertSeeText('Admin registration is successful.');
    }

    /**
     * @test
     */
    public function test_not_able_to_login_with_wrong_credentials()
    {
        User::create([
            'email' => 'john@gmail.com',
            'password' => Hash::make('Test123'),
        ]);

        $loginCredentials = [
            'email' => 'john@gmail.com',
            'password' => 'Test1234'
        ];

        $this->response = $this->json(
            'POST',
            '/api/v1/auth/login',
            $loginCredentials
        );

        $this->response->assertUnauthorized();
        $this->response->assertSeeText('Unauthorized');
    }

    /**
     * @test
     */
    public function test_not_able_to_login_if_email_is_not_verified()
    {
        User::create([
            'email' => 'john@gmail.com',
            'password' => Hash::make('Test123'),
        ]);

        $loginCredentials = [
            'email' => 'john@gmail.com',
            'password' => 'Test123'
        ];

        $this->response = $this->json(
            'POST',
            '/api/v1/auth/login',
            $loginCredentials
        );

        $this->response->assertStatus(403);
        $this->response->assertSeeText('Your account is not verified yet');
    }

    /**
     * @test
     */
    public function test_able_to_login_if_email_is_verified()
    {
        $user = new User();
        $user->email = 'john@gmail.com';
        $user->password = Hash::make('Test123');
        $user->email_verified_at = now();
        $user->save();

        UserProfile::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '2345666',
            'user_id' => $user->id
        ]);

        $loginCredentials = [
            'email' => 'john@gmail.com',
            'password' => 'Test123'
        ];

        $this->response = $this->json(
            'POST',
            '/api/v1/auth/login',
            $loginCredentials
        );

        $this->response->assertStatus(200);
        $this->assertArrayHasKey('access_token', $this->response['data']);
    }
}
