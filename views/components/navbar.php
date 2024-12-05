<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <img src="../../public/img/logo.png" alt="Pollster" width="30" height="24">
        <a class="navbar-brand ms-2" href="../../views/home/home.php">Pollster</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" onclick="mostrarMenuNav()" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../../views/feed/feedUser.php">Feed</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../views/components/decideCreate.php">Crear</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Usuario
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../feed/configuration.php">Configuración</a></li>
                        <li><a class="dropdown-item" href="../feed/aboutUs.php">Sobre nosotros</a></li>
                        <li><a class="dropdown-item" href="../users/logOut.php">Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>
            <!-- Barra de búsqueda eliminada -->
        </div>
    </div>
</nav>