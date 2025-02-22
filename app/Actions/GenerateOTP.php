<?php

namespace App\Actions;

class GenerateOTP
{
    public function generate(int $length = 6): string
    {
        return str_pad(mt_rand(100000, 999999), $length, '0', STR_PAD_LEFT);
    }
}
