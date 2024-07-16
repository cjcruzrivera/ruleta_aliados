<?php 
// Break down the URL path
$path = explode('/', $_SERVER['REQUEST_URI']);

// Get the last element of the array
$lastElement = end($path);

// Explode the last element by '.'
$parts = explode('.', $lastElement);

// Get the first part
$url = $parts[0];

?>

<nav class="navbar navbar-dark navbar-expand-sm fixed-top">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#Navbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#"><img src="../../assets/images/Aliados.png" height="45" width="140"></a>
        <div class="collapse navbar-collapse" id="Navbar">
            <ul class="navbar-nav mr-auto">
            <li class="nav-item active pr-5 " style="font-size: 18px;"><a class="nav-link" href="#"><?php echo $name; ?></a></li>
                <li class="nav-item active "><a class="nav-link" href="./participaciones.php">Participaciones</a></li>
            </ul>
            <span class="navbar-text">
                <a href="./logout.php" class="openModal">
                    <i class="fa fa-sign-in"></i> Cerrar Sesión
                </a>
            </span>
        </div>
    </div>
</nav>