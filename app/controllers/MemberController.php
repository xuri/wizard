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
        $datas = User::whereNotNull('portrait')->orderBy('created_at', 'desc')->paginate(1);
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
		$like 			   = Like::where('sender_id', Auth::user()->id)->first();
		$constellationInfo = getConstellation($profile->constellation); // Get user's constellation
		$tag_str           = explode(',', substr($profile->tag_str, 1));
		return View::make('members.show')->with(compact('data', 'like', 'profile', 'constellationInfo', 'tag_str'));
	}

	public function like($id)
	{
		// Get all form data.
		$data = Input::all();
		// Create validation rules
		$rules = array(
			'answer'              => 'required|min:3',
		);
		// Custom validation message
		$messages = array(
			'answer.required'     => '请回答爱情考验问题。',
			'answer.min'          => '至少要写:min个字哦。',
		);

		// Begin verification
		$validator   = Validator::make($data, $rules, $messages);
		if ($validator->passes()) {
			$have_like = Like::where('sender_id', Auth::user()->id)->where('receiver_id', $id)->first();
			if($have_like) // This user already sent like
			{
				$have_like->answer      = Input::get('answer');
				$have_like->count       = $have_like->count + 1;
				if($have_like->save())
				{
					return Redirect::back()
					->withInput()
					->with('success', '发送成功。');
				}
			} else { // First like
				$like              = new Like();
				$like->sender_id   = Auth::user()->id;
				$like->receiver_id = $id;
				$like->status      = 1;
				$like->answer      = Input::get('answer');
				$like->count       = 1;
				if($like->save())
				{
					return Redirect::back()
						->withInput()
						->with('success', '发送成功。');
				}
			}
		} else { // Validation fail
			return Redirect::back()
				->withInput()
				->withErrors($validator);
		}
		// if($like->save())
		// {
		// 	return Response::json(
		// 		array(
		// 			'success' => true
		// 		)
		// 	);
		// } else {
		// 	return Response::json(
		// 		array(
		// 			'success' => true
		// 		)
		// 	);
		// }
	}
}
