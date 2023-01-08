<?php
session_start();
$page_title = "Editar filme";
include('./../includes/header.php');
include('./../includes/navbar.php');
include('./../../dbcon.php');
include('../../check-session.php');
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
                        <h5>A editar o filme "<?php echo $_POST['name']?>"</h5>
                    </div>
                    <div class="card-body">
                        <form  enctype='multipart/form-data' action="../controllers/filmeController.php" method="POST">
                            <div type="hidden">
                                <input type="hidden" name="id" class="form-control" value="<?php echo $_POST['id_filme']?>">
                            </div>
                            <div class=" mb-3">
                                <label for="">Nome</label>
                                <input type="text" name="name" class="form-control" value="<?php echo $_POST['name']?>">
                            </div>
                            <div class=" mb-3">
                                <label for="">Descrição</label>
                                <input type="text" name="description" class="form-control" value="<?php echo $_POST['description']?>">
                            </div>
                            <div class=" mb-3">
                                <label for="">Preço</label>
                                <input type="text" name="price" class="form-control" value="<?php echo $_POST['price']?>">
                            </div>
                            <div class=" mb-3">
                                <label for="">Selecionar o estado</label>
                               <select class="form-select" aria-label="Default select example" name="state">
                                    <option value="Disponível">Disponível</option>
                                    <option value="Indisponível">Indisponível</option>
                                    <option value="Brevemente">Brevemente</option>
                                </select>
                            </div>
                            </div>
                            <div class=" mb-3">
                                <label for="">Gênero</label>
                                <input type="text" name="category" class="form-control" value="<?php echo $_POST['category']?>"> 
                            </div>
                            <div class="mb-3">
                                <label for="">Escolha a foto do filme</label>
                                <br>
                                <input type="file" name="image_upload" accept=".png,.jpg,.jpeg">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success" name="editFilme" style="height:40px">Submeter Alterações</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('./../includes/footer.php'); ?>