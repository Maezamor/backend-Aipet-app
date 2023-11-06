<?php

namespace Tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    
    public function setUp(): void 
    {
        //* in first time proccess, all databases is destroy
        //* result fresh databases
        parent::setUp();
        DB::delete("delete from users");
    }
}
