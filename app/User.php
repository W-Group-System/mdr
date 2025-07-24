<?php

namespace App;

use App\Admin\DepartmentApprovers;
use App\Admin\Company;
use App\Admin\Department;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use Notifiable;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'name', 'email', 'password', 'account_role', 'account_status', 'department_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function approver() 
    {
        return $this->hasOne(DepartmentApprovers::class, 'approver_id');
    }
    public function department() 
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function company() 
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function audit()
    {
        return $this->hasMany(Audit::class);
    }
    public function access_module()
    {
        return $this->hasMany(UserAccessModule::class);
    }
}
