<?php

namespace App\Approver;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PmoAttachment extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'pmo_attachments';

    protected $primaryKey = 'id';

}
