<?php
/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @uses 		Laravel The PHP frameworks for web artisans http://laravel.com
 * @author 		Ri Xu http://xuri.me
 * @copyright 	Copyright (c) Harbin Wizard Techonlogy Co., Ltd.
 * @link 		http://www.jinglingkj.com
 * @since  		25th Nov, 2014
 * @license   	Licensed under The MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 	0.1
 */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Homepage Routes
|--------------------------------------------------------------------------
|
*/

Route::group(array('before' => 'cache', 'after' => 'cache'), function () {
	$controller = 'HomeController@';
	# Homepage
	Route::get(            '/', array('as' => 'home'     , 'uses' => $controller.'getIndex'   					));
	Route::get(   'promotion', array('as' => 'promotion' , 'uses' => 'Admin_SupportResource@promotionPublic'	));

	// Articles
	Route::group(array('prefix' => 'article'), function () use ($controller) {
		# Articles Catrgory
		Route::get('/category/{id}', array('as' => 'category' , 'uses' => $controller.'getCategory' ));
		# Show Article
		Route::get(        '{slug}', array('as' => 'show'     , 'uses' => $controller.'getShow' 	));
	});


});

/*
|--------------------------------------------------------------------------
| WAP Routes
|--------------------------------------------------------------------------
|
*/
Route::group(array('prefix' => 'wap', 'before' => 'is.desktop'), function () {
	$controller = 'WapController@';
	# Index
	Route::get(					'/', array('as' => 'wap.index'	, 'uses' => $controller.'getIndex'		));
	Route::get(			 '/members', array('as' => 'wap.members', 'uses' => $controller.'getMembers'	));
	Route::get(		'/members/{id}', array('as' => 'wap.show'	, 'uses' => $controller.'getShow'		));
	Route::get(				'/more', array('as' => 'wap.more'	, 'uses' => $controller.'getMore'		));
	Route::get(			 '/success', array('as' => 'wap.success', 'uses' => $controller.'getSuccess'	));
});


/*
|--------------------------------------------------------------------------
| Basic Competence (Signin and Signup Routes)
|--------------------------------------------------------------------------
|
*/

Route::group(array('prefix' => 'auth', 'before' => 'cache|is.desktop', 'after' => 'cache'), function () {
	$Authority = 'AuthorityController@';

	# Signout
	Route::get('signout', array('as' => 'signout', 'uses' => $Authority.'getSignout'));

	# Route Group
	Route::group(array('before' => 'cache|guest', 'after' => 'cache'), function () use ($Authority) {
		# Signin
		Route::get('signin' 					, array('as' => 'signin'		, 'uses' => $Authority.'getSignin'			));
		Route::post('signin'					, $Authority.'postSignin');
		# Signup
		Route::get('signup' 					, array('as' => 'signup'        , 'uses' => $Authority.'getSignup'			));
		Route::post('signup'					, $Authority.'postSignup');
		# Signup Success
		Route::get('success/{email}'			, array('as' => 'signupSuccess' , 'uses' => $Authority.'getSignupSuccess'	));
		# Activation Account
		Route::get('activate/{activationCode}'	, array('as' => 'activate'		, 'uses' => $Authority.'getActivate'		));
		# Forgot password
		Route::get('forgot-password'			, array('as' => 'forgotPassword', 'uses' => $Authority.'getForgotPassword'	));
		Route::post('forgot-password'			, $Authority.'postForgotPassword');
		# Reset password
		Route::get('forgot-password/{token}'	, array('as' => 'reset'         , 'uses' => $Authority.'getReset'			));
		Route::post('forgot-password/{token}'	, $Authority.'postReset');
		# SMS Verify
		Route::post('verifycode'				, array('as' => 'verifycode'	, 'uses' => $Authority.'postVerifyCode'		));
		Route::post('postsmsreset'				, array('as' => 'postsmsreset'  , 'uses' => $Authority.'postSMSReset'		));
		# Captcha
		Route::post('captcha'					, array('as' => 'captcha'		, 'uses' => $Authority.'postCaptcha'			));
	});
});

/*
|--------------------------------------------------------------------------
| Members Routes
|--------------------------------------------------------------------------
|
*/

