<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surrendered extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'surrendered';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'alias',
        'gender',
        'region',
        'province',
        'municipality',
        'barangay',
        'date_of_birth',
        'former_group',
        'date_surrendered',
        'status',
        'remarks',
        'photo_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'date_surrendered' => 'datetime',
    ];

    /**
     * Get the documents for the surrendered individual.
     */
    public function documents()
    {
        return $this->hasMany(SurrenderedDocument::class);
    }
}
