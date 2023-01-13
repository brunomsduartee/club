<?php 

session_start();
include('../dbcon.php');


if(isset($_POST['alugarFilme']))
{
    $name = $_POST['name'];
    $id = $_POST['id'];
    $price = $_POST['price'];
    $email_user = $_SESSION['auth_user']['email'];

    if(empty($name) or empty($id) or empty($price) or empty($email_user))
    {
        $_SESSION['status'] = "Ocorreu um erro ao identificar o filme pretendido";
        header("Location: ../index.php");
        exit(0);
    }
    else
    {

        $alugados_query = "SELECT * FROM filmes_alugados WHERE id_filme='$id' AND esta_alugado=1";
        $alugados_query_run = mysqli_query($con, $alugados_query);
        if(mysqli_num_rows($alugados_query_run) > 0)
        {
            $_SESSION['status'] = "Não é possivel alugar um filme já alugado";
            header("Location: ../index.php");
            exit(0);
        }
        else
        {
            $alugar_query = "INSERT INTO filmes_alugados (nome_filme, id_filme, preco_filme, email_user) VALUES ('$name', '$id', '$price', '$email_user')";
            $alugar_query_run = mysqli_query($con, $alugar_query);

            if(!$alugar_query_run) {

                $_SESSION['status'] = "Ocorreu um erro ao efetuar o aluguer";
                header("Location: ../index.php");
                exit(0);

            }
            else
            {

                $update_filme_query = "UPDATE filmes SET state='Indisponível' WHERE id='$id'";
                $update_filme_query_run = mysqli_query($con, $update_filme_query);

                if($update_filme_query_run)
                {
                    $_SESSION['status'] = "Aluger feito com sucesso!";
                    header("Location: ../index.php");
                    exit(0);
                }
                else
                {
                    $_SESSION['status'] = "Ocorreu um erro ao alugar o filme!";
                    header("Location: ../index.php");
                    exit(0);
                }
            }
        }
    }
}
?>