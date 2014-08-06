<?php
$VerificationDetailsForm->prepare();
$VerificationDetailsForm->setAttribute('action', $this->url('verified-email-already-exists'));

echo $this->doctype();
?>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
    <?php echo $this->headTitle('NotaryToolz - Member Access.')->setSeparator(' - ')->setAutoEscape(false) ?>

	<?php echo $this->headMeta()
        ->appendName('viewport', 'width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no')
        ->appendHttpEquiv('X-UA-Compatible', 'IE=edge,IE=9,IE=8,chrome=1')
        ->appendName('description', '')
        ->appendName('author', '')
    ?>

	<?php echo $this->headScript()
        ->prependFile('http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js'   , 'text/javascript', array('conditional' => 'lt IE 9',))
        ->prependFile('http://html5shiv.googlecode.com/svn/trunk/html5.js'                          , 'text/javascript', array('conditional' => 'lt IE 9',))
        ->prependFile($this->basePath() . '/admin/js/flot/excanvas.min.js'                           , 'text/javascript', array('conditional' => 'lt IE 9',))
    ; ?>

    <?php echo $this->headLink
                    (
                        array
                        (
                            'rel'   =>  'shortcut icon',
                            'type'  =>  'image/vnd.microsoft.icon',
                            'href'  =>  $this->basePath() . '/application/images/favicon.ico'
                        )
                    )
                    ->prependStylesheet('http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700')
                    ->prependStylesheet($this->basePath() . '/admin/css/animatecss/animate.min.css')
                    ->prependStylesheet($this->basePath() . '/admin/js/uniform/css/uniform.default.min.css')
                    ->prependStylesheet($this->basePath() . '/admin/js/bootstrap-daterangepicker/daterangepicker-bs3.css')
                    ->prependStylesheet($this->basePath() . '/admin/font-awesome/css/font-awesome.min.css')
                    ->prependStylesheet($this->basePath() . '/admin/css/cloud-admin.css')
    ?>






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
								<a href="<?php echo $this->url('home'); ?>"><img src="<?php echo $this->basePath(); ?>/admin/img/logo/notarytoolz-logo.png" height="60" alt="logo name" /></a>
							</div>
						</div>
					</div>
				</div>
				<!--/NAV-BAR -->
			</header>
			<!--/HEADER -->
			<!-- LOGIN -->
			<section id="verification-details" class="visible">
				<div class="container">
					<div class="row">
						<div class="col-md-4 col-md-offset-4">
							<div class="login-box-plain">
								<h2 class="bigintro">Verification Details</h2>
								<div class="center">
									<strong>Your Email Address is Verified.</strong><br>
									<strong>Please, personalize your account to access your Member Section.</strong>
								</div>

                                <?php if($VerificationDetailsFormMessages != ''): ?>
                                <div class="alert alert-block alert-danger fade in">
                                    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                    <h4><i class="fa fa-times"></i> Oh snap! You got an error!</h4>
                                    <?php
                                        echo $this->formElementErrors()
                                                ->setMessageOpenFormat('<ul><li>')
                                                ->setMessageSeparatorString('</li><li>')
                                                ->setMessageCloseString('</li></ul>')
                                                ->render($VerificationDetailsForm)
                                        ;
                                    ?>
                                </div>
                                <?php endif; ?>


								<div class="divide-40"></div>

                                    <?php

                                        echo $this->form()->openTag($VerificationDetailsForm);
                                        echo $this->formHidden($VerificationDetailsForm->get('verification_details_csrf'));
                                        echo $this->formHidden($VerificationDetailsForm->get('vcode'));
                                    ?>
                                        <div class="form-group">
                                            <?php echo $this->formLabel($VerificationDetailsForm->get('first_name')); ?>
                                            <?php echo $this->formInput($VerificationDetailsForm->get('first_name')); ?>
                                        </div>
                                        <div class="form-group">
                                            <?php echo $this->formLabel($VerificationDetailsForm->get('last_name')); ?>
                                            <?php echo $this->formInput($VerificationDetailsForm->get('last_name')); ?>
                                        </div>
                                        <div class="form-group">
                                            <?php echo $this->formLabel($VerificationDetailsForm->get('gender')); ?>
                                            <?php echo $this->formSelect($VerificationDetailsForm->get('gender')); ?>
                                        </div>
                                        <div class="form-group">
                                            <?php echo $this->formLabel($VerificationDetailsForm->get('member_type')); ?>
                                            <?php echo $this->formSelect($VerificationDetailsForm->get('member_type')); ?>
                                        </div>
                                        <div class="form-group">
                                            <?php echo $this->formLabel($VerificationDetailsForm->get('zipcode')); ?>
                                            <?php echo $this->formInput($VerificationDetailsForm->get('zipcode')); ?>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" class="btn-lg btn-primary">Personalize</button>
                                        </div>
                                    <?php
                                        echo $this->form()->closeTag($VerificationDetailsForm);
                                    ?>

							</div>
						</div>
					</div>
				</div>
			</section>
			<!--/LOGIN -->
	</section>
	<!--/PAGE -->

    <?php echo $this->headScript()
        ->prependFile($this->basePath() . '/admin/js/script.js'                                                          , 'text/javascript', array())
        ->prependFile($this->basePath() . '/admin/js/uniform/jquery.uniform.min.js'                                      , 'text/javascript', array())
        ->prependFile($this->basePath() . '/admin/bootstrap-dist/js/bootstrap.min.js'                              , 'text/javascript', array())
        ->prependFile($this->basePath() . '/admin/js/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js'    , 'text/javascript', array())
        ->prependFile($this->basePath() . '/admin/js/jquery/jquery-2.0.3.min.js'                                         , 'text/javascript', array())
    ; ?>

    <script>
		jQuery(document).ready(function() {
			App.setPage("verification-details");  //Set current page
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