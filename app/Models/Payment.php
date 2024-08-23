<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'payment_method',
        'prestation_id',
        'prestation_id',
        'payment_method_id',
        'payment_date',
    ];

    protected $appends = ['payment_method_name'];



    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            "amount" => "double",
            "prestation_id" => "int",
            "payment_date" => "datetime"
        ];
    }

    public function prestation() {
        return $this->belongsTo(Prestation::class);
    }
    public function paymentmethod() {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id',"id");
    }

    public function getPaymentMethodNameAttribute()
    {
        try {
            return $this->paymentmethod->name ?? $this->payment_method;
        } catch (\Exception $e) {
            return $this->payment_method;
        }
    }
}
