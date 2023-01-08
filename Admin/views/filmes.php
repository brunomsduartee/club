<?php
session_start();
$page_title = "Filmes";
include('./../includes/header.php');
include('./../includes/navbar.php');
include('./../../dbcon.php');
include('../../check-session.php');
?>

<br>
<div class="text-center">
  <br>
  <h2 class="text-center">Filmes</h2>
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
  <table class="table table-striped">
    <thead class="thead-dark">
      <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Description</th>
        <th>price</th>
        <th>State</th>
        <th>Category</th>
        <th>Upload Date</th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <?php
    
    $filme_query = "SELECT * FROM filmes";
    $filme_query_run = mysqli_query($con, $filme_query);
    $count = 1;

    if($filme_query_run -> num_rows > 0)
    {
      while ($row = $filme_query_run -> fetch_assoc())
      {
        ?>                
        <tr>
          <td><?=$row["id"]?></td>
          <td><?=$row["name"]?></td>
          <td><?=$row["description"]?></td>
          <td><?=$row["price"]?></td>
          <td><?=$row["state"]?></td>
          <td><?=$row["category"]?></td>
          <td><?=$row["uploaded_date"]?></td>
          <td>
            <form action="../controllers/filmeController.php" method="POST">
              <div class=" mb-3" type="hidden">
                  <input type="hidden" name="id_filme" class="form-control" value="<?= $row['id'];?>">
              </div>
              <div class="form-group">
                <button type="submit" name="deleteFilme" >Apagar</button>
              </div>
            </form>
          </td>
          <td>
              <form action="./editFilme.php" method="POST">
                <div class=" mb-3" type="hidden">
                    <input type="hidden" name="id_filme" class="form-control" value="<?= $row['id'];?>">
                    <input type="hidden" name="name" class="form-control" value="<?= $row['name'];?>">
                    <input type="hidden" name="description" class="form-control" value="<?= $row['description'];?>">
                    <input type="hidden" name="price" class="form-control" value="<?= $row['price'];?>">
                    <input type="hidden" name="category" class="form-control" value="<?= $row['category'];?>">
                    <input type="hidden" name="state" class="form-control" value="<?= $row['state'];?>">
                </div>
                <div class="form-group">
                  <button type="submit" name="editFilme" >Editar</button>
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
    <button type="button" class="btn btn-dark" style="height:40px" data-bs-toggle="modal" data-bs-target="#addFilme">
        <strong>Adicionar filme</strong>
    </button>
  </div>
  <br><br>

  <!-- Modal adicionar filme -->
  <div class="modal fade" id="addFilme" role="dialog">
    <div class="modal-dialog">
    
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Novo Filme</h4>
          <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form  enctype='multipart/form-data' action="../controllers/filmeController.php" method="POST">
            <div class=" mb-3">
              <label for="">Name</label>
              <input type="text" name="name" class="form-control">
            </div>
            <div class=" mb-3">
              <label for="">Description</label>
              <input type="text" name="description" class="form-control">
            </div>
            <div class=" mb-3">
              <label for="">Price</label>
              <input type="text" name="price" class="form-control">
            </div>
            <div class=" mb-3">
              <label for="">Selecionar o estado</label>
              <select class="form-select" aria-label="Default select example" name="state">
                <option value="Disponível">Disponível</option>
                <option value="Brevemente">Brevemente</option>
              </select>
            </div>
            <div class=" mb-3">
              <label for="">Category</label>
              <input type="text" name="category" class="form-control">
            </div>
            <div class="mb-3">
              <label for="">Escolha a foto do filme</label>
              <br>
              <input type="file" name="image_upload" accept=".png,.jpg,.jpeg" >
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-success" name="addFilme" style="height:40px">Adicionar Filme</button>
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