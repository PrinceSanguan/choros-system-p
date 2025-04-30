<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurrenderedDocument extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'surrendered_documents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'surrendered_id',
        'document_type',
        'file_path',
        'filename',
        'original_filename',
        'mime_type',
        'file_size',
        'file_name',
        'file_type',
    ];

    /**
     * Get the surrendered individual that owns the document.
     */
    public function surrendered()
    {
        return $this->belongsTo(Surrendered::class);
    }
}
