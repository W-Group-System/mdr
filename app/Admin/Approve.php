<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;

class Approve extends Model
{
    protected $table = 'manage_approvers';

    protected $fillable = ['approver_id', 'no_approver'];
}
