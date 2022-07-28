<?php

namespace App\Tests\UserDomain\Exception;

use Domain\UserDomain\Exception\ExceptionUser;
use PHPUnit\Framework\TestCase;

class ExceptionUserTest extends TestCase
{

    public function testGetField()
    {
        $ex = new ExceptionUser('fieldFake', 'Le message');
        $this->assertEquals('fieldFake', $ex->getField());
        $this->assertEquals('Le message', $ex->getMessage());
    }
}
