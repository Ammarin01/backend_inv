<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'slug',
        'description',
        'price',
        'image',
        'user_id',
    ];
    
    //Relationship to User
    public function users(){

        //join user
        return $this->belongsTo('App\Models\User','user_id')->select(['id','fullname','avatar']);
    }
}
