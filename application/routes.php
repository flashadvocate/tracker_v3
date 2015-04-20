<?php

// maintenance mode
// Flight::route('*', array('ApplicationController', '_unavailable'));

if (empty($_SESSION['userid'])) {

	Flight::route('/', array('UserController', '_login'));
	Flight::route('/register', array('UserController', '_register'));
	Flight::route('POST /do/login', array('UserController', '_doLogin'));
	Flight::route('POST /do/register', array('UserController', '_doRegister'));
	Flight::route('/invalid-login', array('ApplicationController', '_invalidLogin'));
	Flight::route('POST /do/online-list', array('ApplicationController', '_doUsersOnline'));

} else {

	// api stuff
	Flight::route('/get/member-data/division/@game', array('MemberController', '_getMemberData'));
	Flight::route('/get/division-structure', array('DivisionController', '_generateDivisionStructure'));

	// user views
	Flight::route('/', array('ApplicationController', '_index'));
	Flight::route('/logout', array('UserController', '_doLogout'));
	Flight::route('/help', array('ApplicationController', '_help'));
	Flight::route('/recruiting', array('RecruitingController', '_index'));
	Flight::route('/recruiting/new-member', array('RecruitingController', '_addNewMember'));

	// manage
	Flight::route('/manage/inactive-members', array('DivisionController', '_manage_inactives'));

	// view
	Flight::route('/divisions/@div', array('DivisionController', '_index'));
	Flight::route('/divisions/@div/@plt', array('PlatoonController', '_index'));
	Flight::route('/member/@id', array('MemberController', '_profile'));

	// updates
	Flight::route('POST /do/search-members', array('ApplicationController', '_doSearch'));
	Flight::route('POST /do/online-list', array('ApplicationController', '_doUsersOnline'));
	Flight::route('POST /do/update-alert', array('ApplicationController', '_doUpdateAlert'));
	Flight::route('POST /do/update-member', array('MemberController', '_doUpdateMember'));
	Flight::route('POST /do/validate-member', array('MemberController', '_doValidateMember'));
	Flight::route('POST /do/add-member', array('MemberController', '_doAddMember'));
	Flight::route('POST /do/update-flag', array('MemberController', '_doUpdateFlag'));

	// modals
	Flight::route('POST /edit/member', array('MemberController', '_edit'));

	// cURLS
	Flight::route('POST /do/check-division-threads', array('RecruitingController', '_doDivisionThreadCheck'));

	/*	
	Flight::route('/settings', array('UserController', '_settings'));

	// view screens
	Flight::route('/member/[0-9]+', array('MemberController', '_profile'));
	
	

	// manage
	
	Flight::route('/manage/division', array('DivisionController', '_manage_division'));
	Flight::route('/manage/loas', array('DivisionController', '_manage_loas'));


	// admin
	Flight::route('/admin', array('AdminController', '_show'));
	*/

	// update user activity
	if (isset($_SESSION['userid'])) {
		User::updateActivityStatus($_SESSION['userid']);
	}
}

// 404 redirect
Flight::map('notFound', array('ApplicationController', '_404'));

// graphics
Flight::route('/stats/top10/@division/division.png', array('GraphicsController', '_generateDivisionTop10'));