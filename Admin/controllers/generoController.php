<?php
    include('./../../dbcon.php');
    session_start();

    if(isset($_POST['addGenero']))
    {
        $gen_name = $_POST['name'];

        if(empty($gen_name))
        {
            $_SESSION['status'] = "Insira o nome do gênero";
            header("Location: ../views/generos.php");
            exit(0);
        }
        else
        {
            $genero_query = $con -> prepare("INSERT INTO generos (name) VALUES (?)");
            $genero_query -> bind_param('s', $gen_name);

            if($genero_query -> execute())
            {
                $_SESSION['status'] = "Gênero adicionado com sucesso!";
                header("Location: ../views/generos.php");
                exit(0);

                $genero_query -> close();
            }
            else
            {
                $_SESSION['status'] = "Ocorreu um erro a inserir o gênero";
                header("Location: ../views/generos.php");
                exit(0);

                $genero_query -> close();
            }
        }
    }

    if(isset($_POST['deleteGenero']))
    {
        $id = $_POST['id_genero'];
        $gen = $_POST['nome_genero'];

        if(!isset($id))
        {
            $_SESSION['status'] = "Id não definido";
            header("Location: ../views/generos.php");
            exit(0);
        }
        else
        {
            
            $get_generos_query = "SELECT category FROM filmes WHERE category='$gen'";
            $get_generos_query_run = mysqli_query($con, $get_generos_query);

            if(mysqli_num_rows($get_generos_query_run) > 0)
            {
                $_SESSION['status'] = "Erro! Existe pelo menos um filme com este gênero!";
                header("Location: ../views/generos.php");
                exit(0);
            }
            else
            {

                $select_query = "SELECT * FROM generos WHERE id='$id'";
                $select_query_run = mysqli_query($con, $select_query);


                if(!mysqli_num_rows($select_query_run) > 0)
                {
                    $_SESSION['status'] = "Não existe nenhum genero com este id";
                    header("Location: ../views/generos.php");
                    exit(0);
                }
                else
                {
                    $delete_query = "DELETE FROM generos WHERE id='$id'";
                    $delete_query_run = mysqli_query($con, $delete_query);
                    
                    if(!$delete_query_run)
                    {
                        $_SESSION['status'] = "Ocorreu um erro ao eliminar o genero";
                        header("Location: ../views/generos.php");
                        exit(0);
                    }
                    else
                    {
                        $_SESSION['status'] = "Gênero eliminado com sucesso!";
                        header("Location: ../views/generos.php");
                        exit(0);
                    }
                }
            }
        }
    }    
?>