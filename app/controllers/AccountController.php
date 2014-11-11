<?php

class AccountController extends BaseController
{
    /**
     * Account index
     * @return Response
     */
    public function getIndex()
    {
        return View::make('account.index');
    }

    public function getComplete()
    {
    	$datas = University::orderBy('created_at', 'desc')->get();
        return View::make('account.complete')->with(compact('datas'));
    }

    public function putComplete()
    {
        // Get all form data
        if (Input::get('username')){

        }
        else {
        	return Redirect::back()->with('success', '操作成功。');
        }
        // $info = array(
        //     'username'      => Input::get('username'),
        //     'nickname'      => Input::get('nickname'),
        //     'alipay'        => Input::get('alipay'),
        //     'phone'         => Input::get('phone'),
        //     'bio'           => Input::get('bio'),
        //     'sex'           => Input::get('sex'),
        //     'born_year'     => Input::get('born_year'),
        //     'born_month'    => Input::get('born_month'),
        //     'born_day'      => Input::get('born_day'),
        //     'province'      => Input::get('province'),
        //     'city'          => Input::get('city'),
        //     'address'       => Input::get('address')
        // );
        // // $info = Input::all();
        // // Create validation rules
        // $rules = array(
        //     'nickname'      => 'required|between:1,30',
        //     'bio'           => 'between:1,60',
        //     'address'       => 'between:1,80',
        //     'phone'         => 'numeric',
        // );
        // // Custom validation message
        // $messages = array(
        //     'username.between'  => '长度请保持在:min到:max字之间',
        //     'username.required' => '请填写您的姓名',
        //     'nickname.required' => '请输入昵称',
        //     'nickname.between'  => '昵称长度请保持在:min到:max字之间',
        //     'bio.between'       => '个人简介长度请保持在:min到:max字之间',
        //     'address.between'   => '长度请保持在:min到:max字之间',
        //     'phone.numeric'     => '请填写正确的手机号码',
        // );
        // // Begin verification
        // $validator = Validator::make($info, $rules, $messages);
        // if ($validator->passes()) {
        //     // Verification success
        //     // Update account
        //     $user                = Auth::user();
        //     $user->username      = Input::get('username');
        //     $user->nickname      = Input::get('nickname');
        //     $user->alipay        = Input::get('alipay');
        //     $user->bio           = Input::get('bio');
        //     $user->sex           = Input::get('sex');
        //     $user->born_year     = Input::get('born_year');
        //     $user->born_month    = Input::get('born_month');
        //     $user->born_day      = Input::get('born_day');
        //     $user->home_province = Input::get('province');
        //     $user->home_city     = Input::get('city');
        //     $user->home_address  = Input::get('address');
        //     $user->phone         = Input::get('phone');
        //     if ($user->save()) {
        //         // Update success
        //         return Redirect::back()
        //             ->with('success', '<strong>基本资料更新成功。</strong>');
        //     } else {
        //         // Update fail
        //         return Redirect::back()
        //             ->withInput()
        //             ->with('error', '<strong>基本资料更新失败。</strong>');
        //     }
        // } else {
        //     // Verification fail, redirect back
        //     return Redirect::back()->withInput()->withErrors($validator);
        // }
    }

}