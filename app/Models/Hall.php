<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class Hall extends Model
{
    use HasFactory;

    const CANCELLED = -1;
    const CREATED = 0;
    const VALIDATED = 1;
    const PROCESSING = 2;
    const CLOSED = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        "name",
        "address",
        "manager_name",
        "manager_phone",
        "manager_email",
        "packing",
        "kitchen",
        "cold_room",
        "ladder",
        "transportation_fee",
        "table",
        "bin",
        "comment",
        "user_id",
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_id' => 'int',
            "packing" => 'int',
            "kitchen"  => 'int',
            "cold_room" => 'int',
            "ladder" => 'int',
            "transportation_fee" => 'int',
            "table" => 'int',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prestations()
    {
        return $this->hasMany(Prestation::class);
    }
}
