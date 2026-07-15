<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'description',
        'photo'
    ];

    /** Crud */

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
}
