<?php

namespace App\HR;

use App\User;
use Illuminate\Database\Eloquent\Model;

class NodAttachments extends Model
{
    protected $table = 'nod_attachments';

    protected $fillable = ['status'];
    
    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function acknowledge() {
        return $this->belongsTo(User::class, 'acknowledge_by');
    }
}
