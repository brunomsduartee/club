<?php

include('dbcon.php');
session_start();


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Enviar email de verificação
function send_email_verify ($name, $email, $verify_token) 
{
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->SMTPAuth   = true;
    
    $mail->Host       = 'smtp.outlook.com';
    $mail->Username   = 'saw.clube.test1@outlook.com';
    $mail->Password   = 'saw123saw';

    $mail->SMTPSecure = "tls";
    $mail->Port       = 587;

    $mail->setFrom('saw.clube.test1@outlook.com', $name);
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Verificação de email | Clube de filmes';

    $email_template = 
    "
    <h2>Você criou uma conta no nosso Clube de filmes</h2>
    <h5>Verifique o seu email para efetuar o login no link abaixo:</h5>
    <br/><br/>
    <a href='http://saw.pt/club/verify-email.php?token=$verify_token'>Clique aqui</a>
    ";

    $mail->Body = $email_template;
    $mail->send();
    // echo 'O email de verificação foi enviado!'; 

}

//Enviar email Reset password
function send_password_reset($get_name, $get_email, $token)
{
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->SMTPAuth   = true;
    
    $mail->Host       = 'smtp.outlook.com';
    $mail->Username   = 'saw.clube.test1@outlook.com';
    $mail->Password   = 'saw123saw';

    $mail->SMTPSecure = "tls";
    $mail->Port       = 587;

    $mail->setFrom('saw.clube.test1@outlook.com', $get_name);
    $mail->addAddress($get_email);

    $mail->isHTML(true);
    $mail->Subject = 'Resetar a password';

    $email_template = 
    "
    <h2>Olá,</h2>
    <h5>Resete a sua password clicando no link abaixo:</h5>
    <br/>
    <a href='http://saw.pt/club/change-password.php?token=$token&email=$get_email'>Clique aqui</a>
    ";

    $mail->Body = $email_template;
    $mail->send();
}