Route::group(array('prefix' => 'members', 'before' => 'cache|is.desktop|auth|auth.activated', 'after' => 'cache'), function () {
	$resource   = 'members';
	$controller = 'MemberController@';
	# Get index
	Route::get(            '/', array('as' => $resource.'.index'   , 'uses' => $controller.'index'	));
	Route::get(         '{id}', array('as' => $resource.'.show'    , 'uses' => $controller.'show'	));
	Route::post(   		'{id}', $controller.'like');
});

/*
|--------------------------------------------------------------------------
| Support Routes
|--------------------------------------------------------------------------
|
*/

Route::group(array('prefix' => 'support', 'before' => 'cache|is.desktop|auth|auth.activated', 'after' => 'cache'), function () {
	$resource   = 'support';
	$controller = 'SupportController@';
	# Get index
	Route::get(            '/', array('as' => $resource.'.index'   , 'uses' => $controller.'index'	));
	Route::post(           '/', array('as' => $resource.'.create'  , 'uses' => $controller.'create' ));
});

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
|
*/

Route::group(array('prefix' => 'account', 'before' => 'cache|is.desktop|auth|auth.activated', 'after' => 'cache'), function () {
	$Account = 'AccountController@';
	# Account Index
	Route::get('/'							, array('as' => 'account',						'uses' => $Account.'getIndex'				));
	# Complete
	Route::get('complete'					, array('as' => 'account.complete',				'uses' => $Account.'getComplete'			));
	Route::post('checkcomplete'				, array('as' => 'checkcomplete', 				'uses' => $Account.'checkComplete'			));
	Route::post('complete'					, $Account.'postComplete');
	# Post university
	Route::post('postuniversity'			, array('as' => 'postuniversity',				'uses' => $Account.'postUniversity'			));
	Route::post('postrenew'					, array('as' => 'postrenew',					'uses' => $Account.'postRenew'				));
	# Like other user
	Route::get('sent'						, array('as' => 'account.sent',					'uses' => $Account.'getSent'				));
	# Other user like me
	Route::get('inbox'						, array('as' => 'account.inbox',				'uses' => $Account.'getInbox'				));
	# Notifications center
	Route::get('notifications'				, array('as' => 'account.notifications',		'uses' => $Account.'getNotifications'		));
	Route::get('notifications/ajax/{type}'	, array('as' => 'account.notifications.type',	'uses' => $Account.'getNotificationsType'	))->where('type', 'first|second|third');
	# Posts in forum
	Route::get('posts'						, array('as' => 'account.posts',				'uses' => $Account.'getPosts'				));
	Route::post('posts'						, $Account.'postDeleteForumPost');
});

/*
|--------------------------------------------------------------------------
| Forum Routes
|--------------------------------------------------------------------------
|
*/

Route::group(array('prefix' => 'forum', 'before' => 'cache|is.desktop|auth|auth.activated', 'after' => 'cache'), function () {
	$resource = 'forum';
	$controller = 'ForumController@';
	# Forum Type
	Route::get('/ajax/{type}'	, array('as' => $resource.'.type',					'uses' => $controller.'getForumType' ))->where('type', 'first|second|third');
	# Forum Index
	Route::get('/'				, array('as' => $resource.'.index',					'uses' => $controller.'getIndex'	));
	Route::post('/'				, $controller.'postNew');
	Route::get('{id}'			, array('as' => $resource.'.show',					'uses' => $controller.'getShow'		));
	Route::post('{id}'			, $controller.'postComment');

});

/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
*/

