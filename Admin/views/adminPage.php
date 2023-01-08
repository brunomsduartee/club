<?php 
session_start();
$page_title = "Admin Panel";
include('./../includes/header.php');
include('./../includes/navbar.php');
include('./../../dbcon.php');
include('../../check-session.php');
?>

<div id="main-content" class="container allContent-section py-4">
        <div class="row">
            <div class="col-sm-3">
                <div class="card">
                    <i class="fa fa-users  mb-2" style="font-size: 70px;"></i>
                    <h4 style="color:black;">Número de Users</h4>
                    <h10 style="color:black;">
                      <?php
                        $count_user_query = "SELECT * FROM users WHERE role='user'";
                        $count_user_query_run = mysqli_query($con, $count_user_query);

                        $count_user = mysqli_num_rows($count_user_query_run);
                        echo "Existem $count_user clientes registados no clube."
                      ?>
                    </h10>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                    <i class="fa fa-th-large mb-2" style="font-size: 70px;"></i>
                    <h4 style="color:black;">Número de gêneros</h4>
                    <h10 style="color:black;">
                      <?php
                        $count_genero_query = "SELECT * FROM generos";
                        $count_genero_query_run = mysqli_query($con, $count_genero_query);

                        $count_genero = mysqli_num_rows($count_genero_query_run);
                        echo "Existem $count_genero gêneros de filmes"
                      ?>
                    </h10>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                    <i class="fa fa-users  mb-2" style="font-size: 70px;"></i>
                    <h4 style="color:black;">Número de Filmes</h4>
                    <h10 style="color:black;">
                      <?php
                        $count_filme_query = "SELECT * FROM filmes";
                        $count_filme_query_run = mysqli_query($con, $count_filme_query);

                        $count_filmes = mysqli_num_rows($count_filme_query_run);
                        echo "Existem $count_filmes filmes no nosso clube."
                      ?>
                    </h10>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                    <i class="fa fa-users  mb-2" style="font-size: 70px;"></i>
                    <h4 style="color:black;">Número de filmes alugados</h4>
                    <h10 style="color:black;">
                      <?php
                        $count_fg_query = "SELECT * FROM filmes_alugados";
                        $count_fg_query_run = mysqli_query($con, $count_fg_query);

                        $count = mysqli_num_rows($count_fg_query_run);
                        echo "Existem $count users registados no clube."
                      ?>
                    </h10>
                </div>
            </div>
        </div>
        
    </div>


<?php include('./../includes/footer.php'); ?>