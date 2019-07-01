<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class State extends Model{
	public static function getstates(){
		$value = DB::table('states')->where('country_id', '=', '231')->orderBy->('name', 'asc')->get();
	}
}
