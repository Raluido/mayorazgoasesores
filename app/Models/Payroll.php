<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Payroll extends Model
{
    use HasFactory;

    protected $table = 'payrolls';

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($payroll) {
            $file = $payroll->filename;
            if (File::isFile($file)) {
                File::delete($file);
            }
        });
    }
}
