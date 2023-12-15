<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    // test for Userregistere Api

    public function testRegisterSuccess()
    {
        // get start testing
        $this->post('/api/users', [
            'username' => 'fieren',
            'password' => 'fieren123',
            'name' => 'fieren si penyihir',
            'address' => 'zenia kingdom',
            'phone' => '123456789',
            'email' => 'fieren@gmail.com',
            'role_id' => '3',
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

    public function testRegisterFormatFailed()
    {
        $this->post('/api/users', [
            'username' => 'fieren',
            'password' => 'fieren123',
            'name' => 'fieren si penyihir',
            'address' => 'zenia kingdom',
            'phone' => '123456789',
            'email' => 'fierengmail.com'
        ])->assertStatus(400)->assertJson([
            "errors" => [
                'email' => [
                    "The email field must be a valid email address."
                ]
            ]
        ]);
    }

    public function tesRegisterEmailFailed()
    {
        $this->post('/api/users', [
            'username' => '',
            'password' => '',
            'name' => '',
            'address' => '',
            'phone' => '',
            'email' => '',
            'role_id' => ''
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


    public function testRegisterEmailAlreadyExists()
    {
        $this->testRegisterSuccess();
        $this->post('/api/users', [
            'username' => 'fieren',
            'password' => 'fieren123',
            'name' => 'fieren si penyihir',
            'address' => 'zenia kingdom',
            'phone' => '123456789',
            'email' => 'fieren@gmail.com',
            'role_id' => 3
        ])->assertStatus(400)->assertJson([
            'errors' => [
                'email' => [
                    "email already exist and registered"
                ]
            ]
        ]);
    }


    public function testLoginSuccess()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'email' => 'fieren@gmail.com',
            'password' => 'fieren123'
        ])->assertStatus(200)->assertJson([
            'data' => [
                'username' => 'fieren',
                'name' => 'fieren si penyihir',
                'address' => 'antasia kingdom',
                'phone' => '123456',
                'email' => 'fieren@gmail.com'
            ]
        ]);

        // penegchekan apakah token sudah di regeberate
        $user = User::where('email', 'fieren@gmail.com')->first();
        self::assertNotNull($user->token);
    }


    public function testLoginFailedEmailNotfound()
    {
        $this->post('/api/users/login', [
            'email' => 'fieren123@gmail.com',
            'password' => 'fieren123'
        ])->assertStatus(401)->assertJson([
            'errors' => [
                "message" => [
                    "username or password wrong"
                ]
            ]
        ]);
    }

    public function testLoginFailedPasswordWrong()
    {
        $this->post('/api/users/login', [
            'email' => 'fieren123@gmail.com',
            'password' => 'fieren12312'
        ])->assertStatus(401)->assertJson([
            'errors' => [
                "message" => [
                    "username or password wrong"
                ]
            ]
        ]);
    }

    public function testLoginFailedWrongEmailFormater()
    {
        $this->post('/api/users/login', [
            'email' => 'fieren123gmail.com',
            'password' => 'fieren12312'
        ])->assertStatus(400)->assertJson([
            "errors" => [
                'email' => [
                    "The email field must be a valid email address."
                ]
            ]
        ]);
    }

    public function testLoginFailedNotingValuelogininput()
    {
        $this->post('/api/users/login', [
            'email' => '',
            'password' => ''
        ])->assertStatus(400)->assertJson([
            "errors" => [
                'email' => [
                    "The email field is required."
                ],
                'password' => [
                    "The password field is required."
                ]
            ]
        ]);
    }

    public function testGetCurrentUserSuccess()
    {
        $this->seed([UserSeeder::class]);
        $this->get('/api/users/current', [
            //header for api
            'Authorization' => 'fierencoll'
        ])->assertStatus(200)->assertJson([
            'data' => [
                'username' => 'fieren',
                'name' => 'fieren si penyihir',
                'address' => 'antasia kingdom',
                'phone' => '123456',
                'email' => 'fieren@gmail.com'
            ]
        ]);
    }

    public function testGetCurrentUserUnauthorized()
    {
        $this->seed([UserSeeder::class]);
        $this->get('/api/users/current', [])->assertStatus(401)->assertJson([
            'errors' => [
                'message' => [
                    "unauthorized"
                ]

            ]
        ]);
    }

    public function testGetCurrentUserInvlaidToken()
    {
        $this->seed([UserSeeder::class]);
        $this->get('/api/users/current', [
            'Authorization' => 'jhlyicl'
        ])->assertStatus(401)->assertJson([
            'errors' => [
                'message' => [
                    "unauthorized"
                ]

            ]
        ]);
    }

    //TODO  testing unutk api update user
    public function testUpdatePasswordSuccess()
    {
        $this->seed([UserSeeder::class]);
        $oldUser = User::where('email', 'fieren@gmail.com')->first();

        $this->patch(
            '/api/users/current',
            [
                'password' => 'fieren123'
            ],
            [
                'Authorization' => 'fierencoll'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'fieren',
                    'name' => 'fieren si penyihir',
                    'address' => 'antasia kingdom',
                    'phone' => '123456',
                    'email' => 'fieren@gmail.com'
                ]
            ]);

        $newUser = User::where('email', 'fieren@gmail.com')->first();
        self::assertNotEquals($oldUser->password, $newUser->password);
    }
    //TODO  testing unutk api update user
    public function testUpdateNameandAddressSuccess()
    {
        $this->seed([UserSeeder::class]);
        $oldUser = User::where('email', 'fieren@gmail.com')->first();

        $this->patch(
            '/api/users/current',
            [
                'name' => 'fren',
                'address' => 'konstania'
            ],
            [
                'Authorization' => 'fierencoll'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'fieren',
                    'name' => 'fren',
                    'address' => 'konstania',
                    'phone' => '123456',
                    'email' => 'fieren@gmail.com'
                ]
            ]);

        $newUser = User::where('email', 'fieren@gmail.com')->first();
        self::assertNotEquals($oldUser->name, $newUser->name);
        self::assertNotEquals($oldUser->address, $newUser->address);
    }

    public function testUpdateFailed()
    {
        $this->seed([UserSeeder::class]);
        $this->patch(
            '/api/users/current',
            [
                'name' => 'frenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfrenfren'
            ],
            [
                'Authorization' => 'fierencoll'
            ]
        )->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'The name field must not be greater than 100 characters.'
                    ]
                ]
            ]);
    }

    //TES LOGOUT
    public function testLogoutSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->delete(uri: '/api/users/logout', headers: [
            'Authorization' => 'fierencoll'
        ])->assertStatus(200)
            ->assertJson([
                "data" => true
            ]);

        $user = User::where('email', 'fieren@gmail.com')->first();
        self::assertNull($user->token);
    }

    public function testLogoutFailed()
    {
        $this->seed([UserSeeder::class]);

        $this->delete(uri: '/api/users/logout', headers: [
            'Authorization' => 'dasdadadad'
        ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "unauthorized"
                    ]
                ]
            ]);
    }
}
