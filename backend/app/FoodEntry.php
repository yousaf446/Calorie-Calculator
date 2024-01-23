<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class FoodEntry extends Model
{
    use Notifiable;
    use \App\Models\Concerns\UseUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'food_entry';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'product_name', 'consumed_at', 'calorie_value', 'price'
    ];

    /**
     * Get user.
     */
    public function user() {
        return $this->belongsTo('App\User');
    }

}
