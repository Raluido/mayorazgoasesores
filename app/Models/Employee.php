<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleted(function ($employee) {
            log::info("softdeleted");
            $employee->payrolls()->delete();
        });
    }

    public function payrolls()
    {
        return $this->hasMany('App\Payroll');
    }
}