Route::group(array('prefix' => 'admin', 'before' => 'cache|is.desktop|auth|auth.activated|admin', 'after' => 'cache'), function () {
	$Admin = 'AdminController@';

	# Admin Index
	Route::get('/'				, array('as' => 'admin'		  , 'uses' => $Admin . 'getIndex'));

	# Server Index
	Route::get('/server'		, array('as' => 'admin.server', 'uses' => $Admin . 'getServer'));

	# User Managment
	Route::group(array('prefix' => 'users'), function () {
		$resource   = 'users';
		$controller = 'Admin_UserResource@';
		Route::get(  		      '/', array('as' => $resource.'.index'  	, 'uses' => $controller.'index'  	));
		Route::get(			 'create', array('as' => $resource.'.create' 	, 'uses' => $controller.'create' 	));
		Route::get(			  'batch', array('as' => $resource.'.batch' 	, 'uses' => $controller.'batch' 	));
		Route::post(		  'batch', array('as' => $resource.'.batchadd' 	, 'uses' => $controller.'batchadd' 	));
		Route::post( 		 'create', array('as' => $resource.'.store'  	, 'uses' => $controller.'store'  	));
		Route::get(	   '{id}/chatdir', array('as' => $resource.'.chatdir'	, 'uses' => $controller.'chatdir'	));
		Route::get('/chatrecord/{id}', array('as' => $resource.'.chatrecord', 'uses' => $controller.'chatrecord'));
		Route::get(		   'noactive', array('as' => $resource.'.noactive'	, 'uses' => $controller.'noactive'	));
		Route::get(		  '{id}/edit', array('as' => $resource.'.edit'   	, 'uses' => $controller.'edit'   	))->before('not.self');
		Route::get(	    '{id}/detail', array('as' => $resource.'.detail' 	, 'uses' => $controller.'detail' 	))->before('not.self');
		Route::post(	  '{id}/edit', array('as' => $resource.'.update' 	, 'uses' => $controller.'update' 	))->before('not.self');
		Route::get(     '{id}/notify', array('as' => $resource.'.notify' 	, 'uses' => $controller.'notify' 	))->before('not.self');
		Route::get( '{id}/sms_notify', array('as' => $resource.'.sms_notify', 'uses' => $controller.'sms_notify'))->before('not.self');
		Route::delete(		   '{id}', array('as' => $resource.'.destroy'	, 'uses' => $controller.'destroy'	))->before('not.self');
		Route::post(    '{id}/notify', $controller.'postNotify' )->before('not.self');
		Route::post(   	 	 '/block', $controller.'block' 		)->before('not.self');
		Route::post(   	   '/unclock', $controller.'unclock'	)->before('not.self');
	});

	# Data Analytics
	Route::group(array('prefix' => 'analytics'), function () {
		$resource   = 'analytics';
		$controller = 'Admin_AnalyticsResource@';
		Route::get(  	   'userform', array('as'	=> $resource.'.userform'   , 'uses' => $controller.'userForm'   ));
		Route::get(  	  'forumform', array('as'	=> $resource.'.forumform'  , 'uses' => $controller.'forumForm'  ));
		Route::get(  	   'likeform', array('as'	=> $resource.'.likeform'   , 'uses' => $controller.'likeForm'   ));
		Route::get(      'usercharts', array('as'	=> $resource.'.usercharts' , 'uses' => $controller.'userCharts' ));
		Route::get(     'forumcharts', array('as'	=> $resource.'.forumcharts', 'uses' => $controller.'forumCharts'));
		Route::get(      'likecharts', array('as'	=> $resource.'.likecharts' , 'uses' => $controller.'likeCharts' ));
	});

	# Forum Management
	Route::group(array('prefix' => 'forum'), function () {
		$resource   = 'admin.forum';
		$controller = 'Admin_ForumResource@';
		Route::get(  		     '/', array('as' => $resource.'.index'   , 'uses' => $controller.'index'   	 ));
		Route::get(    '/block/{id}', array('as' => $resource.'.block'   , 'uses' => $controller.'block'	 ));
		Route::get(   '/unlock/{id}', array('as' => $resource.'.unlock'  , 'uses' => $controller.'unlock' 	 ));
		Route::get(	     '/top/{id}', array('as' => $resource.'.top' 	 , 'uses' => $controller.'top' 		 ));
		Route::get(	   '/untop/{id}', array('as' => $resource.'.untop' 	 , 'uses' => $controller.'untop'	 ));
		Route::delete(		  '{id}', array('as' => $resource.'.destroy' , 'uses' => $controller.'destroy'	 ));
	});

	# Support Management
	Route::group(array('prefix' => 'support'), function () {
		$resource   = 'admin.support';
		$controller = 'Admin_SupportResource@';
		Route::get(  		     '/', array('as' => $resource.'.index'   	, 'uses' => $controller.'index'   	 ));
		Route::get(  	'/promotion', array('as' => $resource.'.promotion'  , 'uses' => $controller.'promotion'  ));
		Route::get(  	'/show/{id}', array('as' => $resource.'.show'    	, 'uses' => $controller.'show'   	 ));
		Route::get(     '/read/{id}', array('as' => $resource.'.read'    	, 'uses' => $controller.'read'		 ));
		Route::get(   '/unread/{id}', array('as' => $resource.'.unread'  	, 'uses' => $controller.'unread' 	 ));
		Route::delete(		  '{id}', array('as' => $resource.'.destroy' 	, 'uses' => $controller.'destroy'	 ));
	});

	# University Management
	Route::group(array('prefix' => 'university'), function () {
		$resource   = 'admin.university';
		$controller = 'Admin_UniversityResource@';
		Route::get(  		 	   	   '/', array('as' => $resource.'.index'   				, 'uses' => $controller.'index'   	 			));
		Route::get(			  '/open/{id}', array('as' => $resource.'.open'    				, 'uses' => $controller.'open'   	 			));
		Route::get(   		 '/close/{id}', array('as' => $resource.'.close'   				, 'uses' => $controller.'close'		 			));
		Route::get(    		  '/edit/{id}', array('as' => $resource.'.edit'    				, 'uses' => $controller.'edit'	 	 			));
		Route::post( 		  '/edit/{id}', array('as' => $resource.'.update'  				, 'uses' => $controller.'update'				));
		Route::delete(	 			'{id}', array('as' => $resource.'.destroy' 				, 'uses' => $controller.'destroy'	 			));
		Route::get(	'/order_by_users_desc', array('as' => $resource.'.order_by_users_desc'  , 'uses' => $controller.'order_by_users_desc'	));
	});

	# Categories Management
	Route::group(array('prefix' => 'categories'), function () {
		$resource   = 'categories';
		$controller = 'Admin_CategoryResource@';
		Route::get(        '/', array('as' => $resource.'.index'  , 'uses' => $controller.'index'  ));
		Route::get(   'create', array('as' => $resource.'.create' , 'uses' => $controller.'create' ));
		Route::post(       '/', array('as' => $resource.'.store'  , 'uses' => $controller.'store'  ));
		Route::get('{id}/edit', array('as' => $resource.'.edit'   , 'uses' => $controller.'edit'   ));
		Route::put(     '{id}', array('as' => $resource.'.update' , 'uses' => $controller.'update' ));
		Route::delete(  '{id}', array('as' => $resource.'.destroy', 'uses' => $controller.'destroy'));
	});

	# Articles Management
	Route::group(array('prefix' => 'articles'), function () {
		$resource   = 'admin.articles';
		$controller = 'Admin_ArticlesResource@';
		Route::get(  	         '/', array('as' => $resource.'.index'   , 'uses' => $controller.'index'  ));
		Route::get( 	    'create', array('as' => $resource.'.create'  , 'uses' => $controller.'create' ));
		Route::post(  		     '/', array('as' => $resource.'.store'   , 'uses' => $controller.'store'  ));
		Route::get(		 '{id}/edit', array('as' => $resource.'.edit'    , 'uses' => $controller.'edit'   ));
		Route::put(    		  '{id}', array('as' => $resource.'.update'  , 'uses' => $controller.'update' ));
		Route::delete( 		  '{id}', array('as' => $resource.'.destroy' , 'uses' => $controller.'destroy'));
		Route::get(  	'/open/{id}', array('as' => $resource.'.open'    , 'uses' => $controller.'open'   ));
		Route::get(    '/close/{id}', array('as' => $resource.'.close'   , 'uses' => $controller.'close'  ));
	});

});

