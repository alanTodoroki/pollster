<?php
// Archivo: createPhoto.php

function crearImagenPerfil($nombre)
{
    // Obtener la primera letra del nombre
    $letra = strtoupper(substr($nombre, 0, 1));

    // Elegir un color de fondo aleatorio
    $coloresFondo = [
        [255, 99, 71],    // Tomate
        [135, 206, 250],  // Azul Claro
        [255, 182, 193],  // Rosa Claro
        [240, 230, 140],  // Caqui Claro
        [175, 238, 238],  // Turquesa Pálido
        [152, 251, 152],  // Verde Pálido
        [255, 160, 122],  // Salmón Claro
        [218, 112, 214],  // Orquídea
        [245, 222, 179],  // Trigo
        [100, 149, 237],  // Azul Cardenal
        [123, 104, 238],  // Azul Pizarra Medio
        [0, 191, 255],    // Azul Profundo
        [238, 130, 238],  // Violeta
        [144, 238, 144],  // Verde Claro
        [255, 228, 225],  // Rosa Nebulosa
        [255, 222, 173],  // Navajo Blanco
        [173, 216, 230],  // Azul Claro
        [220, 20, 60],    // Carmesí
        [255, 105, 180],  // Rosa Caliente
        [32, 178, 170],   // Verde Agua Oscuro
        [47, 79, 79],     // Gris Pizarra Oscuro
        [210, 180, 140],  // Bronceado
        [72, 61, 139],    // Azul Medianoche
        [176, 224, 230],  // Polvo Azul
        [102, 205, 170],  // Verde Agua Medio
        [70, 130, 180],   // Azul Acero
        [240, 128, 128],  // Coral Claro
        [46, 139, 87],    // Verde Bosque
        [255, 140, 0],    // Naranja Oscuro
        [199, 21, 133],   // Rosa Oscuro
        [189, 183, 107],  // Caqui Oscuro
        [0, 139, 139],    // Cian Oscuro
        [128, 0, 128],    // Púrpura
        [255, 228, 181],  // Arena Blanca
        [250, 250, 210],  // Amarillo Pálido
        [219, 112, 147],  // Rosado Violeta
        [244, 164, 96],   // Salmonado
        [255, 218, 185],  // Papaya Batida
        [245, 245, 220],  // Beige
        [255, 250, 205],  // Oro Claro
        [72, 209, 204],   // Turquesa Medio
        [153, 50, 204],   // Orquídea Oscura
        [240, 255, 255],  // Azul Cielo Claro
        [0, 128, 128],    // Verde Azulado
        [255, 215, 0],    // Oro
        [0, 100, 0],      // Verde Oscuro
        [128, 128, 0],    // Oliva
        [244, 164, 96],   // Arena Dorada
        [112, 128, 144],  // Gris Pizarra
        [0, 255, 127],    // Primavera Verde
    ];

    $colorFondo = $coloresFondo[array_rand($coloresFondo)];

    // Crear una imagen en blanco
    $ancho = 200; // Aumentamos el tamaño
    $alto = 200;
    $imagen = imagecreatetruecolor($ancho, $alto);

    // Asignar el color de fondo
    $colorFondoRGB = imagecolorallocate($imagen, $colorFondo[0], $colorFondo[1], $colorFondo[2]);
    imagefill($imagen, 0, 0, $colorFondoRGB);

    // Asignar color para la letra
    $colorLetra = imagecolorallocate($imagen, 255, 255, 255); // Blanco

    // Configurar fuente y tamaño
    $fuente = __DIR__ . '/../../fonts/Roboto/Roboto-Black.ttf'; // Asegúrate de que esto apunte correctamente
    if (!file_exists($fuente)) {
        die('La fuente no se encuentra en la ruta especificada.');
    }
    $tamanioFuente = 100; // Tamaño de la letra

    // Calcular la posición para centrar la letra
    $cajaTexto = imagettfbbox($tamanioFuente, 0, $fuente, $letra);
    if (!$cajaTexto) {
        die('Error al calcular la caja de texto.');
    }
    $x = ($ancho - ($cajaTexto[2] - $cajaTexto[0])) / 2;
    $y = ($alto - ($cajaTexto[5] - $cajaTexto[1])) / 2;
    $y += abs($cajaTexto[5]); // Ajustar para que no se corte

    // Dibujar la letra en la imagen
    if (!imagettftext($imagen, $tamanioFuente, 0, $x, $y, $colorLetra, $fuente, $letra)) {
        die('Error al dibujar el texto en la imagen.');
    }

    // Enviar la cabecera para mostrar la imagen
    header('Content-Type: image/png');
    imagepng($imagen);
    imagedestroy($imagen);
}

// Validar si se recibió un nombre como parámetro
if (isset($_GET['nombre']) && !empty($_GET['nombre'])) {
    crearImagenPerfil($_GET['nombre']);
} else {
    http_response_code(400);
    echo 'El nombre es requerido para generar la imagen.';
}
