<?php

use Authenticator\Authenticate;
use Authenticator\UserProvider;

require __DIR__.'/../vendor/autoload.php';

session_start();

class LoginTest
{
    /**
     *
     * @var \PDO
     */
    private \PDO $pdo;

    /**
     *
     * @var UserProvider
     */
    private UserProvider $userProvider;

    /**
     *
     * @var Authenticate
     */
    private Authenticate $auth;
    
    /**
     *
     * @var string
     */
    private string $username;
    
    /**
     *
     * @var string
     */
    private string $password;

    /**
     * Setup test envoirement
     *
     * @return void
     */
    public function setup()
    {
        $this->pdo = new \PDO('sqlite::memory:');
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec("CREATE TABLE users (id INTEGER PRIMARY KEY, username TEXT, password TEXT)");
        $this->userProvider = new UserProvider($this->pdo);
        $this->auth = new Authenticate($this->userProvider, '/login');


        $this->username = 'username';
        $this->password = password_hash('password', PASSWORD_DEFAULT);

        $this->pdo->exec("INSERT INTO users (username, password) VALUES ('$this->username', '$this->password')");
    }

    /** @test */
    public function user_is_retreived()
    {
        session_reset();

        $user = $this->userProvider->getUser($this->username);

        $user ? print("Test Passed\n") : print("Test Failed: User not found.\n");
    }

     /** @test */
    public function user_is_logged_in()
    {
        session_reset();

        $this->auth->login($this->username, 'password') ? print("Test Passed\n") : throw new Exception("Test Failed: Login failed.", 1);
        isset($_SESSION['auth_session']) ? print("Test Passed\n") : throw new Exception("Test failed: Session does not have 'auth' attribute", 1);
        isset($_SESSION['auth_session']) && $_SESSION['auth_session'] === true ?
            print("Test Passed\n") :
            throw new Exception("Test failed: Session 'auth' attribute expected to be true but has value false", 1);
    }
   
    /** @test */
	public function user_has_remember_set()
	{
		session_reset();
		$logged = $this->auth->login($this->username, 'password', true);
		
		if ($logged) { 
		
			$_SESSION['auth_session'] = false;
			$_COOKIE['remember'] = $this->username;
		}	
        
        $this->auth->isLogged() ?  print("Test Passed\n") : print("Test Failed: remember flag not correctly set.\n");
	}
}


$testCase = new LoginTest();
$testCase->setup();

$testCase->user_is_retreived();
$testCase->user_is_logged_in();
$testCase->user_has_remember_set();

