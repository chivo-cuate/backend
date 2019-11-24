<?php

namespace tests\unit\models;

use app\models\User;
use Yii;

class UserTest extends \Codeception\Test\Unit
{
    public function testFindUserById()
    {
        expect_that($user = User::findIdentity(1));
        expect($user->username)->equals('admin');

        expect_not(User::findIdentity(999));
    }

    public function testFindUserByAccessToken()
    {
        expect_that($user = User::findIdentity(1));
        expect($user->username)->equals('admin');
        expect_not(User::findIdentityByAccessToken('non-existing'));        
    }

    public function testFindUserByUsername()
    {
        expect_that($user = User::findByUsername('admin')->getAuthUser());
        expect_not(User::findByUsername('not-admin')->getAuthUser());
    }

    /**
     * @depends testFindUserByUsername
     */
    public function testValidateUser($user)
    {
        $user = User::findByUsername('admin');
        
        expect_that($user->validatePassword('a'));
        expect_not($user->validatePassword('123'));
    }

}
