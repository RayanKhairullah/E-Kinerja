<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // Tambahkan ini untuk Filament Shield

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // Tambahkan HasRoles

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi untuk evaluasi yang dibuat oleh user ini (sebagai Penilai)
    public function evaluationsCreated()
    {
        return $this->hasMany(Evaluation::class, 'evaluated_by_id');
    }

    // Relasi untuk evaluasi di mana user ini adalah guru yang dievaluasi
    public function evaluationsReceived()
    {
        return $this->hasMany(Evaluation::class, 'evaluated_user_id');
    }

    // Relasi untuk validasi yang dilakukan oleh user ini (sebagai Validator)
    public function validations()
    {
        return $this->hasMany(EvaluationValidation::class, 'validator_id');
    }

    // Relasi untuk feedback yang diberikan oleh user ini
    public function feedbackGiven()
    {
        return $this->hasMany(Feedback::class, 'given_by_id');
    }
}