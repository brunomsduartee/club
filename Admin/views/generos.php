<?php
session_start();
$page_title = "Gêneros";
include('./../includes/header.php');
include('./../includes/navbar.php');
include('../../dbcon.php');
include('../../check-session.php');
?>

<br>
<div class="text-center">
  <br>
  <h2 class="text-center">Gêneros</h2>
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
        <th>Id</th>
        <th>Nome</th>
        <th>Ações</th>
      </tr>
    </thead>
    </div>
    <?php
    
    $genero_query = "SELECT * FROM generos";
    $genero_query_run = mysqli_query($con, $genero_query);
    $count = 1;

    if($genero_query_run -> num_rows > 0)
    {
      while ($row = $genero_query_run -> fetch_assoc())
      {
    ?>                
    <tr>
    <td><?=$row["id"]?></td>
    <td><?=$row["name"]?></td>
    <td>
        <form action="../controllers/generoController.php" method="POST">
          <div class=" mb-3" type="hidden">
              <input type="hidden" name="id_genero" class="form-control" value="<?= $row['id'];?>">
              <input type="hidden" name="nome_genero" class="form-control" value="<?= $row['name'];?>">
          </div>
          <div class="form-group">
            <button type="submit" name="deleteGenero" >Apagar</button>
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
  <!-- Button para abrir modal -->
  <div class="col text-center">
    <button type="button" class="btn btn-dark" style="height:40px" data-bs-toggle="modal" data-bs-target="#addGenero">
        <strong>Adicionar gênero</strong>
    </button>
  </div>
  <br><br>

  <!-- Modal adicionar gênero -->
  <div class="modal fade" id="addGenero" role="dialog">
    <div class="modal-dialog">
    
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Novo género</h4>
          <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form  enctype='multipart/form-data' action="../controllers/generoController.php" method="POST">
            <div class="form-group">
              <label for="name">Nome do Gênero:</label>
              <input type="text" class="form-control" name="name">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-success" name="addGenero" style="height:40px">Adicionar Gênero</button>
            </div>
          </form>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-bs-dismiss="modal" style="height:40px">Cancelar</button>
        </div>
      </div>
      
    </div>
  </div>


<?php include('./../includes/footer.php'); ?>