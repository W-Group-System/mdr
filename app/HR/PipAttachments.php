<?php

namespace App\HR;

use App\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PipAttachments extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function acknowledge() {
        return $this->belongsTo(User::class, 'acknowledge_by');
    }
}
