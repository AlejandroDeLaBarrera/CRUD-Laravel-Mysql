<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model {

    protected $fillable = [
        'name',
        'surname',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function hobbies() {
        return $this->belongsToMany(Hobbie::class, 'customers_hobbies');
    }
}

