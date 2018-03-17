<?php

use Firebase\JWT\JWT;

define('ALGORITHM', 'HS512');

class AccountModel extends Model
{
    public $_table = 'account';

    function generateJWT($userid, $username)
    {
        $objectId = base64_encode(random_bytes(32));
        $createdAt = time();
        $expire = $createdAt + 600; // 10minutes

        $data = [
            'cat' => $createdAt,
            'jti' => $objectId,
            'exp' => $expire,
            'data' => [
                'id' => $userid,
                'name' => $username
            ]
        ];

        $secretKey = $GLOBALS['secretKey'];

        $jwt = JWT::encode($data, $secretKey, ALGORITHM);

        $unencodedArray = ['createdAt' => $createdAt, 'objectId' => $objectId, 'sessionToken' => $jwt];

        return $unencodedArray;
    }

    public function login($username, $password)
    {
    	$results = $this->where(['username = :username'], [':username' => $username])->select();

    	if ($results == false)
    	{
    		return 0;
    	}

    	$salt = $results['salt'];

        $password_with_salt = $password . $salt;
        $encrypted_password = hash('sha256', $password_with_salt);

    	if($encrypted_password == $results['password'])
    	{
    		return 1;
    	}

    	return 0;
    }

    public function register($username, $password, $email)
    {
    	$results = $this->where(['username = :username'], [':username' => $username])->select();

    	if ($results == true)
    	{
    		return 0;
    	}

        $bytes = random_bytes(32);
        $salt = bin2hex($bytes);

        $password_with_salt = $password . $salt;
        $encrypted_password = hash('sha256', $password_with_salt);

        $data['username'] = $username;
        $data['password'] = $encrypted_password;
        $data['salt'] = $salt;
        $data['email'] = $email;

        $this->add($data);

        $userid = $this->getLastInsertID();

        $jwt = $this->generateJWT($userid, $username);

        return $jwt;
    }
}
