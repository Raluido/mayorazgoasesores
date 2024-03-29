<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\File;


class OtherDocument extends Model
{
    use HasFactory;

    protected $table = 'others_documents';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
