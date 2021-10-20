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
            "email" => "vidyagowda123@gmail.com",
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
            "email" => "vidyagowda123@gmail.com",
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

    public function test_IfGiven_LoginAccessToken_ShouldValidate_AndReturnSuccessStatus()
    {
        $this->withoutExceptionHandling();
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzNDYzMzkxMCwiZXhwIjoxNjM0NjM3NTEwLCJuYmYiOjE2MzQ2MzM5MTAsImp0aSI6InJtVTYwT0ZYVElRcm5TcmQiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.5-f-AMv7vwpNTf8bwREZcZWihx5-cYcJ0__GSon4iDA'
        ])->json('POST', '/api/auth/logout');
        
        $response->assertStatus(201)->assertJson(['message'=> 'User successfully signed out']);
    }

    public function test_IfGiven_WrongLoginAccessToken_ShouldValidate_AndReturnErrorStatus()
    {
        $this->withoutExceptionHandling();
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzNDYzMzkxMCwiZXhwIjoxNjM0NjM3NTEwLCJuYmYiOjE2MzQ2MzM5MTAsImp0aSI6InJtVTYwT0ZYVElRcm5TcmQiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.5-f-AMv7vwpNTf8bwREZcZWihx5-cYcJ0__GSon4iDA'
        ])->json('POST', '/api/auth/logout');
        
        $response->assertStatus(404)->assertJson(['message'=> 'Invalid authorization token']);
    }

    public function test_IfGiven_ForgottenMail_ShouldValidate_AndReturnSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])->json('POST', '/api/auth/forgotpassword', [
            "email" => "balupinisetty@gmail.com"
        ]);
        
        $response->assertStatus(205)->assertJson(['message'=> 'we have emailed your password reset link to respective mail']);
    }

    public function test_IfGiven_WrongForgottenMail_ShouldValidate_AndReturnErrorStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])->json('POST', '/api/auth/forgotpassword', [
            "email" => "balu@gmail.com"
        ]);
        
        $response->assertStatus(404)->assertJson(['message'=> 'we can not find a user with that email address']);
    }
}
