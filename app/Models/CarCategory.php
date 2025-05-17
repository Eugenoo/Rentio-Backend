<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarCategory extends Model
{
    protected $fillable = [
        'id',
        'name',
        'description',
        'photo'
    ];


    /** Crud */

    public function createPost($data)
    {
//        $carCategory = new self($data);
//        $carCategory->save();
//        return $carCategory;

    }
    public static function make($data)
    {
        $carCategory = new self($data);
        $carCategory->save();
        return $carCategory;
    }

    public function edit($data)
    {
        $this->update($data);
        return $data;
    }

    public function remove()
    {
        $this->delete();
        return $this;
    }
}
