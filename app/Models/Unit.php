<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    public function images() {
        return $this->HasMany(UnitImage::class);
    }
}
