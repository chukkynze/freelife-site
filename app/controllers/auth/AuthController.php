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
    const POLICY_AllowedVerificationSeconds_Signup				=   43200;
	const POLICY_AllowedVerificationSeconds_ChangePassword		=   10800;

	const POLICY_AllowedLoginAttempts       					=   3;
    const POLICY_AllowedLoginCaptchaAttempts    				=   3;
    const POLICY_AllowedSignupAttempts       					=   3;
    const POLICY_AllowedForgotAttempts       					=   3;
    const POLICY_AllowedChangeVerifiedMemberPasswordAttempts 	=   3;
    const POLICY_AllowedChangeOldMemberPasswordAttempts 		=   3;
    const POLICY_AllowedLostSignupVerificationAttempts 			=   3;
    const POLICY_AllowedAttemptsLookBackDuration  				=   'Last1Hour';


    private $activity;
    private $reason;

    public function __construct()
    {
        $this->getSiteUser();   // Find/Create a SiteUser uid from cookie
        $this->setSiteHit();    // Register a SiteHit
    }


    public function showAccess()
	{
        $activity   =   ( isset($this->activity)    ?   $this->activity :   'login');
        $reason     =   ( isset($this->reason)      ?   $this->reason   :   '');

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


    public function logout()
    {
		if ($this->getAuthService()->hasIdentity())
        {
            $this->getAuthService()->clearIdentity();
        }
    }

    public function loginAgain()
    {
        $this->activity     =   "login";
        $this->reason       =   "expired-session";
        return $this->showAccess();
    }

    public function successfulLogout()
    {
        $this->activity     =   "login";
        $this->reason       =   "intentional-logout";
        return $this->showAccess();
    }

    public function successfulAccessCredentialChange()
    {
        $this->activity     =   "login";
        $this->reason       =   "changed-password";
        return $this->showAccess();
    }

    public function loginCaptcha()
    {
        $this->activity     =   "login-captcha";
        $this->reason       =   "";
        return $this->showAccess();
    }

    public function memberLogout()
    {
        $this->logout();

		// return $this->redirect()->toRoute('member-login-after-intentional-logout');
    }

    public function memberLogoutExpiredSession()
    {
        $this->logout();

		// return $this->redirect()->toRoute('member-login-after-expired-session');
    }

    public function signup()
    {
        $this->activity     =   "signup";
        $this->reason       =   "";
        return $this->showAccess();
    }

    public function processSignup()
    {
        $returnToRoute          =   array(
            'name'  =>  FALSE,
            'data'  =>  FALSE,
        );
        $SignupFormMessages     =   array();

        if(Request::isMethod('post'))
        {
            $AccessAttempt      =   new AccessAttempt();
            $AttemptedSignups   =   $AccessAttempt->getAccessAttemptByUserIDs
                                                    (
                                                        'SignupForm',
                                                        array($this->getSiteUser()->id),
                                                        self::POLICY_AllowedAttemptsLookBackDuration
                                                    );

            if($AttemptedSignups['total'] < self::POLICY_AllowedSignupAttempts)
            {
                $SubmittedPostValues    =   Input::all();
                $SubmittedFormName      =   'SignupForm';

                /**
                 * Check for robot entries against dummy variables
                 */
                if($this->isFormClean($SubmittedFormName, $SubmittedPostValues))
                {
                    $formFields     =   array
                                        (
                                            'new_member'                =>  Input::get('new_member'),
                                            'password'                  =>  Input::get('password'),
                                            'password_confirmation '    =>  Input::get('password_confirmation'),
                                            'acceptTerms'               =>  Input::get('acceptTerms'),
                                        );
                    $formRules      =   array
                                        (
                                            'new_member'                =>  array
                                                                            (
                                                                                'required',
                                                                                'email',
                                                                                'unique:member_emails,email_address',
                                                                                'between:5,120',
                                                                            ),
                                            'password'                  =>  array
                                                                            (
                                                                                'required',
                                                                                'confirmed',
                                                                                'same:password_confirmation',
                                                                                'between:10,256',
                                                                            ),
                                            'password_confirmation '    =>  array
                                                                            (
                                                                                'required',
                                                                                'between:10,256',
                                                                            ),
                                            'acceptTerms'               =>  array
                                                                            (
                                                                                'required',
                                                                                'boolean',
                                                                                'accepted',
                                                                            ),
                                        );
                    $formMessages   =   array
                                        (
                                            'new_member.required'   =>  "An email address is required and can not be empty.",
                                            'new_member.email'      =>  "Your email address format is invalid.",
                                            'new_member.unique'     =>  "Please, check your inbox for previous sign up instructions.",
                                            'new_member.between'    =>  "Please, re-check your email address' size.",

                                            'password.required'                     =>  "Please enter your password.",
                                            'password.confirmed'                    =>  "A password confirmation is required.",
                                            'password.same:password_confirmation'   =>  "Passwords do not match.",
                                            'password.between'                      =>  "Passwords must be more than 10 digits. Valid characters only.",

                                            'password_confirmation.required'    =>  "Password confirmation is required.",
                                            'password_confirmation.between'     =>  "A confirmed passwords must be more than 10 digits. Valid characters only.",

                                            'acceptTerms.required'  =>  "Please indicate that you read our Terms & Privacy Policy.",
                                            'acceptTerms.boolean'   =>  "Please, indicate that you read our Terms & Privacy Policy.",
                                            'acceptTerms.accepted'  =>  "Please indicate that you read our Terms & Privacy Policy",
                                        );

                    $validator      =   Validator::make($formFields, $formRules, $formMessages);

                    if ($validator->passes())
                    {
                        // Add the emailAddress
                        $this->addEmailStatus($formFields['new_member'], 'AddedUnverified');

                        // Get the Site User so you can associate this user behaviour with this new member
                        $this->SiteUser     =   $this->getSiteUser();

                        // Create a Member Object
                        $NewMemberStatus    =   $this->addMember($formFields['new_member'], $formFields['password']);

                        if(!$NewMemberStatus)
                        {
                            // todo: handle this better. Write an error, add fatal admin alert and a log entry
                            $this->registerAccessAttempt($this->getSiteUser()->getID(), $SubmittedFormName, 0);
                            Log::info($SubmittedFormName . " - Could not create a new member object.");
                        }

                        // Update User with Member ID
                        $this->SiteUser->setUserMemberID($NewMemberObject->id);
                        $this->SiteUser     =   $this->getUserTable()->getUser($this->getUserTable()->saveUser($this->SiteUser));

                        // Create & Save a Member Status Object
                        $this->addMemberStatus($NewMemberObject->id, 'Successful-Signup');

                        // Create & Save a Member Emails Object
                        $NewMemberEmail         =   new MemberEmails();
                        $NewMemberEmail->setMemberEmailsMemberID($NewMemberObject->id);
                        $NewMemberEmail->setMemberEmailsEmailAddress($validatedData['new_member']);
                        $NewMemberEmail->setMemberEmailsVerificationSent(0);
                        $NewMemberEmail->setMemberEmailsVerificationSentOn(0);
                        $NewMemberEmail->setMemberEmailsVerified(0);
                        $NewMemberEmail->setMemberEmailsVerifiedOn(0);
                        $NewMemberEmail->setMemberEmailsCreationTime();
                        $NewMemberEmail->setMemberEmailsLastUpdateTime();
                        $NewMemberEmailObject   =   $this->getMemberEmailsTable()->getMemberEmails($this->getMemberEmailsTable()->saveMemberEmails($NewMemberEmail));

                        if(!is_object($NewMemberEmailObject))
                        {
                            // todo: handle this better. Write an error, add fatal admin alert and a log entry
                            $this->registerAccessAttempt($this->getSiteUser()->getID(), $SubmittedFormName, 0);
                            $this->_writeLog('info', $SubmittedFormName . " - Could not create a new member email object.");
                            throw new \Exception("Could not create a new member email object.");
                        }

                        // Prepare an Email for Validation
                        // setup SMTP options
                        $verifyEmailLink    =   $this->getMemberEmailsTable()->getVerifyEmailLink($validatedData['new_member'], $NewMemberObject->id, 'verify-new-member');
                        $this->sendEmail('verify-new-member', array('verifyEmailLink' => $verifyEmailLink), 'General', $NewMemberEmailObject->getMemberEmailsEmailAddress());

                        // Update Member emails that verification was sent and at what time for this member
                        $NewMemberEmailObject->setMemberEmailsVerified(0);
                        $NewMemberEmailObject->setMemberEmailsVerifiedOn(0);
                        $NewMemberEmailObject->setMemberEmailsVerificationSent(1);
                        $NewMemberEmailObject->setMemberEmailsVerificationSentOn(strtotime('now'));
                        $NewMemberEmailObject   =   $this->getMemberEmailsTable()->getMemberEmails($this->getMemberEmailsTable()->saveMemberEmails($NewMemberEmailObject));


                        // Add the emailAddress status
                        $this->addEmailStatus($NewMemberEmailObject->getMemberEmailsEmailAddress(), 'VerificationSent');

                        // Store admin alert for new member
                        // todo: Create a cron script that checks for new members since the last check and adds alerts and sends off emails to whomever needs to know plus other tasks. Call it process new members

                        // Add

                        // Redirect to Successful Signup Page that informs them of the need to validate the email before they can enjoy the free 90 day Premium membership
                        $this->registerAccessAttempt($this->getSiteUser()->getID(), $SubmittedFormName, 1);
                        return $this->redirect()->toRoute('member-signup-success');
                    }
                    else
                    {
                        $SignupFormErrors   =   $validator->messages()->toArray();
                        $SignupFormMessages =   array();
                        foreach($SignupFormErrors as $errors)
                        {
                            $SignupFormMessages[]   =   $errors[0];
                        }

                        $this->registerAccessAttempt($this->getSiteUser()->getID(),$SubmittedFormName, 0);
                    }
                }
                else
                {
                    $this->registerAccessAttempt($this->getSiteUser()->getID(), $SubmittedFormName, 0);
                    $this->addAdminAlert();
                    Log::warning($SubmittedFormName . " has invalid dummy variables passed.");
                    return Redirect::route('custom-error',array('errorNumber' => 23));
                }
            }
            else
            {
                $this->applyLock('Locked:Excessive-Signup-Attempts', '','excessive-signups');
                $returnToRoute  =   array
                                    (
                                        'name'  =>  'custom-error',
                                        'data'  =>  array('errorNumber' => 18),
                                    );
            }
        }
        else
        {
            $returnToRoute  =   array
                                (
                                    'name'  =>  'custom-error',
                                    'data'  =>  array('errorNumber' => 23),
                                );
        }

        if(isset($returnToRoute['name']))
        {
            return Redirect::route($returnToRoute['name'],$returnToRoute['data']);
        }
        else
        {
            $viewData   =   array(
                'activity'                  =>  "signup",

                'LoginFormMessages'         =>  array(),
                'LoginAttemptMessage'       =>  array(),

                'LoginCaptchaFormMessages'  =>  array(),
                'LoginCaptchaAttemptMessage'=>  array(),

                'reCaptcha'                 =>  NULL,
                'reCaptchaError'            =>  NULL,
                'PauseGifDisplaySeconds'    =>  0,

                'SignupFormMessages'        =>  $SignupFormMessages,
                'ForgotFormMessages'        =>  array(),
                'LoginHeaderMessage'        =>  array()
            );

            return  is_int($this->SiteUserCookie) && $this->SiteUserCookie > 0
                ?   Response::make(View::make('auth/login', $viewData))
                :   Response::make(View::make('auth/login', $viewData))->withCookie($this->SiteUserCookie);
        }
    }

    public function vendorSignup()
    {
        $this->activity     =   "signup";
        $this->reason       =   "";
        return $this->showAccess();
    }

    public function freelancerSignup()
    {
        $this->activity     =   "signup";
        $this->reason       =   "";
        return $this->showAccess();
    }

    public function forgot()
    {
        $this->activity     =   "forgot";
        $this->reason       =   "";
        return $this->showAccess();
    }

    public function resetPassword()
    {
        $this->activity     =   "forgot";
        $this->reason       =   "";
        return $this->showAccess();
    }

    public function changePasswordWithOldPassword()
    {

    }

    public function processVerificationDetails()
    {

    }

    public function resendSignupConfirmation()
    {
        // lostSignupVerificationAction
    }

    public function verifyEmail($vcode)
    {
        echo $vcode;
    }

    public function changePasswordWithVerifyEmailLink($vcode)
    {

    }

	/**
	 * Applies an appropriate lock to a user, ip, and or member and sends an email if necessary and possible
	 *
	 * @param        $lockStatus
	 * @param string $contactEmail
	 * @param string $emailTemplateName
	 * @param array  $emailTemplateOptionsArray
	 * @param string $emailTemplateSendFromTag
	 */
	public function applyLock($lockStatus, $contactEmail='', $emailTemplateName='', $emailTemplateOptionsArray=array(), $emailTemplateSendFromTag='Customer Service')
	{
	    // Lock user status
		$this->getSiteUser()->lockUserStatus($lockStatus, $this->getSiteUser()->getID());

		// Create an IP Block
		$ipBin  =	new IPBin();
        $ipBin->blockIPAddress($this->getSiteUser()->getID(), $lockStatus, $this->getSiteUser()->getMemberID());

		// Lock the user member
		$this->getSiteUser()->lockMemberStatus($lockStatus, $this->getSiteUser()->getMemberID());

        $validator  =   Validator::make(
            array(
                'email' => $contactEmail
            ),
            array(
                'email' => 'required|email|unique:member_emails'
            )
        );

        if ($validator->passes())
        {
            // if email is in our database
			$MemberEmailsObject = 	$this->getMemberEmailsTable()->getMemberEmailsByEmail($contactEmail);
			if(is_object($MemberEmailsObject))
			{
				// Lock the member associated with the email address
				$MemberStatus 	=	$this->getMemberStatusTable()->getMemberStatusByMemberID($MemberEmailsObject->getMemberEmailsMemberID());
				$MemberStatus->setMemberStatusStatus($lockStatus);
				$this->getMemberStatusTable()->saveMemberStatus($MemberStatus);

				// Send an email to the member
				$this->sendEmail($emailTemplateName, $emailTemplateOptionsArray, $emailTemplateSendFromTag, $MemberEmailsObject->getMemberEmailsEmailAddress());
			}
			else
			{
				// Send an email to the user
				$this->sendEmail($emailTemplateName, $emailTemplateOptionsArray, $emailTemplateSendFromTag, $contactEmail);
			}
        }
	}



	/**
	 * Checks if form has been populated by robots
	 *
	 * @param $formName
	 * @param $formValues
	 *
	 * @return bool
	 */
	public function isFormClean($formName, $formValues)
    {
        $returnValue    =   FALSE;

        if(is_array($formValues))
        {
            switch($formName)
            {
                case 'LoginForm'            					:   $dummyInput     =   array
																						(
																							'usr'           =>  '',
																							'username'      =>  '',
																							'email'         =>  '',
																							'login_email'   =>  '',
																						);
																	break;

                case 'SignupForm'     							:   $dummyInput     =   array
																						(
																							'usr'           =>  '',
																							'username'      =>  '',
																							'email'         =>  '',
																							'login_email'   =>  '',
																						);
																	break;

                case 'ForgotForm'     							:   $dummyInput     =   array
																						(
																							'usr'           =>  '',
																							'username'      =>  '',
																							'email'         =>  '',
																							'login_email'   =>  '',
																						);
																	break;

                case 'LoginCaptchaForm'     					:   $dummyInput     =   array
																						(
																							'usr'           =>  '',
																							'username'      =>  '',
																							'email'         =>  '',
																							'login_email'   =>  '',
																						);
																	break;

                case 'ChangePasswordWithVerifyLinkForm'     	:   $dummyInput     =   array
																						(
																							'usr'           =>  '',
																							'username'      =>  '',
																							'email'         =>  '',
																							'login_email'   =>  '',
																						);
                                                					break;

                case 'ChangePasswordWithOldPasswordForm'     	:   $dummyInput     =   array
																						(
																							'usr'           =>  '',
																							'username'      =>  '',
																							'email'         =>  '',
																							'login_email'   =>  '',
																						);
																	break;


                default  :   $dummyInput     =	array
												(
													'false'     =>  'FALSE',
												);
            }

            foreach ($dummyInput as $dumbKey => $dumbValue)
            {
                if(array_key_exists($dumbKey, $formValues))
                {
                    if($dummyInput[$dumbKey] != 'FALSE')
                    {
                        if($formValues[$dumbKey] == $dummyInput[$dumbKey])
                        {
                            $returnValue    =   TRUE;
                        }
                        else
                        {
                            $this->_writeLog('info', "Form value for dummy input has incorrect value of [" . $formValues[$dumbKey]. "]. It should be [" . $dummyInput[$dumbKey]. "].");
                            $returnValue    =   FALSE;
                        }
                    }
                    else
                    {
                        $this->_writeLog('info', "Invalid formName. => dummyInput[" . $dumbValue . "]");
                        $returnValue    =   FALSE;
                    }
                }
                else
                {
                    $this->_writeLog('info', "Array key from variable dumbKey (" . $dumbKey . ") does not exist in variable array formValues.");
                    $returnValue    =   FALSE;
                }
            }
        }
        else
        {
            $this->_writeLog('info', "Variable formValues is not an array.");
            $returnValue    =   FALSE;
        }

        return $returnValue;
    }




	/**
	 * Stores an access attempt
	 *
	 * @param $userID
	 * @param $accessFormName
	 * @param $attemptBoolean
	 */
	public function registerAccessAttempt($userID, $accessFormName, $attemptBoolean)
    {
        try
        {
             $AccessAttempt  =   new AccessAttempt();
            $AccessAttempt->registerAccessAttempt($userID, $accessFormName, $attemptBoolean);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not add a new Access Attempt. " . $e);
        }
    }

    public function addEmailStatus($emailAddress, $status)
    {
        try
        {
            $EmailStatus    =   new EmailStatus();
            $EmailStatus->addEmailStatus($emailAddress, $status);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not add a new Email Status. " . $e);
        }
    }

    public function generateLoginCredentials($newMemberEmail, $newMemberPassword)
    {
        $siteSalt           =   $_ENV['ENCRYPTION_KEY_SITE_default_salt'];
        $memberSalt1        =   uniqid(mt_rand(0, 61), true);
        $memberSalt2        =   uniqid(mt_rand(0, 61), true);
        $memberSalt3        =   uniqid(mt_rand(0, 61), true);
        $loginCredentials   =   $this->createHash
                                (
                                    $memberSalt1 . $newMemberEmail . $siteSalt . $newMemberPassword . $memberSalt2,
                                    $siteSalt . $memberSalt3
                                );
        return  array
                (
                    $loginCredentials,
                    $memberSalt1,
                    $memberSalt2,
                    $memberSalt3,
                );
    }

    public function addMember($newMemberEmail, $newMemberPassword)
    {
        try
        {
            $LoginCredentials   =   $this->generateLoginCredentials($newMemberEmail, $newMemberPassword);
            $NewMember          =   new Member();
            $NewMember->addMember($LoginCredentials);
            return TRUE;
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not add a new Email Status. " . $e);
            return FALSE;
        }
    }

    public function addAdminAlert()
    {
        // todo: Add an Admin Alert for certain issues
    }
}