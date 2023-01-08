<?php 
session_start();
$page_title = "Register";
include('includes/header.php');
include('includes/navbar.php');
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
                <div class="card shadow">
                    <div class="card-header">
                        <h5>Registo</h5>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="POST" enctype="multipart/form-data">
                            <div class=" mb-3">
                                <label for="">Name</label>
                                <input type="text" name="name" class="form-control">
                            </div>
                            <div class=" mb-3">
                                <label for="">Email</label>
                                <input type="text" name="email" class="form-control">
                            </div>
                            <div class=" mb-3">
                                <label for="">Password</label>
                                <input type="password" name="password" class="form-control" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}">
                            </div>
                            <div class=" mb-3">
                                <label for="">Confirm password</label>
                                <input type="password" name="confirm_password" class="form-control" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}">
                            </div>
                            <div class="mb-3">
                                <label for="">Escolha a sua foto de perfil</label>
                                <br>
                                <input type="file" name="image_upload" accept=".png,.jpg,.jpeg" >
                            </div>
                            <div class="form-group">
                                <button type="submit"  name ="registerBtn" class="btn btn-primary">Register</button>
                            </div>
                        </form>
                         <br>
                        <div class="card-header">
                            <h6>Nota</h6>
                        </div>
                        <div class="card-body">
                            <label>Password entre 8 a 20 caracteres, Contendo 1 maiuscula, 1 minuscula e 1 numero</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>