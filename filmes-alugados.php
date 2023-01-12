<?php
session_start();
$page_title = "Filmes Alugados";
include('includes/header.php');
include('includes/navbar.php');
include('./check-session.php');
include('./dbcon.php');
?>

<br>
<div class="text-center">
  <br>
  <h2 class="text-center">Alugueres Ativos</h2>
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
</div>
  <div style="margin: 100px">
  <table class="table table-striped">
    <thead class="thead-dark">
      <tr>
        <th>Id Aluguer</th>
        <th>Filme</th>
        <th>Preço</th>        
        <th>Data Requisição</th>
        <th>Ações</th>
      </tr>
    </thead>
    </div>
    <?php

    $email_user = $_SESSION['auth_user']['email'];
    $filmes_alugados_query = "SELECT * FROM filmes_alugados WHERE email_user='$email_user' AND esta_alugado = '1'";
    $filmes_alugados_query_run = mysqli_query($con, $filmes_alugados_query);
    $count = 1;

    if($filmes_alugados_query_run -> num_rows > 0)
    {
      while ($row = $filmes_alugados_query_run -> fetch_assoc())
      {
        ?>                
        <tr>
          <td><?=$row["id"]?></td>
          <td><?=$row["nome_filme"]?></td>
          <td><?=$row["preco_filme"]?></td>
          <td><?=$row["data_req"]?></td>
          <td>
            <form action="./controllers/aluguer.php" method="POST">
              <div class=" mb-3" type="hidden">
                  <input type="hidden" name="id_aluguer" class="form-control" value="<?= $row['id'];?>">
                  <input type="hidden" name="id_filme" class="form-control" value="<?= $row['id_filme'];?>">
              </div>
              <div class="form-group">
                <button class="btn btn-secondary btn-sm" type="submit" name="devolverFilme" style="widht: 20px;" >Devolver</button>
              </div>
            </form>
          </td>
        </tr>
        <?php
        $count=$count+1;
      }
    }
    ?>
  </table>
  <br>
<br>
<div class="text-center">
  <br>
  <h2 class="text-center">Histórico de filmes alugados</h2>
  <br>
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
</div>
  <br>
  <div style="margin: 100px">
  <table class="table table-striped">
    <thead class="thead-dark">
      <tr>
        <th>Id Aluguer</th>
        <th>Filme</th>
        <th>Preço</th>
        <th>Data de Requisição</th>
        <th>Data de Devolução</th>
      </tr>
    </thead>
    </div>
    <?php

    $email_user = $_SESSION['auth_user']['email'];
    $filmes_alugados_query = "SELECT * FROM filmes_alugados WHERE email_user='$email_user' AND esta_alugado = '0'";
    $filmes_alugados_query_run = mysqli_query($con, $filmes_alugados_query);
    $count = 1;

    if($filmes_alugados_query_run -> num_rows > 0)
    {
      while ($row = $filmes_alugados_query_run -> fetch_assoc())
      {
        ?>                
        <tr>
          <td><?=$row["id"]?></td>
          <td><?=$row["nome_filme"]?></td>
          <td><?=$row["preco_filme"]?></td>
          <td><?=$row["data_req"]?></td>
          <td><?=$row["data_dev"]?></td>
        </tr>
        <?php
        $count=$count+1;
      }
    }
    ?>
  </table>
  <br>


<?php include('includes/footer.php'); ?>