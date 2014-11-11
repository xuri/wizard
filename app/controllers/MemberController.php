<?php

class MemberController extends BaseController {

	/**
     * View: Members
     * @return Response
     */

	public function index()
	{
		return View::make('members.index');
	}

	/**
     * View: Show info
     * @return Response
     */
	public function show($id)
	{
		return View::make('members.show');
	}
}
