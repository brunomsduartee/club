<?php 
$page_title = "Login";
include('includes/header.php');
include('includes/navbar.php');
session_start();
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
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
                <div class="card">
                    <div class="card-header">
                        <h5>Resetar a password</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="code.php" method="POST">
                            <input type="hidden" name="password_token" value="<?php if (isset($_GET['token'])){echo $_GET['token'];} ?>">
                            <div class="form-group mb-3">
                                <label for="">Email</label>
                                <input type="text" name="email" value="<?php if (isset($_GET['email'])){echo $_GET['email'];} ?>"class="form-control" placeholder="Introduza o email">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Password</label>
                                <input type="password" name="new_password" class="form-control" placeholder="Introduza a password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Confirme a password</label>
                                <input type="password" name="confirm_password" class="form-control" placeholder="Repita a password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}">
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" name="reset_pass_btn" class="btn btn-primary">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>