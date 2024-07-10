<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface Executable
{
    public static function execute(array $data, Model|null $model = null): Model|false;
}