<?php
require(dirname(__FILE__) . '/includes/bootstrap.php');

//Getting Current User ID
$userID = buckys_is_logged_in();

if( isset($_POST['action']) && $_POST['action'] == 'delete_account' )
{    
    $isValid = true;
    if(!$_POST['userID'] || !$_POST['userIDHash'] || !buckys_check_id_encrypted($_POST['userID'], $_POST['userIDHash']) || $userID != $_POST['userID'])
    {
        buckys_redirect("/index.php");
    }
    if(!$_POST['password'] || !$_POST['password2'])
    {
        buckys_add_message(MSG_INVALID_REQUEST, MSG_TYPE_ERROR);
        $isValid = false;
    }else if($_POST['password'] != $_POST['password2']){
        buckys_redirect("/change_password.php", MSG_NOT_MATCH_PASSWORD, MSG_TYPE_ERROR);
        $isValid = false;
    }
    
    //Check Current Password
    $data = BuckysUser::getUserData($userID);
    if(!$data)
        buckys_redirect("/index.php");
        
    if(!buckys_validate_password($_POST['password'], $data['password']))
    {
        buckys_add_message(MSG_CURRENT_PASSWORD_NOT_CORRECT, MSG_TYPE_ERROR);
        $isValid = false;
    }
    if($isValid)
    {
        //Delete Account
        BuckysUser::deleteUserAccount($userID);
        unset($_SESSION['userID']);

        setcookie('bkuid0', null, time() - 1000, "/", "buckysroom.com");
        setcookie('bkuid1', null, time() - 1000, "/", "buckysroom.com");
        setcookie('bkuid2', null, time() - 1000, "/", "buckysroom.com");

        buckys_session_destroy();
        
        buckys_session_start();

        buckys_redirect('/index.php', MSG_ACCOUNT_DELETED);
    }

}


buckys_enqueue_stylesheet('account.css');
buckys_enqueue_stylesheet('info.css');

$BUCKYS_GLOBALS['content'] = 'delete_account';

$BUCKYS_GLOBALS['title'] = "Delete Account - BuckysRoom";

require(DIR_FS_TEMPLATE . $BUCKYS_GLOBALS['template'] . "/" . $BUCKYS_GLOBALS['layout'] . ".php");  
