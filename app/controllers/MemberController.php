<?php

class MemberController extends BaseController {

	/**
	 * Resource identification
	 * @var string
	 */
	protected $resource = 'members';

	/**
     * View: Members
     * @return Response
     */

	public function index()
	{
        $datas = User::orderBy('created_at', 'desc')->paginate(12);
		return View::make($this->resource.'.index')->with(compact('datas'));
	}

	/**
     * View: Show info
     * @return Response
     */
	public function show($id)
	{
		$data              = User::where('id', $id)->first();
		$profile           = Profile::where('user_id', $id)->first();
		$constellationInfo = getConstellation($profile->constellation); // Get user's constellation
		$tag_str           = explode(',', substr($profile->tag_str, 1));
		return View::make('members.show')->with(compact('data', 'profile', 'constellationInfo', 'tag_str'));
	}
}
