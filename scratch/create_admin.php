<?php

use App\Models\User;

$user = User::updateOrCreate(
    ['email' => 'admin@etuderapide.com'],
    [
        'name' => 'Admin Makis',
        'password' => hash_password('Mkd_2026_@vPz9!TrXq'),
        'is_admin' => true,
    ]
);

echo 'Admin configurado com sucesso: '.$user->email."\n";
