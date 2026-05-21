<?php

/**
 * Database Configuration
 * Values are read from environment variables so the same image works
 * in local Docker, Render.com, or any other host.
 *
 * Set these in the Render dashboard under Environment → Environment Variables,
 * or in a local .env / docker-compose.yml for development.
 */
define('DB_HOST',   getenv('DB_HOST')   ?: 'db.syiqftmpmmpvjbpmqspp.supabase.co');
define('DB_PORT',   getenv('DB_PORT')   ?: '5432');
define('DB_NAME',   getenv('DB_NAME')   ?: 'postgres');
define('DB_USER',   getenv('DB_USER')   ?: 'postgres');
define('DB_PASS',   getenv('DB_PASS')   ?: 'Alexander12.Dalmatax1011');
define('DB_SCHEMA', getenv('DB_SCHEMA') ?: 'public');
