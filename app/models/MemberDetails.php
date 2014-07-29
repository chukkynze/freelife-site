<?php
 /**
  * Class MemberDetails
  *
  * filename:   MemberDetails.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/28/14 8:20 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class MemberDetails extends Eloquent
{
    protected $table        =   'member_details';
    protected $primaryKey   =   'id';
    protected $connection   =   'main_db';
    protected $fillable     =   array
                                (
                                    'member_id',
                                    'prefix',
                                    'first_name',
                                    'mid_name1',
                                    'mid_name2',
                                    'last_name',
                                    'display_name',
                                    'suffix',
                                    'gender',
                                    'birth_date',
                                    'zipcode',
                                    'personal_summary',
                                    'profile_pic_url',
                                    'personal_website_url',
                                    'linkedin_url',
                                    'google_plus_url',
                                    'twitter_url',
                                    'facebook_url',
                                );
    protected $guarded      =   array
                                (
                                    'id',
                                );


}