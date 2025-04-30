<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PAG extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'region',
        'address',
        'pob',
        'dob',
        'affiliation',
        'last_seen',
        'status',
        'photo_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dob' => 'date',
        'last_seen' => 'datetime',
    ];

    /**
     * Get the documents for the PAG.
     */
    public function documents()
    {
        return $this->hasMany(PAGDocument::class);
    }
}
