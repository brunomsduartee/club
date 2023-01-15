<?php
session_start();
include('dbcon.php');

if(isset($_GET['token']))
{
    $token = $_GET['token'];
    $verify_query = "SELECT verify_token, verify_status FROM users WHERE verify_token = '$token' LIMIT 1";
    $verify_query_run = mysqli_query($con, $verify_query);

    if(mysqli_num_rows($verify_query_run) > 0)
    {
        $row = mysqli_fetch_array($verify_query_run);
        if($row['verify_status'] == "0")
        {
            $clicked_token = $row['verify_token'];

            $update_query = $con -> prepare("UPDATE users SET verify_status = '1' WHERE verify_token = ?");
            $update_query -> bind_param('s', $clicked_token);

            if($update_query -> execute())
            {
                $_SESSION['status'] = "Verificado com sucesso";
                header("Location: login.php");
                exit(0);    
            }
            else
            {
                $_SESSION['status'] = "Erro na verificação :c";
                header("Location: login.php");
                exit(0);                    
            }
        }
        else
        {
            $_SESSION['status'] = "Email já verificado, faça login!";
            header("Location: login.php");
            exit(0);
        }
    }
    else
    {
        $_SESSION['status'] = "Token inválido";
        header("Location: login.php");
        exit();
    }
}
else
{
    $_SESSION['status'] = "Não permitido";
    header("Location: login.php");
    exit();
}
?>