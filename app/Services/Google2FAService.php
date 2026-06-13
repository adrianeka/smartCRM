<?php

namespace App\Services;

class Google2FAService
{
    /**
     * Generate a random 16-character base32 secret key.
     */
    public static function generateSecretKey(): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < 16; $i++) {
            $secret .= $chars[random_int(0, 31)];
        }

        return $secret;
    }

    /**
     * Generate the Google Charts QR Code URL.
     */
    public static function getQrCodeUrl(string $email, string $secret): string
    {
        $label = rawurlencode($email);
        $issuer = rawurlencode(config('app.name', 'SmartCRM'));
        $otpauthUrl = "otpauth://totp/{$issuer}:{$label}?secret={$secret}&issuer={$issuer}&algorithm=SHA1&digits=6&period=30";

        return 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data='.urlencode($otpauthUrl);
    }

    /**
     * Verify a 6-digit TOTP code against a secret.
     */
    public static function verify(string $secret, string $code, int $window = 1): bool
    {
        $code = str_replace(' ', '', $code);
        if (! preg_match('/^\d{6}$/', $code)) {
            return false;
        }

        $currentTimeSlice = (int) floor(time() / 30);

        for ($i = -$window; $i <= $window; $i++) {
            $calculatedCode = self::getCode($secret, $currentTimeSlice + $i);
            if (hash_equals($calculatedCode, $code)) {
                return true;
            }
        }

        return false;
    }

    public static function getCode(string $secret, ?int $timeSlice = null): string
    {
        if ($timeSlice === null) {
            $timeSlice = (int) floor(time() / 30);
        }
        $secretBytes = self::base32Decode($secret);
        if (empty($secretBytes)) {
            return '';
        }

        $timeBytes = pack('N*', 0, $timeSlice);

        $hmac = hash_hmac('sha1', $timeBytes, $secretBytes, true);

        $offset = ord(substr($hmac, -1)) & 0x0F;
        $part = substr($hmac, $offset, 4);
        $value = unpack('N', $part)[1];
        $value = $value & 0x7FFFFFFF;

        $code = $value % 1000000;

        return str_pad((string) $code, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Decode a base32 string to binary.
     */
    private static function base32Decode(string $secret): string
    {
        $secret = strtoupper(str_replace(' ', '', $secret));
        if (! preg_match('/^[A-Z2-7\=]+$/', $secret)) {
            return '';
        }

        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $map = array_flip(str_split($chars));

        $binary = '';
        foreach (str_split($secret) as $char) {
            if ($char === '=') {
                break;
            }
            if (! isset($map[$char])) {
                continue;
            }
            $val = $map[$char];
            $binary .= str_pad(decbin($val), 5, '0', STR_PAD_LEFT);
        }

        $bytes = '';
        foreach (str_split($binary, 8) as $chunk) {
            if (strlen($chunk) < 8) {
                break;
            }
            $bytes .= chr(bindec($chunk));
        }

        return $bytes;
    }
}
