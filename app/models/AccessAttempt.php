<?php
 /**
  * Class AccessAttempt
  *
  * filename:   AccessAttempt.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/22/14 8:46 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class AccessAttempt extends Eloquent
{
    protected $table        =   'access_attempts';
    protected $primaryKey   =   'id';
    protected $connection   =   'utils_db';
    protected $fillable     =   array
                                (
                                    'user_id',
                                    'attempt_type',
                                    'success',
                                    'attempted_at',
                                );
    protected $guarded      =   array
                                (
                                    'id',
                                );



}