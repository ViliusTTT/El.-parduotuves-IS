<?php
error_reporting(E_ALL ^ E_DEPRECATED);
include("database.php");
include("form.php");

class Session {

    var $username;     //Username given on sign-up
    var $userid;       //Random value generated on current login
    var $userlevel;    //The level to which the user pertains
    var $time;         //Time user was last active (page loaded)
    var $logged_in;    //True if user is logged in, false otherwise
    var $userinfo = array();  //The array holding all user info
    var $url;          //The page url current being viewed
    var $referrer;     //Last recorded site page viewed

    /**
     * Note: referrer should really only be considered the actual
     * page referrer in process.php, any other time it may be
     * inaccurate.
     */
    /* Class constructor */

    function Session() {
        $this->time = time();
        $this->startSession();
    }

    /**
     * startSession - Performs all the actions necessary to 
     * initialize this session object. Tries to determine if the
     * the user has logged in already, and sets the variables 
     * accordingly. Also takes advantage of this page load to
     * update the active visitors tables.
     */
    function startSession() {
        global $database;  //The database connection
        session_start();   //Tell PHP to start the session

        /* Determine if user is logged in */
        $this->logged_in = $this->checkLogin();

        /**
         * Set guest value to users not logged in, and update
         * active guests table accordingly.
         */
        if (!$this->logged_in) {
            $this->username = $_SESSION['username'] = GUEST_NAME;
            $this->userlevel = GUEST_LEVEL;
            $database->addActiveGuest($_SERVER['REMOTE_ADDR'], $this->time);
        }
        /* Update users last active timestamp */ else {
            $database->addActiveUser($this->username, $this->time);
        }

        /* Remove inactive visitors from database */
        $database->removeInactiveUsers();
        $database->removeInactiveGuests();

        /* Set referrer page */
        if (isset($_SESSION['url'])) {
            $this->referrer = $_SESSION['url'];
        } else {
            $this->referrer = "/";
        }

        /* Set current url */
        $this->url = $_SESSION['url'] = $_SERVER['PHP_SELF'];
    }

