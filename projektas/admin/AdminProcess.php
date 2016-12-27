<?php

include("../include/session.php");

class AdminProcess {
    /* Class constructor */

    function AdminProcess() {
        global $session;
        /* Make sure administrator is accessing page */
        if (!$session->isAdmin()) {
            header("Location: ../index.php");
            return;
        }
        /* Admin submitted update user level form */
        if (isset($_POST['subupdlevel'])) {
            $this->procUpdateLevel();
        }
        /* Admin submitted delete user form */ else if (isset($_GET['d'])) {
            $this->procDeleteUser();
        }
			 /* Admin submitted delete client form */ else if (isset($_GET['c'])) {
            
			 $this->procDeleteClient();
        }
			 /* Admin submitted delete employee form */ else if (isset($_GET['e'])) {
            
			 $this->procDeleteEmployee();
        }
        /* Admin submitted delete inactive users form */ else if (isset($_POST['subdelinact'])) {
            $this->procDeleteInactive();
        }
        /* Admin submitted ban user form */ else if (isset($_GET['b'])) {
            $this->procBanUser();
        }
        /* Admin submitted delete banned user form */ else if (isset($_GET['db'])) {
            $this->procDeleteBannedUser();
        }
        /* Should not get here, redirect to home page */ else {
            header("Location: ../index.php");
        }
    }

    
    /**
     * procDeleteUser - If the submitted username is correct,
     * the user is deleted from the database.
     */
    function procDeleteUser() {
        global $session, $database, $form;
        /* Username error checking */
        $subuser = $this->checkUsername("deluser");
        /* Errors exist, have user correct them */
        if ($form->num_errors > 0) {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);
        }
        /* Delete user from database */ else {
            $q = "DELETE FROM " . TBL_USERS . " WHERE username = '$subuser'";
            $database->query($q);
            header("Location: " . $session->referrer);
        }
    }
    /**
     * procDeleteUser - If the submitted username is correct,
     * the client is deleted from the database.
     */
    function procDeleteClient() {
        global $session, $database, $form;
        /* Username error checking */
        $subuser = $this->checkUsername("deluser");
        /* Errors exist, have user correct them */
        if ($form->num_errors > 0) {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);
        }
        /* Delete user from database */ else {
            $q = "DELETE FROM " . TBL_KLIENTAS . " WHERE slapyvardis = '$subuser'";
            $database->query($q);
			  $q2 = "DELETE FROM " . TBL_USERS . " WHERE username = '$subuser'";
            $database->query($q2);
            header("Location: " . $session->referrer);
        }
    }
	    /**
     * procDeleteEmployee - If the submitted username is correct,
     * the employee is deleted from the database.
     */
    function procDeleteEmployee() {
        global $session, $database, $form;
        /* Username error checking */
        $subuser = $this->checkUsername("deluser");
        /* Errors exist, have user correct them */
        if ($form->num_errors > 0) {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);
        }
        /* Delete user from database */ else {
            $q = "DELETE FROM " . TBL_DARBUOTOJAI . " WHERE slapyvardis = '$subuser'";
            $database->query($q);
			  $q2 = "DELETE FROM " . TBL_USERS . " WHERE username = '$subuser'";
            $database->query($q2);
            header("Location: " . $session->referrer);
        }
    }
    /**
     * procDeleteInactive - All inactive users are deleted from
     * the database, not including administrators. Inactivity
     * is defined by the number of days specified that have
     * gone by that the user has not logged in.
     */
    function procDeleteInactive() {
        global $session, $database;
        $inact_time = $session->time - $_POST['inactdays'] * 24 * 60 * 60;
        $q = "DELETE FROM " . TBL_USERS . " WHERE timestamp < $inact_time "
                . "AND userlevel != " . ADMIN_LEVEL;
        $database->query($q);
        header("Location: " . $session->referrer);
    }

    

    /**
     * checkUsername - Helper function for the above processing,
     * it makes sure the submitted username is valid, if not,
     * it adds the appropritate error to the form.
     */
    function checkUsername($uname, $ban = false) {
        global $database, $form;
        /* Username error checking */
        $subuser = $_REQUEST[$uname];
        $field = $uname;  //Use field name for username
        if (!$subuser || strlen($subuser = trim($subuser)) == 0) {
            $form->setError($field, "* Username not entered<br>");
        } else {
            /* Make sure username is in database */
            $subuser = stripslashes($subuser);
            if (strlen($subuser) < 5 || strlen($subuser) > 30 ||
                    !eregi("^([0-9a-z])+$", $subuser) ||
                    (!$ban && !$database->usernameTaken($subuser))) {
                $form->setError($field, "* Username does not exist<br>");
            }
        }
        return $subuser;
    }

}

/* Initialize process */
$adminprocess = new AdminProcess;
?>