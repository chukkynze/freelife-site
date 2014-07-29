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
                                    'member_id',
                                    'agent',
                                    'ip_address',
                                    'user_status',
                                );
    protected $guarded      =   array
                                (
                                    'id',
                                );


    public function getId()
    {
        return $this->id;
    }


    public function getMemberId()
    {
        return $this->member_id;
    }

    public function lockUserStatus($lockStatus)
    {
        $SiteUser   =   SiteUser::where("id","=", $this->getId())->first();
        $newData    =   array
                        (
                            'user_status'   =>  $lockStatus,
                        );
        $SiteUser->fill($newData);
        $SiteUser->save();
    }

    public function lockMemberStatus($lockStatus, $memberID)
    {
        if($memberID > 0)
        {
            $SiteUserMember     =   MemberStatus::where("member_id","=", $memberID)->first();
            $newData            =   array
                                    (
                                        'status'   =>  $lockStatus,
                                    );
            $SiteUserMember->fill($newData);
            $SiteUserMember->save();
        }
    }
}