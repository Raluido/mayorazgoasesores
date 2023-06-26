<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\File;

class Payroll extends Model
{
    use HasFactory;

    protected $table = 'payrolls';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
