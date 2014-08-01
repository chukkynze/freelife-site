<?php

class BaseController extends Controller
{
    const POLICY_CookiePrefix                   =   'ekinect_';
    const POLICY_UserIDCookieDurationMinutes    =   525600;

    public $SiteUser;
    public $SiteUserCookie;
    public $SiteHit;


    /**
     *
     */
    public function setSiteHit()
    {
        $allCookies   =   array();
        foreach($_COOKIE as $cKey => $cValue)
        {
            if(strpos($cKey,self::POLICY_CookiePrefix, 0) >= 0)
            {
                $allCookies[$cKey]    =   $cValue;
            }
        }

        $newSiteHit    =   SiteHit::create(
                                        array
                                        (
                                            'user_id'       =>  $this->getSiteUser()->getId(),
                                            'cookies'       =>  json_encode($allCookies),
                                            'url_location'  =>  Request::path(),
                                            'client_time'   =>  0,
                                            'server_time'   =>  strtotime('now'),
                                        ));
        $newSiteHit->save();

        $this->SiteHit = $newSiteHit;
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

        $this->SiteUser         =   $newSiteUser;
        $this->SiteUserCookie   =   Cookie::make('ekinect_uid', urlencode($this->SiteUser->getId()), self::POLICY_UserIDCookieDurationMinutes, '/', $_SERVER['SERVER_NAME'], 0, 0);

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
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

    protected function createHash($val,$key)
    {
        $hash   =   hash_hmac('sha512', $val, $key);
        return $hash;
    }

}
