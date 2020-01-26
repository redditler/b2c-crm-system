<?php
/**
 * Created by PhpStorm.
 * User: pomazan_rn
 * Date: 18.01.2019
 * Time: 15:16
 */

namespace App\Support;

use PragmaRX\Google2FALaravel\Exceptions\InvalidSecretKey;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class Google2FAAuthentication extends Authenticator
{

    protected function canPassWithoutCheckingOTP()
    {
        if(!($this->getUser()->passwordSecurity))
            return true;
        return
            !$this->getUser()->passwordSecurity->google2fa_enable ||
            !$this->isEnabled() ||
            $this->noUserIsAuthenticated() ||
            $this->twoFactorAuthStillValid();
    }

    protected function getGoogle2FASecretKey()
    {
        $secret = $this->getUser()->passwordSecurity->{$this->config('otp_secret_column')};

        if (is_null($secret) || empty($secret)) {
            throw new InvalidSecretKey('Secret key cannot be empty.');
        }

        return $secret;
    }
}