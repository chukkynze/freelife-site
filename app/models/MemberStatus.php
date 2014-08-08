<?php
 /**
  * Class MemberStatus
  *
  * filename:   MemberStatus.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/8/14 5:11 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class MemberStatus extends Eloquent
{
    protected $table        =   'member_status';
    protected $primaryKey   =   'id';
    protected $connection   =   'main_db';
    protected $fillable     =   array
                                (
                                    'member_id',
                                    'status',
                                );
    protected $guarded      =   array
                                (
                                    'id',
                                );




    public function addMemberStatus($newStatus, $memberID)
    {
        if($memberID > 0)
        {
            $newMemberStatus    =   MemberStatus::create
                                    (
                                        array
                                        (
                                            'member_id' =>  $memberID,
                                            'status'    =>  $newStatus,
                                        )
                                    );
            $newMemberStatus->save();
            return TRUE;
        }
    }

    public function updateMemberStatus($memberID, $fillableArray)
    {
        if($memberID > 0)
        {
            try
            {
                $MemberStatus =   MemberStatus::where("member_id","=", $memberID)->first();
                $MemberStatus->fill($fillableArray);
                $MemberStatus->save();
                return TRUE;
            }
            catch(\Whoops\Example\Exception $e)
            {
                throw new \Whoops\Example\Exception($e);
            }
        }
        else
        {
            throw new \Whoops\Example\Exception("MemberStatus ID is invalid.");
        }
    }
}