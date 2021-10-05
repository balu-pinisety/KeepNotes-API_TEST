<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LabelTest extends TestCase
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

    public function test_IfGiven_NoteIDAndLabel_ShouldValidate_AndReturnSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzMzQxMDQ5NywiZXhwIjoxNjMzNDE0MDk3LCJuYmYiOjE2MzM0MTA0OTcsImp0aSI6IkV1RmdkY01VendmejdVM3giLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.IIOFA7rpbrtgRxYjJTK4sKmc_TRpgw4_v4YHD2al7vE'
        ])->json('POST', '/api/auth/createlabel', 
        [
            "note_id" => "7",
            "labelname" => "label test",
        ]);

        $response->assertStatus(201)->assertJson(['message' => 'Label created Sucessfully']);
    }

    public function test_IfGiven_WrongAccessToken_ShouldReturnErrorStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer EyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzMzQxMDQ5NywiZXhwIjoxNjMzNDE0MDk3LCJuYmYiOjE2MzM0MTA0OTcsImp0aSI6IkV1RmdkY01VendmejdVM3giLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.IIOFA7rpbrtgRxYjJTK4sKmc_TRpgw4_v4YHD2al7vE'
        ])->json('POST', '/api/auth/createlabel', 
        [
            "note_id" => "7",
            "labelname" => "label test",
        ]);

        $response->assertStatus(404)->assertJson(['message' => 'Invalid authorization token']);
    }

    public function test_IfGiven_IdAndLabel_ShouldValidate_AndReturnSuccess_UpdateStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzMzQxMDk4MCwiZXhwIjoxNjMzNDE0NTgwLCJuYmYiOjE2MzM0MTA5ODAsImp0aSI6Ik9wdGpjWVFTQWxTUUZoYkIiLCJzdWIiOjIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.CAiGPPjCUjSG7qzhn8ncNiBIRCLdntOKbMkFoyBZkb8'
        ])->json('PUT', '/api/auth/updatelabel', 
        [
            "id" => "7",
            "labelname" => "label update test"
        ]);
        $response->assertStatus(201)->assertJson(['message' => 'Label updated Sucessfully']);
    }

    public function test_IfGiven_WrongId_Label_ShouldValidate_AndReturnNotes_NotFoundStatus()
     {
         $response = $this->withHeaders([
             'Content-Type' => 'Application/json',
             'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzMzQxMDk4MCwiZXhwIjoxNjMzNDE0NTgwLCJuYmYiOjE2MzM0MTA5ODAsImp0aSI6Ik9wdGpjWVFTQWxTUUZoYkIiLCJzdWIiOjIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.CAiGPPjCUjSG7qzhn8ncNiBIRCLdntOKbMkFoyBZkb8'
        ])->json('PUT', '/api/auth/updatelabel', 
         [
            "id" => "12",
            "labelname" => "label update test"
         ]);
         $response->assertStatus(404)->assertJson(['message' => 'Label not Found']);
    }

    public function test_IfGiven_IdForLabel_ShouldValidate_AndReturn_Delete_SuccessStatus()
     {
         $response = $this->withHeaders([
             'Content-Type' => 'Application/json',
             'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzMzQxMDk4MCwiZXhwIjoxNjMzNDE0NTgwLCJuYmYiOjE2MzM0MTA5ODAsImp0aSI6Ik9wdGpjWVFTQWxTUUZoYkIiLCJzdWIiOjIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.CAiGPPjCUjSG7qzhn8ncNiBIRCLdntOKbMkFoyBZkb8'
        ])->json('POST', '/api/auth/deletelabel', 
         [
             "id" => "6",
         ]);
         $response->assertStatus(201)->assertJson(['message' => 'Label deleted Sucessfully']);
     }

     public function test_IfGiven_WrongLabelId_ShouldValidate_AndReturnNotes_NotFoundStatus()
     {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzMzQxMDk4MCwiZXhwIjoxNjMzNDE0NTgwLCJuYmYiOjE2MzM0MTA5ODAsImp0aSI6Ik9wdGpjWVFTQWxTUUZoYkIiLCJzdWIiOjIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.CAiGPPjCUjSG7qzhn8ncNiBIRCLdntOKbMkFoyBZkb8'
       ])->json('POST', '/api/auth/deletelabel', 
        [
            "id" => "5",
        ]);
         $response->assertStatus(404)->assertJson(['message' => 'Label not Found']);
     }

     public function test_IfGiven_AuthorisedToken_AndReturnAllLabels_SuccessStatus()
     {
         $response = $this->withHeaders([
             'Content-Type' => 'Application/json',
             'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzMzQxMDk4MCwiZXhwIjoxNjMzNDE0NTgwLCJuYmYiOjE2MzM0MTA5ODAsImp0aSI6Ik9wdGpjWVFTQWxTUUZoYkIiLCJzdWIiOjIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.CAiGPPjCUjSG7qzhn8ncNiBIRCLdntOKbMkFoyBZkb8'
         ])->json('GET', '/api/auth/fetchlabels');

         $response->assertStatus(201)->assertJson(['message' => 'Fetched Labels Successfully']);
     }

     public function test_IfGiven_WrongAuthorisedTokenLabel_AndReturnInvalid_ErrorStatus()
     {
         $response = $this->withHeaders([
             'Content-Type' => 'Application/json',
             'Authorization' => 'Bearer EyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzMzQxMDk4MCwiZXhwIjoxNjMzNDE0NTgwLCJuYmYiOjE2MzM0MTA5ODAsImp0aSI6Ik9wdGpjWVFTQWxTUUZoYkIiLCJzdWIiOjIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.CAiGPPjCUjSG7qzhn8ncNiBIRCLdntOKbMkFoyBZkb8'
         ])->json('GET', '/api/auth/fetchlabels');

         $response->assertStatus(404)->assertJson(['message' => 'Labels not found']);
     }

     
}
