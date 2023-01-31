<?php
namespace Authenticator;

interface UserProviderInterface
{
    /**
     * function that searches for the user in the data source.
     * if the user exists, it returns an array containing the user's information
     * otherwise it returns "null"*
     * 
     * @param String $username
     * @return array|null
     */
    
    public function getUser(String $username): ?array;

}
