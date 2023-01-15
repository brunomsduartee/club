<?php
if(!empty($_SESSION['start_time']))
{
    if((time() - $_SESSION['start_time']) > 60*60)
    {
        session_destroy();
    }
}
?>