<?php
 /**
  * Class HomeController
  *
  * filename:   HomeController.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/8/14 5:24 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class HomeController extends AbstractLandingController
{
    public $var;

	public function showHome()
	{
        $this->getSiteUser();   // Find/Create a SiteUser uid from cookie
        $this->setSiteHit();    // Register a SiteHit

        $viewData   =   array
                        (
                            'activity'  =>  'login',
                        );

		return is_int($this->SiteUserCookie) && $this->SiteUserCookie > 0
            ?   Response::make(View::make('landing/home', $viewData))
            :   Response::make(View::make('landing/home', $viewData))->withCookie($this->SiteUserCookie);
	}



}