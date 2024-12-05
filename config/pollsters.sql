-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 05-12-2024 a las 02:56:07
-- Versión del servidor: 8.0.31
-- Versión de PHP: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pollsters`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestas`
--

DROP TABLE IF EXISTS `encuestas`;
CREATE TABLE IF NOT EXISTS `encuestas` (
  `id_encuesta` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado` enum('activa','inactiva') NOT NULL,
  `id_usuario` int NOT NULL,
  PRIMARY KEY (`id_encuesta`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `encuestas`
--

INSERT INTO `encuestas` (`id_encuesta`, `titulo`, `descripcion`, `fecha_inicio`, `fecha_fin`, `estado`, `id_usuario`) VALUES
(23, 'Encuesta sobre el jefe de grupo de 7mo semestre', 'Cuéntanos tus opiniones.', '2024-12-04', '2024-12-05', 'inactiva', 34),
(20, 'Educación', 'Ayúdanos a conocer tu opinión', '2024-12-01', '2024-12-06', 'inactiva', 28),
(19, 'ENTRETENIMIENTO', 'Ayudanos a conocer tus gustos...', '2024-12-04', '2024-12-12', 'activa', 27),
(21, 'FANTASIA', 'Todos tenemos un talento único, ¡vota por el que más te represente!', '2024-12-02', '2024-12-13', 'activa', 31),
(22, '¿Qué tan raro eres?', 'En esta encuesta divertida y sin prejuicios, exploraremos tu lado más raro, esas pequeñas manías y costumbres que te hacen único(a). ', '2024-12-04', '2024-12-11', 'activa', 33);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

DROP TABLE IF EXISTS `notificaciones`;
CREATE TABLE IF NOT EXISTS `notificaciones` (
  `id_notificacion` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `tipo_notificacion` enum('nueva_encuesta','nueva_votacion','resultados') NOT NULL,
  `referencia_id` int NOT NULL,
  `fecha_notificacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_notificacion`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opciones`
--

DROP TABLE IF EXISTS `opciones`;
CREATE TABLE IF NOT EXISTS `opciones` (
  `id_opcion` int NOT NULL AUTO_INCREMENT,
  `id_pregunta` int NOT NULL,
  `texto_opcion` varchar(255) NOT NULL,
  PRIMARY KEY (`id_opcion`),
  KEY `id_pregunta` (`id_pregunta`)
) ENGINE=MyISAM AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `opciones`
--

INSERT INTO `opciones` (`id_opcion`, `id_pregunta`, `texto_opcion`) VALUES
(72, 39, 'Dormir 15 horas seguidas'),
(71, 39, 'Comer sin engordar (o eso creo)'),
(70, 38, 'VEMOS'),
(69, 38, 'NO'),
(68, 38, 'SI'),
(67, 37, 'Dark'),
(10, 5, 'efwefwef'),
(11, 5, 'wefwefwefwe'),
(12, 5, 'wefwefwefwefef'),
(13, 5, 'efefwf'),
(80, 41, 'Solo cuando estoy muy aburrido(a).'),
(75, 40, 'Me como el queso antes de la base'),
(66, 37, 'Euphoria'),
(65, 37, 'Elite'),
(64, 37, 'Rain'),
(63, 37, 'Stranger Things'),
(78, 40, 'La como normal, como cualquier persona'),
(79, 41, 'Todo el tiempo. Mi taza tiene nombre.'),
(77, 40, 'Le quito todos los ingredientes y me los como por separado'),
(76, 40, 'Doblo la pizza como un taco'),
(74, 39, 'Evitar todas las responsabilidades viendo TIK TOK'),
(73, 39, 'Despertar con 1% de batería y sobrevivir el día'),
(81, 41, 'Una vez, pero me sentí raro(a).'),
(82, 41, 'jamas'),
(83, 42, ' Aplico la regla de los 5 segundos'),
(84, 42, 'La limpio y la como igual.'),
(85, 42, 'Depende de dónde cayó.'),
(86, 43, 'Como estrella de mar (extendido).'),
(87, 43, 'Como burrito (envuelto en la sábana).'),
(88, 43, ' Cambiando de posición cada 5 minutos.'),
(89, 44, ' Sí, y me siento mal cuando los \"destruyo\".'),
(90, 44, 'Solo cuando estoy de buen humor.'),
(91, 44, 'No, pero a veces lo pienso'),
(92, 44, '¡Qué raro! Nunca lo haría.'),
(93, 48, 'Si'),
(94, 48, 'Tal vez'),
(95, 48, 'No'),
(96, 48, 'Obvio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opciones_votacion`
--

DROP TABLE IF EXISTS `opciones_votacion`;
CREATE TABLE IF NOT EXISTS `opciones_votacion` (
  `id_opcion` int NOT NULL AUTO_INCREMENT,
  `id_votacion` int NOT NULL,
  `texto_opcion` varchar(255) NOT NULL,
  PRIMARY KEY (`id_opcion`),
  KEY `id_votacion` (`id_votacion`)
) ENGINE=MyISAM AUTO_INCREMENT=183 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `opciones_votacion`
--

INSERT INTO `opciones_votacion` (`id_opcion`, `id_votacion`, `texto_opcion`) VALUES
(1, 1, 'rfgwrg'),
(2, 1, 'rgwrgfwr'),
(3, 1, 'rgwgfwreg'),
(4, 1, 'rwfgwrg'),
(165, 57, 'Matematicas'),
(166, 57, 'Geografia'),
(167, 57, 'Historia'),
(168, 57, 'Fisica'),
(169, 57, 'Español'),
(170, 57, 'Ciencias'),
(171, 58, 'Pizza, porque nunca te traiciona'),
(164, 56, 'Classroom'),
(27, 0, 'ejbfjbefef'),
(163, 56, 'Mercado Libre'),
(162, 56, 'Instagram'),
(158, 55, 'Negro'),
(172, 58, 'Tacos, porque son la base de la pirámide alimenticia'),
(161, 56, 'WhatsApp'),
(160, 56, 'Tinder'),
(159, 55, 'Blanco'),
(173, 58, 'Chocolate, para la depresión existencial'),
(181, 60, 'Tokyo Ghoul'),
(182, 60, 'Servamp'),
(174, 58, 'Sopas instantáneas, porque somos prácticos, no pobres'),
(179, 60, 'Jujutsu Kaisen'),
(180, 60, 'Inuyasha'),
(157, 55, 'Amarillo'),
(156, 55, 'Azul'),
(155, 55, 'Rojo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

DROP TABLE IF EXISTS `preguntas`;
CREATE TABLE IF NOT EXISTS `preguntas` (
  `id_pregunta` int NOT NULL AUTO_INCREMENT,
  `id_encuesta` int NOT NULL,
  `texto_pregunta` text NOT NULL,
  `tipo_pregunta` enum('multiple_choice','abierto') NOT NULL,
  PRIMARY KEY (`id_pregunta`),
  KEY `id_encuesta` (`id_encuesta`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`id_pregunta`, `id_encuesta`, `texto_pregunta`, `tipo_pregunta`) VALUES
(42, 22, '¿Qué haces si se cae comida al suelo?', 'multiple_choice'),
(5, 3, 'eqwfwefewfwefewf', 'multiple_choice'),
(6, 3, 'ewfwefwefwef', 'abierto'),
(39, 21, '¿Cuál es tu súperpoder de la vida diaria?', 'multiple_choice'),
(38, 20, '¿Prefieres clases presenciales o virtuales?', 'multiple_choice'),
(37, 19, '¿Cuál es tu serie favorita del momento?', 'multiple_choice'),
(41, 22, '¿Has hablado con objetos inanimados como si fueran tus amigos?', 'multiple_choice'),
(48, 23, '¿Crees que el jefe de grupo deba reelegirse?', 'multiple_choice'),
(40, 22, 'Cuando comes pizza, ¿qué haces primero?', 'multiple_choice'),
(47, 22, '¿Qué sueles hacer con los envoltorios de caramelos o papeles pequeños?', 'abierto'),
(46, 22, 'Si fueras un superhéroe, ¿cuál sería tu poder más raro?', 'abierto'),
(45, 22, '¿Cómo reaccionas si alguien pisa tu pie accidentalmente?', 'abierto'),
(44, 22, '¿Alguna vez le has dado una personalidad a tu comida antes de comerla?', 'multiple_choice'),
(43, 22, '¿Cómo prefieres dormir?', 'multiple_choice');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas_abiertas`
--

DROP TABLE IF EXISTS `respuestas_abiertas`;
CREATE TABLE IF NOT EXISTS `respuestas_abiertas` (
  `id_respuesta` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `id_pregunta` int NOT NULL,
  `texto_respuesta` text NOT NULL,
  `fecha_respuesta` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_respuesta`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_pregunta` (`id_pregunta`)
) ENGINE=MyISAM AUTO_INCREMENT=148 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `respuestas_abiertas`
--

INSERT INTO `respuestas_abiertas` (`id_respuesta`, `id_usuario`, `id_pregunta`, `texto_respuesta`, `fecha_respuesta`) VALUES
(69, 4, 21, 'UUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUY', '2024-12-01 20:04:59'),
(68, 4, 19, 'AHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH', '2024-12-01 20:04:59'),
(67, 4, 14, 'JESUS', '2024-12-01 20:03:00'),
(66, 4, 21, 'TRECIENTOS', '2024-12-01 20:02:26'),
(65, 4, 19, 'DOCIENTOS', '2024-12-01 20:02:26'),
(64, 4, 14, 'Maybe', '2024-12-01 19:08:55'),
(63, 4, 14, 'MAYONESAAAA\r\n', '2024-12-01 18:23:52'),
(62, 4, 21, 'ATZIIIIIIIIIN', '2024-12-01 18:23:38'),
(61, 4, 19, 'CARLOOOOOOOOOOOOSSSS', '2024-12-01 18:23:38'),
(60, 4, 21, 'EMANUEL', '2024-12-01 18:10:48'),
(59, 4, 19, 'EMANUEL', '2024-12-01 18:10:48'),
(58, 4, 14, 'Yei', '2024-12-01 18:01:39'),
(57, 4, 14, 'Ajua', '2024-12-01 18:01:19'),
(56, 4, 21, 'Hace 2 años', '2024-12-01 18:00:27'),
(55, 4, 19, 'Kimetsu no Yaiba', '2024-12-01 18:00:27'),
(54, 4, 21, 'Anaconda', '2024-12-01 17:55:47'),
(53, 4, 19, 'Anaconda', '2024-12-01 17:55:47'),
(52, 4, 21, 'Hace 1', '2024-12-01 17:52:08'),
(51, 4, 19, 'Mazinller Z', '2024-12-01 17:52:08'),
(50, 4, 21, 'Hace 10 años', '2024-12-01 17:43:25'),
(49, 4, 19, 'Tokyo Ghoul', '2024-12-01 17:43:25'),
(48, 4, 21, 'Unos 6', '2024-12-01 17:33:51'),
(47, 4, 19, 'Mucho', '2024-12-01 17:33:51'),
(46, 4, 14, 'AJUAAA', '2024-12-01 17:16:09'),
(70, 4, 19, 'TU HERMANO', '2024-12-01 20:12:32'),
(71, 4, 21, 'HACE MUCHO', '2024-12-01 20:12:32'),
(72, 4, 14, 'nO CREO', '2024-12-01 20:13:05'),
(73, 4, 14, 'aJA\r\n', '2024-12-01 20:13:37'),
(74, 4, 14, 'aJAAAA', '2024-12-01 20:14:09'),
(75, 4, 14, 'MENTAAA\r\n', '2024-12-01 20:14:29'),
(76, 4, 14, 'Ya VOYYY', '2024-12-01 20:20:25'),
(77, 6, 23, 'AUDI', '2024-12-01 23:45:34'),
(78, 10, 25, 'No', '2024-12-02 02:43:58'),
(79, 10, 25, 'Si ya', '2024-12-02 02:44:09'),
(80, 10, 25, 'Aja', '2024-12-02 02:44:53'),
(81, 10, 25, 'Sja', '2024-12-02 02:45:17'),
(82, 4, 19, 'ZZZZZZZZZZZZZZZZZZZZZZZ', '2024-12-02 06:36:04'),
(83, 4, 21, 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', '2024-12-02 06:36:04'),
(84, 4, 19, 'SIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII', '2024-12-02 06:39:01'),
(85, 4, 21, 'SIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII', '2024-12-02 06:39:01'),
(86, 4, 19, 'NOOOOOOOOOOOOOOOOOOOO', '2024-12-02 06:40:09'),
(87, 4, 21, 'NOOOOOOOOOOOOOOOOOOOOOOOOOOO', '2024-12-02 06:40:09'),
(88, 4, 19, 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', '2024-12-02 06:40:57'),
(89, 4, 21, 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', '2024-12-02 06:40:57'),
(90, 4, 4, 'YESSSSSSSSSSSSSSS', '2024-12-02 07:20:30'),
(91, 4, 25, 'eS UNA PEJELAGARTO', '2024-12-02 07:21:56'),
(92, 4, 14, 'AKa', '2024-12-02 17:12:50'),
(93, 4, 19, 'Woiwrehgiorhgf', '2024-12-02 17:49:57'),
(94, 4, 21, 'eihf8iefo9qehf', '2024-12-02 17:49:57'),
(95, 4, 27, 'QUE TE IMPORTA', '2024-12-02 17:57:39'),
(96, 4, 28, '18', '2024-12-02 17:57:39'),
(97, 4, 29, 'LEER', '2024-12-02 17:57:39'),
(98, 4, 35, 'NAH', '2024-12-02 17:57:39'),
(99, 12, 36, 'hei´foh3pofhw3', '2024-12-02 18:50:49'),
(100, 4, 27, 'Victoria', '2024-12-02 20:27:49'),
(101, 4, 28, '21', '2024-12-02 20:27:49'),
(102, 4, 29, 'Leer', '2024-12-02 20:27:49'),
(103, 4, 35, 'Nambre mijo', '2024-12-02 20:27:49'),
(104, 6, 27, 'Victoria', '2024-12-02 20:29:24'),
(105, 6, 28, '21', '2024-12-02 20:29:24'),
(106, 6, 29, 'Leer', '2024-12-02 20:29:24'),
(107, 6, 35, 'Nambre mija', '2024-12-02 20:29:24'),
(108, 4, 27, 'lksfbnñlenfº', '2024-12-03 15:43:04'),
(109, 4, 28, 'knsklwnfkl', '2024-12-03 15:43:04'),
(110, 4, 29, 'lkncñlncloñ', '2024-12-03 15:43:04'),
(111, 4, 35, 'oheqfioheofhj', '2024-12-03 15:43:04'),
(112, 4, 27, 'odhvoiehvop', '2024-12-03 16:08:52'),
(113, 4, 28, 'piepohef', '2024-12-03 16:08:52'),
(114, 4, 29, 'ibeibfioehfoef', '2024-12-03 16:08:52'),
(115, 4, 35, 'ihoeihfi9ehyf', '2024-12-03 16:08:52'),
(116, 4, 14, 'es cachonda jsjsjs', '2024-12-03 17:08:27'),
(117, 21, 27, 'Atz', '2024-12-03 17:59:17'),
(118, 21, 28, '18', '2024-12-03 17:59:17'),
(119, 21, 29, 'juegar videogames', '2024-12-03 17:59:17'),
(120, 21, 35, 'nel ', '2024-12-03 17:59:17'),
(121, 21, 27, 'Atzin', '2024-12-03 18:01:15'),
(122, 21, 28, '11', '2024-12-03 18:01:15'),
(123, 21, 29, 'juegar videojuegos ', '2024-12-03 18:01:15'),
(124, 21, 35, 'me pegan', '2024-12-03 18:01:15'),
(125, 4, 14, 'tiene varios la cul·ra', '2024-12-03 19:47:03'),
(126, 4, 10, 'Ok', '2024-12-04 03:44:29'),
(127, 24, 47, 'nada', '2024-12-04 17:48:44'),
(128, 24, 46, 'volar con las manos', '2024-12-04 17:48:44'),
(129, 24, 45, 'grito', '2024-12-04 17:48:44'),
(130, 24, 47, 'los huelo', '2024-12-04 17:49:30'),
(131, 24, 46, 'ser una gelatina', '2024-12-04 17:49:30'),
(132, 24, 45, 'grito', '2024-12-04 17:49:30'),
(133, 24, 47, 'lo tiro a la basura', '2024-12-04 17:50:07'),
(134, 24, 46, 'rayos laser con los pies', '2024-12-04 17:50:07'),
(135, 24, 45, 'lo golpeo', '2024-12-04 17:50:07'),
(136, 24, 47, 'nada', '2024-12-04 17:50:45'),
(137, 24, 46, 'caminar dormido', '2024-12-04 17:50:45'),
(138, 24, 45, 'grito', '2024-12-04 17:50:45'),
(139, 24, 47, 'me los como', '2024-12-04 17:51:36'),
(140, 24, 46, 'ser una pizza con extra queso', '2024-12-04 17:51:36'),
(141, 24, 45, 'nada, le pido disculpas', '2024-12-04 17:51:36'),
(142, 24, 47, 'los observo por horas', '2024-12-04 17:52:28'),
(143, 24, 46, 'un super amigo', '2024-12-04 17:52:28'),
(144, 24, 45, 'nada', '2024-12-04 17:52:28'),
(145, 33, 47, 'Los hago avioncitos.', '2024-12-04 18:13:37'),
(146, 33, 46, 'Leer mentes.', '2024-12-04 18:13:37'),
(147, 33, 45, 'Pues se lo piso de regreso.', '2024-12-04 18:13:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `contrasenia` varchar(255) NOT NULL,
  `rol` enum('administrador','usuario') NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `nombre_usuario` varchar(100) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `correo_electronico` (`correo_electronico`),
  UNIQUE KEY `nombre_usuario` (`nombre_usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `correo_electronico`, `contrasenia`, `rol`, `foto_perfil`, `nombre_usuario`) VALUES
(1, '', '', 'admin@example.com', 'hashed_password', 'administrador', NULL, 'AdminUser '),
(34, 'Sindy', 'Hernandez', 'syndi@gmail.com', '$2y$10$Df2C8mO5aaWLsGlyXhuV5uMFdjb9.3K5Sun4oxoAY.pfWTzxKrVpq', 'usuario', 'profilePhoto/34_1733335328_perfil4 (2).jpg', 'sindic'),
(4, 'Kevin', 'Michel', 'kevin@gmail.com', '$2y$10$ni/rz8TsL4RvVdrXg7dzR.fLiChK646y.LbPjidxUaAW7RE6Vdpaa', 'usuario', 'profilePhoto/4_1733291818_perfil4.jpg', 'kevinsky'),
(33, 'Palmer', 'Ramírez Avecilla', 'viejecillo@gmail.com', '$2y$10$GVmIBpK3/5uVXidCvrrbYebIaVbHH.D4cF6x7nPdLB.pU3btrJTM6', 'usuario', NULL, 'arroz'),
(28, 'Natalia', 'Pérez Hernández', 'nat10@gmail.com', '$2y$10$/Bq5AO0j34LjPhfm7tLPq.ErTII9xxr9pnS8aupfD1RZvwyazoGei', 'usuario', NULL, 'natinat'),
(29, 'Mauricio', 'Cristino Trejo', 'mau@gmail.com', '$2y$10$v8x2CP8gU0HYNC01d6oPUeZ4lOiRc35oGF8THejvGUx7CO2/meDm.', 'usuario', NULL, 'mau20'),
(30, 'Alan', 'Mendoza', 'alanmc@gmail.com', '$2y$10$OTk64.rWj8xh/ZPspgK6hODraqA0o3Fgt.2FSfWYHHo2mPQi75v1a', 'usuario', 'profilePhoto/30_1733331768_WIN_20230228_10_34_45_Pro.jpg', '300alan'),
(16, 'ROKO', 'ROKO', 'roko@gmail.com', '$2y$10$dnCct6mJoALLu0onc.sBEOdHxwM3v6KOIIoYRdN7gfThyUji6uiMa', 'administrador', NULL, 'rokoko'),
(35, 'Zaira', 'Chávez Trejo', 'zaide@gmail.com', '$2y$10$r5yHVmBM2dVxugjByWfF/.oFsctgINuZ2lQ883YJYg0rwl4QNnWIC', 'usuario', NULL, 'otaku9312'),
(32, 'Emanuel', 'Muñoz', 'emma@gmail.com', '$2y$10$kwZ/WQpsraR1p/fj0Z.O/uG71FNXwXXLkwS4R3Z8O/8GJJLBN3Un2', 'usuario', 'profilePhoto/32_1733335841_perfil3.jpg', 'ema11'),
(31, 'Victoria', 'Gonzalez', 'vicky@vyky.com', '$2y$10$LeJjHmUpggYnYkMGlj7ldOq6JJZ5Ffm/xidu2vuOoW0bQomDDKde6', 'usuario', 'profilePhoto/31_1733332361_LOBOS.jpeg', 'toria99'),
(26, 'Armando', 'Jimenez', 'arm55@gmail.com', '$2y$10$kHQYJYzpDzM5YT0o0bVXbeHgSz7Gjz4etv1c1hiHLjM5mCUM5JezO', 'usuario', NULL, 'arjim'),
(25, 'Xitlali Abigail', 'Castillo', 'abi45@gmail.com', '$2y$10$9XzMea4oNe7JFw2nazXzU.ebXk8FWbzUxZtDm/cbDuk9lqcSGMi4G', 'usuario', NULL, 'xitla'),
(24, 'Luis Gerardo', 'Rodriguez', 'luis30@gmail.com', '$2y$10$cDW6HNjE7paUCpSBPBu6luF/4mIMaVjr4leV1y0OpMMClgvVDIjc6', 'usuario', NULL, 'gera2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `votaciones`
--

DROP TABLE IF EXISTS `votaciones`;
CREATE TABLE IF NOT EXISTS `votaciones` (
  `id_votacion` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado` enum('activa','inactiva') NOT NULL,
  `id_usuario` int NOT NULL,
  PRIMARY KEY (`id_votacion`),
  KEY `fk_id_usuario` (`id_usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `votaciones`
--

INSERT INTO `votaciones` (`id_votacion`, `titulo`, `descripcion`, `fecha_inicio`, `fecha_fin`, `estado`, `id_usuario`) VALUES
(60, '¿Cuál es tu anime favorito?', 'Cuéntanos.', '2024-12-04', '2024-12-18', 'activa', 34),
(55, 'GUSTOS', '¿Cuál es tu color favorito?', '2024-12-04', '2024-12-11', 'activa', 27),
(56, 'TECNOLOGIA', '¿Cuál es tu App favorita para Ligar?', '2024-12-04', '2024-12-13', 'activa', 28),
(57, 'EDUACION', 'Cuál es tu materia favorita', '2024-12-03', '2024-12-11', 'activa', 30),
(58, 'El alimento del alma (o del estómago)', 'Si te dejaran comer solo una cosa por el resto de tu vida, ¿qué elegirías?', '2024-12-04', '2024-12-11', 'activa', 31);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `votos`
--

DROP TABLE IF EXISTS `votos`;
CREATE TABLE IF NOT EXISTS `votos` (
  `id_voto` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `id_opcion` int DEFAULT NULL,
  `tipo_opcion` enum('encuesta','votacion') DEFAULT NULL,
  `cantidad` int DEFAULT '1',
  `fecha_voto` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_voto`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=181 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `votos`
--

INSERT INTO `votos` (`id_voto`, `id_usuario`, `id_opcion`, `tipo_opcion`, `cantidad`, `fecha_voto`) VALUES
(150, 21, 50, 'encuesta', 1, '2024-12-03 17:59:17'),
(149, 21, 47, 'encuesta', 1, '2024-12-03 17:59:17'),
(148, 4, 26, 'encuesta', 1, '2024-12-03 17:08:27'),
(147, 4, 62, 'encuesta', 1, '2024-12-03 16:08:52'),
(146, 4, 56, 'encuesta', 1, '2024-12-03 16:08:52'),
(145, 4, 54, 'encuesta', 1, '2024-12-03 16:08:52'),
(144, 4, 107, 'votacion', 1, '2024-12-03 15:44:30'),
(143, 4, 53, 'encuesta', 1, '2024-12-03 15:43:04'),
(142, 6, 61, 'encuesta', 1, '2024-12-02 20:29:24'),
(141, 6, 58, 'encuesta', 1, '2024-12-02 20:29:24'),
(140, 6, 53, 'encuesta', 1, '2024-12-02 20:29:24'),
(139, 6, 50, 'encuesta', 1, '2024-12-02 20:29:24'),
(138, 6, 48, 'encuesta', 1, '2024-12-02 20:29:24'),
(137, 4, 61, 'encuesta', 1, '2024-12-02 20:27:49'),
(136, 4, 48, 'encuesta', 1, '2024-12-02 20:27:49'),
(135, 10, 146, 'votacion', 1, '2024-12-02 20:24:19'),
(134, 4, 60, 'encuesta', 1, '2024-12-02 17:57:39'),
(133, 4, 57, 'encuesta', 1, '2024-12-02 17:57:39'),
(132, 4, 52, 'encuesta', 1, '2024-12-02 17:57:39'),
(131, 4, 51, 'encuesta', 1, '2024-12-02 17:57:39'),
(130, 4, 47, 'encuesta', 1, '2024-12-02 17:57:39'),
(129, 4, 39, 'encuesta', 1, '2024-12-02 17:49:57'),
(128, 4, 34, 'encuesta', 1, '2024-12-02 17:49:57'),
(127, 4, 27, 'encuesta', 1, '2024-12-02 17:12:50'),
(126, 11, 146, 'votacion', 1, '2024-12-02 16:57:00'),
(125, 11, 151, 'votacion', 1, '2024-12-02 16:55:11'),
(124, 11, 150, 'votacion', 1, '2024-12-02 16:54:57'),
(123, 4, 148, 'votacion', 1, '2024-12-02 07:34:47'),
(122, 4, 149, 'votacion', 1, '2024-12-02 07:34:27'),
(121, 4, 145, 'votacion', 1, '2024-12-02 07:33:00'),
(120, 6, 146, 'votacion', 1, '2024-12-02 07:24:48'),
(119, 4, 45, 'encuesta', 1, '2024-12-02 07:21:56'),
(118, 4, 9, 'encuesta', 1, '2024-12-02 07:20:30'),
(117, 4, 35, 'votacion', 1, '2024-12-02 07:20:00'),
(116, 4, 146, 'votacion', 1, '2024-12-02 07:19:40'),
(115, 4, 36, 'encuesta', 1, '2024-12-02 06:40:09'),
(114, 4, 33, 'encuesta', 1, '2024-12-02 06:40:09'),
(151, 21, 52, 'encuesta', 1, '2024-12-03 17:59:17'),
(152, 21, 56, 'encuesta', 1, '2024-12-03 17:59:17'),
(153, 21, 62, 'encuesta', 1, '2024-12-03 17:59:17'),
(154, 21, 58, 'encuesta', 1, '2024-12-03 18:01:15'),
(155, 4, 114, 'votacion', 1, '2024-12-03 19:53:49'),
(156, 4, 154, 'votacion', 1, '2024-12-04 03:44:06'),
(157, 4, 20, 'encuesta', 1, '2024-12-04 03:44:29'),
(158, 24, 83, 'encuesta', 1, '2024-12-04 17:48:44'),
(159, 24, 79, 'encuesta', 1, '2024-12-04 17:48:44'),
(160, 24, 77, 'encuesta', 1, '2024-12-04 17:48:44'),
(161, 24, 90, 'encuesta', 1, '2024-12-04 17:48:44'),
(162, 24, 87, 'encuesta', 1, '2024-12-04 17:48:44'),
(163, 24, 76, 'encuesta', 1, '2024-12-04 17:49:30'),
(164, 24, 92, 'encuesta', 1, '2024-12-04 17:49:30'),
(165, 24, 86, 'encuesta', 1, '2024-12-04 17:49:30'),
(166, 24, 85, 'encuesta', 1, '2024-12-04 17:50:07'),
(167, 24, 82, 'encuesta', 1, '2024-12-04 17:50:07'),
(168, 24, 78, 'encuesta', 1, '2024-12-04 17:50:07'),
(169, 24, 84, 'encuesta', 1, '2024-12-04 17:50:45'),
(170, 24, 75, 'encuesta', 1, '2024-12-04 17:50:45'),
(171, 24, 91, 'encuesta', 1, '2024-12-04 17:51:36'),
(172, 24, 81, 'encuesta', 1, '2024-12-04 17:52:28'),
(173, 34, 96, 'encuesta', 1, '2024-12-04 18:05:00'),
(174, 34, 181, 'votacion', 1, '2024-12-04 18:08:56'),
(175, 32, 179, 'votacion', 1, '2024-12-04 18:11:01'),
(176, 33, 85, 'encuesta', 1, '2024-12-04 18:13:37'),
(177, 33, 81, 'encuesta', 1, '2024-12-04 18:13:37'),
(178, 33, 76, 'encuesta', 1, '2024-12-04 18:13:37'),
(179, 33, 91, 'encuesta', 1, '2024-12-04 18:13:37'),
(180, 33, 88, 'encuesta', 1, '2024-12-04 18:13:37');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
