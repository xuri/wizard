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

Route::group(array(), function () {
	$controller = 'HomeController@';
	# Homepage
	Route::get(            '/', array('as' => 'home'             , 'uses' => $controller.'getIndex'     ));
});

/*
|--------------------------------------------------------------------------
| Basic Competence (Signin and Signup Routes)
|--------------------------------------------------------------------------
|
*/

Route::group(array('prefix' => 'auth'), function () {
	$Authority = 'AuthorityController@';

	# Signout
	Route::get('signout', array('as' => 'signout', 'uses' => $Authority.'getSignout'));

	# Route Group
	Route::group(array('before' => 'guest'), function () use ($Authority) {
		# Signin
		Route::get('signin'                   , array('as' => 'signin'        , 'uses' => $Authority.'getSignin'        ));
		Route::post('signin'                  , $Authority.'postSignin');
		# Signup
		Route::get('signup'                   , array('as' => 'signup'        , 'uses' => $Authority.'getSignup'        ));
		Route::post('signup'                  , $Authority.'postSignup');
		# Signup Success
		Route::get('success/{email}'          , array('as' => 'signupSuccess' , 'uses' => $Authority.'getSignupSuccess' ));
		# Activation Account
		Route::get('activate/{activationCode}', array('as' => 'activate'      , 'uses' => $Authority.'getActivate'      ));
		# Forgot password
		Route::get('forgot-password'          , array('as' => 'forgotPassword', 'uses' => $Authority.'getForgotPassword'));
		Route::post('forgot-password'         , $Authority.'postForgotPassword');
		# Reset password
		Route::get('forgot-password/{token}'  , array('as' => 'reset'         , 'uses' => $Authority.'getReset'         ));
		Route::post('forgot-password/{token}' , $Authority.'postReset');
		# SMS Verify
		Route::post('verifycode'			  , array('as' => 'verifycode'	  , 'uses' => $Authority.'postVerifyCode'	));
		Route::post('postsmsreset'			  , array('as' => 'postsmsreset'  , 'uses' => $Authority.'postSMSReset'		));
	});
});

/*
|--------------------------------------------------------------------------
| Members Routes
|--------------------------------------------------------------------------
|
*/

Route::group(array('prefix' => 'members', 'before' => 'auth'), function () {
	$resource   = 'members';
	$controller = 'MemberController@';
	# Get index
	Route::get(            '/', array('as' => $resource.'.index'   , 'uses' => $controller.'index'	));
	Route::get(         '{id}', array('as' => $resource.'.show'    , 'uses' => $controller.'show'	));
	Route::post(   '{id}', $controller.'like');
});

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
|
*/

Route::group(array('prefix' => 'account', 'before' => 'auth'), function () {
	$Account = 'AccountController@';
	# Account Index
	Route::get('/'				, array('as' => 'account',			'uses' => $Account.'getIndex'		));
	# Complete
	Route::get('complete'		, array('as' => 'account.complete',	'uses' => $Account.'getComplete'	));
	Route::post('complete'		, $Account.'postComplete');
	# Post university
	Route::post('postuniversity', array('as' => 'postuniversity',	'uses' => $Account.'postUniversity'	));
	Route::post('postrenew'		, array('as' => 'postrenew',		'uses' => $Account.'postRenew'		));
	# Like other user
	Route::get('sent'			, array('as' => 'account.sent',		'uses' => $Account.'getSent'		));
	# Other user like me
	Route::get('inbox'			, array('as' => 'account.inbox',	'uses' => $Account.'getInbox'		));


});

/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
*/

Route::group(array('prefix' => 'admin', 'before' => 'auth|admin'), function () {
	$Admin = 'AdminController@';
	# Admin index
	Route::get('/', array('as' => 'admin', 'uses' => $Admin.'getIndex'));

});

/*
|--------------------------------------------------------------------------
| Android Routes
|--------------------------------------------------------------------------
|
*/

Route::group(array('prefix' => 'android'), function () {
	$controller = 'AndroidController@';

	# Account Index
	Route::get('debug'               , array('as' => 'debug'         , 'uses' => $controller.'getDebug'));
	Route::post('debug'              , $controller.'postAndroid');

});

/*
|--------------------------------------------------------------------------
| System Routes
|--------------------------------------------------------------------------
|
*/

Route::get('migrate', array('as' => 'migrate',function()
// Route::get('migrate', array('as' => 'migrate', 'before' => 'auth|admin', function()
{
	 return View::make('tools.migrate');
}));

// Route::get('browser_not_support', array('as' => 'browser_not_support', function()
// {
//     return View::make('system.browserUpdate');
// }));

// App::missing(function($exception)
// {
//     return Response::view('system.missing', array(), 404);
// });

// App::error(function($exception)
// {
//     return Response::view('system.missing', array(), 404);
// });