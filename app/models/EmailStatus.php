<?php
 /**
  * Class EmailStatus
  *
  * filename:   EmailStatus.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/31/14 8:46 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class EmailStatus extends Eloquent
{
    protected $table        =   'email_status';
    protected $primaryKey   =   'id';
    protected $connection   =   'main_db';
    protected $fillable     =   array
                                (
                                    'email_address',
                                    'email_address_status',
                                );
    protected $guarded      =   array
                                (
                                    'id',
                                );


    public function addEmailStatus($emailAddress, $status)
    {
        $newEmailStatus =   EmailStatus::create
                            (
                                array
                                (
                                    'email_address'         =>  $emailAddress,
                                    'email_address_status'  =>  $status,
                                )
                            );
        $newEmailStatus->save();
    }
}