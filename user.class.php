<?php

require_once ("DB.class.php");

class User
{
    private $loggedInUserAttributes = [];
    const COOKIE_NAME = "swapi_persons";

    public function __get($attr)
    {
        if (isset($this->loggedInUserAttributes[$attr]))
            return $this->loggedInUserAttributes[$attr];

        return false;
    }

    private function __construct($attributes)
    {
        $this->loggedInUserAttributes = $attributes;
    }

    /**
     * @param int $id the user id to load
     * @return User $this
     * Loads user attributes from DB
     */
    public static function loadById($id)
    {
        $query = new SQLQuery();
        $query->table = "tbl_user";
        $query->condition = "user_id = " . $id;

        $attributes = DB::instance()->getRow($query);

        $user = new self($attributes);
        return $user;
    }

    /**
     * @param int $username the username to load
     * @return User $this
     * Loads user attributes from DB
     */
    public static function loadByUserName($username)
    {
        $query = new SQLQuery();
        $query->table = "tbl_user";
        $query->condition = "username = '" . $username."'";

        $attributes = DB::instance()->getRow($query);

        $user = new self($attributes);
        return $user;
    }

    /**
     * @param $username
     * @param $password
     * @return boolean success if user exists
     */
    public static function login($username, $password, $rememberMe = false)
    {
        // find user in db
        $user = self::loadByUserName($username);
        //hash password
        $hashed = md5($password);
        //if found and rememberMe = true - create a cookie
        if ($hashed === $user->password) {
            $cookie_name = self::COOKIE_NAME;
            $cookie_value = $username;
            if ($rememberMe) {
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30)); // 86400 = 1 day
            }
            else {
                setcookie($cookie_name, $cookie_value, 0);
            }
            return true;
        }
        return false;
    }

    /**
     * @param $attributes - The user attributes to insert to DB (username => lpmcr, password => "hashedpassword" ....)
     * Creates a record in DB
     */
    public static function register($attributes)
    {
        foreach($attributes as $key => $attribute)
        {
            $attributes[$key] = DB::instance()->escapeString($attribute);
        }

        $query = new SQLQuery();
        $query->table = "tbl_user";
        $attributes['password'] = md5($attributes['password']);
        $query->setParams = $attributes;

        $db = DB::instance();
        $db->insert($query);
    }

    /**
     * @return User|boolean User's object if logged in, false if guest
     */
    public static function currentLoggedIn()
    {
        if(isset($_COOKIE[self::COOKIE_NAME])) {
            return self::loadByUserName($_COOKIE[self::COOKIE_NAME]);
        }
        else {
            return false;
        }
    }

    /**
     * logout
     */
    public function logout()
    {
        setcookie(self::COOKIE_NAME, "", time() - 3600);
    }
}