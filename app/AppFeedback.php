<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppFeedback extends Model
{

	protected $table = "app_feedbacks";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'rating', 'text',
    ];
}
