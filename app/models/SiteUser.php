<?php
 /**
  * Class SiteUser
  *
  * filename:   SiteUser.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/8/14 5:11 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class SiteUser extends Eloquent
{
    protected $table        =   'site_users';
    protected $primaryKey   =   'id';
    protected $connection   =   'main_db';
    protected $fillable     =   array
                                (
                                    'user_type',
                                    'member_id',
                                    'agent',
                                    'ip_address',
                                    'user_status',
                                );
    protected $guarded      =   array
                                (
                                    'id',
                                    'hash',
                                );



}