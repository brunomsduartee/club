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
//email de aviso quando a conta é bloqueada
function send_email_block_account($email)
{
    $admin = 'brunoduarteems@gmail.com';
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->SMTPAuth   = true;
    
    $mail->Host       = 'smtp.outlook.com';
    $mail->Username   = 'saw.clube.test1@outlook.com';
    $mail->Password   = 'saw123saw';

    $mail->SMTPSecure = "tls";
    $mail->Port       = 587;

    $mail->setFrom('saw.clube.test1@outlook.com');
    $mail->addAddress($email, $admin);

    $mail->isHTML(true);
    $mail->Subject = 'Conta bloqueada';

    $email_template = 
    "
    <h1>Olá,</h1>
    <br/>
    <h5>A sua conta foi bloqueada temporáriamente devido a introduzir 5 vezes as credenciais inválidas... Volte a tentar daqui a 1 hora.</h5>
    <h10> Email da conta bloqueada : $email </h10>
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
    // $check_email_query = "SELECT email FROM users WHERE email = '$email' LIMIT 1";
    // $check_email_query_run = mysqli_query($con, $check_email_query);

    $query = $con -> prepare("SELECT email FROM users WHERE email = ? LIMIT 1");
    $query -> bind_param('s', $email);
    $query -> execute();
    $query -> store_result();
    
    

    if($query -> num_rows > 0)
    {
        $_SESSION['status'] = "Email já existente";
        header("Location: login.php");
        exit(0);
        
        $query -> close();
    }
    else
    {
        $query -> close();

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
                            $imgContent = file_get_contents($image);

                        
                            $stmt = $con->prepare("INSERT INTO users (name, email, password, verify_token, image) VALUES (?, ?, ?, ?, ?)");
                            $stmt-> bind_param("sssss", $name, $email, $password, $verify_token, $imgContent);
                            
                            if ($stmt-> execute()) 
                            {
                                send_email_verify("$name", "$email", "$verify_token");
                                
                                $_SESSION['status'] = "Registo com sucesso! Verifique o seu email.";
                                header("Location: register.php");
                                exit(0);

                                $stmt-> close();
                            }
                            else
                            {
                                $_SESSION['status'] = "Ocorreu um erro :c";
                                header("Location: register.php");
                                exit(0);

                                $stmt-> close();
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
        $email = $_POST['email'];
        $password = md5($_POST['password']);

        $check_email =  $con -> prepare("SELECT email FROM users WHERE email= ?");
        $check_email -> bind_param('s', $email);
        $check_email -> execute();
        $check_email -> store_result();

        if($check_email -> num_rows > 0)
        {
            $check_email -> close();

            $login_query = $con -> prepare("SELECT name, email, image, verify_status, role FROM users WHERE email= ? AND password= ?");
            $login_query -> bind_param('ss', $email, $password);
            $login_query -> execute();
            $login_query -> store_result();
            $login_query -> bind_result($name, $email, $image, $verify_status, $role);
            $login_query->fetch();

            if($login_query -> num_rows > 0)
            {
                $is_blocked = $con -> prepare("SELECT blocked FROM users WHERE email= ?");
                $is_blocked -> bind_param('s', $email);
                $is_blocked -> execute();
                $is_blocked -> store_result();
                $is_blocked -> bind_result($blocked);
                $is_blocked -> fetch();

                if($blocked == NULL)
                {
                    if($verify_status == 1)
                    {
                        $login_query -> close();

                        $_SESSION['authenticated'] = TRUE;
                        $_SESSION['auth_user'] = [
                            'name' => $name,
                            'email' => $email,
                            'image' => $image,
                            'role' => $role,
                        ];

                        if(isset($_REQUEST['rememberMe']))
                        {
                            $_SESSION['email'] = $email;
                            $_SESSION['start_time'] = time();
                        }

                        $clear_errors = $con -> prepare("UPDATE users SET count_erros = 0, blocked = NULL WHERE email = ?");
                        $clear_errors -> bind_param('s', $email);
                        $clear_errors -> execute();
                        $clear_errors -> close();

                        $_SESSION['status'] = "Login com sucesso!";
                        header("Location: dashboard.php");
                        exit(0); 
                    }
                    else
                    {
                        $login_query -> close();

                        $_SESSION['status'] = "Verifique o seu email para fazer o login";
                        header("Location: login.php");
                        exit(0);                
                    }
                }
                else
                {
                    // $date = date("Y-m-d H:i:s");
// (strtotime($date) - strtotime($blocked) - 3600) > 10
                    if((strtotime(date("Y-m-d H:i:s")) - strtotime($blocked) - 3600) > 10)
                    {
                        if($verify_status == 1)
                        {
                            $login_query -> close();

                            $_SESSION['authenticated'] = TRUE;
                            $_SESSION['auth_user'] = [
                                'name' => $name,
                                'email' => $email,
                                'image' => $image,
                                'role' => $role,
                            ];

                            if(isset($_REQUEST['rememberMe']))
                            {
                                $_SESSION['email'] = $email;
                                $_SESSION['start_time'] = time();
                            }

                            $clear_errors = $con -> prepare("UPDATE users SET count_erros = 0, blocked = NULL WHERE email = ?");
                            $clear_errors -> bind_param('s', $email);
                            $clear_errors -> execute();
                            $clear_errors -> close();

                            $_SESSION['status'] = "Login com sucesso!";
                            header("Location: dashboard.php");
                            exit(0); 
                        }
                        else
                        {
                            $login_query -> close();

                            $_SESSION['status'] = "Verifique o seu email para fazer o login";
                            header("Location: login.php");
                            exit(0);                
                        }
                    }
                    else
                    {
                        $_SESSION['status'] = "A sua conta está temporáriamente bloqueada, aguarde...";
                        header("Location: login.php");
                        exit(0);    
                    }
                }
            }
            else
            {
                $get_user = $con -> prepare("SELECT count_erros FROM users WHERE email = ?");
                $get_user -> bind_param('s', $email);
                $get_user -> execute();
                $get_user -> store_result();
                $get_user -> bind_result($count);
                $get_user -> fetch();

                $new_count = $count + 1;

                if($new_count < 5)
                {
                    //adicionar um erro count_erros

                    $add_error = $con -> prepare("UPDATE users SET count_erros = ? WHERE email = ? ");
                    $add_error -> bind_param('is', $new_count, $email);
                    $add_error -> execute();
                    $add_error -> close();
                    
                    $_SESSION['status'] = "Credenciais inválidas";
                    header("Location: login.php");
                    exit(0);
                }
                else
                {
                    // Bloquear a conta temporáriamente 

                    $update_count = $con -> prepare("UPDATE users SET count_erros = ?, blocked = CURRENT_TIMESTAMP() WHERE email = ?");
                    $update_count -> bind_param('is', $new_count, $email);
                    $update_count -> execute();
                    $update_count -> close();

                    //Enviar email de aviso ao user bloqueado e admin

                    send_email_block_account("$email");

                    $_SESSION['status'] = "Conta bloqueada! Muitos erros!";
                    header("Location: login.php");
                    exit(0);
                }
            }
        }
        else
        {
            $check_email -> close();

            $_SESSION['status'] = "Não existe uma conta com este email";
            header("Location: register.php");
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

        // $check_email_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        // $check_email_query_run = mysqli_query($con, $check_email_query);

        $resend_query = $con -> prepare("SELECT name, email, verify_status, verify_token FROM users WHERE email= ? LIMIT 1");
        $resend_query -> bind_param('s', $email);
        $resend_query -> execute();
        $resend_query -> store_result();
        $resend_query -> bind_result($name, $email, $verify_status, $verify_token);
        $resend_query->fetch();

        if($resend_query -> num_rows > 0)
        {
            

            if($verify_status == 0)
            {
                send_email_verify($name, $email, $verify_token);

                $_SESSION['status'] = "Verificação enviada, verifique o seu email";
                header("Location: login.php");
                exit(0);

                $resend_query -> close();
            }
            else
            {
                $_SESSION['status'] = "O email inserido já está verificado, faça login!";
                header("Location: login.php");
                exit(0);

                $resend_query -> close();
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


    // $check_email = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    // $check_email_run = mysqli_query($con, $check_email);

    $recuperacao_query = $con -> prepare("SELECT name, email, verify_status, verify_token FROM users WHERE email= ? LIMIT 1");
    $recuperacao_query -> bind_param('s', $email);
    $recuperacao_query -> execute();
    $recuperacao_query -> store_result();
    $recuperacao_query -> bind_result($name, $email, $verify_status, $verify_token);
    $recuperacao_query->fetch();


    if($recuperacao_query -> num_rows > 0)
    {

        $update_token = "UPDATE users SET verify_token='$token' WHERE email='$email' LIMIT 1";
        $update_token_run = mysqli_query($con, $update_token);

        if($update_token_run)
        {
            send_password_reset($name, $email, $token);
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
    $new_password = mysqli_real_escape_string($con, md5($_POST['new_password']));
    $confirm_password = mysqli_real_escape_string($con, md5($_POST['confirm_password']))    ;

    $token = mysqli_real_escape_string($con, $_POST['password_token']);

    if(!empty($token))
    {
        if(!empty($token) or !empty($new_password) or !empty($confirm_password))
        {

            $check_token = $con -> prepare("SELECT verify_token FROM users WHERE verify_token= ? ");
            $check_token -> bind_param('s', $token);
            $check_token -> execute();
            $check_token -> store_result();
            $check_token -> bind_result($verify_token);
            $check_token -> fetch();
            //Verificação do token
            // $check_token ="SELECT verify_token FROM users WHERE verify_token='$token'";
            // $check_token_run = mysqli_query($con, $check_token);

            if($check_token -> num_rows > 0)
            {
                if($new_password == $confirm_password)
                {
                    $update_pasword = $con -> prepare("UPDATE users SET password= ? WHERE verify_token = ?");
                    $update_pasword -> bind_param('ss', $new_password, $token);
                    

                    // $update_password = "UPDATE users SET password='$new_password' WHERE verify_token='$token' LIMIT 1";
                    // $update_password_run = mysqli_query($con, $update_password);

                    if($update_pasword -> execute())
                    {
                        $update_pasword -> close();
                        $new_token = md5(rand());

                        $update_to_new_token = $con -> prepare("UPDATE users SET verify_token='$new_token' WHERE verify_token= ?");
                        $update_to_new_token -> bind_param('s', $token);
                        $update_to_new_token -> execute();
                        $update_to_new_token -> close();

                            // $update_to_new_token = "UPDATE users SET verify_token='$new_token' WHERE verify_token='$token' LIMIT 1";
                            // $update_to_new_token_run = mysqli_query($con, $update_to_new_token);

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
                    $imgContent = file_get_contents($image);

                    $change_query = $con -> prepare("UPDATE users SET name = ?, image = ? WHERE email = ? ");
                    $change_query -> bind_param('sss', $new_name, $imgContent, $email);

                    if($change_query -> execute())
                    {   
                        $_SESSION['status'] = "Dados alterados com sucesso!";
                        header("Location: login.php");
                        exit(0);

                        session_destroy();
                        
                        $change_query -> close();
                    }
                    else
                    {
                        $_SESSION['status'] = "Ocorreu um erro :c";
                        header("Location: dashboard.php");
                        exit(0);

                        $change_query -> close();
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