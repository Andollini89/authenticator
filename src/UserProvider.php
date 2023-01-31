<?php
namespace Authenticator;

use Authenticator\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private $pdo;

	/**
	 * Initialize DB connection
	 *
	 * @param \PDO $pdo
	 */
	public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

	/**
	 * Implement logic for get user function:
	 * - prepare sql query to get user with given username
	 *
	 * @param String $username
	 * @return array|null
	 */
	public function getUser(String $username): ?array
	{
		$query = $this->pdo->prepare('SELECT * FROM users WHERE username = :username');
        $query->execute(['username' => $username]);

        return $query->fetch(\PDO::FETCH_ASSOC);
	}

}
