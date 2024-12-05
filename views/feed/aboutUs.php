<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nosotros - Pollster</title>
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

        .team-member svg {
            width: 50px;
            height: 50px;
            margin-top: 15px;
        }

        .icon-container {
            display: flex;
            align-items: center;
            gap: 20px;
            /* Espacio entre el icono y el texto */
        }

        .icon-container img {
            width: 80px;
            /* Ajusta el tamaño de las imágenes */
            height: 80px;
            /* Ajusta el tamaño de las imágenes */
        }

        .icon-container p {
            font-size: 1.1rem;
            /* Aumenta el tamaño del texto */
        }
    </style>
</head>

<body>
    <!-- Navbar similar al index.html -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.html">Pollster</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../feed/feedUser.php">Inicio</a>
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
                <div class="icon-container">
                    <img src="../../public/img/voto.svg" alt="Historia">
                    <p>Pollster nació de la idea de crear una plataforma simple e intuitiva para realizar encuestas y votaciones en línea...</p>
                </div>
            </div>
            <div class="col-lg-6">
                <h2>Nuestra Misión</h2>
                <div class="icon-container">
                    <img src="../../public/img/encuesta.svg" alt="Misión">
                    <p>Facilitar la recopilación y análisis de datos mediante herramientas digitales accesibles para todos.</p>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12 text-center">
                <h2>Nuestro Equipo</h2>
            </div>
            <div class="col-md-6 team-member">
                <img src="../../public/img/alan.svg" alt="Miembro del Equipo">
                <h4>Alan Mendoza</h4>
                <p>Backend Developer</p>
                <!-- SVG Example -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="50" height="50">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="6" x2="12" y2="12"></line>
                    <line x1="12" y1="12" x2="16" y2="12"></line>
                </svg>
            </div>
            <div class="col-md-6 team-member">
                <img src="../../public/img/kevin.svg" alt="Miembro del Equipo">
                <h4>Kevin Michel</h4>
                <p>CTO</p>
                <!-- SVG Example -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="50" height="50">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="6" x2="12" y2="12"></line>
                    <line x1="12" y1="12" x2="16" y2="12"></line>
                </svg>
            </div>
        </div>
    </div>

    <!-- Footer similar al index.html -->
    <footer class="bg-dark text-white text-center py-3">
        <div class="container">
            <p>&copy; 2024 Pollster. Todos los derechos reservados.</p>
            <p>Contacto: info@pollster.com</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>