<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface Create
{
    public static function execute(array $data): Model|false;
}
