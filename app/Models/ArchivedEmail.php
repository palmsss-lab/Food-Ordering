<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivedEmail extends Model
{
    protected $table = 'archived_emails';

    protected $fillable = [
        'user_id',
        'original_email',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
