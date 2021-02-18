<?php

namespace App\DataFixtures\Faker\Provider;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HashProvider
{
//    private $encoder;
//
//    public function __construct(UserPasswordEncoderInterface $encoder)
//    {
//        $this->encoder = $encoder;
//    }

    public static function hash($str)
    {
//        return $this->encoder->encodePassword($user = null, $str);
        return $str. 'adminAdmin';
    }
}
