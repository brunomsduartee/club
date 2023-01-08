<?php
    include('./../../dbcon.php');
    session_start();

    // Adicionar filme
    if(isset($_POST['addFilme']))
    {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $state = $_POST['state'];

        if(empty($name) && empty($description) && empty($price) && empty($category) && empty($state))
        {
            $_SESSION['status'] = "Preencha todos os campos";
            header("Location: ../views/filmes.php");
            exit(0);
        }
        else
        {
            if (!isset($_FILES['image_upload']['name']))
            {
                $_SESSION['status'] = "Preencha todos os campos";
                header("Location: ../views/filmes.php");
                exit(0);
            }
            else
            {
                $fileName = basename($_FILES['image_upload']['name']);
                $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
            

                $allowTypes = array('jpg', 'png', 'jpeg');
                if(in_array($fileType, $allowTypes))
                {
                    $image = $_FILES['image_upload']['tmp_name'];
                    $imgContent = addslashes(file_get_contents($image));

                    $filme_query = "INSERT INTO filmes (name, description, price, category, image) VALUES ('$name', '$description', '$price', '$category', '$imgContent')"; 
                    $filme_query_run = mysqli_query($con, $filme_query);

                    if(!$filme_query_run)
                    {
                        $_SESSION['status'] = "Ocorreu um erro ao adicionar o filme";
                        header("Location: ../views/filmes.php");
                        exit(0);
                    }
                    else
                    {
                        $_SESSION['status'] = "Filme adicionado com sucesso!";
                        header("Location: ../views/filmes.php");
                        exit(0);
                    }
                }
            }
        }
    }


    // Editar filme
    if(isset($_POST['editFilme']))
    {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $state = $_POST['state'];

        if(empty($name) && empty($description) && empty($price) && empty($category) && empty($state))
        {
            $_SESSION['status'] = "Preencha todos os campos";
            header("Location: ../views/filmes.php");
            exit(0);
        }
        else
        {
            if (!isset($_FILES['image_upload']['name']))
            {
                $_SESSION['status'] = "Preencha todos os campos";
                header("Location: ../views/filmes.php");
                exit(0);
            }
            else
            {
                $fileName = basename($_FILES['image_upload']['name']);
                $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
            

                $allowTypes = array('jpg', 'png', 'jpeg');
                if(in_array($fileType, $allowTypes))
                {
                    $image = $_FILES['image_upload']['tmp_name'];
                    $imgContent = addslashes(file_get_contents($image));

                
                    // Registar user
                    $update_filme_query = "UPDATE filmes SET name='$name', description='$description', price='$price', category='$category', state='$state', image='$imgContent' WHERE id='$id'"; 
                    $update_filme_query_run = mysqli_query($con, $update_filme_query);

                    if(!$update_filme_query_run)
                    {
                        $_SESSION['status'] = "Ocorreu um erro ao adicionar o filme";
                        header("Location: ../views/filmes.php");
                        exit(0);
                    }
                    else
                    {
                        $_SESSION['status'] = "Filme editado com sucesso!";
                        header("Location: ../views/filmes.php");
                        exit(0);
                    }
                }
            }
        }
    }



    // Apagar filme
    if(isset($_POST['deleteFilme']))
    {
        $id = $_POST['id_filme'];

        if(empty($id))
        {
            $_SESSION['status'] = "Id não definido";
            header("Location: ../views/filmes.php");
            exit(0);
        }
        else
        {
            $select_query = "SELECT * FROM filmes WHERE id='$id'";
            $select_query_run = mysqli_query($con, $select_query);

            if(!mysqli_num_rows($select_query_run) > 0)
            {
                $_SESSION['status'] = "Não existe nenhum filme com este id";
                header("Location: ../views/filmes.php");
                exit(0);
            }
            else
            {
                $delete_query = "DELETE FROM filmes WHERE id='$id'";
                $delete_query_run = mysqli_query($con, $delete_query);
                
                if(!$delete_query_run)
                {
                    $_SESSION['status'] = "Ocorreu um erro ao eliminar o filme";
                    header("Location: ../views/filmes.php");
                    exit(0);
                }
                else
                {
                    $_SESSION['status'] = "Filme eliminado com sucesso!";
                    header("Location: ../views/filmes.php");
                    exit(0);
                }
            }
        }
    }    
?>