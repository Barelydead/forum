<?php
namespace CJ\User;

use \Anax\Database\ActiveRecordModel;

/**
 * A database driven model.
 */
class User extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "f_user";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $password;
    public $username;
    public $name;
    public $mail;
    public $age;
    public $karma;
    public $created;
    public $updated;
    public $deleted;
    public $admin;


    /**
    * Creating $session var for inject
    */
    private $session;

    /**
    *   Init the class with session and database
    */
    public function init($db, $session)
    {
        $this->session = $session;
        $this->setDb($db);
    }

    /**
     * Set the password.
     *
     * @param string $password the password to use.
     *
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }


    /**
     * Verify the acronym and the password, if successful the object contains
     * all details from the database row.
     *
     * @param string $acronym  acronym to check.
     * @param string $password the password to use.
     *
     * @return boolean true if acronym and password matches, else false.
     */
    public function verifyPassword($mail, $password)
    {
        $this->find("mail", $mail);
        return password_verify($password, $this->password);
    }

    /*
    * Get all users.
    */
    public function getAllUsers()
    {
        return $this->findAll();
    }


    /*
    * Get all users.
    */
    public function deleteUser($id)
    {
        $this->find("id", $id);
        $this->delete();
    }


    /*
    * Get a user based on ID
    */
    public function getUser($id)
    {
        return $this->find("id", $id);
    }

    /*
    * Get a user based on ID
    */
    public function getVotes($id)
    {
        $sql = "SELECT type, count(id) as count
        FROM f_like2user WHERE user = ?
        GROUP BY type";

        $votes = $this->db->executeFetchAll($sql, [$id]);
        return $votes;
    }

    /*
    * Get a user based on ID
    */
    public function isLoggedIn()
    {
        return $this->session->has("user");
    }


    /*
    * Get a user based on ID
    */
    public function getLoggedInUserId()
    {
        return $this->session->get("user");
    }

    /*
    * Get a user based on username
    */
    public function getUserFromName($name)
    {
        return $this->find("username", $name);
    }

    /*
    * Get a user based on ID
    */
    public function logOutUser()
    {
        if ($this->session->has("user")) {
            $this->session->delete("user");
        }
    }


    /*
    * Get logged in user
    * @return user class
    */
    public function getLoggedInUser()
    {
        if ($this->session->has("user")) {
            return $this->find("id", $this->session->get("user"));
        }
    }


    public function getUserImg($mail)
    {
        $hash = md5($mail);

        $href = "https://www.gravatar.com/avatar/$hash?s=200&default=mm";
        return $href;
    }


    /*
    * Return true is user is admin
    */
    public function isUserAdmin()
    {
        if ($this->isLoggedIn()) {
            $id = $this->getLoggedInUserId();

            $this->find("id", $id);
            if ($this->userType == "admin") {
                return true;
            }
        }

        return false;
    }

    public function getMostActiveUser()
    {
        $sql = "SELECT * FROM f_user
        ORDER BY karma DESC LIMIT 2";

        return $this->db->executeFetchAll($sql);
    }
}
