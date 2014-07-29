<?php
 /**
  * Class MemberEmails
  *
  * filename:   MemberEmails.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/8/14 5:11 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class MemberEmails extends Eloquent
{
    protected $table        =   'member_emails';
    protected $primaryKey   =   'id';
    protected $connection   =   'main_db';
    protected $fillable     =   array
                                (
                                    'member_id',
                                    'email_address',
                                    'verification_sent',
                                    'verification_sent_on',
                                    'verified',
                                    'verified_on',
                                );
    protected $guarded      =   array
                                (
                                    'id',
                                );


}