<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nosotros - VotePro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .team-member {
            text-align: center;
            margin-bottom: 30px;
        }

        .team-member img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <!-- Navbar similar al index.html -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.html">VotePro</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="login.html">Iniciar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- About Us Content -->
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-6">
                <h2>Nuestra Historia</h2>
                <p>VotePro nació de la idea de crear una plataforma simple e intuitiva para realizar encuestas y votaciones en línea...</p>
            </div>
            <div class="col-lg-6">
                <h2>Nuestra Misión</h2>
                <p>Facilitar la recopilación y análisis de datos mediante herramientas digitales accesibles para todos.</p>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12 text-center">
                <h2>Nuestro Equipo</h2>
            </div>
            <div class="col-md-4 team-member">
                <img src="https://via.placeholder.com/200" alt="Miembro del Equipo">
                <h4>Juan Pérez</h4>
                <p>Fundador & CEO</p>
            </div>
            <div class="col-md-4 team-member">
                <img src="https://via.placeholder.com/200" alt="Miembro del Equipo">
                <h4>María García</h4>
                <p>CTO</p>
            </div>
            <div class="col-md-4 team-member">
                <img src="https://via.placeholder.com/200" alt="Miembro del Equipo">
                <h4>Carlos Rodríguez</h4>
                <p>Director de Producto</p>
            </div>
        </div>
    </div>

    <!-- Footer similar al index.html -->
    <footer class="bg-dark text-white text-center py-3">
        <div class="container">
            <p>&copy; 2023 VotePro. Todos los derechos reservados.</p>
            <p>Contacto: info@votepro.com</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>