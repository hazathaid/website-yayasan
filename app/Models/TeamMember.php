<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasLocalizedAttributes;

    protected $fillable = ['name', 'position', 'position_en', 'photo_path', 'sort_order'];
}
