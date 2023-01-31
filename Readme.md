# PHP Simple Login Package

## Installation

``` php
composer require Andollini89/authenticator
```

``` php
composer install
```

## Details

The package contains:

- Authenticate Class which provide the login function and the isLogged function.
- UserProvider which implements UserProviderInterface and provide the getUser function to search for user in source/database.

## Usage exemple

``` php 
<?php

use Authenticator/UserProvider;
use Authenticator/Authenticate;

$pdo = new \PDO('sqlite:test.db'); // or any other source

$userProvider = new UserProvider($pdo);
$auth = new Authenticate($userProvider, 'path/to/login')

if ($authenticated = $auth->isLogged() === false) {
    $authenticated = $auth->login('username', 'password', true)
} else {
   // more code here
}



```
