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
    public $SiteUser;
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
     * @param mixed $SiteUser
     */
    public function setSiteUser($SiteUser)
    {
        $this->SiteUser = $SiteUser;
    }

    /**
     * @return mixed
     */
    public function getSiteUser()
    {
        return $this->SiteUser;
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