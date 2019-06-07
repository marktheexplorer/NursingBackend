<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'content',
    ];
}
