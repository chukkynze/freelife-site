<?php
 /**
  * Class Member
  *
  * filename:   Member.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/8/14 5:04 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
 

class Member extends Eloquent
             implements UserInterface, RemindableInterface
{
	use UserTrait, RemindableTrait;

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table        =   'members';
    protected $primaryKey   =   'id';
    protected $connection   =   'main_db';
    protected $fillable     =   array
                                (
                                    'member_type',
                                    'login_credentials',
                                    'salt1',
                                    'salt2',
                                    'salt3',
                                    'paused',
                                    'cancelled',
                                    'remember_token'
                                );
    protected $guarded      =   array
                                (
                                    'id',
                                );

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden       =   array
                                (
                                    'login_credentials',
                                    'salt1',
                                    'salt2',
                                    'salt3',
                                );

    public function addMember($LoginCredentials)
    {
        $NewMember  =   Member::create
                        (
                            array
                            (
                                'member_type'       =>  'unknown',
                                'login_credentials' =>  $LoginCredentials[0],
                                'salt1'             =>  $LoginCredentials[1],
                                'salt2'             =>  $LoginCredentials[2],
                                'salt3'             =>  $LoginCredentials[3],
                                'paused'            =>  0,
                                'cancelled'         =>  0,
                                'remember_token'    =>  '',
                            )
                        );
        $NewMember->save();
    }
}