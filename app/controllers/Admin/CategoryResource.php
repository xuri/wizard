<?php

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP Version 5.5
 */

/**
 * Class for website CMS system, create, update and destory posts category
 *
 * @uses        Laravel The PHP frameworks for web artisans http://laravel.com
 * @author      Ri Xu http://xuri.me <xuri.me@gmail.com>
 * @copyright   Copyright (c) Harbin Wizard Techonlogy Co., Ltd.
 * @link        http://www.jinglingkj.com
 * @license     Licensed under The MIT License http://www.opensource.org/licenses/mit-license.php
 * @version     Release: 0.1 2014-12-25
 */

class Admin_CategoryResource extends BaseResource
{
    /**
     * Define recource view
     * @var string
     */
    protected $resourceView = 'admin.category';

    /**
     * Resource model name, after initialization into model instances
     * @var string|Illuminate\Database\Eloquent\Model
     */
    protected $model = 'Category';

    /**
     * Resource identification
     * @var string
     */
    protected $resource = 'categories';

    /**
     * Resource table
     * @var string
     */
    protected $resourceTable = 'article_categories';

    /**
     * Resource name
     * @var string
     */
    protected $resourceName = '文章分类';

    /**
     * Custom validation message
     * @var array
     */
    protected $validatorMessages = array(
        'name.required'       => '请填写分类名称。',
        'name.unique'         => '已有同名分类。',
        'sort_order.required' => '请填写分类排序。',
        'sort_order.integer'  => '请填写一个整数。',
    );

    /**
     * Resource index view
     * GET         /resource
     * @return Response
     */
    public function index()
    {
        $datas = $this->model->orderBy('sort_order')->paginate(15);
        return View::make($this->resourceView.'.index')->with(compact('datas'));
    }

    /**
     * Resource create action
     * POST        /resource
     * @return Response
     */
    public function store()
    {
        // Get all form data
        $data   = Input::all();
        // 创建验证规则
        $unique = $this->unique();
        $rules  = array(
            'name'       => 'required|'.$unique,
            'sort_order' => 'required|integer',
        );
        // 自定义验证消息
        $messages = $this->validatorMessages;
        // 开始验证
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->passes()) {
            // 验证成功
            // 添加资源
            $model = $this->model;
            $model->name       = e($data['name']);
            $model->sort_order = e($data['sort_order']);
            if ($model->save()) {
                // 添加成功
                return Redirect::back()
                    ->with('success', '<strong>'.$this->resourceName.'添加成功：</strong>您可以继续添加新'.$this->resourceName.'，或返回'.$this->resourceName.'列表。');
            } else {
                // 添加失败
                return Redirect::back()
                    ->withInput()
                    ->with('error', '<strong>'.$this->resourceName.'添加失败。</strong>');
            }
        } else {
            // 验证失败
            return Redirect::back()->withInput()->withErrors($validator);
        }
    }

    /**
     * 资源编辑动作
     * PUT/PATCH   /resource/{id}
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        // 获取所有表单数据.
        $data = Input::all();
        // 创建验证规则
        $rules = array(
            'name'       => 'required|'.$this->unique('name', $id),
            'sort_order' => 'required|integer',
        );
        // 自定义验证消息
        $messages  = $this->validatorMessages;
        // 开始验证
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->passes()) {
            // 验证成功
            // 更新资源
            $model = $this->model->find($id);
            $model->name       = e($data['name']);
            $model->sort_order = e($data['sort_order']);
            if ($model->save()) {
                // 更新成功
                return Redirect::back()
                    ->with('success', '<strong>'.$this->resourceName.'更新成功：</strong>您可以继续编辑'.$this->resourceName.'，或返回'.$this->resourceName.'列表。');
            } else {
                // 更新失败
                return Redirect::back()
                    ->withInput()
                    ->with('error', '<strong>'.$this->resourceName.'更新失败。</strong>');
            }
        } else {
            // 验证失败
            return Redirect::back()->withInput()->withErrors($validator);
        }
    }


}
