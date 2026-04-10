-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 10-04-2026 a las 16:28:44
-- Versión del servidor: 8.0.42
-- Versión de PHP: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto_29797`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno`
--

CREATE TABLE `alumno` (
  `id_alumno` int NOT NULL,
  `Usuario_alumno` varchar(50) NOT NULL,
  `Clave_alumno` varchar(100) NOT NULL,
  `Nombre_alumno` varchar(60) NOT NULL,
  `Cedula_alumno` varchar(10) NOT NULL,
  `Fecha_nacimiento` date NOT NULL,
  `Edad_alumno` int NOT NULL,
  `Direccion_alumno` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Correo_alumno` varchar(50) NOT NULL,
  `Telefono_alumno` varchar(10) NOT NULL,
  `Nota_1` int NOT NULL,
  `Nota_2` int NOT NULL,
  `Nota_3` int NOT NULL,
  `Nota_recuperacion` int DEFAULT NULL,
  `Promedio_alumno` int NOT NULL,
  `Estado_alumno` varchar(15) NOT NULL,
  `Estado_alumno_AI` varchar(15) NOT NULL DEFAULT 'Activo',
  `id_curso` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `alumno`
--

INSERT INTO `alumno` (`id_alumno`, `Usuario_alumno`, `Clave_alumno`, `Nombre_alumno`, `Cedula_alumno`, `Fecha_nacimiento`, `Edad_alumno`, `Direccion_alumno`, `Correo_alumno`, `Telefono_alumno`, `Nota_1`, `Nota_2`, `Nota_3`, `Nota_recuperacion`, `Promedio_alumno`, `Estado_alumno`, `Estado_alumno_AI`, `id_curso`) VALUES
(1, 'Dalmatax', '$2y$10$slOFLhiFpXFdij/Fw5L1DubEQb/B9OxCotB0wxStmCyi9OXyB13OS', 'Dylan', '1752536175', '2015-11-10', 10, 'Guajalo', 'alvaradodylan05@gmail.com', '995345843', 11, 11, 12, NULL, 11, 'Desaprobado', 'Activo', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id_auditoria` int NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `ip` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `auditoria`
--

INSERT INTO `auditoria` (`id_auditoria`, `fecha`, `hora`, `descripcion`, `ip`) VALUES
(1, '2026-02-13', '08:49:38', 'Administrador agregó un usuario: Klever Aguilar (KAguilar)', '127.0.0.1'),
(2, '2026-02-13', '08:58:41', 'Administrador registró al alumno Dylan con promedio 11.33 (Desaprobado)', '127.0.0.1'),
(3, '2026-02-13', '08:59:39', 'Administrador registró el curso: Sistemas Operativos', '127.0.0.1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso`
--

CREATE TABLE `curso` (
  `id_curso` int NOT NULL,
  `Nombre_curso` varchar(30) NOT NULL,
  `Descripcion_curso` varchar(50) NOT NULL,
  `Estado_curso` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `curso`
--

INSERT INTO `curso` (`id_curso`, `Nombre_curso`, `Descripcion_curso`, `Estado_curso`) VALUES
(2, 'Sistemas Operativos', 'Sistemas Operativos', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opciones`
--

CREATE TABLE `opciones` (
  `id_opcion` int NOT NULL,
  `codigo_opcion` varchar(30) NOT NULL,
  `nombre_opcion` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `menu_opcion` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `opciones`
--

INSERT INTO `opciones` (`id_opcion`, `codigo_opcion`, `nombre_opcion`, `menu_opcion`) VALUES
(1, 'usuario_ingresar', 'Ingreso de Usuarios', 'USUARIOS'),
(2, 'usuario_listar', 'Listado de Usuarios', 'USUARIOS'),
(3, 'usuario_editar', 'Edita Datos de Usuarios', 'USUARIOS'),
(4, 'rol_ingresar', 'Ingreso de Roles', 'ROLES'),
(5, 'rol_listar', 'Listado de Roles', 'ROLES'),
(6, 'rol_editar', 'Edita Datos de Roles', 'ROLES'),
(7, 'permisos_editar', 'Edición de Permisos', 'SEGURIDAD'),
(8, 'usuario_eliminar', 'Inahibilita Usuarios', 'USUARIOS'),
(9, 'rol_eliminar', 'Inhabilita Roles', 'ROLES'),
(10, 'auditoria_ver', 'Ver la Auditoria', 'AUDITORIA'),
(11, 'alumno_ingresar', 'Ingresa Alumnos', 'ALUMNOS'),
(12, 'alumno_listar', 'Listado de alumnos', 'ALUMNOS'),
(13, 'alumno_desaprobados', 'Ve a los alumnos desaprobados', 'ALUMNOS'),
(14, 'alumno_recuperacion', 'Da nota de recuperación a alumnos', 'ALUMNOS'),
(15, 'reporte_generar', 'Genera reportes de alumno', 'ALUMNOS'),
(16, 'usuario_editar_alumno', 'Edita Datos Alumnos', 'ALUMNOS'),
(17, 'usuario_eliminar_alumno', 'Inahibilita Alumnos', 'ALUMNOS'),
(18, 'curso_ingresar', 'Ingresa cursos', 'CURSOS'),
(19, 'curso_listar', 'Listado de Cursos', 'CURSOS'),
(20, 'curso_eliminar', 'Inhabilita Cursos', 'CURSOS'),
(21, 'curso_editar', 'Edita Datos de Cursos', 'CURSOS'),
(22, 'alumno_notas', 'Edita Notas de alumnos', 'CURSOS'),
(23, 'generar_reportes', 'Genera Distintos Reportes', 'REPORTE'),
(24, 'usuario_normal', 'Edita Aspectos de su Perfil', 'SEGURIDAD');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id_rol` int NOT NULL,
  `id_opcion` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_rol`, `id_opcion`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(1, 21),
(1, 22),
(1, 23),
(1, 24);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int NOT NULL,
  `Nombre_rol` varchar(50) NOT NULL,
  `Descripcion_rol` varchar(100) NOT NULL,
  `Estado_rol` varchar(10) NOT NULL DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `Nombre_rol`, `Descripcion_rol`, `Estado_rol`) VALUES
(1, 'Administrador', 'Administrador del sistema', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL,
  `Nombres_user` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Apellidos_user` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Usuario_user` varchar(15) NOT NULL,
  `Cedula_user` varchar(10) NOT NULL,
  `Edad_user` int NOT NULL,
  `Estado_user` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Activo',
  `Clave_user` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Fecha_nacimiento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `Nombres_user`, `Apellidos_user`, `Usuario_user`, `Cedula_user`, `Edad_user`, `Estado_user`, `Clave_user`, `Fecha_nacimiento`) VALUES
(1, 'Dylan Alexander', 'Alvarado Palacios', 'Administrador', '1752536175', 22, 'Activo', '$2y$10$KiCfGAVMgq4ATc5DWF5QvOUKoTs.ttxiMNtkMdD5FNu5Xq42nmfTO', '2003-11-10'),
(2, 'Klever', 'Aguilar', 'KAguilar', '1714092044', 25, 'Activo', '$2y$10$yKwSnBnK6jsJW6tZJrEIp.MOvHc/L/RWzBIEd4X9sHrpIo5ZV1IF2', '2000-11-10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_rol`
--

CREATE TABLE `usuarios_rol` (
  `id_usuario` int NOT NULL,
  `id_rol` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios_rol`
--

INSERT INTO `usuarios_rol` (`id_usuario`, `id_rol`) VALUES
(1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD PRIMARY KEY (`id_alumno`),
  ADD UNIQUE KEY `Cedula_alumno` (`Cedula_alumno`),
  ADD UNIQUE KEY `Telefono_alumno` (`Telefono_alumno`),
  ADD UNIQUE KEY `Usuario_alumno` (`Usuario_alumno`),
  ADD KEY `fk_alumno_curso` (`id_curso`);

--
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id_auditoria`);

--
-- Indices de la tabla `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`id_curso`),
  ADD UNIQUE KEY `Nombre_curso` (`Nombre_curso`);

--
-- Indices de la tabla `opciones`
--
ALTER TABLE `opciones`
  ADD PRIMARY KEY (`id_opcion`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_rol`,`id_opcion`),
  ADD KEY `id_opcion` (`id_opcion`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `Cedula_user` (`Cedula_user`);

--
-- Indices de la tabla `usuarios_rol`
--
ALTER TABLE `usuarios_rol`
  ADD PRIMARY KEY (`id_usuario`,`id_rol`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumno`
--
ALTER TABLE `alumno`
  MODIFY `id_alumno` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id_auditoria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `curso`
--
ALTER TABLE `curso`
  MODIFY `id_curso` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `opciones`
--
ALTER TABLE `opciones`
  MODIFY `id_opcion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD CONSTRAINT `fk_alumno_curso` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `permisos_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`),
  ADD CONSTRAINT `permisos_ibfk_2` FOREIGN KEY (`id_opcion`) REFERENCES `opciones` (`id_opcion`);

--
-- Filtros para la tabla `usuarios_rol`
--
ALTER TABLE `usuarios_rol`
  ADD CONSTRAINT `usuarios_rol_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `usuarios_rol_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
