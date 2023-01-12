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
                  <a href="clientes.php">
                    <i class="fa fa-users  mb-2" style="font-size: 70px;"></i>
                  </a>
                    <h4 style="color:black;">Users</h4>
                    <h10 style="color:black;">
                      <?php
                        $count_user_query = "SELECT * FROM users WHERE role='user'";
                        $count_user_query_run = mysqli_query($con, $count_user_query);

                        $count_user = mysqli_num_rows($count_user_query_run);
                        echo "Número de users:   $count_user "
                      ?>
                    </h10>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                  <a href="generos.php">
                    <i class="fa fa-th-large mb-2" style="font-size: 70px;"></i>
                  </a>
                    <h4 style="color:black;">Gêneros</h4>
                    <h10 style="color:black;">
                      <?php
                        $count_genero_query = "SELECT * FROM generos";
                        $count_genero_query_run = mysqli_query($con, $count_genero_query);

                        $count_genero = mysqli_num_rows($count_genero_query_run);
                        echo "Número de gêneros:   $count_genero"
                      ?>
                    </h10>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                  <a href="filmes.php">
                    <i class="fa fa-film    mb-2" style="font-size: 70px;"></i>
                    </a>
                    <h4 style="color:black;">Filmes</h4>
                    <h10 style="color:black;">
                      <?php
                        $count_filme_query = "SELECT * FROM filmes";
                        $count_filme_query_run = mysqli_query($con, $count_filme_query);

                        $count_filmes = mysqli_num_rows($count_filme_query_run);
                        echo "Número de filmes:   $count_filmes"
                      ?>
                    </h10>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                  <a href="filmesAlugados.php">
                    <i class="fa fa-shopping-cart  mb-2" style="font-size: 70px;"></i>
                  </a>
                    <h4 style="color:black;">Filmes Alugados</h4>
                    <h10 style="color:black;">
                      <?php
                        $count_alugueres_ativos_query = "SELECT * FROM filmes_alugados WHERE esta_alugado = 1";
                        $count_alugueres_ativos_query_run = mysqli_query($con, $count_alugueres_ativos_query);

                        $count_alugueres_inativos_query = "SELECT * FROM filmes_alugados WHERE esta_alugado = 0";
                        $count_alugueres_inativos_query_run = mysqli_query($con, $count_alugueres_inativos_query);

                        $count_ativos = mysqli_num_rows($count_alugueres_ativos_query_run);
                        $count_inativos = mysqli_num_rows($count_alugueres_inativos_query_run);
                        echo "Ativos:  $count_ativos    |    Finalizados:  $count_inativos";
                      ?>
                    </h10>
                </div>
            </div>
        </div>
        
    </div>


<?php include('./../includes/footer.php'); ?>