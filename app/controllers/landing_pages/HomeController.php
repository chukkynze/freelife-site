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
        // Find/Create a SiteUser uid from cookie
        // Register a SiteHit
		return View::make('landing/home');
	}



}