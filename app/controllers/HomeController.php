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

	public function getIndex()
	{
		if (Agent::isMobile()) { // Mobile Detect
			return View::make('home.mobile');
		} else {
			return View::make('home.index');
		}
	}

	/**
	 * Articles category list
	 * @return Respanse
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