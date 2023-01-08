<div class="bg-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
                <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">Clube</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        </li>                        
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Dashboard</a>
                        </li>
                        <?php  if(isset($_SESSION['authenticated']) && $_SESSION['auth_user']['role'] == 'admin') : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="./Admin/views/adminPage.php">Admin</a>
                        </li>
                        <?php endif ?>
                        <?php  if(!isset($_SESSION['authenticated'])) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <?php endif ?>
                        <?php  if(isset($_SESSION['authenticated'])) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="filmes-alugados.php">Filmes Alugados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                        <?php endif ?>
                    </ul>
                </div>
                </div>
                </nav>
            </div>
        </div>
    </div>
</div>