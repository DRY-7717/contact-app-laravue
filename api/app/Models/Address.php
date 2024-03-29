<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory, HasUlids;
    protected $guarded = ['id'];


    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id','id');
    }
}
