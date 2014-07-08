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
	protected $table = 'members';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden   =   array
                            (
                                'login_credentials',
                                'salt1',
                                'salt2',
                                'salt3',
                                'remember_token'
                            );

}