//Registar um user
if (isset($_POST['registerBtn']))
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $confirm_password = md5($_POST['confirm_password']);
    $verify_token = md5(rand());

    $pattern_nome =  '/^[A-Za-z]{3,}$/';

    // Existe email
    $check_email_query = "SELECT email FROM users WHERE email = '$email' LIMIT 1";
    $check_email_query_run = mysqli_query($con, $check_email_query);

    if(mysqli_num_rows($check_email_query_run) > 0)
    {
        $_SESSION['status'] = "Email já existente";
        header("Location: login.php");
        exit(0);
    }
    else
    {
        if(preg_match($pattern_nome, $name))
        {
            if(filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                if($password == $confirm_password)
                {
                    if (isset($_FILES['image_upload']['name']))
                    {
                        $fileName = basename($_FILES['image_upload']['name']);
                        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
                    

                        $allowTypes = array('jpg', 'png', 'jpeg');
                        if(in_array($fileType, $allowTypes))
                        {
                            $image = $_FILES['image_upload']['tmp_name'];
                            $imgContent = addslashes(file_get_contents($image));

                        
                            // Registar user
                            $query = "INSERT INTO users (name, email, password, verify_token, image) VALUES ('$name', '$email', '$password', '$verify_token', '$imgContent')"; 
                            $query_run = mysqli_query($con, $query);

                            if($query_run)
                            {
                                send_email_verify("$name", "$email", "$verify_token");
                                
                                $_SESSION['status'] = "Registo com sucesso! Verifique o seu email.";
                                header("Location: register.php");
                                exit(0);
                            }
                            else
                            {
                                $_SESSION['status'] = "Ocorreu um erro :c";
                                header("Location: register.php");
                                exit(0);
                            }
                        } 
                        else
                        {
                            $_SESSION['status'] = "Tipo de ficheiro inválido (apenas JPG, JPEG e PNG)";
                            header("Location: register.php");
                            exit(0);
                        }
                    }
                    else
                    {
                        $_SESSION['status'] = "Insira uma foto de perfil, por favor.";
                        header("Location: register.php");
                        exit(0);
                    }
                }
                else
                {
                    $_SESSION['status'] = "Passwords não coincidem";
                    header("Location: register.php");
                    exit(0);
                }
            }
            else
            {
                $_SESSION['status'] = "Email inválido";
                header("Location: register.php");
                exit(0);
            }
        }
        else
        {
            $_SESSION['status'] = "Nome inválido";
            header("Location: register.php");
            exit(0);
        }
    }
} 
// Login
else if (isset($_POST['loginBtn'])) 
{

    //se tiver logado vai para dashboard
    if(isset($_SESSION['authenticated']))
    {
        $_SESSION['status'] = "está logado.";
        header("Location: dashboard.php");
        exit(0); 
    }

    if(!empty(trim($_POST['email'])) && !empty(trim($_POST['password'])))
    {
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $password = mysqli_real_escape_string($con, md5($_POST['password']));

        $login_query = "SELECT * FROM users WHERE email='$email' AND password='$password' LIMIT 1";
        $login_query_run = mysqli_query($con, $login_query);

        if(mysqli_num_rows($login_query_run) > 0)
        {
            $row = mysqli_fetch_array($login_query_run);

            if($row['verify_status'] == "1")
            {
                $_SESSION['authenticated'] = TRUE;
                $_SESSION['auth_user'] = [
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'image' => $row['image'],
                    'role' => $row['role'],
                ];

                if(isset($_REQUEST['rememberMe']))
                {
                    $_SESSION['email'] = $email;
                    $_SESSION['start_time'] = time();
                }

                $_SESSION['status'] = "Login com sucesso!";
                header("Location: dashboard.php");
                exit(0); 
            }
            else
            {
                $_SESSION['status'] = "Verifique o seu email para fazer o login";
                header("Location: login.php");
                exit(0);                
            }
        }
        else
        {
            $_SESSION['status'] = "Credenciais inválidas";
            header("Location: login.php");
            exit(0);
        }
    }
    else
    {
        $_SESSION['status'] = "Todos os campos são obrigatórios";
        header("Location: login.php");
        exit(0);
    }
}
//Reenviar email verificação
else if (isset($_POST['resendBtn'])) 
{
    if(!empty(trim($_POST['email'])))
    {
        $email = mysqli_real_escape_string($con, $_POST['email']);

        $check_email_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $check_email_query_run = mysqli_query($con, $check_email_query);

        if(mysqli_num_rows($check_email_query_run) > 0)
        {
            $row = mysqli_fetch_array($check_email_query_run);
            if($row['verify_status'] == '0')
            {
                $name = $row['name'];
                $verify_token = $row['verify_token'];
                $email = $row['email'];

                send_email_verify($name, $email,$verify_token);

                $_SESSION['status'] = "Verificação enviada, verifique o seu email";
                header("Location: login.php");
                exit(0);
            }
            else
            {
                $_SESSION['status'] = "O email inserido já está verificado, faça login!";
                header("Location: login.php");
                exit(0);
            }
        }
        else
        {
            $_SESSION['status'] = "Email não registado, por favor registe!";
            header("Location: register.php");
            exit(0);
        }
    }
    else
    {
        $_SESSION['status'] = "Introduza o email por favor";
        header("Location: resend-email.php");
        exit(0);

    }
}
//Enviar email recuperação password
else if (isset($_POST['send_email_recover_password_btn']))
{
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $token = md5(rand());


    $check_email = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $check_email_run = mysqli_query($con, $check_email);

    if(mysqli_num_rows($check_email_run) > 0)
    {
        $row = mysqli_fetch_array($check_email_run);

        $get_name = $row['name'];
        $get_email = $row['email'];

        $update_token = "UPDATE users SET verify_token='$token' WHERE email='$get_email' LIMIT 1";
        $update_token_run = mysqli_query($con, $update_token);

        if($update_token_run)
        {
            send_password_reset($get_name, $get_email, $token);
            $_SESSION['status'] = "Email para resetar password enviado, verifique o seu email";
            header("Location: login.php");
            exit(0);
        }
        else
        {
            $_SESSION['status'] = "Something went wrong :C #1 ";
            header("Location: reset-password.php");
            exit(0);
        }
    }
    else
    {
        $_SESSION['status'] = "Email não registado";
        header("Location: reset-password.php");
        exit(0);
    }
}
//Alterar password
else if (isset($_POST['reset_pass_btn']))
{
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    $token = mysqli_real_escape_string($con, $_POST['password_token']);

    if(!empty($token))
    {
        if(!empty($token) && !empty($new_password) && !empty($confirm_password))
        {
            //Verificação do token
            $check_token ="SELECT verify_token FROM users WHERE verify_token='$token' LIMIT 1";
            $check_token_run = mysqli_query($con, $check_token);

            if(mysqli_num_rows($check_token_run) > 0)
            {
                if($new_password == $confirm_password)
                {
                    $update_password = "UPDATE users SET password='$new_password' WHERE verify_token='$token' LIMIT 1";
                    $update_password_run = mysqli_query($con, $update_password);

                    if($update_password_run)
                    {
                        $new_token = md5(rand());
                        $update_to_new_token = "UPDATE users SET verify_token='$new_token' WHERE verify_token='$token' LIMIT 1";
                        $update_to_new_token_run = mysqli_query($con, $update_to_new_token);

                        $_SESSION['status'] = "Password alterada com sucesso";
                        header("Location: login.php");
                        exit(0);
                    }
                    else
                    {
                        $_SESSION['status'] = "Something went wrong :c #2";
                        header("Location: change-password.php?token=$token&email=$email");
                        exit(0);
                    }
                }
                else
                {
                    $_SESSION['status'] = "As passwords não coincidem";
                    header("Location: change-password.php?token=$token&email=$email");
                    exit(0);
                }
            }
            else
            {
                $_SESSION['status'] = "Token inválido";
                header("Location: change-password.php?token=$token&email=$email");
                exit(0);
            }
        }
        else
        {
            $_SESSION['status'] = "Preencha todos os campos";
            header("Location: change-password.php?token=$token&email=$email");
            exit(0);
        }
    }
    else
    {
        $_SESSION['status'] = "Token não disponibilizado.";
        header("Location: change-password.php");
        exit(0);
    }
}

