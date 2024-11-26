CREATE DATABASE proyecto_restaurante;
USE proyecto_restaurante;

-- Tabla de roles de usuario
CREATE TABLE tb_rol
(
 id_rol int primary key,
 nombre_rol varchar(20)
);

-- Tabla de menús de navegación
CREATE TABLE tb_menu_item
(
 id_item int primary key,
 item_label varchar(50),
 item_link varchar(255),
 item_tipo varchar(20),
 id_parent int DEFAULT 0
);

-- Tabla rol x menu
CREATE TABLE tb_rol_menu
(
 id_rol int, -- [PK/FK]
 id_item int -- [PK/FK]
);
ALTER TABLE tb_rol_menu ADD PRIMARY KEY (id_rol, id_item);
ALTER TABLE tb_rol_menu ADD FOREIGN KEY(id_rol) REFERENCES tb_rol(id_rol);
ALTER TABLE tb_rol_menu ADD FOREIGN KEY(id_item) REFERENCES tb_menu_item(id_item);

-- Tabla distrito
CREATE TABLE tb_distrito
(
 id_distrito int PRIMARY KEY,
 distrito varchar(50),
 provincia varchar(50)
);

-- Tabla ubicación
CREATE TABLE tb_ubicacion
(
 id_ubicacion int PRIMARY KEY AUTO_INCREMENT,
 direccion text,
 id_distrito int, -- [FK]
 coord_lat decimal(7,4),
 coord_lng decimal(7,4)
);
ALTER TABLE tb_ubicacion ADD FOREIGN KEY (id_distrito) REFERENCES tb_distrito(id_distrito);

-- Tabla usuario
CREATE TABLE tb_usuario
(
 codigo_usuario varchar(80) PRIMARY KEY,
 clave varchar(255),
 fecha_registro datetime DEFAULT CURRENT_TIMESTAMP,
 id_rol int NOT NULL -- [FK]
);
ALTER TABLE tb_usuario ADD FOREIGN KEY(id_rol) REFERENCES tb_rol(id_rol);

-- Tabla empleado
CREATE TABLE tb_empleado
(
 nro_empleado int PRIMARY KEY AUTO_INCREMENT,
 nombre varchar(30),
 apellido varchar(30),
 email varchar(80),
 codigo_usuario varchar(80) NOT NULL -- [FK]
);
ALTER TABLE tb_empleado ADD FOREIGN KEY(codigo_usuario) REFERENCES tb_usuario(codigo_usuario); 

-- Tabla cliente
CREATE TABLE tb_cliente
(
 nro_cliente int PRIMARY KEY AUTO_INCREMENT,
 nombre varchar(30),
 apellido varchar(30),
 email varchar(80),
 telefono varchar(10),
 fidelidad int DEFAULT 0,
 codigo_usuario varchar(80) NULL, -- [FK]
 id_ubicacion int NULL    -- [FK]
);
ALTER TABLE tb_cliente ADD FOREIGN KEY(codigo_usuario) REFERENCES tb_usuario(codigo_usuario); 
ALTER TABLE tb_cliente ADD FOREIGN KEY(id_ubicacion) REFERENCES tb_ubicacion(id_ubicacion) ON DELETE SET NULL; 

