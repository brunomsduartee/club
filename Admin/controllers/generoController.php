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
            $genero_query = "INSERT INTO generos (name) VALUES ('$gen_name')"; 
            $genero_query_run = mysqli_query($con, $genero_query);

            if(!$genero_query_run)
            {
                $_SESSION['status'] = "Ocorreu um erro a inserir o gênero";
                header("Location: ../views/generos.php");
                exit(0);
            }
            else
            {
                $_SESSION['status'] = "Gênero adicionado com sucesso!";
                header("Location: ../views/generos.php");
                exit(0);
            }
        }
    }

    if(isset($_POST['deleteGenero']))
    {
        $id = $_POST['id_genero'];

        if(!isset($id))
        {
            $_SESSION['status'] = "Id não definido";
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
?>