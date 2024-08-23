<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class Demand extends Model
{
    use HasFactory;

    const CANCELLED = -1;
    const CREATED = 0;
    const VALIDATED = 1;
    const COMPLETED = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'service',
        'event_type',
        'event_location',
        'reception_start_time',
        'reception_period',
        'number_people',
        'comment',
        'event_date',
        'demand_date',
        'commentaire',
        'hall_id',
        'event_type_id',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        "days_left"
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'customer_id' => 'int',
            'user_id' => 'int',
            'event_type_id' => 'int',
            'status' => 'int',
            'number_people' => 'int',
            'event_date' => 'date',
            'demand_date' => 'date',
        ];
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function hall() {
        return $this->belongsTo(Hall::class);
    }
    public function eventtype() {
        return $this->belongsTo(EventType::class, 'event_type_id','id');
    }
    public function prestation() {
        return $this->hasOne(Prestation::class);
    }
    public function getDaysLeftAttribute()
    {

        return (int)Carbon::now()->diffInDays(Carbon::parse($this->event_date));
    }
}