//Alterar Nome e Imagem no dashboard
else if (isset($_POST['alterarBtn']))
{
    $new_name = $_POST['new_name'];
    $email = $_SESSION['auth_user']['email'];

    $pattern_nome =  '/^[A-Za-z]{3,}$/';


    if(!empty($new_name))
    {
        if(preg_match($pattern_nome, $new_name))
        {
            if (isset($_FILES['image_update']['name']))
            {
                $fileName = basename($_FILES['image_update']['name']);
                $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
            

                $allowTypes = array('jpg', 'png', 'jpeg');
                if(in_array($fileType, $allowTypes))
                {
                    $image = $_FILES['image_update']['tmp_name'];
                    $imgContent = addslashes(file_get_contents($image));

                
                    // Update Nome e Foto
                    $query = "UPDATE users SET name='$new_name', image='$imgContent' WHERE email='$email'"; 
                    $query_run = mysqli_query($con, $query);

                    if($query_run)
                    {   
                        session_destroy();

                        $_SESSION['status'] = "Dados alterados com sucesso!";
                        header("Location: login.php");
                        exit(0);
                    }
                    else
                    {
                        $_SESSION['status'] = "Ocorreu um erro :c";
                        header("Location: dashboard.php");
                        exit(0);
                    }
                } 
                else
                {
                    $_SESSION['status'] = "Tipo de ficheiro inválido (apenas JPG, JPEG e PNG)";
                    header("Location: dashboard.php");
                    exit(0);
                }
            }
            else
            {
                $_SESSION['status'] = "Insira uma foto de perfil, por favor.";
                header("Location: dashboard.php");
                exit(0);
            }
        }
        else
        {
            $_SESSION['status'] = "Nome inválido";
            header("Location: dashboard.php");
            exit(0);
        }
    }
    else
    {
        $_SESSION['status'] = "Insira o novo nome, por favor!";
        header("Location: dashboard.php");
        exit(0);
    }
}

?>