<?php
session_start();
$page_title = "Filmes Alugados";
include('./../includes/header.php');
include('./../includes/navbar.php');
include('./../../dbcon.php');
include('../../check-session.php');
?>

<br>
<div class="text-center">
  <br>
  <h2 class="text-center">Alugueres</h2>
  <br>
  <?php
    if(isset($_SESSION['status']))
    {
        ?>
        <div class="alert alert-success" style="margin: 100px">
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
        <th>Utilizador</th>
        <th>Estado</th>
        <th>Data Requisição</th>
        <th>Data Devolução</th>
        <th>Ações</th>
      </tr>
    </thead>
    </div>
    <?php

    $filmes_alugados_query = "SELECT * FROM filmes_alugados";
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
          <td><?=$row["email_user"]?></td>
          <?php if ($row["esta_alugado"] == 1)
          { ?>
            <td>Ativo</td>
          <?php
          } ?> 
          <?php if ($row["esta_alugado"] == 0)
          { ?>
            <td>Devolvido</td>
          <?php
          } ?> 
          <td><?=$row["data_req"]?></td>
          
          <?php if ($row["data_dev"] == NULL)
          { ?>
            <td>----------</td>
          <?php
          } else 
          {?> 
            <td><?=$row["data_dev"]?></td>
          <?php     
          } ?> 
          <?php if ($row["esta_alugado"] == 1)
          {
          ?> 
            <td>
              <form action="../controllers/aluguerController.php" method="POST">
                <div class=" mb-3" type="hidden">
                    <input type="hidden" name="id_aluguer" class="form-control" value="<?= $row['id'];?>">
                    <input type="hidden" name="id_filme" class="form-control" value="<?= $row['id_filme'];?>">
                </div>
                <div>
                  <button class="btn btn-secondary btn-sm" type="submit" name="devolverFilme" style="font-size: 12px" >Devolver</button>
                </div>
              </form>
            </td>
          <?php
          } ?>
          <?php if ($row["esta_alugado"] == 0)
          {
          ?> 
            <td>-</td>
          <?php
          } ?>
        </tr>
        <?php
        $count=$count+1;
      }
    }
    ?>
  </table>
  <br>

<?php include('./../includes/footer.php'); ?>