<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CTGDocument extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'ctg_id',
        'file_path',
        'file_name',
        'file_type',
    ];

    /**
     * Get the CTG that owns the document.
     */
    public function ctg()
    {
        return $this->belongsTo(CTG::class);
    }
}
