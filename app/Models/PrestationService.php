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

class PrestationService extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        /*'id',*/
        'name',
        'quantity',
        'total',
        'price',
        'prestation_id',
        'service_id',
    ];

    protected $appends = ["service_name"];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            "amount" => "double",
            "prestation_id" => "int"
        ];
    }

    public function prestation() {
        return $this->belongsTo(Prestation::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class, "service_id",'id');
    }
    public function getServiceNameAttribute()
    {
        try {
            return $this->service->name ?? $this->name;
        } catch (\Exception $e) {
            return $this->name;
        }
    }
}
