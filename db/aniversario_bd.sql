-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Versión del servidor: 10.3.27-MariaDB-cll-lve
-- Versión de PHP: 7.3.6
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;
--
-- Base de datos: `sorteo_aliados`
--
-- CREATE DATABASE sorteo_aliados;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `usuarios`
--
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
    `id` int(10) NOT NULL,
    `username` varchar(50) NOT NULL,
    `password` varchar(50) NOT NULL,
    `fullname` varchar(70) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
ADD PRIMARY KEY (`id`);
--
-- AUTO_INCREMENT de las tablas volcadas
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 0;
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;
INSERT INTO `usuarios` (`username`, `password`, `fullname`)
VALUES (
        'admin',
        '25d55ad283aa400af464c76d713c07ad',
        'Camilo Cruz'
    ),
    (
        'gestor_sorteos',
        '25d55ad283aa400af464c76d713c07ad',
        'Gestion Sorteo'
    );

-- ------------------------------------------------------

DROP TABLE IF EXISTS `premios`;
CREATE TABLE `premios` (
    `id` int(10) NOT NULL,
    `nombre` varchar(150) NOT NULL,
    `url_img` varchar(50) NOT NULL,
    `cantidad` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
-- Indices de la tabla `premios`
--
ALTER TABLE `premios`
ADD PRIMARY KEY (`id`);
ALTER TABLE `premios`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 0;
COMMIT;

INSERT INTO `premios` (`nombre`, `cantidad`, `url_img`)
VALUES ('Marca maletas', 5,'../../assets/images/maletas.jpg'),
    ('Gafas de sol', 2,'../../assets/images/sol.jpg'),
    ('Gorras', 2,'../../assets/images/Gorras.jpg'),
    ('Funda sumergible', 4,'../../assets/images/sumergible.jpg'),
    ('3% DTO PT NAL', 5,'../../assets/images/3nacional.jpg'),
    ('4% DTO PT NAL', 5,'../../assets/images/4nacional.jpg'),
    ('5% DTO PT NAL', 5,'../../assets/images/5nacional.jpg'),
    ('5% DTO PT INT', 5,'../../assets/images/5internacional.jpg'),
    ('7% DTO PT INT', 5,'../../assets/images/7internacional.jpg'),
    ('8% DTO PT INT', 5,'../../assets/images/8internacional.jpg'),
    ('10% DTO PT INT', 5,'../../assets/images/10internacional.jpg'),
    ('Termos', 3,'../../assets/images/Termos.jpg');

-- --------------------------------------------------------

DROP TABLE IF EXISTS `participantes`;
CREATE TABLE `participantes` (
    `cedula` varchar(30) NOT NULL,
    `fullname` varchar(70) NOT NULL,
    `agencia` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
-- Indices de la tabla `participantes`
--
ALTER TABLE `participantes`
ADD PRIMARY KEY (`cedula`);

-- --------------------------------------------------------
DROP TABLE IF EXISTS `participaciones`;
CREATE TABLE `participaciones` (
    `id` int(10) NOT NULL,
    `cedula_participante` varchar(30) NOT NULL,
    `fecha_generacion` date NOT NULL,
    `fecha_sorteo` date,
    `id_premio` int(10),
    FOREIGN KEY (cedula_participante) REFERENCES participantes(cedula),
    FOREIGN KEY (id_premio) REFERENCES premios(id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
-- Indices de la tabla `participaciones`
--
ALTER TABLE `participaciones`
ADD PRIMARY KEY (`id`);
ALTER TABLE `participaciones`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 0;
COMMIT;