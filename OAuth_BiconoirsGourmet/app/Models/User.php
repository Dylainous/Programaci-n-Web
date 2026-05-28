<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    // ─── NOTA: password_hash eliminado. Google OAuth maneja la autenticación.
    // Solo almacenamos datos personales del usuario.
    protected $fillable = [
        'user_id',   // = Google "sub" (ID único e inmutable del usuario en Google)
        'name',
        'email',
        'phone',
        'birthdate',
        'role'
    ];

    public static function getAll() {
        return self::all()->toArray();
    }

    /**
     * Busca un usuario por su Google ID (sub).
     * Si no existe, lo crea automáticamente (primer login = registro).
     * Devuelve el array del usuario listo para guardarse en sesión.
     */
    public static function findOrCreateFromGoogle(array $googleData): array {
        $user = self::find($googleData['sub']);

        if (!$user) {
            // Primera vez que este usuario inicia sesión → lo registramos
            $user = self::create([
                'user_id' => $googleData['sub'],
                'name'    => $googleData['name'],
                'email'   => $googleData['email'],
                'role'    => 'customer'
            ]);
        }

        return $user->toArray();
    }
}
