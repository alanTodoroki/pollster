<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VotePro - Plataforma de Encuestas y Votaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background-color: #f4f4f4;
            padding: 100px 0;
            text-align: center;
        }

        .features-section,
        .details-section,
        .community-section {
            padding: 50px 0;
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .illustration-section {
            background-color: #f8f9fa;
            padding: 50px 0;
        }

        .detail-card {
            margin-bottom: 30px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <!-- Navbar (como en el ejemplo anterior) -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <!-- ... (mismo código del navbar anterior) ... -->
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <h1>Crea, Comparte y Analiza Encuestas</h1>
            <p>La plataforma definitiva para votaciones y encuestas interactivas</p>
            <!-- Espacio para tu SVG principal -->
            <div id="main-illustration" class="mt-4">
                <!-- Aquí irá tu ilustración principal -->
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="features-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="feature-icon">📊</div>
                    <h3>Crea Encuestas</h3>
                    <p>Diseña encuestas personalizadas en minutos</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="feature-icon">🔗</div>
                    <h3>Comparte</h3>
                    <p>Distribuye tus encuestas fácilmente</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="feature-icon">📈</div>
                    <h3>Analiza Resultados</h3>
                    <p>Exporta y visualiza datos en PDF y CSV</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Illustrations Section -->
    <div class="illustration-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div id="encuestas-illustration">
                        <!-- Ilustración de Encuestas -->
                    </div>
                </div>
                <div class="col-md-6">
                    <h2>Encuestas Dinámicas</h2>
                    <p>Crea encuestas personalizadas con múltiples tipos de preguntas, diseños atractivos y opciones de personalización.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Features Section -->
    <div class="details-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="detail-card">
                        <div id="votaciones-illustration">
                            <!-- Ilustración de Votaciones -->
                        </div>
                        <h3>Votaciones en Tiempo Real</h3>
                        <p>Resultados instantáneos y gráficos dinámicos para una experiencia interactiva.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="detail-card">
                        <div id="graficas-illustration">
                            <!-- Ilustración de Gráficas -->
                        </div>
                        <h3>Visualización de Datos</h3>
                        <p>Gráficos avanzados para interpretar resultados de manera clara y precisa.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="detail-card">
                        <div id="resultados-illustration">
                            <!-- Ilustración de Resultados -->
                        </div>
                        <h3>Exportación de Resultados</h3>
                        <p>Exporta tus datos en formatos PDF y CSV para análisis profundos.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Community Section -->
    <div class="community-section bg-light">
        <div class="container text-center">
            <h2>Únete a Nuestra Comunidad</h2>
            <div id="comunidad-illustration" class="my-4">
                <!-- Ilustración de Comunidad -->
            </div>
            <p>Conecta, comparte y descubre encuestas de usuarios de todo el mundo.</p>
            <a href="#" class="btn btn-primary btn-lg">Únete Ahora</a>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="container text-center my-5">
        <h2>Comienza a Crear tus Encuestas Hoy</h2>
        <p>Regístrate gratis y desbloquea todo el potencial de VotePro</p>
        <a href="/views/users/signUp.php" class="btn btn-success btn-lg">Crear Cuenta Gratis</a>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <div class="container">
            <p>&copy; 2023 VotePro. Todos los derechos reservados.</p>
            <p>Contacto: info@votepro.com</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>