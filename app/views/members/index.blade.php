@include('members.header')
@yield('content')

    @include('layout.navigation')
    @yield('content')

    <div id="lu_content">
        <div class="lu_con_title">{{ Lang::get('navigation.discover') }}</div>
        <div class="lu_con_img">
            <span class="lu_line1"></span>
            <span class="lu_line2"></span>
            {{ HTML::image('assets/images/hert.png') }}

        </div>
        <div class="lu_content_box clear">
            <div class="lu_content_main clear">
                {{ Form::open(array('method' => 'get', 'class' => 'lu_content_main_tab')) }}
                    <!-- <select class="lu_school lu_public" name="university" id="university_select" rel="{{ Session::get('university') }}">
                        <option value="all">{{ Lang::get('members/index.all_school') }}</option>
                        @foreach($open_universities as $open_university)
                        <option value="{{ $open_university->university }}">{{ $open_university->university }}</option>
                        @endforeach
                        <option value="others">{{ Lang::get('members/index.other_school') }}</option>
                        @foreach($pending_universities as $pending_university)∂
                        <option vlaue="">{{ $pending_university->university }} ({{ date('m月d日', strtotime($pending_university->open_at)) }} {{ Lang::get('members/index.open') }})</option>
                        @endforeach
                    </select> -->

                    <select class="lu_school lu_public" name="province" id="university_select" rel="{{ Session::get('province') }}">
                        <option value="all">{{ Lang::get('members/index.all_province') }}</option>
                        @foreach($provinces as $province)
                        <option value="{{ $province->id }}">{{ $province->province }}</option>
                        @endforeach
                    </select>

                    {{
                        Form::select(
                            'target',
                            array('all' => Lang::get('members/index.sex'), 'M' => Lang::get('members/index.male'), 'F' => Lang::get('members/index.female')),
                            Input::get('target', 'sex'),
                            array('class' => 'lu_sex lu_public', 'name' => 'sex', 'id' => 'sex_select', 'rel' => Session::get('sex'))
                        )
                    }}
                    {{--
                        Form::select(
                            'target',
                            array('all' => Lang::get('members/index.select_grade'),
                                    '2015' => '2015' . Lang::get('members/index.year'),
                                    '2014' => '2014' . Lang::get('members/index.year'),
                                    '2013' => '2013' . Lang::get('members/index.year'),
                                    '2012' => '2012' . Lang::get('members/index.year'),
                                    '2011' => '2011' . Lang::get('members/index.year'),
                                    '2010' => '2010' . Lang::get('members/index.year'),
                                ),
                            Input::get('target', 'grade'),
                            array('class' => 'lu_school lu_public', 'name' => 'grade', 'id' => 'grade_select', 'rel' => Session::get('grade'))
                        )
                    --}}

                    {{
                        Form::select(
                            'target',
                            array('all' => Lang::get('members/index.select_born_year'),
                                    '1997' => '1997' . Lang::get('members/index.year'),
                                    '1996' => '1996' . Lang::get('members/index.year'),
                                    '1995' => '1995' . Lang::get('members/index.year'),
                                    '1994' => '1994' . Lang::get('members/index.year'),
                                    '1993' => '1993' . Lang::get('members/index.year'),
                                    '1992' => '1992' . Lang::get('members/index.year'),
                                    '1991' => '1991' . Lang::get('members/index.year'),
                                    '1990' => '1990' . Lang::get('members/index.year'),
                                    '1989' => '1989' . Lang::get('members/index.year'),
                                    '1988' => '1988' . Lang::get('members/index.year'),
                                ),
                            Input::get('target', 'born_year'),
                            array('class' => 'lu_school lu_public', 'name' => 'born_year', 'id' => 'grade_select', 'rel' => Session::get('born_year'))
                        )
                    }}

                    <button type="submit" class="lu_search lu_public">{{ Lang::get('members/index.search') }}</button>
                    <a href="{{ route('account') }}" class="lu_release lu_public">{{ Lang::get('members/index.profile') }}</a>
                {{ Form::close() }}

                <div id="load-ajax">
                    @foreach($datas as $data)
                    <?php
                        if(Cache::has('data_' . $data->id)) {
                            $data = Cache::get('data_' . $data->id);
                        } else {
                            Cache::put('data_' . $data->id, $data, 60);
                        }
                    ?>
                    @if($data->portrait)
                    <?php
                        if (Cache::has('profile_' . $data->id)) {
                            $profile = Cache::get('profile_' . $data->id);
                        } else {
                            $profile = Profile::where('user_id', $data->id)->first();
                            $tag_str = array_unique(explode(',', substr($profile->tag_str, 1)));
                            Cache::put('profile_' . $data->id, $profile, 60);
                        }

                        if (Cache::has('profile_' . $data->id . '_tag_str')) {
                            $tag_str = Cache::get('profile_' . $data->id . '_tag_str');
                        } else {
                            $tag_str = array_unique(explode(',', substr($profile->tag_str, 1)));
                            Cache::put('profile_' . $data->id . '_tag_str', $tag_str, 60);
                        }
                    ?>

                    <div class="lu_resumes clear">
                        <div class="lu_resumes_user clear">
                            @if($data->is_verify == 1)
                            <a href="{{ str_finish(URL::to('/article'), '/verified-accounts.html') }}" target="_blank" class="icon_verify" title="{{ Lang::get('members/index.verify') }}" alt="{{ Lang::get('members/index.verify') }}"><span class="icon_approve"></span></a>
                            @else
                            @endif
                            <a href="{{ route('members.show', $data->id) }}">
                                @if(File::exists('portrait/' . $data->portrait) && File::size('portrait/' . $data->portrait) > 0)
                                    {{ HTML::image('portrait/' . $data->portrait, '', array('class' => 'lu_img')) }}
                                @else
                                    {{ HTML::image('assets/images/preInfoEdit/peo.png', '', array('class' => 'lu_img')) }}
                                @endif
                            </a>
                            <div class="lu_userMessage">
                                {{ HTML::image('assets/images/arrow.png', '', array('class' => 'lu_userMessage_arrow')) }}
                                @if($data->sex == 'M')
                                    {{ HTML::image('assets/images/sex/male_icon.png', '', array('class' => 'lu_left', 'width' => '20')) }}
                                @else
                                    {{ HTML::image('assets/images/sex/female_icon.png', '', array('class' => 'lu_left', 'width' => '20')) }}
                                @endif

                                @if($profile->crenew >= 30)
                                    <p class="lu_te lu_userMessage_name lu_left">
                                        @if($data->is_admin)
                                        <span class="admin"><a href="{{ route('members.show', $data->id) }}">{{ Lang::get('system.moderator') }}</a></span>
                                        @else
                                        @endif
                                        <span class="crenew-nickname"><a href="{{ route('members.show', $data->id) }}" class="nickname">{{ $data->nickname }}</a></span>
                                    </p>
                                @else
                                    <p class="lu_te lu_userMessage_name lu_left">
                                        @if($data->is_admin)
                                            <span class="admin"><a href="{{ route('members.show', $data->id) }}">{{ Lang::get('system.moderator') }}</a></span>
                                        @else
                                        @endif
                                        <a href="{{ route('members.show', $data->id) }}" class="nickname">{{ $data->nickname }}</a>
                                    </p>
                                @endif
                                <p class="lu_te lu_userMessage_p lu_userMessage_school lu_left">{{ $data->school }}</p>
                                <p class="lu_userMessage_p lu_left">{{ $profile->grade }}{{ Lang::get('members/index.grade') }}</p>
                                <a class="lu_userMessage_detail lu_right" href="{{ route('members.show', $data->id) }}">{{ Lang::get('members/index.detail') }}</a>
                                <p class="lu_userMessage_readme lu_left">{{ $profile->self_intro }}</p>
                                <ul class="lu_userMessage_character">

                                    @foreach($tag_str as $tag)
                                    <li>{{ getTagName($tag) }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @else
                    @endif
                    @endforeach
                    {{ pagination($datas->appends(Input::except('page')), 'layout.paginator') }}
                </div>
            </div>
            <div class="lu_content_right">
                {{ HTML::image('assets/images/sidebar_1.jpg') }}
            </div>
        </div>
    </div>

    @include('layout.copyright')
    @yield('content')

</body>

{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}

<script type="text/javascript">
    var aColor=['#e64150','#5cd5d5','#8acd47','#ffcc00','#a036a0', '#FF3399', '#6699FF', '#FF9900',
                '#e64150','#5cd5d5','#8acd47','#ffcc00','#a036a0', '#FF3399', '#6699FF', '#FF9900',
                '#e64150','#5cd5d5','#8acd47','#ffcc00','#a036a0', '#FF3399', '#6699FF', '#FF9900',
                '#e64150','#5cd5d5','#8acd47','#ffcc00','#a036a0', '#FF3399', '#6699FF', '#FF9900',
                '#e64150','#5cd5d5','#8acd47','#ffcc00','#a036a0', '#FF3399', '#6699FF', '#FF9900'];
    function loop(classValue){
        var aT=document.getElementsByClassName(classValue);
        for(var i=0;i<aT.length;i++){
            var aLi=aT[i].getElementsByTagName('li');
            for(var a=0;a<aLi.length;a++){
                aLi[a].style.background=aColor[a];
            }
        }
    }
    loop('lu_userMessage_character');

    // Ajax pagination

    $(function() {
        $('#load-ajax').on('click', '.lu_paging a', function (e) {
            getPosts($(this).attr('href').split('page=')[1]);
            e.preventDefault();
        });
    });

    function getPosts(page) {
        $.ajax({
            url : '?page=' + page,
            dataType: 'json',
        }).done(function (data) {
            $('#load-ajax').html(data);
        }).fail(function () {
            alert('Posts could not be loaded.');
        });
    }

    @if(Session::get('university'))
    $("#university_select").val($("#university_select").attr("rel"));
    @endif

    @if(Session::get('sex'))
    $("#sex_select").val($("#sex_select").attr("rel"));
    @endif

    @if(Session::get('grade'))
    $("#grade_select").val($("#grade_select").attr("rel"));
    @endif
</script>
</html>