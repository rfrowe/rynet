<?php

class UserUtils {
    private $_credentials;
    private $_username;
    private $_name;
    private $_loggedIn;

    function __construct() {
        $this->_credentials = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"]."/utils/credentials.json"), /* assoc */ true);
        foreach($this->_credentials as &$user) {
            $username = $user["username"];
            $password = $user["password"];
            $user["token"] = password_hash($this->token($username, $password), PASSWORD_DEFAULT);
        }
        $this->_loggedIn = $this->checkToken();
    }

    /**
    * Checks if a user is logged in using the cookie token.
    *
    * @returns whether the user is logged in.
    */
    private function checkToken() {
        return isset($_COOKIE["token"]) && $this->verifyToken($_COOKIE["token"]) ? true : false;
    }

    /**
    * Checks if a user is logged in using the cookie token.
    *
    * @returns whether the user is logged in.
    */
    function loggedIn() {
        return $this->_loggedIn;
    }

    /**
    * Logs a user in. Stores their unique token in a cookie for one day.
    *
    * @param $username  The user's username; case insensitive.
    * @param $password  The user's password.
    * @returns whether the log in was successful.
    */
    function logIn($username, $password) {
        $token = $this->verifyCredentials($username, $password);
        if($token) {
            setcookie("token", $token, time()+60*60*24, "/"); // One day
            return true;
        } else {
            return false;
        }
    }

    /**
    * Logs a user out by deleting their token
    */
    function logOut() {
        unset($_COOKIE["token"]);
        setcookie("token", "", time() - 3600, "/");
        $this->_loggedIn = false;
        unset($this->username);
        unset($this->name);
    }

    /**
    * Verifies that a user's credentials match a set of valid credentials.
    *
    * @param $username  The user's username; case insensitive.
    * @param $password  The user's password.
    * @return whether the credentials are valid.
    */
    private function verifyCredentials($username, $password) {
        return $this->verifyToken($this->token($username, $password));
    }

    /**
    * Verifies that a token is valid.
    *
    * @param $token The token to test.
    * @return whether the login token is valid
    */
    private function verifyToken($token) {
        $valid = false;
        foreach($this->_credentials as $user) {
            $validUser = password_verify($token, $user["token"]);
            if($validUser) {
                $this->_username = $user["username"];
                $this->_name = $user["name"];
            }
            $valid = $valid || $validUser;
        }

        return $valid ? $token : false;
    }

    /**
    * Generates a token given a username and password.
    *
    * @param $username  The user's username; case insensitive.
    * @param $password  The user's password.
    * @return the token.
    */
    private function token($username, $password) {
        return base64_encode(hash("sha256", strtolower($username) . $password, true));
    }

    public function username() {
        return $this->_username;
    }

    public function name() {
        return $this->_name;
    }
}
