<?php

class AccountModel extends Model
{
    public $_table = 'account';

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

    public function register($username, $password)
    {
    	$results = $this->where(['username = :username'], [':username' => $username])->select();

    	if ($results == false)
    	{
    		$bytes = random_bytes(32);
            $salt = bin2hex($bytes);

            $password_with_salt = $password . $salt;
            $encrypted_password = hash('sha256', $password_with_salt);

    		$data['username'] = $username;
    		$data['password'] = $encrypted_password;
    		$data['salt'] = $salt;

    		$this->add($data);

    		return 1;
    	}

    	return 0;
    }
}
