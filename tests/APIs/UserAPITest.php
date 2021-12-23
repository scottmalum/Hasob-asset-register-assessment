<?php

namespace Tests\APIs;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\ApiTestTrait;

class UserAPITest extends TestCase
{
    // DatabaseTransactions
    use ApiTestTrait, WithoutMiddleware, RefreshDatabase;

    /**
     * @test
     */
    public function test_register_user()
    {
        $user = [
            'email' => 'user1@gmail.com',
            'password' => 'Test123',
            'password_confirmation' => 'Test123',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '2345666',
        ];

        $this->response = $this->json(
            'POST',
            '/api/v1/users',
            $user
        );

        $this->assertApiSuccess();
        $this->response->assertSeeText('User registration is successful.');
    }

    /**
     * @test
     */
    public function test_fetch_all_users()
    {
        User::create([
            'email' => 'user1@gmail.com',
            'password' => Hash::make('Test123'),
        ]);

        User::create([
            'email' => 'user2@gmail.com',
            'password' => Hash::make('Test123'),
        ]);

        $this->response = $this->json(
            'GET',
            '/api/v1/users',
        );

        $this->assertApiSuccess();
        $this->response->assertSeeText('All users');
    }

    /**
     * @test
     */
    public function test_fetch_failed_if_user_does_not_exist()
    {
        $this->response = $this->json(
            'GET',
            '/api/v1/users/1',
        );

        $this->response->assertStatus(404);
        $this->response->assertSeeText('User with ID: 1 is not found');
    }

    /**
     * @test
     */
    public function test_fetch_single_user()
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

        $this->response = $this->json(
            'GET',
            '/api/v1/users/' . $user->id,
        );

        $this->assertApiSuccess();
        $this->response->assertSeeText('User details');
    }

    /**
     * @test
     */
    public function test_disable_user()
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

        $this->response = $this->json(
            'PATCH',
            '/api/v1/users/' . $user->id . '/disable',
        );

        $message = $user->is_disabled ? 'disabled' : 'enabled';

        $this->assertApiSuccess();
        $this->response->assertSeeText("Account successfully {$message}");
    }

    /**
     * @test
     */
    public function test_update_user()
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

        $this->response = $this->json(
            'PUT',
            '/api/v1/users/' . $user->id,
            [
                'first_name' => 'Mark',
                'last_name' => 'Bro',
            ]
        );

        $this->assertApiSuccess();
        $this->response->assertSeeText("User profile successfully updated");
    }

    /**
     * @test
     */
    public function test_delete_user()
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

        $this->response = $this->json(
            'DELETE',
            '/api/v1/users/' . $user->id
        );

        $this->assertApiSuccess();
        $this->response->assertSeeText("User successfully deleted");
    }
}
