<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
   // test for Userregistere Api

   public function testRegisterSuccess (){
    // get start testing
    $this->post('/api/users',[
        'username' => 'fieren',
        'password' => 'fieren123',
        'name' => 'fieren si penyihir',
        'address' => 'zenia kingdom',
        'phone' => '123456789',
        'email' => 'fieren@gmail.com'
    ])->assertStatus(201)->assertJson([
        "data" => [
            'username' => 'fieren',
            'name' => 'fieren si penyihir',
            'address' => 'zenia kingdom',
            'phone' => '123456789',
            'email' => 'fieren@gmail.com'
        ]
    ]);
   }

   public function testRegisterFailed(){
    $this->post('/api/users',[
        'username' => '',
        'password' => '',
        'name' => '',
        'address' => '',
        'phone' => '',
        'email' => ''
    ])->assertStatus(400)->assertJson([
        "errors" => [
            'username' => [
                "The username field is required."
            ],
            'password' => [
                "The password field is required."
            ],
            'name' => [
                "The name field is required."
            ],
            'address' => [
                "The address field is required."
            ],
            'phone' => [
                "The phone field is required."
            ],
            'email' => [
                "The email field is required."
            ]
        ]
    ]);
   }


   public function testRegisterEmailAlreadyExists(){
        $this->testRegisterSuccess();
        $this->post('/api/users',[
            'username' => 'fieren',
            'password' => 'fieren123',
            'name' => 'fieren si penyihir',
            'address' => 'zenia kingdom',
            'phone' => '123456789',
            'email' => 'fieren@gmail.com'
        ])->assertStatus(400)->assertJson([
            'errors' => [
                'email' => [
                    "email already exist and registered"
                ]
            ]
        ]);
   }
}
