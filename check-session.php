<?php
if(!empty($_SESSION['start_time']))
{
    if((time() - $_SESSION['start_time']) > 60*60)
    {
        session_destroy();

        $_SESSION['status'] = "Revalide o login";
        header("Location: login.php");
        exit(0);
    }
}
?>