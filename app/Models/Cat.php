<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
    use HasFactory;

    public function blogs()
    {
        // withTimesamp로 데이터 업데이트한 시간 추가
        return $this->belongsToMany(Blog::class)->withTimestamps();
    }
}
