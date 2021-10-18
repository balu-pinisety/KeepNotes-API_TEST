<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NoteTest extends TestCase
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

    public function test_IfGiven_TitleAndDescription_ShouldValidate_AndReturnSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzMzQwNTM4NSwiZXhwIjoxNjMzNDA4OTg1LCJuYmYiOjE2MzM0MDUzODUsImp0aSI6IjBVb3JOME9qT3l6enM3SXIiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.jvWpR2qN1ykUjsjbNWzTdk4DfY4En7nL0orPt7I2j80'
        ])->json('POST', '/api/auth/createnote', 
        [
            "title" => "title test two",
            "description" => "description test two",
        ]);

        $response->assertStatus(201)->assertJson(['message' => 'notes created successfully']);
    }

    public function test_IfGiven_WrongAccessToken_ShouldReturnInvalidStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzMzM2OTU5NCwiZXhwIjoxNjMzMzczMTk0LCJuYmYiOjE2MzMzNjk1OTQsImp0aSI6InQ0MHo5djlXNFNGOUZRblEiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.t09kohx5hV15u8aAH-WXAsdnzmh9HsAqCOLci9QKyQ'
        ])->json('POST', '/api/auth/createnote', 
        [
            "title" => "title test one",
            "description" => "description test one",
        ]);

        $response->assertStatus(404)->assertJson(['message' => 'Invalid authorization token']);
    }

    public function test_IfGiven_Id_TitleAndDescription_ShouldValidate_AndReturnSuccess_UpdateStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzMzQwNTM4NSwiZXhwIjoxNjMzNDA4OTg1LCJuYmYiOjE2MzM0MDUzODUsImp0aSI6IjBVb3JOME9qT3l6enM3SXIiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.jvWpR2qN1ykUjsjbNWzTdk4DfY4En7nL0orPt7I2j80'
        ])->json('PUT', '/api/auth/updatenote', 
        [
            "id" => "7",
            "title" => "title test two update",
            "description" => "description test two update",
        ]);
        $response->assertStatus(201)->assertJson(['message' => 'Note updated Sucessfully']);
    }

    public function test_IfGiven_WrongId_TitleAndDescription_ShouldValidate_AndReturnNotes_NotFoundStatus()
     {
         $response = $this->withHeaders([
             'Content-Type' => 'Application/json',
             'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzMzQwNTM4NSwiZXhwIjoxNjMzNDA4OTg1LCJuYmYiOjE2MzM0MDUzODUsImp0aSI6IjBVb3JOME9qT3l6enM3SXIiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.jvWpR2qN1ykUjsjbNWzTdk4DfY4En7nL0orPt7I2j80'
         ])->json('PUT', '/api/auth/updatenote', 
         [
             "id" => "20",
             "title" => "titleupdate",
             "description" => "description test one update",
         ]);
         $response->assertStatus(404)->assertJson(['message' => 'Notes not Found']);
     }

     public function test_IfGiven_Id_ShouldValidate_AndReturn_Delete_SuccessStatus()
     {
         $response = $this->withHeaders([
             'Content-Type' => 'Application/json',
             'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzMzQwNzE2NiwiZXhwIjoxNjMzNDEwNzY2LCJuYmYiOjE2MzM0MDcxNjYsImp0aSI6Im9UYTZCN2VxTU5SRk9NaGMiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.XQCOGU3Cbs40wAuJ3VBYYqC9sUGDCVllL2FoUQDs8vc'
         ])->json('POST', '/api/auth/deletenote', 
         [
             "id" => "6",
         ]);
         $response->assertStatus(201)->assertJson(['message' => 'Note deleted Sucessfully']);
     }

     public function test_IfGiven_WrongId_ShouldValidate_AndReturnNotes_NotFoundStatus()
     {
         $response = $this->withHeaders([
             'Content-Type' => 'Application/json',
             'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzMzQwNzE2NiwiZXhwIjoxNjMzNDEwNzY2LCJuYmYiOjE2MzM0MDcxNjYsImp0aSI6Im9UYTZCN2VxTU5SRk9NaGMiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.XQCOGU3Cbs40wAuJ3VBYYqC9sUGDCVllL2FoUQDs8vc'
         ])->json('POST', '/api/auth/deletenote', 
         [
             "id" => "20",
         ]);
         $response->assertStatus(404)->assertJson(['message' => 'Notes not Found']);
     }

     public function test_IfGiven_AuthorisedToken_AndReturnAllNotes_SuccessStatus()
     {
         $response = $this->withHeaders([
             'Content-Type' => 'Application/json',
             'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzMzQxMTQxMCwiZXhwIjoxNjMzNDE1MDEwLCJuYmYiOjE2MzM0MTE0MTAsImp0aSI6Imd5WThtclFFWG50N2JDUTgiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.b4CReGFIWLimgv0auC4sRTEHn8S6cZ8t9L6H_rNqwz4'
         ])->json('GET', '/api/auth/fetchnotes');

         $response->assertStatus(201)->assertJson(['message' => 'Fetched Notes Successfully']);
     }

     public function test_IfGiven_WrongAuthorisedToken_AndReturnInvalid_ErrorStatus()
     {
         $response = $this->withHeaders([
             'Content-Type' => 'Application/json',
             'Authorization' => 'Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzMzQwNzE2NiwiZXhwIjoxNjMzNDEwNzY2LCJuYmYiOjE2MzM0MDcxNjYsImp0aSI6Im9UYTZCN2VxTU5SRk9NaGMiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.XQCOGU3Cbs40wAuJ3VBYYqC9sUGDCVllL2FoUQDs8vc'
         ])->json('GET', '/api/auth/fetchnotes');

         $response->assertStatus(404)->assertJson(['message' => 'Notes not found']);
     }

     /*
     * Success Status
     */
    public function test_IfGiven_Registered_EmailId_ShouldValidate_AndReturnSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])->json('POST', '/api/auth/forgotpasssword', 
        [
            "email" => "balupinisetty@gmail.com",
        ]);

        $response->assertStatus(200)->assertJson(['message' => 'we have emailed your password reset link to respective mail']);
    }

    public function test_IfGiven_UnRegistered_EmailId_ShouldValidate_AndReturnErrorStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])->json('POST', '/api/auth/forgotpasssword', 
        [
            "email" => "sathramnithin1@gmail.com",
        ]);

        $response->assertStatus(404)->assertJson(['message' => 'we can not find a user with that email address']);
    }


}
