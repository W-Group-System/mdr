<?php

namespace App\Admin;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Approve extends Model
{
    protected $table = 'manage_approvers';

    protected $fillable = ['user_id', 'status_level'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
