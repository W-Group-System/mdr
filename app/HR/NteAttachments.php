<?php

namespace App\HR;

use App\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class NteAttachments extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'nte_attachments';

    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
