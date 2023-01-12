<?php 

include('./../../dbcon.php');
    session_start();    

if(isset($_POST['devolverFilme']))
{
    $id_aluguer = $_POST['id_aluguer'];
    $id_filme = $_POST['id_filme'];


    if(empty($id_aluguer) && empty($id_filme))
    {
        $_SESSION['status'] = "Ocorreu um erro ao identificar o filme para devolver";
        header("Location: ../views/filmesAlugados.php");
        exit(0);
    }
    else
    {
        $update_filme_alugado_query = "UPDATE filmes_alugados SET esta_alugado= 0, data_dev = CURRENT_TIMESTAMP() WHERE id='$id_aluguer'";
        $update_filme_alugado_query_run = mysqli_query($con, $update_filme_alugado_query);

        if (!$update_filme_alugado_query_run)
        {
            $_SESSION['status'] = "Ocorreu um erro a atualizar o estado do aluguer.";
            header("Location: ../views/filmesAlugados.php");
            exit(0);
        }
        else
        {
            $update_filme_query = "UPDATE filmes SET state='Disponível' WHERE id='$id_filme'";
            $update_filme_query_run = mysqli_query($con, $update_filme_query);

                if($update_filme_query_run)
                {}

            $_SESSION['status'] = "Filme devolvido com sucesso!";
            header("Location: ../views/filmesAlugados.php");
            exit(0);
        }
    }

}

?>