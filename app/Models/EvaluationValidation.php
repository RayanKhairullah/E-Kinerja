<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationValidation extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_id',
        'validator_id',
        'status',
        'notes',
    ];

    // Relasi ke Evaluation
    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    // Relasi ke User (Validator)
    public function validator()
    {
        return $this->belongsTo(User::class, 'validator_id');
    }
}