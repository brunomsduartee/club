<?php 
session_start();
$page_title = "Home Page";
include('includes/header.php');
include('includes/navbar.php'); 
include('./dbcon.php');
?>

<div class="py-5">
    <div class="container">
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
        <div class="row">
            <?php
                $filme_query = "SELECT * FROM filmes";
                $filme_query_run = mysqli_query($con, $filme_query);
                $count = 1;

                if($filme_query_run -> num_rows > 0)
                {
                    while ($row = $filme_query_run -> fetch_assoc())
                    {
            ?>
                        <div class="card h-50" style="width: 15rem;" style="style.css">
                            <?php echo '<img class="card-img-top " src="data:image;base64,'.base64_encode( $row['image']).'" alt="Imagem não disponível" style="width: 50px, height: 50px;" >'; ?> 
                            <div class="card-body">
                                <h5 class="card-title"><?= $row["name"]?></h5>
                                <p class="card-text"><?= $row["description"]?></p>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Preço:</strong> <?= $row["price"]?></li>
                                <li class="list-group-item"><strong>Estado:</strong> <?= $row["state"]?></li>
                                <li class="list-group-item"><strong>Gênero:</strong> <?= $row["category"]?></li>
                            </ul>
                            <?php  if(isset($_SESSION['authenticated']) &&  $row["state"] == "Indisponível"): ?>
                                <div class="card-body">
                                    <h10 class="alert alert-danger">Filme indisponível</h10>
                                </div>
                            <?php endif ?>
                            <?php  if(isset($_SESSION['authenticated']) &&  $row["state"] == "Disponível"): ?>
                                <form action="./controllers/aluguer.php" method="POST">
                                    <div class=" mb-3" type="hidden">
                                        <input type="hidden" name="id" class="form-control" value="<?= $row['id'];?>">
                                        <input type="hidden" name="name" class="form-control" value="<?= $row['name'];?>">
                                        <input type="hidden" name="price" class="form-control" value="<?= $row['price'];?>">
                                        <input type="hidden" name="state" class="form-control" value="<?= $row['state'];?>">
                                    </div>
                                    <div class="card-body">
                                    <button type="submit" class="btn btn-success" name="alugarFilme">Alugar</button>
                                </div>
                                </form>
                            <?php endif ?>
                        </div>
                        <?php
                        $count=$count+1;
                    }
                }
                        ?>
                        <p></p>
        </div>
        <p></p>
    </div>
</div>

<?php include('includes/footer.php'); ?>