<?php

namespace Andres\YoucabOk\Tests;

require_once __DIR__ . '/../../vendor/autoload.php';

use Andres\YoucabOk\models\Dbh;
use Andres\YoucabOk\models\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PDO;
use PDOStatement;


class UserTest extends TestCase
{
    public function testConstructor()
    {
        $user = new User('username', 'email@example.com', 'password');
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('username', $user->getUsername());
        $this->assertEquals('email@example.com', $user->getEmail());
        $this->assertEquals('password', $user->getPassword());
    }

    public function testSave()
    {
        $user = new User('username', 'email@example.com', 'password');
        $user->save();
        $this->assertNotNull($user->getUuid());
    }
    public function testCheckCredentials()
    {
        $user = new User('username', 'email@example.com', 'password');
        $user->save();
        $result = User::checkCredentials('username', 'password');
        $this->assertIsArray($result);
        $this->assertEquals('username', $result['username']);
    }

    public function testGenerateToken()
    {
        $user = new User('username', 'email@example.com', 'password');
        $token = $user->generateToken(32);
        $this->assertEquals(64, strlen($token));
    }




}
