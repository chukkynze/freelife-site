<?php
/**
 * filename:   login.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
 * @since       7/13/14 1:27 AM
 * 
 * @copyright   Copyright (c) 2014 www.eKinect.com
 */
?>

<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,IE=9,IE=8,chrome=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>EKinect - Member Access</title>

	<!--[if lt IE 9]><script type="text/javascript" src="/auth/theme/js/flot/excanvas.min.js"></script><![endif]-->
    <!--[if lt IE 9]><script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <!--[if lt IE 9]><script type="text/javascript" src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script><![endif]-->

    <link href="/auth/theme/css/cloud-admin.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/auth/theme/font-awesome/css/font-awesome.min.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/auth/theme/js/bootstrap-daterangepicker/daterangepicker-bs3.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/auth/theme/js/uniform/css/uniform.default.min.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/auth/theme/css/animatecss/animate.min.css" media="screen" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" media="screen" rel="stylesheet" type="text/css">
    <link href="/auth/views/css/login.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">

</head>
<body class="login">
	<!-- PAGE -->
	<section id="page">
			<!-- HEADER -->
			<header>
				<!-- NAV-BAR -->
				<div class="container">
					<div class="row">
						<div class="col-md-4 col-md-offset-4">
							<div id="logo">
								<a href="/"><img src="/app/images/site_images/logo.png" height="60" alt="logo name" /></a>
							</div>
						</div>
					</div>
				</div>
				<!--/NAV-BAR -->
			</header>
			<!--/HEADER -->
			<!-- LOGIN -->
			<section id="login" class="<?php echo ($activity == 'login' ? 'visible' : '' ); ?>">
				<div class="container">
					<div class="row">
						<div class="col-md-4 col-md-offset-4">
							<div class="login-box-plain">
								<h2 class="bigintro">Member Login</h2>
								<h3 class="center">
									<?php
										switch($LoginHeaderMessage)
										{
											case 1	:	echo "Your Member Session Expired. Please, login again to get back to your...stuff.";
														break;
											case 2	:	echo "Your Have Successfully Logged Out<br>We'll leave the light on for when you get back!";
														break;
											case 3	:	echo "Your Have Successfully Updated Your Access Credentials<br>Open Sesame!";
														break;

											default : echo "";
										}
									?>
								</h3>

                                <?php if($LoginFormMessages != '' || $LoginAttemptMessage != ''): ?>
                                <div class="alert alert-block alert-danger fade in">
                                    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                    <h4><i class="fa fa-times"></i> Oh snap! You got an error!</h4>
                                    <?php

                                        if($LoginFormMessages != '')
                                        {
                                            echo $this->formElementErrors()
                                                    ->setMessageOpenFormat('<ul><li>')
                                                    ->setMessageSeparatorString('</li><li>')
                                                    ->setMessageCloseString('</li></ul>')
                                                    ->render($LoginForm)
                                            ;
                                        }

                                        if($LoginAttemptMessage != '')
                                        {
                                            echo '<ul><li>' . $LoginAttemptMessage . '</li></ul>';
                                        }
                                    ?>
                                </div>
                                <?php endif; ?>


								<div class="divide-40"></div>
                                    {{ Form::open(array('action' => 'AuthController@showAccess')) }}

                                        <input class="notarytoolzInput Input1" name="usr"          type="text"  value="">
                                        <input class="notarytoolzInput Input2" name="username"     type="text"  value="">
                                        <input class="notarytoolzInput Input3" name="email"        type="text"  value="">
                                        <input class="notarytoolzInput Input4" name="login_email"  type="text"  value="">

                                        <div class="form-group">
                                            <?php echo Form::label('returning_member', 'E-Mail Address'); ?>
                                            <i class="fa fa-envelope"></i>
                                            <?php echo Form::text('returning_member'); ?>
                                        </div>
                                        <div class="form-group">
                                            <?php echo Form::label('LoginFormPasswordField', 'Password'); ?>
                                            <i class="fa fa-lock"></i>
                                            <?php echo Form::password('LoginFormPasswordField'); ?>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" class="btn-lg btn-danger">Login</button>
                                        </div>

                                    {{ Form::close() }}
								<!-- SOCIAL LOGIN
								<div class="divide-20"></div>
								<div class="center">
									<strong>Or login using your social account</strong>
								</div>
								<div class="divide-20"></div>
								<div class="social-login center">
									<a class="btn btn-primary btn-lg">
										<i class="fa fa-facebook"></i>
									</a>
									<a class="btn btn-info btn-lg">
										<i class="fa fa-twitter"></i>
									</a>
									<a class="btn btn-danger btn-lg">
										<i class="fa fa-instagram"></i>
									</a>
								</div>
								/SOCIAL LOGIN -->
								<div class="login-helpers">
									<a href="#" onclick="swapScreen('forgot');return false;">Forgot Password?</a> <br>
									Don't have an account with us? <a href="#" onclick="swapScreen('register');return false;">Sign up now!</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!--/LOGIN -->
			<!-- REGISTER -->
			<section id="register" class="<?php echo ($activity == 'signup' ? 'visible' : '' ); ?>">
				<div class="container">
					<div class="row">
						<div class="col-md-4 col-md-offset-4">
							<div class="login-box-plain">
								<h2 class="bigintro">Free Sign Up</h2>
								<div class="center">
									<h3><strong>no credit card required</strong></h3>
									<strong><a href='/resend-signup-confirmation'>Re-send</a> your signup verification email</strong>
								</div>

                                <?php if($SignupFormMessages != ''): ?>
                                <div class="alert alert-block alert-danger fade in">
                                    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                    <h4><i class="fa fa-times"></i> Oh snap! You got an error!</h4>
                                    <?php
                                        echo $this->formElementErrors()
                                                ->setMessageOpenFormat('<ul><li>')
                                                ->setMessageSeparatorString('</li><li>')
                                                ->setMessageCloseString('</li></ul>')
                                                ->render($SignupForm)
                                        ;
                                    ?>
                                </div>
                                <?php endif; ?>


								<div class="divide-40"></div>

                                {{ Form::open(array('method' => 'POST', 'action' => 'AuthController@processSignup')) }}

                                    <input class="notarytoolzInput Input1" name="usr"          type="text"  value="">
                                    <input class="notarytoolzInput Input2" name="username"     type="text"  value="">
                                    <input class="notarytoolzInput Input3" name="email"        type="text"  value="">
                                    <input class="notarytoolzInput Input4" name="login_email"  type="text"  value="">

                                    <div class="form-group">
                                        <?php echo Form::label('new_member', 'Email Address'); ?>
                                        <i class="fa fa-envelope"></i>
                                        <?php echo Form::text('new_member'); ?>
                                    </div>
                                    <div class="form-group">
                                        <?php echo Form::label('password', 'Password'); ?>
                                        <i class="fa fa-lock"></i>
                                        <?php echo Form::password('password'); ?>
                                    </div>
                                    <div class="form-group">
                                        <?php echo Form::label('cpassword', 'Password'); ?>
                                        <i class="fa fa-check-square-o"></i>
                                        <?php echo Form::password('cpassword'); ?>
                                    </div>
                                    <div class="form-actions">
                                        <label class="checkbox" for="SignupFormTermsBox">
                                            <input type="hidden" name="acceptTerms" value="">
                                            <input type="checkbox" name="acceptTerms" id="SignupFormTermsBox" class="uniform" value="1">
                                            I agree to the <a href="/terms">Terms of Service</a> and <a href="/privacy">Privacy Policy</a>
                                        </label>
                                        <button type="submit" class="btn-lg btn-success">Sign Up</button>
                                    </div>

                                {{ Form::close() }}

								<!-- SOCIAL REGISTER
								<div class="divide-20"></div>
								<div class="center">
									<strong>Or signup using your social account</strong>
								</div>
								<div class="divide-20"></div>
								<div class="social-login center">
									<a class="btn btn-primary btn-lg">
										<i class="fa fa-facebook"></i>
									</a>
									<a class="btn btn-info btn-lg">
										<i class="fa fa-twitter"></i>
									</a>
									<a class="btn btn-danger btn-lg">
										<i class="fa fa-instagram"></i>
									</a>
								</div>
								/SOCIAL REGISTER -->
								<div class="login-helpers">
									<a href="#" onclick="swapScreen('login');return false;"> Back to Login</a> <br>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!--/REGISTER -->
			<!-- FORGOT PASSWORD -->
			<section id="forgot" class="<?php echo ($activity == 'forgot' ? 'visible' : '' ); ?>">
				<div class="container">
					<div class="row">
						<div class="col-md-4 col-md-offset-4">
							<div class="login-box-plain">
								<h2 class="bigintro">Reset Password</h2>

                                <?php if($ForgotFormMessages != ''): ?>
                                <div class="alert alert-block alert-danger fade in">
                                    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                    <h4><i class="fa fa-times"></i> Oh snap! You got an error!</h4>
                                    <?php
                                        echo $this->formElementErrors()
                                                ->setMessageOpenFormat('<ul><li>')
                                                ->setMessageSeparatorString('</li><li>')
                                                ->setMessageCloseString('</li></ul>')
                                                ->render($ForgotForm)
                                        ;
                                    ?>
                                </div>
                                <?php endif; ?>

								<div class="divide-40"></div>

                                {{ Form::open(array('action' => 'AuthController@showAccess')) }}

                                    <input class="notarytoolzInput Input1" name="usr"          type="text"  value="">
                                    <input class="notarytoolzInput Input2" name="username"     type="text"  value="">
                                    <input class="notarytoolzInput Input3" name="email"        type="text"  value="">
                                    <input class="notarytoolzInput Input4" name="login_email"  type="text"  value="">


                                    <div class="form-group">
                                            <?php echo Form::label('forgot_email', 'E-Mail Address'); ?>
                                        <i class="fa fa-envelope"></i>
                                            <?php echo Form::text('forgot_email'); ?>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" class="btn-lg btn-info">Send Me Reset Instructions</button>
                                    </div>

                                {{ Form::close() }}

								<div class="login-helpers">
									<a href="#" onclick="swapScreen('login');return false;">Back to Login</a> <br>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- FORGOT PASSWORD -->
	</section>
	<!--/PAGE -->

    <script type="text/javascript" src="/auth/theme/js/jquery/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="/auth/theme/js/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="/auth/theme/bootstrap-dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/auth/theme/js/uniform/jquery.uniform.min.js"></script>
    <script type="text/javascript" src="/auth/theme/js/script.js"></script>
    <!--[if lt IE 9]><script type="text/javascript" src="/auth/theme/js/flot/excanvas.min.js"></script><![endif]-->
    <!--[if lt IE 9]><script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <!--[if lt IE 9]><script type="text/javascript" src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script><![endif]-->


    <script>
		jQuery(document).ready(function() {
			App.setPage("login");  //Set current page
			App.init(); //Initialise plugins and elements
		});
	</script>
	<script type="text/javascript">
		function swapScreen(id) {
			jQuery('.visible').removeClass('visible animated fadeInUp');
			jQuery('#'+id).addClass('visible animated fadeInUp');
		}
	</script>
	<!-- /JAVASCRIPTS -->
</body>
</html>