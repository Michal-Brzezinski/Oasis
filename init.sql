-- init.sql – baza pod aplikację Oasis

DROP TABLE IF EXISTS login_attempts CASCADE;
DROP TABLE IF EXISTS user_preferences CASCADE;
DROP TABLE IF EXISTS watering_actions CASCADE;
DROP TABLE IF EXISTS schedules CASCADE;
DROP TABLE IF EXISTS cameras CASCADE;
DROP TABLE IF EXISTS sensors CASCADE;
DROP TABLE IF EXISTS regions CASCADE;
DROP TABLE IF EXISTS users CASCADE;

-- Użytkownicy
CREATE TABLE users (
    id              SERIAL PRIMARY KEY,
    email           VARCHAR(255) NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,
    display_name    VARCHAR(100) NOT NULL,
    role            VARCHAR(20) NOT NULL DEFAULT 'OWNER', -- OWNER / WORKER / ADMIN
    is_active       BOOLEAN NOT NULL DEFAULT TRUE,
    created_at      TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMP
);

-- Preferencje użytkownika (powiadomienia itd.)
CREATE TABLE user_preferences (
    id              SERIAL PRIMARY KEY,
    user_id         INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    email_notifications_enabled BOOLEAN NOT NULL DEFAULT TRUE,
    created_at      TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMP
);

-- Próby logowania (do limitu / audytu)
CREATE TABLE login_attempts (
    id              SERIAL PRIMARY KEY,
    email           VARCHAR(255) NOT NULL,
    ip_address      VARCHAR(45),
    attempted_at    TIMESTAMP NOT NULL DEFAULT NOW(),
    success         BOOLEAN NOT NULL
);

-- Regiony (strefy / uprawy)
CREATE TABLE regions (
    id              SERIAL PRIMARY KEY,
    owner_id        INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    name            VARCHAR(100) NOT NULL,
    description     TEXT,
    created_at      TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMP
);

-- Czujniki
CREATE TABLE sensors (
    id              SERIAL PRIMARY KEY,
    region_id       INT NOT NULL REFERENCES regions(id) ON DELETE CASCADE,
    name            VARCHAR(100) NOT NULL,
    type            VARCHAR(50) NOT NULL, -- SOIL_MOISTURE, TEMP, LIGHT, etc.
    is_active       BOOLEAN NOT NULL DEFAULT TRUE,
    created_at      TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMP
);

-- Kamery
CREATE TABLE cameras (
    id              SERIAL PRIMARY KEY,
    region_id       INT NOT NULL REFERENCES regions(id) ON DELETE CASCADE,
    name            VARCHAR(100) NOT NULL,
    stream_url      TEXT NOT NULL,
    is_active       BOOLEAN NOT NULL DEFAULT TRUE,
    created_at      TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMP
);

-- Harmonogramy podlewania
CREATE TABLE schedules (
    id              SERIAL PRIMARY KEY,
    region_id       INT NOT NULL REFERENCES regions(id) ON DELETE CASCADE,
    name            VARCHAR(100) NOT NULL,
    cron_expression VARCHAR(100) NOT NULL,
    volume_liters   NUMERIC(10,2) NOT NULL,
    is_enabled      BOOLEAN NOT NULL DEFAULT TRUE,
    created_at      TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMP
);

-- Akcje podlewania (ręczne + z harmonogramu)
CREATE TABLE watering_actions (
    id              SERIAL PRIMARY KEY,
    region_id       INT NOT NULL REFERENCES regions(id) ON DELETE CASCADE,
    schedule_id     INT REFERENCES schedules(id) ON DELETE SET NULL,
    initiated_by    INT REFERENCES users(id) ON DELETE SET NULL,
    started_at      TIMESTAMP NOT NULL DEFAULT NOW(),
    stopped_at      TIMESTAMP,
    status          VARCHAR(20) NOT NULL DEFAULT 'PENDING', -- PENDING/RUNNING/COMPLETED/FAILED/CANCELLED
    volume_liters   NUMERIC(10,2)
);

-- Przykładowe dane

INSERT INTO users (email, password_hash, display_name, role)
VALUES
    ('owner@example.com', '$2y$10$abcdefghijklmnopqrstuvABCDEFGHijklmnOPQRSTUVwx', 'Owner User', 'OWNER'),
    ('worker@example.com', '$2y$10$abcdefghijklmnopqrstuvABCDEFGHijklmnOPQRSTUVwx', 'Worker User', 'WORKER'),
    ('admin@example.com',  '$2y$10$abcdefghijklmnopqrstuvABCDEFGHijklmnOPQRSTUVwx', 'Admin User',  'ADMIN');

-- UWAGA: powyższe hasła to placeholder – nadpiszesz je realnym hash-em z password_hash().

INSERT INTO regions (owner_id, name, description)
VALUES
    (1, 'Szklarnia', 'Główna szklarnia z pomidorami'),
    (1, 'Trawnik', 'Trawnik przed domem');

INSERT INTO sensors (region_id, name, type)
VALUES
    (1, 'Czujnik wilgotności gleby 1', 'SOIL_MOISTURE'),
    (1, 'Czujnik temperatury powietrza', 'AIR_TEMPERATURE'),
    (2, 'Czujnik nasłonecznienia', 'LIGHT');

INSERT INTO cameras (region_id, name, stream_url)
VALUES
    (1, 'Kamera szklarnia 1', 'rtsp://example.com/greenhouse1'),
    (2, 'Kamera trawnik 1', 'rtsp://example.com/lawn1');

INSERT INTO schedules (region_id, name, cron_expression, volume_liters)
VALUES
    (1, 'Poranne podlewanie szklarni', '0 6 * * *', 10.0),
    (2, 'Wieczorne podlewanie trawnika', '0 20 * * *', 15.0);
