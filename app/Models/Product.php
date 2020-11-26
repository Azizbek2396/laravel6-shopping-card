<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function getCategory()
    {
        return  Category::find($this->category_id);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getPriceForCount() {
        if (!is_null($this->pivot->count)){
            return $this->pivot->count * $this->price;
        } else {
            return $this->price;
        }
    }
}
