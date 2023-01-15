<?php 
$page_title = "Login";
include('includes/header.php');
include('includes/navbar.php');
session_start();

if(!empty($_SESSION['start_time']))
{
    if((time() - $_SESSION['start_time']) > 10)
    {
        session_destroy();

        $_SESSION['status'] = "remember me expirado";
        header("Location: login.php");
        exit(0);
    }

    if(isset($_SESSION['email']))
    {
        $_SESSION['authenticated'] = TRUE;
        $_SESSION['start_time'] = time();
    }
}

if(isset($_SESSION['authenticated']))
    {
        $_SESSION['status'] = "está logado.";
        header("Location: dashboard.php");
        exit(0); 
    }
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <?php
                    if(isset($_SESSION['status']))
                    {
                        ?>
                        <div class="alert alert-success">
                            <h5><?= $_SESSION['status']; ?></h5>
                        </div>
                        <?php
                        unset($_SESSION['status']);
                    }
                    ?>
                    <div class="card shadow">
                    <div class="card-header">
                        <h5>Login</h5>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="POST">
                            <div class=" mb-3">
                                <label for="">Email</label>
                                <input type="text" name="email" class="form-control">
                            </div>
                            <div class=" mb-3">
                                <label for="">Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" for="">Remember Me
                              <input class="form-check-input" type="checkbox" name="rememberMe">
                            </div>
                            <br>
                            <div class="form-group">
                                <button type="submit" name="loginBtn" class="btn btn-primary">Login</button>

                                <a href="reset-password.php" class="float-end">Esqueceu-se da password?</a>
                            </div>
                        </form>
                        <hr>
                        <h5>
                            Não recebeu o email de verificação? 
                            <a href="resend-email.php">Reenviar</a>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>