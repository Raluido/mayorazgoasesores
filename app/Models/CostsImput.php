<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;


class CostsImput extends Model
{
    use HasFactory;

    protected $table = 'costs_imputs';

}