    /**
     * checkLogin - Checks if the user has already previously
     * logged in, and a session with the user has already been
     * established. Also checks to see if user has been remembered.
     * If so, the database is queried to make sure of the user's 
     * authenticity. Returns true if the user has logged in.
     */
    function checkLogin() {
        global $database;  //The database connection
        /* Check if user has been remembered */
        if (isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])) {
            $this->username = $_SESSION['username'] = $_COOKIE['cookname'];
            $this->userid = $_SESSION['userid'] = $_COOKIE['cookid'];
        }

        /* Username and userid have been set and not guest */
        if (isset($_SESSION['username']) && isset($_SESSION['userid']) &&
                $_SESSION['username'] != GUEST_NAME) {
            /* Confirm that username and userid are valid */
            if ($database->confirmUserID($_SESSION['username'], $_SESSION['userid']) != 0) {
                /* Variables are incorrect, user not logged in */
                unset($_SESSION['username']);
                unset($_SESSION['userid']);
                return false;
            }

            /* User is logged in, set class variables */
            $this->userinfo = $database->getUserInfo($_SESSION['username']);
            $this->username = $this->userinfo['username'];
            $this->userid = $this->userinfo['userid'];
            $this->userlevel = $this->userinfo['userlevel'];
            return true;
        }

        /* User not logged in */ else {
            return false;
        }
    }
					 function getPareiguTipai() {
						  global $database;
		$query = "  SELECT *
					FROM `pareigostipai`";
		$data = mysqli_query($database->connection,$query);
		return $data;
	}
	 function getImones() {
						  global $database;
		$query = "  SELECT *
					FROM `imone`";
		$data = mysqli_query($database->connection,$query);
		return $data;
	}
    /**
     * login - The user has submitted his username and password
     * through the login form, this function checks the authenticity
     * of that information in the database and creates the session.
     * Effectively logging in the user if all goes well.
     */
    function login($subuser, $subpass, $subremember) {
        global $database, $form;  //The database and form object

        /* Username error checking */
        $field = "user";  //Use field name for username
        if (!$subuser || strlen($subuser = trim($subuser)) == 0) {
            $form->setError($field, "* Neįvestas vartotojo vardas");
        } else {
            /* Check if username is not alphanumeric */
            if (!eregi("^([0-9a-z])*$", $subuser)) {
                $form->setError($field, "* Vartotojo vardas gali būti sudarytas
                    <br>&nbsp;&nbsp;tik iš raidžių ir skaičių");
            }
        }

        /* Password error checking */
        $field = "pass";  //Use field name for password
        if (!$subpass) {
            $form->setError($field, "* Neįvestas slaptažodis");
        }

        /* Return if form errors exist */
        if ($form->num_errors > 0) {
            return false;
        }

        /* Checks that username is in database and password is correct */
        $subuser = stripslashes($subuser);
        $result = $database->confirmUserPass($subuser, md5($subpass));

        /* Check error codes */
        if ($result == 1) {
            $field = "user";
            $form->setError($field, "* Tokio vartotojo nėra");
        } else if ($result == 2) {
            $field = "pass";
            $form->setError($field, "* Neteisingas slaptažodis");
        }

        /* Return if form errors exist */
        if ($form->num_errors > 0) {
            return false;
        }

        /* Username and password correct, register session variables */
        $this->userinfo = $database->getUserInfo($subuser);
        $this->username = $_SESSION['username'] = $this->userinfo['username'];
        $this->userid = $_SESSION['userid'] = $this->generateRandID();
        $this->userlevel = $this->userinfo['userlevel'];

        /* Insert userid into database and update active users table */
        $database->updateUserField($this->username, "userid", $this->userid);
        $database->addActiveUser($this->username, $this->time);
        $database->removeActiveGuest($_SERVER['REMOTE_ADDR']);

        /**
         * This is the cool part: the user has requested that we remember that
         * he's logged in, so we set two cookies. One to hold his username,
         * and one to hold his random value userid. It expires by the time
         * specified in constants.php. Now, next time he comes to our site, we will
         * log him in automatically, but only if he didn't log out before he left.
         */
        if ($subremember) {
            setcookie("cookname", $this->username, time() + COOKIE_EXPIRE, COOKIE_PATH);
            setcookie("cookid", $this->userid, time() + COOKIE_EXPIRE, COOKIE_PATH);
        }

        /* Login completed successfully */
        return true;
    }

    /**
     * logout - Gets called when the user wants to be logged out of the
     * website. It deletes any cookies that were stored on the users
     * computer as a result of him wanting to be remembered, and also
     * unsets session variables and demotes his user level to guest.
     */
    function logout() {
        global $database;  //The database connection
        /**
         * Delete cookies - the time must be in the past,
         * so just negate what you added when creating the
         * cookie.
         */
        if (isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])) {
            setcookie("cookname", "", time() - COOKIE_EXPIRE, COOKIE_PATH);
            setcookie("cookid", "", time() - COOKIE_EXPIRE, COOKIE_PATH);
        }

        /* Unset PHP session variables */
        unset($_SESSION['username']);
        unset($_SESSION['userid']);

        /* Reflect fact that user has logged out */
        $this->logged_in = false;

        /**
         * Remove from active users table and add to
         * active guests tables.
         */
        $database->removeActiveUser($this->username);
        $database->addActiveGuest($_SERVER['REMOTE_ADDR'], $this->time);

        /* Set user level to guest */
        $this->username = GUEST_NAME;
        $this->userlevel = GUEST_LEVEL;
    }

    /**
     * register - Gets called when the user has just submitted the
     * registration form. Determines if there were any errors with
     * the entry fields, if so, it records the errors and returns
     * 1. If no errors were found, it registers the new user and
     * returns 0. Returns 2 if registration failed.
     */
    function register($subuser,$subusername,$sublastname,$subaddress,$subnr, $subpass, $subemail,$imone) {
        global $database, $form;   //The database, form and mailer object

        /* Username error checking */
        $field = "user";  //Use field name for username
        if (!$subuser || strlen($subuser = trim($subuser)) == 0) {
            $form->setError($field, "* Vartotojas neįvestas");
        } else {
            /* Spruce up username, check length */
            $subuser = stripslashes($subuser);
            if (strlen($subuser) < 5) {
                $form->setError($field, "* Vartotojo vardas turi mažiau kaip 5 simbolius");
            } else if (strlen($subuser) > 30) {
                $form->setError($field, "* Vartotojo vardas virš 30 simbolių");
            }
            /* Check if username is not alphanumeric */ else if (!eregi("^([0-9a-z])+$", $subuser)) {
                $form->setError($field, "* Vartotojo vardas gali būti sudarytas
                    <br>&nbsp;&nbsp;tik iš raidžių ir skaičių");
            }
            /* Check if username is reserved */ else if (strcasecmp($subuser, GUEST_NAME) == 0) {
                $form->setError($field, "* Rezervuotas vartotojo vardas");
            }
            /* Check if username is already in use */ else if ($database->usernameTaken($subuser)) {
                $form->setError($field, "* Toks vartotojo vardas jau yra");
            }
            /* Check if username is banned */ else if ($database->usernameBanned($subuser)) {
                $form->setError($field, "* Vartotojas užblokuotas");
            }
        }
		$field = "username";
		  $subusername = stripslashes($subusername);
            if (strlen($subusername) ==0) {
                $form->setError($field, "* Kliento vardas neįvestas");
			}
			else if (strlen($subusername) > 30) {
                $form->setError($field, "* Kliento vardas virš 30 simbolių");
            }
			else if (!eregi("^([a-z])+$", $subusername)) {
                $form->setError($field, "* Vardas gali būti sudarytas
                    <br>&nbsp;&nbsp;tik iš raidžių");
            }
			
			$field = "pavarde";
		  $sublastname = stripslashes($sublastname);
            if (strlen($sublastname) ==0) {
                $form->setError($field, "* Kliento pavardė neįvesta");
			}
			else if (strlen($sublastname) > 30) {
                $form->setError($field, "* Kliento pavardė virš 30 simbolių");
            }
			else if (!eregi("^([a-z])+$", $sublastname)) {
                $form->setError($field, "* Pavardė gali būti sudaryta
                    <br>&nbsp;&nbsp;tik iš raidžių");
            }
			$field = "adresas";
		  $subaddress = stripslashes($subaddress);
            if (strlen($subaddress) ==0) {
                $form->setError($field, "* Kliento adresas neįvestas");
			}
			
			else if (strlen($subaddress) > 50) {
                $form->setError($field, "* Kliento adresas virš 50 simbolių");
            }
			
				$field = "usernr";
		  $subnr = stripslashes($subnr);
            if (strlen($subnr) ==0) {
                $form->setError($field, "* Kliento numeris neįvestas");
			}
			else if (!eregi("^([0-9])+$", $subnr)) {
                $form->setError($field, "* Telefono numeris gali būti sudarytas
                    <br>&nbsp;&nbsp;tik iš skaičių");
            }
			else if (strlen($subnr) > 50) {
                $form->setError($field, "* Kliento numeris virš 15 simbolių");
            }
		
            
        /* Password error checking */
        $field = "pass";  //Use field name for password
        if (!$subpass) {
            $form->setError($field, "* Neįvestas slaptažodis");
        } else {
            /* Spruce up password and check length */
            $subpass = stripslashes($subpass);
            if (strlen($subpass) < 4) {
                $form->setError($field, "* Ne mažiau kaip 4 simboliai");
            }
            /* Check if password is not alphanumeric */ else if (!eregi("^([0-9a-z])+$", ($subpass = trim($subpass)))) {
                $form->setError($field, "* Slaptažodis gali būti sudarytas
                    <br>&nbsp;&nbsp;tik iš raidžių ir skaičių");
            }
            /**
             * Note: I trimmed the password only after I checked the length
             * because if you fill the password field up with spaces
             * it looks like a lot more characters than 4, so it looks
             * kind of stupid to report "password too short".
             */
        }

        /* Email error checking */
        $field = "email";  //Use field name for email
        if (!$subemail || strlen($subemail = trim($subemail)) == 0) {
            $form->setError($field, "* Neįvestas e-pašto adresas");
        } else {
            /* Check if valid email address */
            $regex = "^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
                    . "@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
                    . "\.([a-z]{2,}){1}$";
            if (!eregi($regex, $subemail)) {
                $form->setError($field, "* Klaidingas e-pašto adresas");
            }
            $subemail = stripslashes($subemail);
        }

        /* Errors exist, have user correct them */
        if ($form->num_errors > 0) {
            return 1;  //Errors with form
        }
        /* No errors, add the new account to the */ else {
            if ($database->addNewClient($subuser,$subusername,$sublastname,$subaddress,$subnr, md5($subpass), $subemail,$imone)) 
			{
                return 0;  //New user added succesfully
            } else {
                return 2;  //Registration attempt failed
            }
        }
    }
/**
     * register - Gets called when the user has just submitted the
     * registration form. Determines if there were any errors with
     * the entry fields, if so, it records the errors and returns
     * 1. If no errors were found, it registers the new user and
     * returns 0. Returns 2 if registration failed.
     */
    function register2($subusername,$subuser,$sublastname, $subpass,$pareigos,$imone, $subemail) {
        global $database, $form;   //The database, form and mailer object

        /* Username error checking */
        $field = "user";  //Use field name for username
        if (!$subuser || strlen($subuser = trim($subuser)) == 0) {
            $form->setError($field, "* Vartotojo vardas neįvestas");
        } else {
            /* Spruce up username, check length */
            $subuser = stripslashes($subuser);
            if (strlen($subuser) < 5) {
                $form->setError($field, "* Vartotojo vardas turi mažiau kaip 5 simbolius");
            } else if (strlen($subuser) > 30) {
                $form->setError($field, "* Vartotojo vardas virš 30 simbolių");
            }
            /* Check if username is not alphanumeric */ else if (!eregi("^([a-z])+$", $subuser)) {
                $form->setError($field, "* Vartotojo vardas gali būti sudarytas
                    <br>&nbsp;&nbsp;tik iš raidžių");
            }
            /* Check if username is reserved */
            /* Check if username is already in use */ 
            
            /* Check if username is banned */ else if ($database->usernameBanned($subuser)) {
                $form->setError($field, "* Vartotojas užblokuotas");
            }
        }
		$field = "username";
		  $subusername = stripslashes($subusername);
            if (strlen($subusername) ==0) {
                $form->setError($field, "* Darbuotojo slapyvardis neįvestas");
			}
			else if ($database->usernameTaken($subusername)) {
                $form->setError($field, "* Toks vartotojo vardas jau yra");
			}
			 else if (strcasecmp($subusername, GUEST_NAME) == 0) {
                $form->setError($field, "* Rezervuotas vartotojo vardas");
            }
			else if (strlen($subusername) > 30) {
                $form->setError($field, "* Darbuotojo slapyvardis virš 30 simbolių");
            }
			else if (!eregi("^([0-9a-z])+$", $subusername)) {
                $form->setError($field, "* Vardas gali būti sudarytas
                    <br>&nbsp;&nbsp;tik iš raidžių");
            }
			
			$field = "user2";
		  $sublastname = stripslashes($sublastname);
            if (strlen($sublastname) ==0) {
                $form->setError($field, "* Darbuotojo pavardė neįvesta");
			}
			else if (strlen($sublastname) > 30) {
                $form->setError($field, "* Darbuotojo pavardė virš 30 simbolių");
            }
			else if (!eregi("^([a-z])+$", $sublastname)) {
                $form->setError($field, "* Pavardė gali būti sudaryta
                    <br>&nbsp;&nbsp;tik iš raidžių");
            }
			
			
				$field = "pareigos";
		 $pareigos = stripslashes($pareigos);
            if (strlen($pareigos) ==0) {
                $form->setError($field, "* Pareigos neįvestos");
			}
		
		
            
        /* Password error checking */
        $field = "pass";  //Use field name for password
        if (!$subpass) {
            $form->setError($field, "* Neįvestas slaptažodis");
        } else {
            /* Spruce up password and check length */
            $subpass = stripslashes($subpass);
            if (strlen($subpass) < 4) {
                $form->setError($field, "* Ne mažiau kaip 4 simboliai");
            }
            /* Check if password is not alphanumeric */ else if (!eregi("^([0-9a-z])+$", ($subpass = trim($subpass)))) {
                $form->setError($field, "* Slaptažodis gali būti sudarytas
                    <br>&nbsp;&nbsp;tik iš raidžių ir skaičių");
            }
            /**
             * Note: I trimmed the password only after I checked the length
             * because if you fill the password field up with spaces
             * it looks like a lot more characters than 4, so it looks
             * kind of stupid to report "password too short".
             */
        }

        /* Email error checking */
        $field = "email";  //Use field name for email
        if (!$subemail || strlen($subemail = trim($subemail)) == 0) {
            $form->setError($field, "* Neįvestas e-pašto adresas");
        } else {
            /* Check if valid email address */
            $regex = "^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
                    . "@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
                    . "\.([a-z]{2,}){1}$";
            if (!eregi($regex, $subemail)) {
                $form->setError($field, "* Klaidingas e-pašto adresas");
            }
            $subemail = stripslashes($subemail);
        }

        /* Errors exist, have user correct them */
        if ($form->num_errors > 0) {
            return 1;  //Errors with form
        }
        /* No errors, add the new account to the */ else {
            if ($database->addNewEmployee($subuser,$subusername,$sublastname, md5($subpass),$pareigos, $subemail,$imone)) 
			{
                return 0;  //New user added succesfully
            } else {
                return 2;  //Registration attempt failed
            }
        }
    }
    /**
     * editAccount - Attempts to edit the user's account information
     * including the password, which it first makes sure is correct
     * if entered, if so and the new password is in the right
     * format, the change is made. All other fields are changed
     * automatically.
     */
    function editAccount($subcurpass, $subnewpass, $subemail) {
        global $database, $form;  //The database and form object
        /* New password entered */
        if ($subnewpass) {
            /* Current Password error checking */
            $field = "curpass";  //Use field name for current password
            if (!$subcurpass) {
                $form->setError($field, "* Neįvestas slaptažodis");
            } else {
                /* Check if password too short or is not alphanumeric */
                $subcurpass = stripslashes($subcurpass);
                if (strlen($subcurpass) < 4 ||
                        !eregi("^([0-9a-z])+$", ($subcurpass = trim($subcurpass)))) {
                    $form->setError($field, "* Slaptažodis gali būti sudarytas
                    <br>&nbsp;&nbsp;tik iš raidžių ir skaičių");
                }
                /* Password entered is incorrect */
                if ($database->confirmUserPass($this->username, md5($subcurpass)) != 0) {
                    $form->setError($field, "* Neteisingas slaptažodis");
                }
            }

            /* New Password error checking */
            $field = "newpass";  //Use field name for new password
            /* Spruce up password and check length */
            $subpass = stripslashes($subnewpass);
            if (strlen($subnewpass) < 4) {
                $form->setError($field, "* Slaptažodis per trumpas");
            }
            /* Check if password is not alphanumeric */ else if (!eregi("^([0-9a-z])+$", ($subnewpass = trim($subnewpass)))) {
                $form->setError($field, "* Slaptažodis gali būti sudarytas
                    <br>&nbsp;&nbsp;tik iš raidžių ir skaičių");
            }
        }
        /* Change password attempted */ else if ($subcurpass) {
            /* New Password error reporting */
            $field = "newpass";  //Use field name for new password
            $form->setError($field, "* Neįvestas naujas slaptažodis");
        }

        /* Email error checking */
        $field = "email";  //Use field name for email
        if ($subemail && strlen($subemail = trim($subemail)) > 0) {
            /* Check if valid email address */
            $regex = "^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
                    . "@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
                    . "\.([a-z]{2,}){1}$";
            if (!eregi($regex, $subemail)) {
                $form->setError($field, "* Neteisingas e-pašto adresas");
            }
            $subemail = stripslashes($subemail);
        }

        /* Errors exist, have user correct them */
        if ($form->num_errors > 0) {
            return false;  //Errors with form
        }

        /* Update password since there were no errors */
        if ($subcurpass && $subnewpass) {
            $database->updateUserField($this->username, "password", md5($subnewpass));
        }

        /* Change Email */
        if ($subemail) {
            $database->updateUserField($this->username, "email", $subemail);
        }

        /* Success! */
        return true;
    }

	function isSandelioVadovas()
	{
		global $database;
		$pareigos=$database->getPareigos($this->username);
		if($pareigos==1)
			return true;
	}
	function isSandelininkas()
	{
		global $database;
		$pareigos=$database->getPareigos($this->username);
		if($pareigos==2)
			return true;
	}
	function isKurjeris()
	{
		global $database;
		$pareigos=$database->getPareigos($this->username);
		if($pareigos==3)
			return true;
	}
	function isPardaveja()
	{
		global $database;
		$pareigos=$database->getPareigos($this->username);
		if($pareigos==4)
			return true;
	}
	function isImonesVadovas()
	{
		global $database;
		$pareigos=$database->getPareigos($this->username);
		if($pareigos==5)
			return true;
	}
    /**
     * isAdmin - Returns true if currently logged in user is
     * an administrator, false otherwise.
     */
    function isAdmin() {
        return ($this->userlevel == ADMIN_LEVEL ||
                $this->username == ADMIN_NAME);
    }

    function isClient() {
        return ($this->userlevel == USER_LEVEL);
    }
	
	function isEmployee() {
        return ($this->userlevel == MANAGER_LEVEL);
    }
    /**
     * generateRandID - Generates a string made up of randomized
     * letters (lower and upper case) and digits and returns
     * the md5 hash of it to be used as a userid.
     */
    function generateRandID() {
        return md5($this->generateRandStr(16));
    }

    /**
     * generateRandStr - Generates a string made up of randomized
     * letters (lower and upper case) and digits, the length
     * is a specified parameter.
     */
    function generateRandStr($length) {
        $randstr = "";
        for ($i = 0; $i < $length; $i++) {
            $randnum = mt_rand(0, 61);
            if ($randnum < 10) {
                $randstr .= chr($randnum + 48);
            } else if ($randnum < 36) {
                $randstr .= chr($randnum + 55);
            } else {
                $randstr .= chr($randnum + 61);
            }
        }
        return $randstr;
    }

}

/**
 * Initialize session object - This must be initialized before
 * the form object because the form uses session variables,
 * which cannot be accessed unless the session has started.
 */
$session = new Session;

/* Initialize form object */
$form = new Form;
?>