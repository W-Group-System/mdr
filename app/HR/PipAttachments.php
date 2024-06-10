<?php

namespace App\HR;

use App\User;
use Illuminate\Database\Eloquent\Model;

class PipAttachments extends Model
{
    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function acknowledge() {
        return $this->belongsTo(User::class, 'acknowledge_by');
    }
}
