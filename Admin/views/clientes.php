<?php
session_start();
$page_title = "Clientes";
include('./../includes/header.php');
include('./../includes/navbar.php');
include('./../../dbcon.php');
include('../../check-session.php');
?>
<br>
<div class="justify-content-center">
  <h2 class="text-center">Todos os Clientes</h2>
</div>
  <br>
  <table class="table table-striped">
    <thead class="thead-dark">
      <tr>
        <th>Id</th>
        <th>Nome </th>
        <th>Email</th>
        <th>Role</th>
      </tr>
    </thead>
    <?php
    
    $user_query = "SELECT * FROM users WHERE role='user'";
    $user_query_run = mysqli_query($con, $user_query);
    $count = 1;

    if($user_query_run -> num_rows > 0)
    {
      while ($row = $user_query_run -> fetch_assoc())
      {
    ?>                
    <tr>
      <td scope="row"><?=$row["id"]?></td>
      <td><?=$row["name"]?></td>
      <td><?=$row["email"]?></td>
      <td><?=$row["role"]?></td>
    </tr>
    <?php
            $count=$count+1;
            }
    }
    ?>
  </table>

<?php include('./../includes/footer.php'); ?>