<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'WowoClean API',
    description: 'API Dokumentasi untuk Sistem Manajemen Limbah B3 WowoClean. Sistem ini menyediakan fitur CRUD kontainer limbah, tracking log perjalanan, autentikasi JWT, dan role-based authorization.',
    contact: new OA\Contact(name: 'WowoClean Team', email: 'admin@wowoclean.com')
)]
#[OA\Server(
    url: 'http://127.0.0.1:8000',
    description: 'Local Development Server'
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'Masukkan token JWT yang didapat dari endpoint login. Format: Bearer {token}'
)]
abstract class Controller
{
    //
}