-- Tabla mensaje de contacto
CREATE TABLE tb_mensaje
(
 id_mensaje int PRIMARY KEY AUTO_INCREMENT,
 nro_cliente int NOT NULL, -- [FK]
 asunto varchar(50),
 contenido text,
 fecha_hora datetime DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE tb_mensaje ADD FOREIGN KEY(nro_cliente) REFERENCES tb_cliente(nro_cliente) ON DELETE CASCADE; 

-- Tabla categoría de plato
CREATE TABLE tb_categoria
(
 id_categoria int PRIMARY KEY AUTO_INCREMENT,
 nombre varchar(50),
 tipo int
);

-- Tabla plato
CREATE TABLE tb_plato
(
 id_plato int PRIMARY KEY AUTO_INCREMENT,
 nombre varchar(50),
 id_categoria int, -- [FK]
 descripcion text,
 imagen varchar(100),
 precio_regular decimal(8,2),
 descuento_general int,
 descuento_fidelidad int
);
ALTER TABLE tb_plato ADD FOREIGN KEY(id_categoria) REFERENCES tb_categoria(id_categoria); 

-- Tabla plato del día
CREATE TABLE tb_plato_del_dia
(
 id_plato_dia int PRIMARY KEY AUTO_INCREMENT,
 id_plato int NOT NULL, -- [FK]
 fecha_eleccion datetime DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE tb_plato_del_dia ADD FOREIGN KEY(id_plato) REFERENCES tb_plato(id_plato) ON DELETE CASCADE;

-- Tabla promoción
CREATE TABLE tb_promocion
(
 id_promocion int PRIMARY KEY AUTO_INCREMENT,
 nombre varchar(50),
 descripcion text,
 imagen varchar(100),
 cantidad_maxima int,
 descuento_promocion int
);

-- Tabla detalle de platos en una promoción
CREATE TABLE tb_detalle_promocion
(
 id_promocion int NOT NULL, -- [PK/FK]
 id_plato int NOT NULL,     -- [PK/FK]
 cantidad_plato int
);
ALTER TABLE tb_detalle_promocion ADD PRIMARY KEY (id_promocion, id_plato);
ALTER TABLE tb_detalle_promocion ADD FOREIGN KEY(id_promocion) REFERENCES tb_promocion(id_promocion) ON DELETE CASCADE;
ALTER TABLE tb_detalle_promocion ADD FOREIGN KEY(id_plato) REFERENCES tb_plato(id_plato) ON DELETE CASCADE;

-- Tabla de pedidos
CREATE TABLE tb_pedido
(
 nro_pedido int PRIMARY KEY AUTO_INCREMENT,
 nro_cliente int NOT NULL, -- [FK]
 opcion_entrega int,
 id_ubicacion int NULL, -- [FK]
 metodo_pago int,
 costo_delivery decimal(8,2),
 total_pagar decimal(8,2),
 fecha_hora datetime,
 estado int
);
ALTER TABLE tb_pedido ADD FOREIGN KEY(nro_cliente) REFERENCES tb_cliente(nro_cliente) ON DELETE CASCADE; 
ALTER TABLE tb_pedido ADD FOREIGN KEY(id_ubicacion) REFERENCES tb_ubicacion(id_ubicacion) ON DELETE SET NULL; 

-- Tabla detalle de platos por pedido
CREATE TABLE tb_detalle_pedido_plato
(
 nro_pedido int NOT NULL, -- [PK/FK]
 id_plato int NOT NULL,   -- [PK/FK]
 cantidad_plato int,
 precio decimal(8,2)
);
ALTER TABLE tb_detalle_pedido_plato ADD PRIMARY KEY (nro_pedido, id_plato);
ALTER TABLE tb_detalle_pedido_plato ADD FOREIGN KEY(nro_pedido) REFERENCES tb_pedido(nro_pedido) ON DELETE CASCADE;
ALTER TABLE tb_detalle_pedido_plato ADD FOREIGN KEY(id_plato) REFERENCES tb_plato(id_plato) ON DELETE CASCADE;

-- Tabla detalle de promociones por pedido
CREATE TABLE tb_detalle_pedido_promocion
(
 nro_pedido int NOT NULL,   -- [PK/FK]
 id_promocion int NOT NULL, -- [PK/FK]
 cantidad_promocion int,
 precio decimal(8,2)
);
ALTER TABLE tb_detalle_pedido_promocion ADD PRIMARY KEY (nro_pedido, id_promocion);
ALTER TABLE tb_detalle_pedido_promocion ADD FOREIGN KEY(nro_pedido) REFERENCES tb_pedido(nro_pedido) ON DELETE CASCADE;
ALTER TABLE tb_detalle_pedido_promocion ADD FOREIGN KEY(id_promocion) REFERENCES tb_promocion(id_promocion) ON DELETE CASCADE;




-- INSERTS
INSERT INTO tb_rol (id_rol, nombre_rol)
VALUES
(1, 'cliente-invitado'),
(2, 'cliente-registrado'),
(3, 'empleado-atencion'),
(4, 'empleado-admin');

INSERT INTO tb_menu_item (id_item, item_label, item_link, item_tipo, id_parent)
VALUES
(1, 'Portal', './', 'nav-menu', 0),
(2, 'Nosotros', 'nosotros/', 'nav-menu', 0),
(3, 'Carta', 'carta/', 'nav-menu', 0),
(4, 'Contacto', 'contacto/', 'nav-menu', 0),
(5, 'Pedido', 'usuario/cli-pedido/', 'nav-menu', 0),
(6, 'Contacto', 'usuario/cli-contacto/', 'nav-menu', 0),
(7, 'Atender Pedidos', 'usuario/atn-pedido/', 'nav-menu', 0),
(8, 'Atender Mensajes', 'usuario/atn-contacto/', 'nav-menu', 0),
(9, 'Platos', 'usuario/adm-plato/', 'nav-menu', 0),
(10, 'Promociones', 'usuario/adm-promocion/', 'nav-menu', 0),
(11, 'Clientes', 'usuario/adm-cliente/', 'nav-menu', 0),
(12, 'Empleados', 'usuario/adm-empleado/', 'nav-menu', 0),
(13, 'Registrarse', 'registro/', 'btn-menu', 0),
(14, 'Iniciar sesión', 'login/', 'btn-menu', 0),
(15, 'Mi Perfil', 'usuario/perfil-cliente/', 'btn-menu', 0),
(16, 'Mi Perfil', 'usuario/perfil-empleado/', 'btn-menu', 0),
(17, 'Salir', './', 'btn-menu', 0),
(18, 'Preguntas frecuentes', './faq.php', 'footer-menu', 0),
(19, 'Términos y condiciones', './terms.php', 'footer-menu', 0),
(20, 'Mapa de sitio', './site-map.php', 'footer-menu', 0);

INSERT INTO tb_rol_menu (id_rol, id_item)
VALUES
-- Cliente invitado: portal, nosotros, carta, contacto-1, registro, login
(1, 1), (1, 2), (1, 3), (1, 4), (1, 13), (1, 14),
-- Cliente registrado: portal, nosotros, carta, pedido, contacto-2, perfil-cliente, logout
(2, 1), (2, 2), (2, 3), (2, 5), (2, 6), (2, 15), (2, 17),
-- Empleado atención: portal, carta, atencion-pedidos, atencion-contacto, perfil-empleado, logout
(3, 1), (3, 3), (3, 7), (3, 8), (3, 16), (3, 17),
-- Empleado admin: portal, platos, promociones, clientes, empleados, perfil-empleado, logout
(4, 1), (4, 9), (4, 10), (4, 11), (4, 12), (4, 16), (4, 17),
-- Todos: menús del pie
(1, 18), (1, 19), (1, 20), (2, 18), (2, 19), (2, 20),
(3, 18), (3, 19), (3, 20), (4, 18), (4, 19), (4, 20);

INSERT INTO tb_distrito (id_distrito, distrito, provincia)
VALUES
(1, 'Ancón', 'Lima Metropolitana'),
(2, 'Ate', 'Lima Metropolitana'),
(3, 'Barranco', 'Lima Metropolitana'),
(4, 'Breña', 'Lima Metropolitana'),
(5, 'Carabayllo', 'Lima Metropolitana'),
(6, 'Chaclacayo', 'Lima Metropolitana'),
(7, 'Chorrillos', 'Lima Metropolitana'),
(8, 'Cieneguilla', 'Lima Metropolitana'),
(9, 'Comas', 'Lima Metropolitana'),
(10, 'El Agustino', 'Lima Metropolitana'),
(11, 'Independencia', 'Lima Metropolitana'),
(12, 'Jesús María', 'Lima Metropolitana'),
(13, 'La Molina', 'Lima Metropolitana'),
(14, 'La Victoria', 'Lima Metropolitana'),
(15, 'Lima', 'Lima Metropolitana'),
(16, 'Lince', 'Lima Metropolitana'),
(17, 'Los Olivos', 'Lima Metropolitana'),
(18, 'Lurigancho', 'Lima Metropolitana'),
(19, 'Lurín', 'Lima Metropolitana'),
(20, 'Magdalena del Mar', 'Lima Metropolitana'),
(21, 'Miraflores', 'Lima Metropolitana'),
(22, 'Pachacámac', 'Lima Metropolitana'),
(23, 'Pucusana', 'Lima Metropolitana'),
(24, 'Pueblo Libre', 'Lima Metropolitana'),
(25, 'Puente Piedra', 'Lima Metropolitana'),
(26, 'Punta Hermosa', 'Lima Metropolitana'),
(27, 'Punta Negra', 'Lima Metropolitana'),
(28, 'Rímac', 'Lima Metropolitana'),
(29, 'San Bartolo', 'Lima Metropolitana'),
(30, 'San Borja', 'Lima Metropolitana'),
(31, 'San Isidro', 'Lima Metropolitana'),
(32, 'San Juan de Lurigancho', 'Lima Metropolitana'),
(33, 'San Juan de Miraflores', 'Lima Metropolitana'),
(34, 'San Luis', 'Lima Metropolitana'),
(35, 'San Martín de Porres', 'Lima Metropolitana'),
(36, 'San Miguel', 'Lima Metropolitana'),
(37, 'Santa Anita', 'Lima Metropolitana'),
(38, 'Santa María del Mar', 'Lima Metropolitana'),
(39, 'Santa Rosa', 'Lima Metropolitana'),
(40, 'Santiago de Surco', 'Lima Metropolitana'),
(41, 'Surquillo', 'Lima Metropolitana'),
(42, 'Villa El Salvador', 'Lima Metropolitana'),
(43, 'Villa María del Triunfo', 'Lima Metropolitana'),
(44,'Callao', 'Callao'),
(45,'Bellavista', 'Callao'),
(46,'Carmen de La Legua-Reynoso', 'Callao'),
(47,'La Perla', 'Callao'),
(48,'La Punta', 'Callao'),
(49,'Ventanilla', 'Callao'),
(50,'Mi Perú', 'Callao');

INSERT INTO `tb_ubicacion` VALUES (1,'Av. Las Palmeras 5728',17,-11.9670,-77.0695),(2,'Jr. Arrieta 399',48,-12.0711,-77.1626),(3,'Jr. San Martín 168',23,-12.0541,-77.1626),(4,'Calle Santa Rosa 421',25,-12.0667,-77.0695),(5,'Av. Los Álamos 239',12,-11.8231,-77.0915),(6,'Jr. Bolívar 315',15,-11.9856,-77.0915),(7,'Calle Los Jazmines 789',18,-11.8190,-77.0695),(8,'Av. El Sol 1425',29,-11.8146,-77.0915),(9,'Jr. Grau 678',12,-11.9829,-77.1626),(10,'Calle Las Margaritas 310',15,-11.9527,-77.0915),(11,'Av. Primavera 876',13,-12.0844,-77.0695),(12,'Jr. Tupac Amaru 523',14,-11.8853,-77.1212),(13,'Calle La Merced 112',7,-12.0172,-77.1626),(14,'Av. Los Olivos 990',5,-11.8082,-77.0695),(15,'Jr. Manco Cápac 215',2,-12.0776,-77.0915),(16,'Calle Los Laureles 456',3,-11.9076,-77.1626),(17,'Av. Libertad 1330',1,-11.9773,-77.0695),(18,'Jr. Pizarro 102',5,-11.8247,-77.1212),(19,'Calle San Juan 740',2,-11.9747,-77.1626),(20,'Av. Arequipa 502',6,-12.0027,-77.0695),(21,'Jr. Ayacucho 625',3,-11.9449,-77.1212),(22,'Calle Los Cedros 983',4,-11.9721,-77.1212),(23,'Av. Las Acacias 701',42,-11.8247,-77.0695),(24,'Jr. Independencia 140',15,-12.0776,-77.1212),(25,'Calle Las Camelias 444',23,-11.9721,-77.1212),(26,'Av. Los Héroes 2001',36,-11.9449,-77.0695),(27,'Jr. Bolognesi 678',31,-11.8247,-77.1626),(28,'Calle Las Rosas 321',32,-12.0776,-77.1212),(29,'Av. Los Próceres 875',34,-12.0027,-77.0695),(30,'Jr. Santa Isabel 523',38,-11.8247,-77.1626),(31,'Calle Los Claveles 711',36,-12.0027,-77.0695);

INSERT INTO `tb_usuario` VALUES ('ADMIN','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2024-06-10 08:30:14',4),('ana.jarana.111','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2024-11-11 22:13:45',3),('ana.jarana.222','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2024-11-11 22:13:45',4),('ana.jarana.329','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2024-11-11 22:13:45',2),('ana.rodriguez.111','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2024-11-11 22:13:45',2),('ana.rodriguez.222','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2024-11-11 22:13:45',3),('ana.rodriguez.329','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2024-11-11 22:13:45',2),('ATENCION','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2024-06-10 08:30:14',3),('carlos.lopez.111','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2020-05-03 06:55:31',2),('carlos.lopez.222','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2020-05-03 06:55:31',3),('carlos.lopez.984','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2020-05-03 06:55:31',2),('carlos.mercedes.123','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2024-10-17 07:50:32',2),('carlos.solano.111','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2020-05-03 06:55:31',3),('carlos.solano.222','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2020-05-03 06:55:31',4),('carlos.solano.984','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2020-05-03 06:55:31',2),('CLIENTE','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2024-06-10 08:30:14',2),('diego.fernandez.111','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2022-12-31 23:59:59',2),('diego.fernandez.112','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2022-12-31 23:59:59',2),('diego.fernandez.222','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2022-12-31 23:59:59',3),('diego.hernandez.111','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2022-12-31 23:59:59',3),('diego.hernandez.112','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2022-12-31 23:59:59',2),('diego.hernandez.222','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2022-12-31 23:59:59',4),('jose.mendoza.111','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2020-02-29 12:00:00',3),('jose.mendoza.222','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2020-02-29 12:00:00',4),('jose.mendoza.654','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2020-02-29 12:00:00',2),('jose.perez.111','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2020-02-29 12:00:00',2),('jose.perez.222','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2020-02-29 12:00:00',3),('jose.perez.654','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2020-02-29 12:00:00',2),('juana.garcia.111','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2022-08-19 11:12:20',2),('juana.garcia.222','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2022-08-19 11:12:20',3),('juana.garcia.231','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2022-08-19 11:12:20',2),('juana.romero.111','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2022-08-19 11:12:20',3),('juana.romero.222','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2022-08-19 11:12:20',4),('juana.romero.231','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2022-08-19 11:12:20',2),('laura.perez.111','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2021-10-10 10:10:10',3),('laura.perez.222','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2021-10-10 10:10:10',4),('laura.perez.789','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2021-10-10 10:10:10',2),('laura.sanchez.111','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2021-10-10 10:10:10',2),('laura.sanchez.222','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2021-10-10 10:10:10',3),('laura.sanchez.789','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2021-10-10 10:10:10',2),('luis.martinez.111','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2021-03-22 09:40:05',2),('luis.martinez.195','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2021-03-22 09:40:05',2),('luis.martinez.222','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2021-03-22 09:40:05',3),('luis.sanchez.111','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2021-03-22 09:40:05',3),('luis.sanchez.195','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2021-03-22 09:40:05',2),('luis.sanchez.222','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2021-03-22 09:40:05',4),('maria.fuentes.111','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2023-07-15 14:25:18',2),('maria.fuentes.222','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2023-07-15 14:25:18',3),('maria.fuentes.456','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2023-07-15 14:25:18',2),('maria.melendez.111','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2023-07-15 14:25:18',3),('maria.melendez.222','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2023-07-15 14:25:18',4),('maria.melendez.456','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2023-07-15 14:25:18',2),('marta.salas.123','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2024-10-13 07:10:00',2),('pablo.morales.123','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2024-10-13 04:10:00',4),('pedro.ramirez.111','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2025-01-25 18:45:00',2),('pedro.ramirez.222','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2025-01-25 18:45:00',3),('pedro.ramirez.562','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2025-01-25 18:45:00',2),('pedro.ruiz.111','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2025-01-25 18:45:00',3),('pedro.ruiz.222','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2025-01-25 18:45:00',4),('pedro.ruiz.562','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2025-01-25 18:45:00',2),('rosa.castillo.123','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2024-10-13 05:10:00',3),('sofia.flores.111','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2024-06-10 08:30:14',3),('sofia.flores.222','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2024-06-10 08:30:14',4),('sofia.flores.873','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2024-06-10 08:30:14',2),('sofia.mendoza.111','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2024-06-10 08:30:14',2),('sofia.mendoza.222','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2024-06-10 08:30:14',3),('sofia.mendoza.873','$2y$10$7Zc4vxE6Zf52vo7yR0wfHe5zGmXkhyOjCXZQY3t.76KG9qePwrda6','2024-06-10 08:30:14',2);

INSERT INTO `tb_empleado` VALUES (1,'Pablo Manuel','Morales','pablo.morales@gmail.com','pablo.morales.123'),(2,'Rosa','Castillo','rosa.castillo@gmail.com','rosa.castillo.123'),(3,'Ada','Advíncula','admin@gmail.com','ADMIN'),(4,'Ana','Jarana','ana4@gmail.com','ana.jarana.111'),(5,'Ana','Jarana','ana5@gmail.com','ana.jarana.222'),(6,'Ana','Rodriguez','ana6@gmail.com','ana.rodriguez.222'),(7,'Atanasio','Atencio','atencion@gmail.com','ATENCION'),(8,'Carlos','Perez','carlosl@gmail.com','carlos.lopez.222'),(9,'Carlos','Romero','carloss@gmail.com','carlos.solano.111'),(10,'Diego','Perez','diegoh@gmail.com','diego.fernandez.222'),(11,'Diego','Romero','diegoh2@gmail.com','diego.hernandez.111'),(12,'Diego','Ruiz','diegoh3@gmail.com','diego.hernandez.222'),(13,'Jose','Mendoza','josem@gmail.com','jose.mendoza.111'),(14,'Jose','Leon','josem1@gmail.com','jose.mendoza.222'),(15,'Jose','Perez','josep@gmail.com','jose.perez.222'),(16,'Juana','Garcia','juanag@gmail.com','juana.garcia.222'),(17,'Juana','Romero','juanar@gmail.com','juana.romero.111'),(18,'Juana','Romano','juanar2@gmail.com','juana.romero.222'),(19,'Laura','Quispe','laurap@gmail.com','laura.perez.111'),(20,'Laura','Supe','laurap2@gmail.com','laura.perez.222'),(21,'Laura','Sanchez','lauras@gmail.com','laura.sanchez.222'),(22,'Luis','Martinez','luism@gmail.com','luis.martinez.222'),(23,'Luis','Molina','luism2@gmail.com','luis.sanchez.111'),(24,'Luis','Sanchez','luism3@gmail.com','luis.sanchez.222'),(25,'Maria','Fuentes','mariaf@gmail.com','maria.fuentes.222'),(26,'Maria','Mar','mariam@gmail.com','maria.melendez.111'),(27,'Maria','Medina','mariam2@gmail.com','maria.melendez.222'),(28,'Pedro','Gomez','pedror@gmail.com','pedro.ruiz.111'),(29,'Pedro','Ruiz','pedror2@gmail.com','pedro.ruiz.222'),(30,'Sofia','Rosas','sofiaf@gmail.com','sofia.flores.111'),(31,'Sofia','Flores','sofiafl@gmail.com','sofia.flores.222'),(32,'Sofia','Mendoza','sofiam@gmail.com','sofia.mendoza.222');

INSERT INTO `tb_cliente` VALUES (1,'Ana','Jarana','ana1@gmail.com','989898989',0,'ana.jarana.329',NULL),(2,'Marta','Salas','marta.salas@gmail.com','987654321',8,'marta.salas.123',1),(3,'Marco','Díaz','pedro.flores.123@gmail.com','989843212',0,NULL,NULL),(4,'Carlos','Mercedes','carlos.mercedes.123@gmail.com','165123611',12,'carlos.mercedes.123',2),(5,'Manuel','López','manuel.lopez@gmail.com','156132615',0,NULL,NULL),(6,'Ana','Rodriguez','ana2@gmail.com','121212211',0,'ana.rodriguez.111',NULL),(7,'Ana','Rodriguez','ana3@gmail.com','111111111',3,'ana.rodriguez.329',4),(8,'Carlos','Lopez','carlos1@gmail.com','222222222',5,'carlos.lopez.111',NULL),(9,'Carlos','Lopez','carlos2@gmail.com','333333333',0,'carlos.lopez.984',5),(10,'Carlos','Solano','carlos3@gmail.com','444444444',0,'carlos.solano.984',NULL),(11,'Mario','Díaz','cliente@gmail.com','555555555',15,'CLIENTE',3),(12,'Diego','Fernandez','diego1@gmail.com','666666666',0,'diego.fernandez.111',NULL),(13,'Diego','Fernández','diego2@gmail.com','777777777',7,'diego.fernandez.112',NULL),(14,'Diego','Hernández','diego3@gmail.com','888888888',0,'diego.hernandez.112',6),(15,'José','Mendoza','jose1@gmail.com','999999999',0,'jose.mendoza.654',NULL),(16,'José','Pérez','jose2@gmail.com','123456789',2,'jose.perez.111',7),(17,'José','Pérez','jose3@gmail.com','987654321',0,'jose.perez.654',NULL),(18,'Juana','Garcia','juana1@gmail.com','123698547',5,'juana.garcia.111',8),(19,'Juana','García','juana2@gmail.com','147852369',0,'juana.garcia.231',NULL),(20,'Juana','Romero','juana3@gmail.com','512369874',6,'juana.romero.231',9),(21,'Laura','Pérez','laura1@gmail.com','478512369',0,'laura.perez.789',NULL),(22,'Laura','Sánchez','laura2@gmail.com','578964123',4,'laura.sanchez.111',10),(23,'Laura','Sánchez','laura3@gmail.com','325698741',0,'laura.sanchez.789',NULL),(24,'Luis','Martínez','luis1@gmail.com','147852369',3,'luis.martinez.111',11),(25,'Luis','Martínez','luis2@gmail.com','179324685',0,'luis.martinez.195',NULL),(26,'Luis','Sánchez','luis3@gmail.com','346821597',9,'luis.sanchez.195',12),(27,'Maria','Fuentes','maria@gmail.com','846168432',0,'maria.fuentes.111',NULL),(28,'Maria','Fuentes','maria2@gmail.com','564984165',5,'maria.fuentes.456',13),(29,'Maria','Meléndez','maria3@gmail.com','984561654',0,'maria.melendez.456',NULL),(30,'Marta','Salas','marta@gmail.com','984318546',3,'marta.salas.123',14),(31,'Pedro','Ramirez','pedro@gmail.com','236168466',0,'pedro.ramirez.111',NULL),(32,'Pedro','Ramirez','pedro2@gmail.com','849616365',2,'pedro.ramirez.562',15),(33,'Pedro','Ruiz','pedrito@gmail.com','984623469',0,'pedro.ruiz.562',NULL),(34,'Sofia','Flores','sofia@gmail.com','135143626',1,'sofia.flores.873',16),(35,'Sofia','Mendoza','sofi@gmail.com','549894631',0,'sofia.mendoza.111',NULL),(36,'Sofia','Mendoza','sofia2@gmail.com','974613158',5,'sofia.mendoza.873',17);

INSERT INTO `tb_mensaje` VALUES (1,10,'Reclamo','Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae ratione magnam explicabo iste dolores velit. Ipsum deleniti molestias minima quam itaque aliquam a dolorum, rerum doloribus veritatis, minus nam beatae!','2024-10-17 13:40:15'),(2,4,'Reclamo','Hola. Reclamo que...','2024-10-17 13:40:15'),(3,5,'Otro asunto','Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sed, ratione eius eos voluptates nobis alias quas neque, pariatur voluptatum maiores illo corporis quaerat. Reprehenderit corrupti quaerat culpa explicabo voluptatibus nesciunt!','2024-11-21 07:23:24'),(4,11,'Sugerencia','Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt adipisci aliquam, magnam perferendis ex libero doloremque facilis maiores. Maxime dolore et blanditiis ut dicta aut nobis doloremque quis facere totam?','2024-11-21 07:23:17'),(5,31,'Reclamo','Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sed, ratione eius eos voluptates nobis alias quas neque, pariatur voluptatum maiores illo corporis quaerat. Reprehenderit corrupti quaerat culpa explicabo voluptatibus nesciunt!','2024-11-21 07:23:24'),(6,15,'Ofrezco un producto o servicio','Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae ratione magnam explicabo iste dolores velit. Ipsum deleniti molestias minima quam itaque aliquam a dolorum, rerum doloribus veritatis, minus nam beatae!','2024-11-21 07:23:33'),(7,11,'Otro asunto','Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt adipisci aliquam, magnam perferendis ex libero doloremque facilis maiores. Maxime dolore et blanditiis ut dicta aut nobis doloremque quis facere totam?','2024-11-21 07:23:40'),(8,11,'Reclamo','Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt adipisci aliquam, magnam perferendis ex libero doloremque facilis maiores. Maxime dolore et blanditiis ut dicta aut nobis doloremque quis facere totam?','2024-10-17 13:40:15'),(9,3,'Sugerencia','Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sed, ratione eius eos voluptates nobis alias quas neque, pariatur voluptatum maiores illo corporis quaerat. Reprehenderit corrupti quaerat culpa explicabo voluptatibus nesciunt!','2024-11-21 07:23:33'),(10,11,'Ofrezco un producto o servicio','Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt adipisci aliquam, magnam perferendis ex libero doloremque facilis maiores. Maxime dolore et blanditiis ut dicta aut nobis doloremque quis facere totam?','2024-10-17 13:40:15'),(11,4,'Reclamo','Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae ratione magnam explicabo iste dolores velit. Ipsum deleniti molestias minima quam itaque aliquam a dolorum, rerum doloribus veritatis, minus nam beatae!','2024-11-21 07:23:24'),(12,2,'Otro asunto','Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sed, ratione eius eos voluptates nobis alias quas neque, pariatur voluptatum maiores illo corporis quaerat. Reprehenderit corrupti quaerat culpa explicabo voluptatibus nesciunt!','2024-11-21 07:23:33'),(13,13,'Reclamo','Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt adipisci aliquam, magnam perferendis ex libero doloremque facilis maiores. Maxime dolore et blanditiis ut dicta aut nobis doloremque quis facere totam?','2024-10-17 13:40:15'),(14,18,'Sugerencia','Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sed, ratione eius eos voluptates nobis alias quas neque, pariatur voluptatum maiores illo corporis quaerat. Reprehenderit corrupti quaerat culpa explicabo voluptatibus nesciunt!','2024-11-21 07:23:17'),(15,11,'Sugerencia','Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sed, ratione eius eos voluptates nobis alias quas neque, pariatur voluptatum maiores illo corporis quaerat. Reprehenderit corrupti quaerat culpa explicabo voluptatibus nesciunt!','2024-11-21 07:23:33'),(16,16,'Ofrezco un producto o servicio','Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sed, ratione eius eos voluptates nobis alias quas neque, pariatur voluptatum maiores illo corporis quaerat. Reprehenderit corrupti quaerat culpa explicabo voluptatibus nesciunt!','2024-10-17 13:40:15'),(17,22,'Otro asunto','Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae ratione magnam explicabo iste dolores velit. Ipsum deleniti molestias minima quam itaque aliquam a dolorum, rerum doloribus veritatis, minus nam beatae!','2024-11-21 07:23:17'),(18,24,'Reclamo','Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sed, ratione eius eos voluptates nobis alias quas neque, pariatur voluptatum maiores illo corporis quaerat. Reprehenderit corrupti quaerat culpa explicabo voluptatibus nesciunt!','2024-10-17 13:40:15'),(19,11,'Sugerencia','Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae ratione magnam explicabo iste dolores velit. Ipsum deleniti molestias minima quam itaque aliquam a dolorum, rerum doloribus veritatis, minus nam beatae!','2024-11-21 07:23:24'),(20,17,'Reclamo','Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sed, ratione eius eos voluptates nobis alias quas neque, pariatur voluptatum maiores illo corporis quaerat. Reprehenderit corrupti quaerat culpa explicabo voluptatibus nesciunt!','2024-10-17 13:40:15'),(21,18,'Otro asunto','Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae ratione magnam explicabo iste dolores velit. Ipsum deleniti molestias minima quam itaque aliquam a dolorum, rerum doloribus veritatis, minus nam beatae!','2024-11-21 07:23:33'),(22,19,'Sugerencia','Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sed, ratione eius eos voluptates nobis alias quas neque, pariatur voluptatum maiores illo corporis quaerat. Reprehenderit corrupti quaerat culpa explicabo voluptatibus nesciunt!','2024-10-17 13:40:15'),(23,20,'Otro asunto','Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae ratione magnam explicabo iste dolores velit. Ipsum deleniti molestias minima quam itaque aliquam a dolorum, rerum doloribus veritatis, minus nam beatae!','2024-11-21 07:23:33'),(24,25,'Reclamo','Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt adipisci aliquam, magnam perferendis ex libero doloremque facilis maiores. Maxime dolore et blanditiis ut dicta aut nobis doloremque quis facere totam?','2024-11-21 07:23:24'),(25,11,'Sugerencia','Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae ratione magnam explicabo iste dolores velit. Ipsum deleniti molestias minima quam itaque aliquam a dolorum, rerum doloribus veritatis, minus nam beatae!','2024-10-17 13:40:15'),(26,26,'Otro asunto','Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae ratione magnam explicabo iste dolores velit. Ipsum deleniti molestias minima quam itaque aliquam a dolorum, rerum doloribus veritatis, minus nam beatae!','2024-10-17 13:40:15'),(27,27,'Ofrezco un producto o servicio','Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sed, ratione eius eos voluptates nobis alias quas neque, pariatur voluptatum maiores illo corporis quaerat. Reprehenderit corrupti quaerat culpa explicabo voluptatibus nesciunt!','2024-11-21 07:23:17'),(28,28,'Sugerencia','Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sed, ratione eius eos voluptates nobis alias quas neque, pariatur voluptatum maiores illo corporis quaerat. Reprehenderit corrupti quaerat culpa explicabo voluptatibus nesciunt!','2024-11-21 07:23:24'),(29,29,'Reclamo','Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sed, ratione eius eos voluptates nobis alias quas neque, pariatur voluptatum maiores illo corporis quaerat. Reprehenderit corrupti quaerat culpa explicabo voluptatibus nesciunt!','2024-11-21 07:23:33'),(30,11,'Otro asunto','Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae ratione magnam explicabo iste dolores velit. Ipsum deleniti molestias minima quam itaque aliquam a dolorum, rerum doloribus veritatis, minus nam beatae!','2024-10-17 13:40:15');

INSERT INTO tb_categoria (nombre, tipo)
VALUES
-- Tipo 1: ENTRADAS
('Aperitivos', 1),
('Ensaladas', 1),
('Sopas', 1),
('Sánguches y hamburguesas', 1),
('Snacks', 1),
('Dips y salsas', 1),
-- Tipo 2: PLATOS PRINCIPALES
('Pastas y fideos', 2),
('Platos de verduras', 2),
('Pescados y mariscos', 2),
('Platos de pollo', 2),
('Carne de res y cerdo', 2),
('Guarniciones', 2),
-- Tipo 3: POSTRES
('Tartas y pasteles', 3),
('Panes', 3),
('Postres fríos', 3),
-- Tipo 4: BEBIDAS
('Jugos y licuados', 4),
('Licores y cócteles', 4),
('Café e infusiones', 4);

INSERT INTO `tb_plato` VALUES (1,'Empanadas de Carne y Queso a la Parrilla',1,'Empanadas crujientes rellenas de carne sazonada y queso fundido, asadas a la parrilla para un toque ahumado. Servidas con una salsa chimichurri fresca para mojar.','empanada.jpg',15.00,0,10),(2,'Costillas a la Parrilla con Salsa BBQ de la Casa',11,'Jugosas costillas de cerdo cocinadas a fuego lento y asadas a la parrilla, bañadas en una salsa BBQ casera con un toque de especias. Acompañadas de papas rústicas y ensalada de col fresca.','carne.jpg',32.00,5,10),(4,'Hamburguesa de carne',4,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae ratione magnam explicabo iste dolores velit. Ipsum deleniti molestias minima quam itaque aliquam a dolorum, rerum doloribus veritatis, minus nam beatae!','hamburguesa.jpg',19.00,10,20),(5,'Tequeños con guacamole',5,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt adipisci aliquam, magnam perferendis ex libero doloremque facilis maiores. Maxime dolore et blanditiis ut dicta aut nobis doloremque quis facere totam?','tequeño.jpg',18.00,10,20),(6,'Ensalada cesar',2,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae ratione magnam explicabo iste dolores velit. Ipsum deleniti molestias minima quam itaque aliquam a dolorum, rerum doloribus veritatis, minus nam beatae!','ensalada-cesar.jpg',16.00,5,20),(7,'Café',18,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam perferendis sunt recusandae veniam dicta ut deserunt quia, dignissimos assumenda necessitatibus a eligendi modi facilis vitae, veritatis at voluptates amet delectus.','cafe.jpg',12.00,5,20),(8,'Sopa a la minuta',3,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae ratione magnam explicabo iste dolores velit. Ipsum deleniti molestias minima quam itaque aliquam a dolorum, rerum doloribus veritatis, minus nam beatae!','sopa-minuta.jpg',14.00,0,10),(9,'Lasaña de carne',7,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt adipisci aliquam, magnam perferendis ex libero doloremque facilis maiores. Maxime dolore et blanditiis ut dicta aut nobis doloremque quis facere totam?','lasaña.jpg',19.00,0,10),(10,'Gratinado de verduras',8,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae ratione magnam explicabo iste dolores velit. Ipsum deleniti molestias minima quam itaque aliquam a dolorum, rerum doloribus veritatis, minus nam beatae!','gratinado.jpg',16.00,0,10),(11,'Ceviche de pescado',9,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam perferendis sunt recusandae veniam dicta ut deserunt quia, dignissimos assumenda necessitatibus a eligendi modi facilis vitae, veritatis at voluptates amet delectus.','ceviche.jpg',20.00,0,10),(12,'Pollo a la brasa',10,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt adipisci aliquam, magnam perferendis ex libero doloremque facilis maiores. Maxime dolore et blanditiis ut dicta aut nobis doloremque quis facere totam?','pollo-brasa.jpg',28.00,0,20),(13,'Lomo saltado de carne',11,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae ratione magnam explicabo iste dolores velit. Ipsum deleniti molestias minima quam itaque aliquam a dolorum, rerum doloribus veritatis, minus nam beatae!','lomo-saltado.jpg',16.00,15,20),(14,'Arroz a la jardinera',12,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam perferendis sunt recusandae veniam dicta ut deserunt quia, dignissimos assumenda necessitatibus a eligendi modi facilis vitae, veritatis at voluptates amet delectus.','arroz-jardinera.jpg',12.00,10,20),(15,'Torta Selva Negra',13,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt adipisci aliquam, magnam perferendis ex libero doloremque facilis maiores. Maxime dolore et blanditiis ut dicta aut nobis doloremque quis facere totam?','selva-negra.jpg',24.00,0,0),(16,'Croissant',14,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae ratione magnam explicabo iste dolores velit. Ipsum deleniti molestias minima quam itaque aliquam a dolorum, rerum doloribus veritatis, minus nam beatae!','croissant.jpg',10.00,0,5),(17,'Helado de chocolate',15,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam perferendis sunt recusandae veniam dicta ut deserunt quia, dignissimos assumenda necessitatibus a eligendi modi facilis vitae, veritatis at voluptates amet delectus.','helado.jpg',12.00,0,5),(18,'Fresa con leche',16,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt adipisci aliquam, magnam perferendis ex libero doloremque facilis maiores. Maxime dolore et blanditiis ut dicta aut nobis doloremque quis facere totam?','jugo-fresa.jpg',8.00,2,5),(19,'Piña colada',17,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae ratione magnam explicabo iste dolores velit. Ipsum deleniti molestias minima quam itaque aliquam a dolorum, rerum doloribus veritatis, minus nam beatae!','piña-colada.jpg',10.00,0,5),(20,'Chicha morada',16,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam perferendis sunt recusandae veniam dicta ut deserunt quia, dignissimos assumenda necessitatibus a eligendi modi facilis vitae, veritatis at voluptates amet delectus.','chicha-morada.jpg',8.00,0,0),(21,'Torta helada',15,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt adipisci aliquam, magnam perferendis ex libero doloremque facilis maiores. Maxime dolore et blanditiis ut dicta aut nobis doloremque quis facere totam?','torta-helada.jpg',10.00,8,15),(22,'Pastel Savarin',13,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae ratione magnam explicabo iste dolores velit. Ipsum deleniti molestias minima quam itaque aliquam a dolorum, rerum doloribus veritatis, minus nam beatae!','torta-savarin.jpg',16.00,0,0),(23,'Papas fritas',12,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam perferendis sunt recusandae veniam dicta ut deserunt quia, dignissimos assumenda necessitatibus a eligendi modi facilis vitae, veritatis at voluptates amet delectus.','papas-fritas.jpg',12.00,10,15),(24,'Papas al horno',12,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt adipisci aliquam, magnam perferendis ex libero doloremque facilis maiores. Maxime dolore et blanditiis ut dicta aut nobis doloremque quis facere totam?','papas-horno.jpg',12.00,0,0),(25,'Chancho a la caja china',11,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae ratione magnam explicabo iste dolores velit. Ipsum deleniti molestias minima quam itaque aliquam a dolorum, rerum doloribus veritatis, minus nam beatae!','chancho-caja.jpg',22.00,0,0),(26,'Chuleta de cerdo',11,'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sed, ratione eius eos voluptates nobis alias quas neque, pariatur voluptatum maiores illo corporis quaerat. Reprehenderit corrupti quaerat culpa explicabo voluptatibus nesciunt!','chuleta-cerdo.jpg',19.00,10,15),(27,'Pollo al cilindro',10,'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sed, ratione eius eos voluptates nobis alias quas neque, pariatur voluptatum maiores illo corporis quaerat. Reprehenderit corrupti quaerat culpa explicabo voluptatibus nesciunt!','pollo-cilindro.jpg',18.00,0,15),(28,'Pollo broster',10,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt adipisci aliquam, magnam perferendis ex libero doloremque facilis maiores. Maxime dolore et blanditiis ut dicta aut nobis doloremque quis facere totam?','pollo-broster.jpg',15.00,0,15),(29,'Arroz con mariscos',9,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae ratione magnam explicabo iste dolores velit. Ipsum deleniti molestias minima quam itaque aliquam a dolorum, rerum doloribus veritatis, minus nam beatae!','arroz-mariscos.jpg',20.00,12,15),(30,'Tallarines rojos',7,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae ratione magnam explicabo iste dolores velit. Ipsum deleniti molestias minima quam itaque aliquam a dolorum, rerum doloribus veritatis, minus nam beatae!','tallarin-rojo.jpg',18.00,0,0);

INSERT INTO tb_plato_del_dia (id_plato, fecha_eleccion) VALUES ('2', '2024-10-13 04:16:53');

INSERT INTO `tb_promocion` VALUES (1,'Combo #1','Descripción del combo 1. Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloribus quisquam at quia eaque, sed, cupiditate commodi minima ex incidunt voluptate reprehenderit quam autem eum impedit soluta voluptatibus veniam laborum expedita!','1.jpg',3,10),(2,'Combo #2','Descripción del combo 2. Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloribus quisquam at quia eaque, sed, cupiditate commodi minima ex incidunt voluptate reprehenderit quam autem eum impedit soluta voluptatibus veniam laborum expedita!','2.jpg',5,5),(3,'Combo #3','Descripción del combo 3. Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloribus quisquam at quia eaque, sed, cupiditate commodi minima ex incidunt voluptate reprehenderit quam autem eum impedit soluta voluptatibus veniam laborum expedita!','3.jpg',4,15),(4,'Combo #4','Descripción del combo 4. Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloribus quisquam at quia eaque, sed, cupiditate commodi minima ex incidunt voluptate reprehenderit quam autem eum impedit soluta voluptatibus veniam laborum expedita!','4.jpg',5,24),(5,'Combo #5','Descripción del combo 5. Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloribus quisquam at quia eaque, sed, cupiditate commodi minima ex incidunt voluptate reprehenderit quam autem eum impedit soluta voluptatibus veniam laborum expedita!','5.jpg',6,18);

INSERT INTO `tb_detalle_promocion` VALUES (1,1,3),(1,8,1),(1,12,2),(2,2,2),(2,6,1),(3,18,1),(3,28,2),(5,7,2),(5,10,1),(5,20,1);

