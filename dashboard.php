<?php
include('authentication.php');

$page_title = "Dashboard";
include('includes/header.php');
include('includes/navbar.php');
include('./check-session.php');

?>


<link rel="stylesheet" href="style.css">
<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12 ">
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
                <div class="card-header">
                    <h4>User Dashboard</h4>
                </div>
                <div class="card-body">
                    <h2>está logado</h2>
                    <h5>Nome: <?= $_SESSION['auth_user']['name']; ?></h5>
                    <h5>Email: <?= $_SESSION['auth_user']['email']; ?></h5>
                    <h5>Role: <?= $_SESSION['auth_user']['role']; ?></h5>
                    <br>
                    <br><br>
                    <!-- Mostrar a imagem -->
                    <div class="row justify-content-center" >
                        <img style="width: 300px; height: 250px;"src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($_SESSION['auth_user']['image']); ?>" />
                    </div>
                    <br>
                    <br>
                    <br>
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                    Alterar dados
                                </button>
                            </h2>
                            <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <form action="code.php" method="POST" enctype="multipart/form-data">
                                        <div class=" mb-3">
                                            <label for="">Novo nome</label>
                                            <input type="text" name="new_name" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Escolha a sua nova foto de perfil</label>
                                            <br>
                                            <br>
                                            <input type="file" name="image_update" accept=".png,.jpg,.jpeg" >
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <button type="submit"  name ="alterarBtn" class="btn btn-primary">Submeter alterações</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>    
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>

<?php include('includes/footer.php'); ?>