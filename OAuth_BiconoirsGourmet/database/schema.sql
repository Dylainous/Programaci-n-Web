-- ═══════════════════════════════════════════════════════════════════════════
-- Biconoir's OAuth Demo — Base de datos simplificada
-- Solo contiene lo necesario para autenticación OAuth con Google
-- y visualización del menú (sin carrito ni pedidos)
-- ═══════════════════════════════════════════════════════════════════════════

DROP TABLE IF EXISTS dishes CASCADE;
DROP TABLE IF EXISTS users CASCADE;

-- ─── USERS ──────────────────────────────────────────────────────────────────
-- user_id = Google "sub" (ID único e inmutable que Google asigna a cada cuenta)
-- No se almacena contraseña. Google es el proveedor de identidad.
CREATE TABLE users (
    user_id   TEXT        PRIMARY KEY,   -- Google sub
    name      TEXT        NOT NULL,
    email     TEXT        UNIQUE NOT NULL,
    role      TEXT        NOT NULL DEFAULT 'customer'
                          CHECK (role IN ('customer', 'admin'))
);

-- ─── DISHES ─────────────────────────────────────────────────────────────────
CREATE TABLE dishes (
    dish_id     TEXT    PRIMARY KEY DEFAULT 'd_' || encode(gen_random_bytes(4), 'hex'),
    name        TEXT    NOT NULL,
    description TEXT,
    price       NUMERIC(10, 2) NOT NULL,
    category    TEXT,
    image       TEXT,
    available   BOOLEAN NOT NULL DEFAULT TRUE
);

-- ─── DATOS DE EJEMPLO ───────────────────────────────────────────────────────
INSERT INTO dishes (name, description, price, category, image) VALUES
('Risotto al Tartufo',        'Arroz arborio con trufa negra rallada, parmesano envejecido y reducción de vino blanco.', 28.90, 'Pastas', 'img/food1.jpg'),
('Salmón Wellington',         'Filete de salmón envuelto en hojaldre crujiente con espinacas a la crema y salsa hollandaise.', 34.50, 'Pescados', 'img/food2.jpg'),
('Gazpacho de Remolacha',     'Sopa fría de remolacha asada con jengibre fresco, crema de queso de cabra y aceite de albahaca.', 14.00, 'Entrantes', 'img/food3.jpg'),
('Magret de Pato',            'Pecho de pato a la plancha con reducción de frambuesas, puré de chirivía y chips de alcachofa.', 38.00, 'Carnes', 'img/food4.jpg'),
('Fondant de Chocolate Negro', 'Bizcocho tibio de chocolate 72% con centro líquido, helado de vainilla bourbon y praliné de avellanas.', 12.00, 'Postres', 'img/food5.jpg');
