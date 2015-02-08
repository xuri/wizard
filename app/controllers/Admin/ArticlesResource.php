<?php

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP Version 5.5
 */

/**
 * Class for website CMS system, create, update and destory posts.
 *
 * @uses 		Laravel The PHP frameworks for web artisans http://laravel.com
 * @author 		Ri Xu http://xuri.me <xuri.me@gmail.com>
 * @copyright 	Copyright (c) Harbin Wizard Techonlogy Co., Ltd.
 * @link 		http://www.jinglingkj.com
 * @license   	Licensed under The MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 	Release: 0.1 2014-12-25
 */

class Admin_ArticlesResource extends BaseResource
{
	/**
	 * Resource view directory
	 * @var string
	 */
	protected $resourceView = 'admin.article';

	/**
	 * Model name of the resource, after initialization to a model instance
	 * @var string|Illuminate\Database\Eloquent\Model
	 */
	protected $model = 'Article';

	/**
	 * Resource identification
	 * @var string
	 */
	protected $resource = 'admin.articles';

	/**
	 * Resource database tables
	 * @var string
	 */
	protected $resourceTable = 'article';

	/**
	 * Resource name (Chinese)
	 * @var string
	 */
	protected $resourceName = '文章';

	/**
	 * Custom validation message
	 * @var array
	 */
	protected $validatorMessages = array(
		'title.required'		=> '请填写文章标题。',
		'title.unique'			=> '已有同名文章。',
		'slug.required'			=> '请填写文章 sulg。',
		'slug.unique'			=> '已有同名 sulg。',
		'content.required'		=> '请填写文章内容。',
		'category.exists'		=> '请填选择正确的文章分类。',
		'thumbnails.mimes'		=> '请上传 :values 格式的图片。',
		'thumbnails.max'		=> '图片的大小请控制在 1M 以内。',
	);

	/**
	 * Resource list view
	 * GET         /resource
	 * @return Response
	 */
	public function index()
	{
		// 获取排序条件
		$orderColumn = Input::get('sort_up', Input::get('sort_down', 'created_at'));
		$direction   = Input::get('sort_up') ? 'asc' : 'desc' ;
		// 获取搜索条件
		switch (Input::get('target')) {
			case 'title':
				$title = Input::get('like');
				break;
		}
		// 构造查询语句
		$query = $this->model->orderBy($orderColumn, $direction);
		isset($title) AND $query->where('title', 'like', "%{$title}%");
		$datas = $query->paginate(15);
		return View::make($this->resourceView.'.index')->with(compact('datas'));
	}

	/**
	 * Resource create views
	 * GET         /resource/create
	 * @return Response
	 */
	public function create()
	{
		$categoryLists = Category::lists('name', 'id');
		return View::make($this->resourceView.'.create')->with(compact('categoryLists'));
	}

	/**
	 * Resource store action
	 * POST        /resource
	 * @return Response
	 */
	public function store()
	{
		// Get all input data
		$data   = Input::all();
		// 创建验证规则
		$unique = $this->unique();
		$rules  = array(
			'title'			=> 'required|'.$unique,
			'slug'			=> 'required|'.$unique,
			'content'		=> 'required',
			'thumbnails'	=> 'mimes:jpg,jpeg,gif,png|max:1024',
			'category'		=> 'exists:article_categories,id',
		);
		// Cusom validation messages
		$messages = $this->validatorMessages;
		// Validation
		$validator = Validator::make($data, $rules, $messages);
		if ($validator->passes()) {
			// Verification success
			// Create resource
			$model						= $this->model;
			$model->user_id				= Auth::user()->id;
			$model->category_id			= $data['category'];
			$model->title				= e($data['title']);
			$model->slug				= e($data['slug']);
			$model->content				= $data['content'];
			$model->meta_title			= e($data['meta_title']);
			$model->meta_description	= e($data['meta_description']);
			$model->meta_keywords		= e($data['meta_keywords']);

			$image						= Input::file('thumbnails');
			if($image) {
				// Get real extension file name
				$ext				= $image->guessClientExtension();

				// Full file name, include extension file name
				$fullname			= $image->getClientOriginalName();

				// Hash processed file name, include extension file name
				$hashname			= date('H.i.s').'-'.md5($fullname).'.'.$ext;
				$model->thumbnails	= $hashname;
				$thumbnails			= Image::make($image->getRealPath());
				$thumbnails->fit(320, 140)->save(public_path('upload/thumbnails/'.$hashname));
			}

			if ($model->save()) {
				//  Store success
				return Redirect::back()
					->with('success', '<strong>'.$this->resourceName.'添加成功：</strong>您可以继续添加新'.$this->resourceName.'，或返回'.$this->resourceName.'列表。');
			} else {
				// Store fail
				return Redirect::back()
					->withInput()
					->with('error', '<strong>'.$this->resourceName.'添加失败。</strong>');
			}
		} else {
			// Validation fail
			return Redirect::back()->withInput()->withErrors($validator);
		}
	}

