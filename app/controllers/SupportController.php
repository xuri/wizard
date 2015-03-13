<?php

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP Version 5.5
 */

/**
 * Class for custom support.
 *
 * @uses 		Laravel The PHP frameworks for web artisans http://laravel.com
 * @author 		Ri Xu http://xuri.me <xuri.me@gmail.com>
 * @copyright 	Copyright (c) Harbin Wizard Techonlogy Co., Ltd.
 * @link 		http://www.jinglingkj.com
 * @license   	Licensed under The MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 	Release: 0.1 2014-12-25
 */

class SupportController extends BaseController {

	/**
	 * Resource identification
	 * @var string
	 */
	protected $resource = 'support';

	/**
	 * View: Support
	 * @return Response
	 */

	public function index()
	{
		return View::make($this->resource . '.index');
	}

	/**
	 * View: Support
	 * POST /support
	 * @return Response
	 */

	public function create()
	{
		// Get all form data.
		$data = Input::all();
		// Create validation rules
		$rules = array(
			'content'              => 'required|min:5',
		);
		// Custom validation message
		$messages = array(
			'content.required'     => '请填写反馈问题。',
			'content.min'          => '至少要写:min个字哦。',
		);

		// Begin verification
		$validator   = Validator::make($data, $rules, $messages);
		if ($validator->passes()) {
			// Create a new support
			$support			= new Support;
			$support->user_id 	= Auth::user()->id;
			$support->content	= htmlentities(Input::get('content'));
			if($support->save()) {
				// Create successful
				return Redirect::back()
						->withInput()
						->with('success', '您的反馈已经收到，我们会及时为您解决。');
			} else {
				return Redirect::back()
						->withInput()
						->with('error', '提交失败，请尝试重新提交。');
			}
		} else {
			return Redirect::back()
						->withInput()
						->withErrors($validator);
		}
	}

}