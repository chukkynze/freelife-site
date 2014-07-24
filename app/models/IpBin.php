<?php
 /**
  * Class IpBin
  *
  * filename:   IpBin.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/22/14 10:10 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class IpBin extends Eloquent
{
    protected $table        =   'ip_bin';
    protected $primaryKey   =   'id';
    protected $connection   =   'utils_db';
    protected $fillable     =   array
                                (
                                    'user_id',
                                    'member_id',
                                    'ip_address',
                                    'ip_status',
                                );
    protected $guarded      =   array
                                (
                                    'id',
                                );



}