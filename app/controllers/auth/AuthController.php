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
        $AccessAttempt      =   new AccessAttempt();
        $AttemptedSignups   =   $AccessAttempt->getAccessAttemptByUserIDs
                                                (
                                                    'SignupForm',
                                                    array($this->getSiteUser()->id),
                                                    self::POLICY_AllowedAttemptsLookBackDuration
                                                );
        if($AttemptedSignups['total'] > self::POLICY_AllowedSignupAttempts)
        {
            $this->applyLock('Locked:Excessive-Signup-Attempts', '','excessive-signups');
            return Redirect::route('custom-error',array('errorNumber' => 18));
        }
        else
        {
            $SubmittedForm          =   $SignupForm;
            $SubmittedFormValues    =   $this->getRequest()->getPost();
            $SubmittedFormName      =   'SignupForm';
            $SubmittedFormCSRF      =   'signup_csrf';

            /**
             * Check for robot entries against dummy variables
             */
            if(!$this->isFormClean($SubmittedFormName, $SubmittedFormValues))
            {
                $this->registerAccessAttempt($SubmittedFormName, 0);

                // todo : add an admin alert

                $this->_writeLog('info', $SubmittedFormName . " has invalid dummy variables passed.");
                $SubmittedFormValues[$SubmittedFormCSRF]    =   '!98475b8!#urwgfwitg2^347tg2%78rtg283*rg';
            }

            $SignupFormValues   =   $this->getRequest()->getPost();
            $SignupForm->setData($SignupFormValues);

            if ($SignupForm->isValid($SignupFormValues))
            {
                $validatedData          =   $SignupForm->getData();

                // Add the emailAddress
                $this->addEmailStatus($validatedData['new_member'], 'AddedUnverified');

                // Get the Site User so you can associate this user behaviour with this new member
                $this->SiteUser         =   $this->getUser();

                // todo: Check if member email already exists
                $doesMemberAlreadyExist =   $this->getMemberEmailsTable()->getMemberEmailsByEmail($validatedData['new_member']);
                if($doesMemberAlreadyExist != FALSE)
                {
                    $this->registerAccessAttempt($SubmittedFormName, 0);
                    return $this->redirect()->toRoute('member-already-exists');
                }


                // Create a Member Object
                $LoginCredentials   =   $this->getMemberTable()->generateLoginCredentials($validatedData['new_member'], $validatedData['password']);
                $NewMember          =   new Member();
                $NewMember->setMemberType('6');
                $NewMember->setMemberCreationTime();
                $NewMember->setMemberPauseTime(1);
                $NewMember->setMemberCancellationTime(1);
                $NewMember->setMemberLastUpdateTime();
                $NewMember->setMemberLoginCredentials($LoginCredentials[0]);
                $NewMember->setMemberLoginSalt1($LoginCredentials[1]);
                $NewMember->setMemberLoginSalt2($LoginCredentials[2]);
                $NewMember->setMemberLoginSalt3($LoginCredentials[3]);
                $NewMemberObject    =   $this->getMemberTable()->getMember($this->getMemberTable()->saveMember($NewMember));

                if(!is_object($NewMemberObject))
                {
                    // todo: handle this better. Write an error, add fatal admin alert and a log entry
                    $this->registerAccessAttempt($SubmittedFormName, 0);
                    $this->_writeLog('info', $SubmittedFormName . " - Could not create a new member object.");
                    throw new \Exception("Could not create a new member object");
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
                    $this->registerAccessAttempt($SubmittedFormName, 0);
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
                $this->registerAccessAttempt($SubmittedFormName, 1);
                return $this->redirect()->toRoute('member-signup-success');
            }
            else
            {
                $this->registerAccessAttempt($SubmittedFormName, 0);
                $SignupFormMessages           =   $SignupForm->getMessages();
            }
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




}