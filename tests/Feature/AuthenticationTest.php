<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_IfGiven_UserCredentials_ShouldValidate_AndReturnSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])->json('POST', '/api/auth/register', [
            "firstname" => "vidya",
            "lastname" => "gowda",
            "email" => "vidyagowda@gmail.com",
            "password" => "Vidya@123",
            "confirm_password" => "Vidya@123"
        ]);
        $response->assertStatus(201)->assertJson(['message' => 'User successfully registered']);

    }

    public function test_IfGiven_UserCredentialsSame_ShouldValidate_AndReturnErrorStatus()
    {
        
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])->json('POST', '/api/auth/register', [
            "firstname" => "vidya",
            "lastname" => "gowda",
            "email" => "vidyagowda@gmail.com",
            "password" => "Vidya@123",
            "confirm_password" => "Vidya@123"
        ]);

        $response->assertStatus(401)->assertJson(['message' => 'The email has already been taken']);

    }

    public function test_IfGiven_LoginCredentials_ShouldValidate_AndReturnSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])->json('POST', '/api/auth/login', 
        [
            "email" => "vidyagowda@gmail.com",
            "password" => "Vidya@123",
        ]);

        $response->assertStatus(200)->assertJson(['message' => 'Login successfull']);
    }

    public function test_IfGiven_WrongLoginCredentials_ShouldValidate_AndReturnErrorStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])->json('POST', '/api/auth/login', 
        [
            "email" => "sample123@gmail.com",
            "password" => "sample@123",
        ]);

        $response->assertStatus(404)->assertJson(['message' => 'we can not find the user with that e-mail address']);
    }

    public function test_IfGiven_AccessToken_ShouldValidate_AndReturnSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzMzQwNTA5OSwiZXhwIjoxNjMzNDA4Njk5LCJuYmYiOjE2MzM0MDUwOTksImp0aSI6IjBqa3E2czZ0dldFZkhFdUwiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.UuO5NcMmIkoyspv65keSXcK9fktH7PyG7zY4u80o2dQ'
        ])->json('POST', '/api/auth/logout');
        
        $response->assertStatus(201)->assertJson(['message'=> 'User successfully signed out']);
    }

    public function test_IfGiven_ForgottenMail_ShouldValidate_AndReturnSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'email' => 'balupinisetty@gmail.com'
        ])->json('POST', '/api/auth/forgotpassword');
        
        $response->assertStatus(200)->assertJson(['message'=> 'we have emailed your password reset link to respective mail']);
    }
}
