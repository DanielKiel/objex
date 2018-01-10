<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 09.01.18
 * Time: 13:56
 */

namespace Objex\Core\Cryptography;


use Defuse\Crypto\Crypto;

class Cryptography
{
    /**
     * @param string $string
     * @return string
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \Exception
     */
    public function encryt(string $string)
    {
        return Crypto::encryptWithPassword($string, $this->getAppKey());
    }

    /**
     * @param string $string
     * @return string
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException
     * @throws \Exception
     */
    public function decrypt(string $string)
    {
        return Crypto::decryptWithPassword($string, $this->getAppKey());
    }

    /**
     * @return array|false|string
     * @throws \Exception
     */
    protected function getAppKey()
    {
        $appKey = getenv('APP_KEY');

        $message = 'you must define an APP_KEY before using encrypt / decrypt functionallity';
        if (! is_string($appKey)) {
            throw new \Exception($message);
        }

        if (strlen($appKey) < 16) {
            throw new \Exception($message);
        }

        return $appKey;
    }
}