<?php

namespace App\Models;

use AngryMoustache\Media\Models\Attachment;
use AngryMoustache\Translatable\Models\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use Translatable;

    protected $fillable = [
        'working_title',
        'attachment_id',
    ];

    public $translatedAttributes = [
        'name',
        'slug',
        'body',
        'online',
    ];

    public $casts = [
        'online' => 'boolean',
    ];

    public function attachment()
    {
        return $this->belongsTo(Attachment::class);
    }
}
