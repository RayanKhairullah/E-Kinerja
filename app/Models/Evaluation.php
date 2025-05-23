<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluated_by_id',
        'evaluated_user_id',
        'evaluation_category_id',
        'score',
        'notes',
        'status',
    ];

    // Relasi ke User yang melakukan evaluasi (Penilai)
    public function evaluatedBy()
    {
        return $this->belongsTo(User::class, 'evaluated_by_id');
    }

    // Relasi ke User yang dievaluasi (Guru)
    public function evaluatedUser()
    {
        return $this->belongsTo(User::class, 'evaluated_user_id');
    }

    // Relasi ke EvaluationCategory
    public function category()
    {
        return $this->belongsTo(EvaluationCategory::class, 'evaluation_category_id');
    }

    // Relasi ke Feedback
    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }

    // Relasi ke EvaluationValidation
    public function validation()
    {
        return $this->hasOne(EvaluationValidation::class);
    }
}