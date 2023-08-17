<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function generatePassword(User $user, $length = 8): string
    {
        $string = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $password = substr(str_shuffle($string), 0, $length);

        return $this->hasher->hashPassword($user, $password);
    }
}
