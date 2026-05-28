-- ═══════════════════════════════════════════════════════════════════════════
-- Biconoir's Gourmet — Nueva base de datos para OAuth Google
-- Supabase (PostgreSQL)
--
-- IMPORTANTE: La tabla users ya NO tiene password_hash.
-- La autenticación la maneja Google OAuth completamente.
-- Solo almacenamos datos personales del usuario.
-- ═══════════════════════════════════════════════════════════════════════════

-- Eliminar tablas si existen (para empezar limpio)
DROP TABLE IF EXISTS order_details CASCADE;
DROP TABLE IF EXISTS orders CASCADE;
DROP TABLE IF EXISTS reservations CASCADE;
DROP TABLE IF EXISTS surveys CASCADE;
DROP TABLE IF EXISTS audit_logs CASCADE;
DROP TABLE IF EXISTS inventory_batches CASCADE;
DROP TABLE IF EXISTS dishes CASCADE;
DROP TABLE IF EXISTS ingredients CASCADE;
DROP TABLE IF EXISTS users CASCADE;

-- ─── USERS ──────────────────────────────────────────────────────────────────
-- user_id = Google "sub" (ID único e inmutable que Google asigna a cada cuenta)
-- No se almacena contraseña. Google es el proveedor de identidad.
CREATE TABLE users (
    user_id   TEXT        PRIMARY KEY,   -- Google sub
    name      TEXT        NOT NULL,
    email     TEXT        UNIQUE NOT NULL,
    phone     TEXT,
    birthdate DATE,
    role      TEXT        NOT NULL DEFAULT 'customer'
                          CHECK (role IN ('customer', 'admin', 'administrator'))
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

-- ─── INGREDIENTS ────────────────────────────────────────────────────────────
CREATE TABLE ingredients (
    ingredient_id   TEXT    PRIMARY KEY DEFAULT 'i_' || encode(gen_random_bytes(4), 'hex'),
    name            TEXT    NOT NULL,
    unit            TEXT    NOT NULL,
    stock_quantity  NUMERIC(10, 2) NOT NULL DEFAULT 0,
    min_stock       NUMERIC(10, 2) NOT NULL DEFAULT 0
);

-- ─── INVENTORY BATCHES ──────────────────────────────────────────────────────
CREATE TABLE inventory_batches (
    batch_id      TEXT    PRIMARY KEY DEFAULT 'b_' || encode(gen_random_bytes(4), 'hex'),
    ingredient_id TEXT    REFERENCES ingredients(ingredient_id) ON DELETE CASCADE,
    quantity      NUMERIC(10, 2) NOT NULL,
    supplier      TEXT,
    received_at   TIMESTAMP NOT NULL DEFAULT NOW()
);

-- ─── ORDERS ─────────────────────────────────────────────────────────────────
CREATE TABLE orders (
    order_id       TEXT    PRIMARY KEY DEFAULT 'o_' || encode(gen_random_bytes(4), 'hex'),
    customer_name  TEXT    NOT NULL,
    customer_email TEXT    NOT NULL,
    total          NUMERIC(10, 2) NOT NULL,
    status         TEXT    NOT NULL DEFAULT 'pending'
                           CHECK (status IN ('pending', 'preparing', 'ready', 'delivered', 'cancelled')),
    created_at     TIMESTAMP NOT NULL DEFAULT NOW()
);

-- ─── ORDER DETAILS ──────────────────────────────────────────────────────────
CREATE TABLE order_details (
    detail_id  TEXT    PRIMARY KEY DEFAULT 'od_' || encode(gen_random_bytes(4), 'hex'),
    order_id   TEXT    REFERENCES orders(order_id) ON DELETE CASCADE,
    dish_id    TEXT    REFERENCES dishes(dish_id),
    dish_name  TEXT    NOT NULL,
    quantity   INTEGER NOT NULL,
    unit_price NUMERIC(10, 2) NOT NULL
);

-- ─── RESERVATIONS ───────────────────────────────────────────────────────────
CREATE TABLE reservations (
    reservation_id TEXT    PRIMARY KEY DEFAULT 'r_' || encode(gen_random_bytes(4), 'hex'),
    user_id        TEXT    REFERENCES users(user_id) ON DELETE SET NULL,
    customer_name  TEXT    NOT NULL,
    customer_email TEXT    NOT NULL,
    party_size     INTEGER NOT NULL,
    date           DATE    NOT NULL,
    time           TIME    NOT NULL,
    notes          TEXT,
    status         TEXT    NOT NULL DEFAULT 'pending'
                           CHECK (status IN ('pending', 'confirmed', 'cancelled')),
    created_at     TIMESTAMP NOT NULL DEFAULT NOW()
);

-- ─── SURVEYS ────────────────────────────────────────────────────────────────
CREATE TABLE surveys (
    survey_id      TEXT    PRIMARY KEY DEFAULT 's_' || encode(gen_random_bytes(4), 'hex'),
    customer_name  TEXT    NOT NULL,
    customer_email TEXT    NOT NULL,
    rating         INTEGER NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment        TEXT,
    created_at     TIMESTAMP NOT NULL DEFAULT NOW()
);

-- ─── AUDIT LOGS ─────────────────────────────────────────────────────────────
CREATE TABLE audit_logs (
    log_id     TEXT    PRIMARY KEY DEFAULT 'l_' || encode(gen_random_bytes(4), 'hex'),
    user_id    TEXT    REFERENCES users(user_id) ON DELETE SET NULL,
    action     TEXT    NOT NULL,
    table_name TEXT,
    record_id  TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
);
