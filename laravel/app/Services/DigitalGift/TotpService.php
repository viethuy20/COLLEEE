<?php

namespace App\Services\DigitalGift;

use OTPHP\TOTP;//Using version ^10.0 for spomky-labs/otphp
use Carbon\Carbon;

class TotpService
{

    /**
     * TOTP を計算する
     */
    function generate_totp(string $seed, int $steps = 15, string $digest_algorithm = 'sha256', int $digits = 8): string
    {

        $otp = TOTP::create(
            $seed,   // Let the secret be defined by the class
            $steps,     // The period (15 seconds)
            $digest_algorithm, // The digest algorithm
            $digits      // The output will generate 8 digits
        );
        $password = $otp->at(Carbon::now()->timestamp);
        //dd($otp->verify($password, time()));

        return $password;
    }
}