/*
|--------------------------------------------------------------------------
| Android Routes
|--------------------------------------------------------------------------
|
*/

Route::group(array('prefix' => 'android', 'before' => 'cache', 'after' => 'cache'), function () {
	$controller = 'AndroidController@';
	# Android API
	// Route::get('debug'               , array('as' => 'debug'         , 'uses' => $controller.'getDebug'));
	Route::post('api'              , $controller.'postAndroid');

});

/*
|--------------------------------------------------------------------------
| iOS Routes
|--------------------------------------------------------------------------
|
*/

Route::group(array('prefix' => 'ios', 'before' => 'cache', 'after' => 'cache'), function () {
	$controller = 'AppleController@';
	# iOS API
	Route::post('api', 	$controller . 'postApple');
	// Route::get('api', 	$controller . 'postApple');
});

/*
|--------------------------------------------------------------------------
| System Routes
|--------------------------------------------------------------------------
|
*/

// Route::get('migrate', array('as' => 'migrate',function()
// Route::get('migrate', array('as' => 'migrate', 'before' => 'auth|admin', function()
// {
// 	 return View::make('tools.migrate');
// }));

Route::get('browser_not_support', array('as' => 'browser_not_support', 'before' => 'cache', 'after' => 'cache', function()
{
	return View::make('system.browserUpdate');
}));