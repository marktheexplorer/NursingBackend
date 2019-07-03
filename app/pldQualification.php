<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

<<<<<<< HEAD
class Qualification extends Model{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'qualifications';
	//protected $primaryKey = 'qualifications_id';

    protected $fillable = [
        'title', 'description'
    ];
}
=======
class Qualification extends Model
{
    protected $fillable = [
        'name', 'created_at', 'updated_at'
    ];
}
>>>>>>> 82702cf8df23738becbdcb269d02afef18499e15
