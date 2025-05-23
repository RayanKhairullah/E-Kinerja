<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_id',
        'given_by_id',
        'content',
    ];

    // Relasi ke Evaluation
    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    // Relasi ke User yang memberikan feedback
    public function givenBy()
    {
        return $this->belongsTo(User::class, 'given_by_id');
    }
}