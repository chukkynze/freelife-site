<?php
 /**
  * Class AbstractLandingController
  *
  * filename:   AbstractLandingController.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/8/14 5:19 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class AbstractLandingController extends BaseController
{
    const POLICY_UserIDCookieDuration    =   365;

    public $SiteUser;
    public $SiteUserCookie;
    public $SiteHit;


    /**
     * @param mixed $SiteHit
     */
    public function setSiteHit($SiteHit)
    {
        $this->SiteHit = $SiteHit;
    }

    /**
     * @return mixed
     */
    public function getSiteHit()
    {
        return $this->SiteHit;
    }



    /**
     * @return bool|\Illuminate\Database\Eloquent\Model|static
     */
    public function setSiteUser()
    {
        $newSiteUser    =   SiteUser::create(
            array
            (
                'member_id'     =>  0,
                'agent'         =>  $_SERVER['HTTP_USER_AGENT'],
                'ip_address'    =>  sprintf('%u', ip2long($_SERVER['REMOTE_ADDR'])),
                'user_status'   =>  'Open',
            ));
        $newSiteUser->save();

        $this->SiteUser = $newSiteUser;

        // Set user cookie
        $timeCookie             =   time() + (60*60*24*self::POLICY_UserIDCookieDuration);
        $cookieValue            =   urlencode($this->SiteUser->id);
        $this->SiteUserCookie   =   Cookie::make('ekinect_uid', $cookieValue, $timeCookie, '/', $_SERVER['SERVER_NAME'], 0, 0);

        return ( is_object($this->SiteUser) ? $this->SiteUser : FALSE );
    }

    /**
     * @return mixed
     */
    public function getSiteUser()
    {
        $siteUserID   =   (int) Cookie::get('ekinect_uid');
        if(isset($siteUserID) && $siteUserID > 0)
        {
            $siteUser   =   SiteUser::find($siteUserID);
            $this->SiteUserCookie = $siteUserID;

            if(FALSE != $siteUser && is_object($siteUser))
            {
                $this->SiteUser =   $siteUser;
                return $this->SiteUser;
            }
        }

        return $this->setSiteUser();
    }



    /**
     * @param \Illuminate\View\View $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getLayout()
    {
        return $this->layout;
    }




}