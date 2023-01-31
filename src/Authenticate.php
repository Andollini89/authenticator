<?php

namespace Authenticator;

use Authenticator\UserProvider;

class Authenticate
{
	protected $userProvider;
	protected $redirectUrl;

	/**
	 * Initialize DB connection
	 *
	 * @param \PDO $pdo
	 */
	public function __construct(UserProvider $userProvider, string $redirectUrl) {
        $this->userProvider = $userProvider;
		$this->redirectUrl = $redirectUrl;
    }



	/**
	 * Login function: 
	 * - check for user in database
	 * - check for hashed password correspondence and set session attribute
	 * - check for remember attribute and set cookie 'remember' for 24H
	 *
	 * @param string $username
	 * @param string $password
	 * @param boolean $remember
	 * @return boolean
	 */

	public function login(string $username, string $password, bool $remember = false): bool
	{	
		if (!$username || !$password) {
			header('Location'. $this->redirectUrl);
			die();	
		}

        $user = $this->userProvider->getUser($username);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['auth_session'] = true;
            $_SESSION['user'] = $user['username'];

            if ($remember) {
                setcookie('remember', $username, time() + (24 * 60 * 60));
            }

            return true;
        }

        header('Location'. $this->redirectUrl);
		die();
    }

	/**
	 *
	 * @param string $username
	 * @return boolean
	 */
	public function isLogged(): bool
	{

		if (isset($_SESSION['auth_session']) && $_SESSION['auth_session']) return true; 
		
		if (isset($_COOKIE['remember']) && $_COOKIE['remember'] != ''){
            $user = $this->userProvider->getUser($_COOKIE['remember']);

            if ($user) {
                $_SESSION['auth_session'] = true;
                $_SESSION['user'] = $user['username'];

                return true;
            }
		}
		return false;
	}

}
