<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class UserAccessModule extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    // list the fillable property
    protected $fillable = [
        'user_id',
        'module_id',
        'submodule_id',
        'read',
        'create',
        'update',
        'delete',
    ];
}
