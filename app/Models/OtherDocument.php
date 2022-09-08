<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;


class OtherDocument extends Model
{
    use HasFactory;

    protected $table = 'others_documents';

    public static function boot()
    {
        parent::boot();

        OtherDocument::deleted(function ($otherDocument) {
            $file = $otherDocument->filename;
            if (File::isFile($file)) {
                File::delete($file);
            }
        });
    }
}
