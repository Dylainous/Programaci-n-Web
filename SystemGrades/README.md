# School Grades System – PHP Monolithic MVC

A simple CRUD system for managing student grades, built with pure PHP (no framework), PostgreSQL via Supabase, and a hand-rolled ORM base class.

---

## Project Structure

```
grades-system/
├── index.php                        ← Front controller (entry point)
├── schema.sql                       ← Run this in Supabase SQL Editor
├── config/
│   └── database.php                 ← DB credentials (edit this)
├── core/
│   ├── Database.php                 ← PDO singleton (Supabase/PostgreSQL)
│   ├── Model.php                    ← Base ORM (create/find/update/delete/all)
│   └── Controller.php               ← Base controller (view/redirect/post/get)
└── app/
    ├── controllers/
    │   └── StudentController.php    ← All student actions
    ├── models/
    │   └── StudentModel.php         ← Student-specific queries
    └── views/
        ├── layouts/
        │   └── main.php             ← HTML shell / nav / flash messages
        └── students/
            ├── list.php             ← Grades table + course average
            └── form.php             ← Add / Edit student form
```

---

## Setup Instructions

### 1. Create the Supabase database

1. Go to [supabase.com](https://supabase.com) and open your project.
2. In the left sidebar, click **SQL Editor**.
3. Paste the contents of `schema.sql` and click **Run**.

### 2. Get your database credentials

In your Supabase project go to **Settings → Database**:

| Setting | Where to find it |
|---|---|
| Host | `DB host` (e.g. `db.abcxyz.supabase.co`) |
| Port | `5432` |
| Database | `postgres` |
| User | `postgres` |
| Password | Your project's database password |

### 3. Configure the connection

Edit `config/database.php`:

```php
define('DB_HOST', 'db.your-project-ref.supabase.co');
define('DB_PORT', '5432');
define('DB_NAME', 'postgres');
define('DB_USER', 'postgres');
define('DB_PASS', 'your-database-password');
```

### 4. Make sure PHP has the pgsql extension

```bash
# Ubuntu / Debian
sudo apt install php-pgsql

# macOS (Homebrew)
brew install php
# Then enable extension=pdo_pgsql in php.ini
```

### 5. Run the application

```bash
cd grades-system
php -S localhost:8000
```

Open your browser at: [http://localhost:8000](http://localhost:8000)

---

## URL Routes

| URL | Method | Action |
|---|---|---|
| `index.php` or `index.php?action=list` | GET | Show grades table |
| `index.php?action=create` | GET | Show add-student form |
| `index.php?action=store` | POST | Save new student |
| `index.php?action=edit&id=X` | GET | Show edit form for student X |
| `index.php?action=update&id=X` | POST | Update student X |
| `index.php?action=delete&id=X` | POST | Delete student X |

---

## Features

- **List page** – table of all students with their 3 grades, individual averages, and the overall course average.
- **Add/Edit form** – collects name, ID number, email, favourite sport (radio buttons), favourite subject (dropdown), date of birth, and 3 grades (0–10). Average is calculated live in the browser and stored in the DB.
- **Delete** – confirmation prompt before removing a record.
- **Validation** – server-side validation for all required fields and grade ranges.
- **ORM base class** – `Model.php` provides `all()`, `find()`, `create()`, `update()`, `delete()`, `rawQuery()`, and `rawExecute()` using PDO prepared statements.
- **MVC** – strict separation between Controller, Model, and View layers.