	/**
	 * Resource edit view
	 * GET         /resource/{id}/edit
	 * @param  integer  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$data			= $this->model->find($id);
		$categoryLists	= Category::lists('name', 'id');
		return View::make($this->resourceView.'.edit')->with(compact('data', 'categoryLists'));
	}

	/**
	 * Resource update action
	 * PUT/PATCH   /resource/{id}
	 * @param  integer  $id
	 * @return Response
	 */
	public function update($id)
	{
		// Get all form input data
		$data = Input::all();
		// Create validation rules
		$rules = array(
			'title'			=> 'required|'.$this->unique('title', $id),
			'slug'			=> 'required|'.$this->unique('slug', $id),
			'content'		=> 'required',
			'thumbnails'	=> 'mimes:jpg,jpeg,gif,png|max:1024',
			'category'		=> 'exists:article_categories,id',
		);
		// Custom validation message
		$messages  = $this->validatorMessages;
		// Begin verification
		$validator = Validator::make($data, $rules, $messages);
		if ($validator->passes()) {
			// Verify success
			// Update the resource
			$model = $this->model->find($id);
			$model->category_id      = $data['category'];
			$model->title            = e($data['title']);
			$model->slug             = e($data['slug']);
			$model->content          = $data['content'];
			$model->meta_title       = e($data['meta_title']);
			$model->meta_description = e($data['meta_description']);
			$model->meta_keywords    = e($data['meta_keywords']);

			$image			= Input::file('thumbnails');
			if($image) {
				$oldImage			= $model->thumbnails;

				// Get real extension file name
				$ext				= $image->guessClientExtension();

				// Full file name, include extension file name
				$fullname			= $image->getClientOriginalName();

				// Hash processed file name, include extension file name
				$hashname			= date('H.i.s').'-'.md5($fullname).'.'.$ext;
				$model->thumbnails	= $hashname;
				$thumbnails			= Image::make($image->getRealPath());
				$thumbnails->fit(320, 140)->save(public_path('upload/thumbnails/'.$hashname));
				if($oldImage)
				{
					// Delete old thumbnails
					File::delete(public_path('upload/thumbnails/' . $oldImage));
				}
			}

			if ($model->save()) {
				// Update success
				return Redirect::back()
					->with('success', '<strong>'.$this->resourceName.'更新成功：</strong>您可以继续编辑'.$this->resourceName.'，或返回'.$this->resourceName.'列表。');
			} else {
				// Update fail
				return Redirect::back()
					->withInput()
					->with('error', '<strong>'.$this->resourceName.'更新失败。</strong>');
			}
		} else {
			// Validation fail
			return Redirect::back()->withInput()->withErrors($validator);
		}
	}

	/**
	 * Mark open university
	 * GET /{id}/open
	 * @param integer $id University ID
	 * @return Response     View
	 */
	public function open($id)
	{
		// Retrieve university
		$data			= $this->model->find($id);

		// Mark open university
		$data->status	= 1;

		if (is_null($data)) {
			return Redirect::back()->with('error', '没有找到对应的'.$this->resourceName.'。');
		}
		elseif ($data->save()){
			return Redirect::back()->with('success', $this->resourceName.'发布成功。');
		} else{
			return Redirect::back()->with('warning', $this->resourceName.'发布失败。');
		}
	}

	/**
	 * Close university service
	 * POST /{id}/close
	 * @param integer $id University ID
	 * @return Response     View
	 */
	public function close($id)
	{
		// Retrieve university
		$data			= $this->model->find($id);

		// Close university
		$data->status	= 0;

		if (is_null($data)) {
			return Redirect::back()->with('error', '没有找到对应的'.$this->resourceName.'。');
		}
		elseif ($data->save()){
			return Redirect::back()->with('success', $this->resourceName.'取消发布成功。');
		} else{
			return Redirect::back()->with('warning', $this->resourceName.'取消发布失败。');
		}
	}

}