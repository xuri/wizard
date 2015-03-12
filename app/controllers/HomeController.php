<?php

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP Version 5.5
 */

/**
 * Class for website home page, and CMS system.
 *
 * @uses 		Laravel The PHP frameworks for web artisans http://laravel.com
 * @author 		Ri Xu http://xuri.me <xuri.me@gmail.com>
 * @copyright 	Copyright (c) Harbin Wizard Techonlogy Co., Ltd.
 * @link 		http://www.jinglingkj.com
 * @license   	Licensed under The MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 	Release: 0.1 2014-12-25
 */

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/
	/**
	 * Home page of website
	 * @return response Rendering home page view
	 */
	public function getIndex()
	{
		// Mobile Detect
		if (Agent::isMobile()) {
			return View::make('home.mobilev2');
		} else {

			// Language select
			$language		= Input::get('lang');

			if($language) {

				// Set language
				Session::put('language', $language);
			}

			$language = Session::get('language', Config::get('app.locale'));
			App::setlocale($language);
			return View::make('home.indexv2')->with(compact('language'));
		}
	}

	/**
	 * Articles category list
	 * @param integer $category_id Category ID in CMS system
	 * @return response
	 */
	public function getCategory($category_id)
	{
		$articles   = Article::where('category_id', $category_id)->orderBy('created_at', 'desc')->paginate(5);
		$categories = Category::orderBy('sort_order')->get();
		return View::make('blog.categoryArticles')->with(compact('articles', 'categories', 'category_id'));
	}

	/**
	 * Article show page
	 * @param  string $slug
	 * @return response
	 */
	public function getShow($slug)
	{
		$article    = Article::where('slug', $slug)->first();
		is_null($article) AND App::abort(404);
		$categories = Category::orderBy('sort_order')->get();
		return View::make('home.show')->with(compact('article', 'categories'));
	}

}