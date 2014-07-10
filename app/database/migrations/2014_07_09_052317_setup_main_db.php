<?php
 /**
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/9/14 5:23 AM
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetupMainDb extends Migration
{
    protected $connection   =   "main_db";

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        /**
         * Entity: SiteUser
         * Table: site_users
         */
		Schema::dropIfExists('site_users');
		Schema::create('site_users', function($table)
        {
            // Parameters
            $table->engine = 'InnoDB';

            // Columns
            $table->increments('id');
            $table->integer('member_id')->default(0);
            $table->string('agent', 240)->default('');
            $table->integer('ip_address')->default(0);
            $table->enum('user_status', array
                                        (
                                            'Open',
                                            'Locked:Excessive-Login-Attempts',
                                            'Locked:Excessive-Signup-Attempts',
                                            'Locked:Excessive-ForgotLogin-Attempts',
                                            'Locked:Excessive-ChangeVerifiedLinkPassword-Attempts',
                                            'Locked:Excessive-ChangeOldPassword-Attempts',
                                            'Locked:Excessive-LostSignupVerification-Attempts'
                                        ))->default('Open');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(array('id', 'member_id'));
        });

        /**
         * Entity: SiteHit
         * Table: site_hits
         */
		Schema::dropIfExists('site_hits');
		Schema::create('site_hits', function($table)
        {
            // Parameters
            $table->engine = 'InnoDB';

            // Columns
            $table->increments('id');
            $table->integer('user_id')->default(0);
            $table->text('cookies');
            $table->string('url_location', 255)->default('');
            $table->integer('client_time')->default(0);
            $table->integer('server_time')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(array('url_location', 'server_time'));
        });

        /**
         * Entity: Member
         * Table: members
         */
		Schema::dropIfExists('members');
		Schema::create('members', function($table)
        {
            // Parameters
            $table->engine = 'InnoDB';

            // Columns
            $table->increments('id');
            $table->enum('member_type', array
                                        (
                                            'unknown',
                                            'vendor',
                                            'freelancer',
                                            'employee',
                                            'report-viewer',
                                        ));
            $table->string('login_credentials', 256);
            $table->string('salt1', 32);
            $table->string('salt2', 32);
            $table->string('salt3', 32);
            $table->integer('paused')->default(0);
            $table->integer('cancelled')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(array('salt1', 'salt2', 'salt3'));
        });

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        /**
         * Entity: SiteUser
         * Table: site_user
         */
		Schema::dropIfExists('site_user');
        /**
         * Entity: SiteHit
         * Table: site_hits
         */
        Schema::dropIfExists('site_hit');
        /**
         * Entity: Member
         * Table: members
         */
        Schema::dropIfExists('members');
	}

}
