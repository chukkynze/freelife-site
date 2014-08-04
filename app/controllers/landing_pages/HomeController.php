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

    public function __construct()
    {
        $this->getSiteUser();   // Find/Create a SiteUser uid from cookie
        $this->setSiteHit();    // Register a SiteHit
    }

	public function showHome()
	{

        $viewData   =   array
                        (
                            'activity'  =>  'login',
                        );

		return  is_int($this->SiteUserCookie) && $this->SiteUserCookie > 0
                    ?   Response::make(View::make('landing/home', $viewData))
                    :   Response::make(View::make('landing/home', $viewData))->withCookie($this->SiteUserCookie);
	}

	public function showTerms()
	{

        $viewData   =   array
                        (
                            'activity'  =>  'login',
                        );

		return  is_int($this->SiteUserCookie) && $this->SiteUserCookie > 0
                    ?   Response::make(View::make('landing/terms', $viewData))
                    :   Response::make(View::make('landing/terms', $viewData))->withCookie($this->SiteUserCookie);
	}

	public function showPrivacy()
	{

        $viewData   =   array
                        (
                            'activity'  =>  'login',
                        );

		return  is_int($this->SiteUserCookie) && $this->SiteUserCookie > 0
                    ?   Response::make(View::make('landing/privacy', $viewData))
                    :   Response::make(View::make('landing/privacy', $viewData))->withCookie($this->SiteUserCookie);
	}

    public function processErrors($errorNumber)
    {
        // todo:  this can be stored in the database so we can track which errors occur the most and at what frequency and by which member and user

		// Customer Service should be the first point of call not Tech support
        $techSupport            =   str_replace("[errorNumber]", $errorNumber, self::POLICY_LinkTechnicalSupport );
        $customerService        =   str_replace("[errorNumber]", $errorNumber, self::POLICY_LinkCustomerService );
        $chooseSubscription     =   "<a href='/plans'>Choose a Plan</a>";

        switch($errorNumber)
        {
			case 'accessTempDisabled'	:	$ErrorMsg       =   "Unfortunately, your access has been temporarily disabled. Please, email " . $customerService;
                            				break;

			case 'accessPermDisabled'	:	$ErrorMsg       =   "Unfortunately, your access has been permanently disabled. Please, email " . $customerService;
                            				break;




            case '1'    :   $ErrorMsg       =   "Sorry, your email verification link could not be validated. Please, re-click the link or email " . $customerService;
                            break;

            case '2'    :   $ErrorMsg       =   "Sorry, your email verification link could not be validated. Please, re-click the link or email " . $customerService;
                            break;

            case '3'    :   $ErrorMsg       =   "Sorry, your email verification link could not be validated. Please, re-click the link or email " . $customerService;
                            break;

            case '4'    :   $ErrorMsg       =   "Sorry, your email verification link could not be validated. Please, re-click the link or email " . $customerService;
                            break;

            case '5'    :   $ErrorMsg       =   "Sorry, your verification details could not be saved. Please, re-click the email verification link or contact " . $customerService;
                            break;

            case '6'    :   $ErrorMsg       =   "Sorry, your login could not be processed at this time. Please, retry or contact " . $customerService;
                            break;

            case '7'    :   $ErrorMsg       =   "Please, verify your email before your access credentials into your NotaryToolz.com member section can be processed. If this error is incorrect, Please email" . $customerService;
                            break;

            case '8'    :   $ErrorMsg       =   "Sorry, your access credentials into your NotaryToolz.com member section could not be processed at this time. A message has been sent to " . $customerService;
                            break;

            case '9'    :   $ErrorMsg       =   "Sorry, your login could not be processed at this time. Please, retry or contact " . $customerService;
                            break;

            case '10'   :   $ErrorMsg       =   "Your email address is not yet registered as a member. Please, click the signup link above or contact " . $customerService;
                            break;

            case '11'   :   $ErrorMsg       =   "Unfortunately, your trial period has expired. To re-activate your account please, " . $chooseSubscription;
                            break;

            case '12'   :   $ErrorMsg       =   "Unfortunately, your account has been paused. Please email " . $customerService;
                            break;

            case '13'   :   $ErrorMsg       =   "Unfortunately, your account has been cancelled. Please email " . $customerService;
                            break;

            case '14'   :   $ErrorMsg       =   "Unfortunately, your account requires an active subscription. Please email " . $customerService;
                            break;

            case '15'   :   $ErrorMsg       =   "This may be the wrong access point into NotaryToolz.com for you or your signup process is unfinished.</p>

            									<p>Please check your signup/membership confirmation for the correct login url or have it <a href='/resendSignupConfirmation'>resent</a></p>
            									<p>If that doesn't work for you, contact " . $customerService;
                            break;

            case '16'   :   $ErrorMsg       =   "Please, first enter your personalized account details so we can customize your account or contact " . $customerService;
                            break;

            case '17'   :   $ErrorMsg       =   "Unfortunately, your account has been cancelled. Please email " . $customerService;
                            break;

            case '18'   :   $ErrorMsg       =   "Unfortunately, you've attempted to sign up too many times. Please email " . $customerService . ". We are sure they can help you.";
                            break;

            case '19'   :   $ErrorMsg       =   "Unfortunately, you've attempted to change your password with the link we have sent you too many times. Please email " . $customerService . ". We are sure they can help you.";
                            break;

            case '20'   :   $ErrorMsg       =   "Unfortunately, you've attempted to change your password too many times. Please email " . $customerService . ". We are sure they can help you.";
                            break;

            case '21'   :   $ErrorMsg       =   "Unfortunately, you've attempted to resend your signup verification email too many times. Please email " . $customerService . ". We are sure they can help you.";
                            break;

            case '22'   :   $ErrorMsg       =   "Unfortunately your verification link has expired. Please, retry or email " . $customerService . ". We are sure they can help you.";
                            break;

            case '23'   :   $ErrorMsg       =   "Unfortunately your security parameters are incorrect. Please, retry, refresh or email " . $customerService . ". We are sure they can help you.";
                            break;

			/**
			 * Change Password Errors
			 */


			default     :   $ErrorMsg       =   "Sorry, We Can't Find What You are Looking For.";
        }

         $viewData   =  array
                        (
                            'Exclamation'   =>  (isset($Exclamation) ?  $Exclamation : 'Uh Oh!' ),
                            'ErrorMsg'   	=>  $ErrorMsg,
                        );

		return  is_int($this->SiteUserCookie) && $this->SiteUserCookie > 0
                    ?   Response::make(View::make('landing/custom-error', $viewData))
                    :   Response::make(View::make('landing/custom-error', $viewData))->withCookie($this->SiteUserCookie);
    }

}