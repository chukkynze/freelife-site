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


    public function addMemberEmail($memberEmail, $memberID)
    {
        if($memberID > 0)
        {
            $newMemberEmail =   MemberEmails::create
                                (
                                    array
                                    (
                                        'member_id'             =>  $memberID,
                                        'email_address'         =>  $memberEmail,
                                        'verification_sent'     =>  0,
                                        'verification_sent_on'  =>  0,
                                        'verified'              =>  0,
                                        'verified_on'           =>  0,
                                    )
                                );
            $newMemberEmail->save();
            return $newMemberEmail->id;
        }
        else
        {
            throw new \Whoops\Example\Exception("Member ID is invalid.");
        }
    }

    public function updateMemberEmail($memberEmailsID, $fillableArray)
    {
        if($memberEmailsID > 0)
        {
            try
            {
                $MemberEmail =   MemberEmails::where("id","=", $memberEmailsID)->first();
                $MemberEmail->fill($fillableArray);
                $MemberEmail->save();
                return TRUE;
            }
            catch(\Whoops\Example\Exception $e)
            {
                throw new \Whoops\Example\Exception($e);
            }
        }
        else
        {
            throw new \Whoops\Example\Exception("MemberEmails ID is invalid.");
        }
    }
}