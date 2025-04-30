<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PAGDocument extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'pag_id',
        'file_path',
        'file_name',
        'file_type',
    ];

    /**
     * Get the PAG that owns the document.
     */
    public function pag()
    {
        return $this->belongsTo(PAG::class);
    }
}
