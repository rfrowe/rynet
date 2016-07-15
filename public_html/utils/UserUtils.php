<?php

class UserUtils {
    private $_credentials;
    private $_username;
    private $_password;
    private $_name;
    private $_loggedIn;
    private $_time;
    private $_SESSION_LENGTH = 604800; // = 60 * 60 * 24 * 7 = 7 days
    private $_TOKEN_KEY = "token";
    private $_OLD_TOKEN_KEY = "oldtoken";
    private $_TIME_KEY = "time";


    function __construct() {
        $this->_credentials = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"]."/utils/credentials.json"), /* assoc */ true);
        $this->_time = time();
        foreach($this->_credentials as &$user) {
            $username = $user["username"];
            $password = $user["password"];
            if(isset($_COOKIE[$this->_TIME_KEY])) {
                $user[$this->_OLD_TOKEN_KEY] = password_hash($this->token($username, $password, $_COOKIE[$this->_TIME_KEY]), PASSWORD_DEFAULT);
            }
            $user[$this->_TOKEN_KEY] = password_hash($this->token($username, $password, $this->_time), PASSWORD_DEFAULT);
        }
        $this->_loggedIn = $this->checkToken();
    }

    /**
    * Checks if a user is logged in using the cookie token.
    *
    * @returns whether the user is logged in.
    */
    private function checkToken() {
        if(isset($_COOKIE[$this->_TOKEN_KEY]) && isset($_COOKIE[$this->_TIME_KEY])) {
            $token = $this->verifyToken($_COOKIE[$this->_TOKEN_KEY], $this->_OLD_TOKEN_KEY);
            if($token) {
                setcookie($this->_TOKEN_KEY, $token, time() + $this->_SESSION_LENGTH, "/"); // One day
                setcookie($this->_TIME_KEY, $this->_time, time() + $this->_SESSION_LENGTH, "/"); // One day
                return true;
            }
        }

        return false;
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
            setcookie($this->_TOKEN_KEY, $token, time() + $this->_SESSION_LENGTH, "/"); // One day
            setcookie($this->_TIME_KEY, $this->_time, time() + $this->_SESSION_LENGTH, "/"); // One day
            return true;
        } else {
            return false;
        }
    }

    /**
    * Logs a user out by deleting their token
    */
    function logOut() {
        unset($_COOKIE[$this->_TOKEN_KEY]);
        unset($_COOKIE[$this->_TIME_KEY]);
        setcookie($this->_TOKEN_KEY, "", time() - 3600, "/");
        setcookie($this->_TIME_KEY, "", time() -3600, "/");
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
        return $this->verifyToken($this->token($username, $password, $this->_time), $this->_TOKEN_KEY);
    }

    /**
    * Verifies that a token is valid.
    *
    * @param $token     The token to test.
    * @param $tokenName The name of the token key in the user.
    * @return whether the login token is valid
    */
    private function verifyToken($token, $tokenName) {
        $valid = false;
        foreach($this->_credentials as $user) {
            $validUser = password_verify($token, $user[$tokenName]);
            if($validUser) {
                $this->_username = $user["username"];
                $this->_password = $user["password"];
                $this->_name = $user["name"];
            }
            $valid = $valid || $validUser;
        }

        $diff = $this->_time;
        if(isset($_COOKIE[$this->_TIME_KEY])) {
            $diff -= $_COOKIE[$this->_TIME_KEY];
        }
        return ($valid && ($diff < $this->_SESSION_LENGTH || $tokenName == $this->_TOKEN_KEY)) ? $this->token($this->_username, $this->_password, $this->_time) : false;
    }

    /**
    * Generates a token given a username and password.
    *
    * @param $username  The user's username; case insensitive.
    * @param $password  The user's password.
    * @param $time      The token time.
    * @return the token.
    */
    private function token($username, $password, $time) {
        return base64_encode(hash("sha512", strtolower($username) . $password . $time, true));
    }

    /**
    * Makes a page secure. Will redirect to login if unauthenticated
    * or call a callback function if it is.
    *
    * @param $callback  The function to call if authenticated
    */
    public function secure($callback = "") {
        if($this->loggedIn()) {
            if(!empty($callback)) {
                call_user_func($callback);
            }
        } else {
            header("Location: /login/");
        }
    }

    public function username() {
        return $this->_username;
    }

    public function name() {
        return $this->_name;
    }
}
