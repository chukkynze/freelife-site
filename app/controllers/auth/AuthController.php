<?php
 /**
  * Class AuthController
  *
  * filename:   AuthController.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/9/14 8:58 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */

class AuthController extends BaseController
{
    private $activity;
    private $reason;


    public function showAccess()
	{
        $activity   =   ( isset($this->activity)    ?   $this->activity :   'login');
        $reason     =   ( isset($this->reason)      ?   $this->reason   :   '');

        $this->getSiteUser();   // Find/Create a SiteUser uid from cookie
        $this->setSiteHit();    // Register a SiteHit

        // Auth Forms
        #$LoginForm          =   new LoginForm();
        #$LoginCaptchaForm   =   new LoginCaptchaForm();
        #$SignupForm         =   new SignupForm();
        #$ForgotForm         =   new ForgotForm();

        #$reCaptcha          =   $this->getServiceLocator()->get('ReCaptchaService');
        #$reCaptchaError     =   FALSE;

        #$myAuthSession      =   new Container('NotaryToolzAuthSession');

        #$AttemptedLogins           =   $this->getAccessAttemptTable()->getAccessAttemptByUserIDs('LoginForm',          array($this->getUser()->id), self::POLICY_AllowedAttemptsLookBackDuration);
        #$AttemptedLoginCaptchas    =   $this->getAccessAttemptTable()->getAccessAttemptByUserIDs('LoginCaptchaForm',   array($this->getUser()->id), self::POLICY_AllowedAttemptsLookBackDuration);
        #$AttemptedSignups          =   $this->getAccessAttemptTable()->getAccessAttemptByUserIDs('SignupForm',         array($this->getUser()->id), self::POLICY_AllowedAttemptsLookBackDuration);
        #$AttemptedForgots          =   $this->getAccessAttemptTable()->getAccessAttemptByUserIDs('ForgotForm',         array($this->getUser()->id), self::POLICY_AllowedAttemptsLookBackDuration);

		$LoginFormMessages          =   '';
        $LoginCaptchaFormMessages   =   '';
        $SignupFormMessages         =   '';
        $ForgotFormMessages         =   '';

        $LoginAttemptMessage        =   '';
        $LoginCaptchaAttemptMessage =   '';
        $SignupAttemptMessage       =   '';
        $ForgotAttemptMessage       =   '';

        if($activity == 'login')
		{
			switch($reason)
			{
				case 'expired-session' 		:	$LoginHeaderMessage 	=	1; break;
				case 'intentional-logout' 	:	$LoginHeaderMessage 	=	2; break;
				case 'changed-password' 	:	$LoginHeaderMessage 	=	3; break;

				default : $LoginHeaderMessage 	=	0;
			}
		}
		else
		{
			$LoginHeaderMessage     =   '';
		}

        $LoginCaptchaHeaderMessage 	=   '';
        $SignupHeaderMessage       	=   '';
        $ForgotHeaderMessage       	=   '';

        $viewData   =   array
                        (
                            'activity'                  =>  (isset($activeForm) ? $activeForm : $activity),

                            #'LoginForm'                 =>  $LoginForm,
                            'LoginFormMessages'         =>  $LoginFormMessages,
                            'LoginAttemptMessage'       =>  $LoginAttemptMessage,

                            #'LoginCaptchaForm'          =>  $LoginCaptchaForm,
                            'LoginCaptchaFormMessages'  =>  $LoginCaptchaFormMessages,
                            'LoginCaptchaAttemptMessage'=>  $LoginCaptchaAttemptMessage,

                            'reCaptcha'                 =>  (isset($reCaptcha)      ? $reCaptcha      : NULL),
                            'reCaptchaError'            =>  (isset($reCaptchaError) ? $reCaptchaError : NULL),
                            'PauseGifDisplaySeconds'    =>  0,

                            #'SignupForm'                =>  $SignupForm,
                            'SignupFormMessages'        =>  $SignupFormMessages,
                            #'ForgotForm'                =>  $ForgotForm,
                            'ForgotFormMessages'        =>  $ForgotFormMessages,

                            'LoginHeaderMessage'        =>  $LoginHeaderMessage
                        );

		return is_int($this->SiteUserCookie) && $this->SiteUserCookie > 0
            ?   Response::make(View::make('auth/login', $viewData))
            :   Response::make(View::make('auth/login', $viewData))->withCookie($this->SiteUserCookie);
	}

    public function loginAgain()
    {
        $this->activity     =   "login";
        $this->reason       =   "expired-session";
        return $this->showAccess();
    }

}