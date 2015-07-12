<?php

// Retrieve opened and pending university
$universities       = University::whereIn('status', array(1, 2))->select('id', 'university', 'open_at', 'status')->orderBy('status', 'desc')->get()->toArray();

// Format pending time
foreach ($universities as $key => $value) {
    $universities[$key]['open_at'] = e(date('m月d日', strtotime($universities[$key]['open_at'])));
}

array_push($universities, array(
    'id'            => 0,
    'university'    => '其他',
    'open_at'       => 'none',
    'status'        => 2
));

// Build Json format
return Response::json(
    array(
        'status'    => 1,
        'data'      => $universities
    )
);
