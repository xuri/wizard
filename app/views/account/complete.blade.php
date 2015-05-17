@include('account.complete-header')
@yield('content')

    {{-- Mask --}}
    <div id="mask"></div>
    <div id="checkbg">
        <a id="check_close" href="javascript:;">×</a>

        <span class="fp_bottom" id="bottom"></span>

        <div id="pinai_avatar">
            @if(Auth::user()->sex == 'M')
            <a class="once" href="javascript:;" id="../assets/images/preInfoEdit/boy/">
                {{ Lang::get('account/complete.pinai_avatar') }}{{-- HTML::image('assets/images/preInfoEdit/bgcolor/boy.png') --}}
            </a>
            <a href="javascript:;" class="type_changer">{{ Lang::get('account/complete.custom_avatar') }}</a>
            @else
            <a class="once" href="javascript:;" id="../assets/images/preInfoEdit/girl/">
                {{ Lang::get('account/complete.pinai_avatar') }}{{-- HTML::image('assets/images/preInfoEdit/bgcolor/girl.png') --}}
            </a>
            <a href="javascript:;" class="type_changer">{{ Lang::get('account/complete.custom_avatar') }}</a>
            @endif
        </div>

        <div id="custom_avater">
        </div>

    </div>
    {{-- Avatar --}}
    <div id="pre_content">
        <a id="pre_close" href="javascript:;">×</a>
        <canvas id="pre_pic_wrap" width="220" height="220">您的浏览器不支持该功能</canvas>
        <div id="pre_pic_list">
        </div>
        <div id="pre_btn_list">
            <div class="btn_img" title="head">
                {{ HTML::image('assets/images/preInfoEdit/boy/head/btn.png', '', array('title' => 'head')) }}
            </div>
            <div class="btn_img" title="eyebrows">
                {{ HTML::image('assets/images/preInfoEdit/boy/eyebrows/btn.png', '', array('title' => 'eyebrows')) }}
            </div>
            <div class="btn_img" title="ears">
                {{ HTML::image('assets/images/preInfoEdit/boy/ears/btn.png', '', array('title' => 'ears')) }}
            </div>
            <div class="btn_img" title="eyes">
                {{ HTML::image('assets/images/preInfoEdit/boy/eyes/btn.png', '', array('title' => 'eyes')) }}
            </div>
            <div class="btn_img" title="nose">
                {{ HTML::image('assets/images/preInfoEdit/boy/nose/btn.png', '', array('title' => 'nose')) }}
            </div>
            <div class="btn_img" title="mouth">
                {{ HTML::image('assets/images/preInfoEdit/boy/mouth/btn.png', '', array('title' => 'mouth')) }}
            </div>
            <div class="btn_img" title="hair">
                {{ HTML::image('assets/images/preInfoEdit/boy/hair/btn.png', '', array('title' => 'hair')) }}
            </div>
            <div class="btn_img" title="bgcolor">
                {{ HTML::image('assets/images/preInfoEdit/bgcolor/btn.png', '', array('title' => 'bgcolor')) }}
            </div>
        </div>
        <a href="javascript:;" id="save_pic">{{ Lang::get('account/complete.save') }}</a>
    </div>
    <div id="upload_panel">
        <a id="upload_panel_close" href="javascript:;">×</a>
        <div class="image-editor">
            <input type="file" class="cropit-image-input">
            <div class="cropit-image-preview"></div>
            <div class="image-size-label">
            {{ Lang::get('account/complete.crop_avatar') }}
            </div>
            <input type="range" class="cropit-image-zoom-input">
            <a href="javascript:;" id="save_upload_pic" class="export">{{ Lang::get('account/complete.save') }}</a>
        </div>
    </div>
    {{-- end Avatar --}}
    {{-- Choose school --}}
    <div class="vs-Popup" id="vote-school">
        <div class="vs-Popup-pass" id="vs-pass">×</div>
        <div class="vs-box clear">
            <div class="vs-Popup-school">{{ Lang::get('account/complete.school_input') }}</div>
            <!-- <div class="vs-search">搜索：<input type="text"/></div> -->
            <div class="vs-shcoollist clear" id="provinces">
                @foreach($provinces as $province)
                    <a href="javascript:;">{{ $province->province }}</a>
                @endforeach
            </div>
            <span class="vs-line-bottom"></span>
            <div class="vs-school clear" id="school_wrap">

            </div>
        </div>
    </div>
    {{-- end Choose school --}}

    {{-- Choose Constellation --}}
    <div class="con-Popup" id="con-Popup">
        <div class="con-Popup-pass" id="con-pass">×</div>
        <div class="con-box clear">
            <div class="con-Popup-school">{{ Lang::get('account/complete.constellation_input') }}</div>
            <ul class="con-Constellation clear">
                <li>
                    {{ HTML::image('assets/images/preInfoEdit/constellation/shuipin.png') }}
                    <p class="con-name">{{ Lang::get('account/constellation.aquarius') }}</p>
                    <p class="con-date">1.20~2.18</p>
                </li>
                <li>
                    {{ HTML::image('assets/images/preInfoEdit/constellation/shuangyu.png') }}
                    <p class="con-name">{{ Lang::get('account/constellation.pisces') }}</p>
                    <p class="con-date">2.19~3.20</p>
                </li>
                <li>
                    {{ HTML::image('assets/images/preInfoEdit/constellation/baiyang.png') }}
                    <p class="con-name">{{ Lang::get('account/constellation.aries') }}</p>
                    <p class="con-date">3.21~4.19</p>
                </li>
                <li>
                    {{ HTML::image('assets/images/preInfoEdit/constellation/jinniu.png') }}
                    <p class="con-name">{{ Lang::get('account/constellation.taurus') }}</p>
                    <p class="con-date">4.20~5.20</p>
                </li>
            </ul>
            <ul class="con-Constellation clear">
                <li>
                    {{ HTML::image('assets/images/preInfoEdit/constellation/shuangzi.png') }}
                    <p class="con-name">{{ Lang::get('account/constellation.gemini') }}</p>
                    <p class="con-date">5.21~6.21</p>
                </li>
                <li>
                    {{ HTML::image('assets/images/preInfoEdit/constellation/juxie.png') }}
                    <p class="con-name">{{ Lang::get('account/constellation.cancer') }}</p>
                    <p class="con-date">6.22~7.22</p>
                </li>
                <li>
                    {{ HTML::image('assets/images/preInfoEdit/constellation/shizi.png') }}
                    <p class="con-name">{{ Lang::get('account/constellation.leo') }}</p>
                    <p class="con-date">7.23~8.22</p>
                </li>
                <li>
                    {{ HTML::image('assets/images/preInfoEdit/constellation/chunv.png') }}
                    <p class="con-name">{{ Lang::get('account/constellation.virgo') }}</p>
                    <p class="con-date">8.23~9.22</p>
                </li>
            </ul>
            <ul class="con-Constellation clear">
                <li>
                    {{ HTML::image('assets/images/preInfoEdit/constellation/tiancheng.png') }}
                    <p class="con-name">{{ Lang::get('account/constellation.libra') }}</p>
                    <p class="con-date">9.23~10.23</p>
                </li>
                <li>
                    {{ HTML::image('assets/images/preInfoEdit/constellation/tianxie.png') }}
                    <p class="con-name">{{ Lang::get('account/constellation.scorpio') }}</p>
                    <p class="con-date">10.24~11.22</p>
                </li>
                <li>
                    {{ HTML::image('assets/images/preInfoEdit/constellation/sheshou.png') }}
                    <p class="con-name">{{ Lang::get('account/constellation.sagittarius') }}</p>
                    <p class="con-date">11.23~12.21</p>
                </li>
                <li>
                    {{ HTML::image('assets/images/preInfoEdit/constellation/mojie.png') }}
                    <p class="con-name">{{ Lang::get('account/constellation.capricorn') }}</p>
                    <p class="con-date">12.22~1.19</p>
                </li>
            </ul>
        </div>
    </div>
    {{-- Choose Constellation End --}}

    {{-- Tags Choose Window --}}
    <div class="tag-Popup" id="tag-Popup">
        <div class="con-Popup-pass" id="tag-pass">×</div>
        <div class="tag-box clear">
            <div class="tag-Popup-school">{{ Lang::get('account/complete.tags_input') }}</div>
            <ul class="tag-list" id="tag-list-r">
                <li><span>高冷</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>颜控</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>女神</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>萌萌哒</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>治愈系</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>小清新</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>女王范</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>天然呆</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>萝莉</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>静待缘分</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>减肥ing</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>戒烟ing</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>缺爱ing</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>暖男</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>创业者</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>直率</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>懒</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>感性</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>理性</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>温柔细心</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>暴脾气</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
                </li>
                <li><span>技术宅</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
                </li>
                <li><span>文艺病</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
                </li>
                <li><span>爱旅行</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
                </li>
                <li><span>健身狂魔</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
                </li>
                <li><span>考研ing</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
                </li>
                <li><span>吃货</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
                </li>
                <li><span>长腿欧巴</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
                </li>
                <li><span>街舞solo</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
                </li>
                <li><span>爱音乐</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
                </li>
                <li><span>幽默</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>乐观</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>事业型</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>完美主义</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>情商略高</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>阳光</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>学霸</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>执着</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>自信</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
                <li><span>独立型</span>
                    {{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
            </ul>
        </div>
        <input type="submit" class="tag-submit" value="{{ Lang::get('account/complete.save') }}" id="tag_end"/>
    </div>
    {{-- Choose Tags End --}}

    @include('layout.navigation')
    @yield('content')

    <div id="content" class="clear">
        <div class="con_title">{{ Lang::get('account/complete.edit_profile') }}</div>
        <div class="con_img">
            <span class="line1"></span>
            <span class="line2"></span>
            {{ HTML::image('assets/images/preInfoEdit/hert.png') }}
        </div>

        <div id="wrap" class="clear">
            <div class="w_left">
                <ul class="w_nav">
                    <li><a href="{{ route('account') }}" class="active a1 fa fa-tasks">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.profile') }}</a></li>
                    <li><a href="{{ route('account.sent') }}" class="a2 fa fa-heart-o">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.friends') }}</a></li>
                    <li><a href="{{ route('account.inbox') }}" class="a2 fa fa-star">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.followers') }}</a></li>
                    <li><a href="{{ route('account.notifications') }}" class="a3 fa fa-inbox">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.inbox') }}</a></li>
                    <li><a href="{{ route('account.posts') }}" class="a3 fa fa-flag-o">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.my_posts') }}</a></li>
                    <li><a href="{{ route('support.index') }}" class="a5 fa fa-life-ring">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.support') }}</a></li>
                    <li><a href="{{ route('home') }}/article/about.html" class="a5 fa fa-bookmark-o">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.about') }}</a></li>
                </ul>
                @include('account.qrcode')
                @yield('content')
            </div>
            <div class="w_right">
                <div class="clear">
                    <div class="img">
                        @if(Auth::user()->portrait)
                            @if(File::exists('portrait/'.Auth::user()->portrait) && File::size('portrait/' . Auth::user()->portrait) > 0)
                                {{ HTML::image('portrait/'.Auth::user()->portrait, '', array('id' => 'head_pic')) }}
                            @else
                                {{ HTML::image('assets/images/preInfoEdit/peo.png', '', array('id' => 'head_pic')) }}
                            @endif
                        @else
                        {{ HTML::image('assets/images/preInfoEdit/peo.png', '', array('id' => 'head_pic'))}}
                        @endif
                        <div id="change_photo">{{ Lang::get('account/complete.avatar') }}{{ $errors->first('portrait', '<strong class="error" style="color: #cc0000">:message</strong>') }}</div>
                    </div>
                    <div class="sgnin">
                        @include('account.points')
                        @yield('content')
                    </div>
                </div>

                {{-- Profile Section --}}
                <div id="data">
                    <a href="{{ route('account') }}" class="editor">{{ Lang::get('account/complete.cancel') }}</a>
                    <div class="data_top clear">
                        <span></span>{{-- Left pink section --}}
                        <p>{{ Lang::get('navigation.profile') }}</p>
                    </div>
                    {{ Form::open(array(
                        'id'           => 'edi_form',
                        'autocomplete' => 'off',
                        'action'       => 'AccountController@postComplete'
                        ))
                    }}
                        <input id="province_token" name="_token" type="hidden" value="{{ csrf_token() }}" />
                        <input name="portrait" value="{{ Input::old('portrait', $profile->portrait) }}" id="portait" type="hidden"/>
                        <input name="constellation" value="{{ Input::old('constellation', $profile->constellation) }}" id="constellation" type="hidden"/>
                        <input name="tag_str" id="tag_str" value="{{ Input::old('tag_str', $profile->tag_str) }}"  type="hidden"/>
                        <input name="school" value="{{ Input::old('school', Auth::user()->school) }}" id="school_str" type="hidden"/>
                        <table>
                            <tr>
                                <td class="data_td1">{{ Lang::get('account/index.nickname') }}:{{ $errors->first('nickname', '<strong class="error" style="color: #cc0000">:message</strong>') }}</td><td class="data_td2">
                                    <input type="text" value="{{ Input::old('nickname', Auth::user()->nickname) }}" name="nickname" placeholder="{{ Lang::get('account/complete.nickname_input') }}"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="data_td1">{{ Lang::get('account/index.sex') }}:{{ $errors->first('sex', '<strong class="error" style="color: #cc0000">:message</strong>') }}</td>
                                <td class="data_td2">
                                    @if(Auth::user()->sex)
                                    <input value="{{ Auth::user()->sex }}" type="hidden" name="sex">
                                        @if(Auth::user()->sex == 'M')
                                            {{ HTML::image('assets/images/sex/male_icon.png', '', array('width' => '18')) }} ({{ Lang::get('account/complete.sex_isset') }})
                                        @else(Auth::user()->sex == 'F')
                                            {{ HTML::image('assets/images/sex/female_icon.png', '', array('width' => '18')) }} ({{ Lang::get('account/complete.sex_isset') }})
                                        @endif
                                    @else
                                    <select name="sex" id="sex_select">
                                        <option value="">{{ Lang::get('account/complete.sex_input') }}</option>
                                        <option value="M">{{ Lang::get('account/complete.male') }}</option>
                                        <option value="F">{{ Lang::get('account/complete.female') }}</option>
                                    </select>
                                    {{ Lang::get('account/complete.sex_notify') }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="data_td1">{{ Lang::get('account/index.birth') }}:{{ $errors->first('born_year', '<strong class="error" style="color: #cc0000">:message</strong>') }}</td>
                                <td class="data_td2">
                                    @if(Auth::user()->born_year)
                                    {{ Auth::user()->born_year }} ({{ Lang::get('account/complete.birth_isset') }})
                                    <input value="{{ Auth::user()->born_year }}" type="hidden" name="born_year" id="born_select">
                                    @else
                                    <select name="born_year" id="born_select">
                                        <option value="1990">1990</option>
                                        <option value="1991">1991</option>
                                        <option value="1992">1992</option>
                                        <option value="1993">1993</option>
                                        <option value="1994">1994</option>
                                        <option value="1995">1995</option>
                                        <option value="1996">1996</option>
                                        <option value="1997">1997</option>
                                    </select>
                                    ({{ Lang::get('account/complete.birth_notify') }})
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="data_td1">{{ Lang::get('account/index.school') }}:{{ $errors->first('school', '<strong class="error" style="color: #cc0000">:message</strong>') }}</td>
                                <td class="data_td2">
                                    @if(Auth::user()->school)
                                    <span type="text" id="check_school">{{ Auth::user()->school }}</span>
                                    @else
                                    <span type="text" id="check_school">{{ Lang::get('account/complete.school_input') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="data_td1">{{ Lang::get('account/index.grade') }}:{{ $errors->first('grade', '<strong class="error" style="color: #cc0000">:message</strong>') }}</td>
                                <td class="data_td2">
                                    <select name="grade" id="grade_select" rel="{{ $profile->grade }}">
                                        <option value="">{{ Lang::get('account/complete.sex_input') }}</option>
                                        <option value="2010">2010</option>
                                        <option value="2011">2011</option>
                                        <option value="2012">2012</option>
                                        <option value="2013">2013</option>
                                        <option value="2014">2014</option>
                                        <option value="2015">2015</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="data_td1">{{ Lang::get('account/index.constellation') }}:{{ $errors->first('constellation', '<strong class="error" style="color: #cc0000">:message</strong>') }}</td><td class="data_td2 constellation">
                                @if($profile->constellation)
                                {{ HTML::image('assets/images/preInfoEdit/constellation/'.$constellationInfo['icon'], '', array('width' => '30', 'height' => '30', 'class' => 'constellation_img', 'id' => 'con_img')) }}
                                <span style="margin-left:40px;" id="check_constellation">{{ $constellationInfo['name'] }}</span></td>
                                @else
                                {{ HTML::image('assets/images/preInfoEdit/constellation/default.png', '', array('width' => '30', 'height' => '30', 'class' => 'constellation_img', 'id' => 'con_img')) }}
                                <span style="margin-left:40px;" id="check_constellation">{{ Lang::get('account/complete.constellation_input') }}</span></td>
                                @endif
                            </tr>
                            <tr>
                                <td class="data_td1 vertical_top">{{ Lang::get('account/index.tags') }}:{{ $errors->first('tag_str', '<strong class="error" style="color: #cc0000">:message</strong>') }}</td>
                                <td class="data_td2 character" id="tag_td">
                                    <span class="end" id="check_tag"><b>+</b>  {{ Lang::get('account/index.tags') }} </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="data_td1">{{ Lang::get('account/index.hobbies') }}:{{ $errors->first('hobbies', '<strong class="error" style="color: #cc0000">:message</strong>') }}</td><td class="data_td2">
                                    <input class="lang" name="hobbies" type="text" placeholder="{{ Lang::get('account/complete.hobbies_input') }}" value="{{ Input::old('hobbies', $profile->hobbies) }}" />
                                </td>
                            </tr>
                            <tr>
                                <td class="data_td1 vertical_top">{{ Lang::get('account/index.intro') }}:{{ $errors->first('self_intro', '<strong class="error" style="color: #cc0000">:message</strong>') }}</td><td class="data_td2 vertical_top">
                                    <textarea rows="4" name="self_intro" placeholder="{{ Lang::get('account/complete.intro_input') }}">{{ Input::old('self_intro', $profile->self_intro) }}</textarea>
                                </td>
                            </tr>
                            <tr class="end_tr">
                                <td class="data_td1">{{ Lang::get('account/index.bio') }}:{{ $errors->first('bio', '<strong class="error" style="color: #cc0000">:message</strong>') }}</td><td class="data_td2">
                                    <input class="lang" name="bio" type="text" placeholder="{{ Lang::get('account/complete.bio_input') }}" value="{{ Input::old('bio', Auth::user()->bio) }}">
                                </td>
                            </tr>
                            <tr class="love_problem">
                                <td class="data_td1 vertical_top">{{ Lang::get('account/index.question') }}:{{ $errors->first('question', '<strong class="error" style="color: #cc0000">:message</strong>') }}</td><td class="data_td2 vertical_top">
                                    <input class="lang" type="text" name="question" placeholder="{{ Lang::get('account/complete.question_input') }}" value="{{ Input::old('question', $profile->question) }}">
                                </td>
                            </tr>
                        </table>

                    <div class="btn_box" id="submit_btn">
                        <input id="submit" type="submit" value="{{ Lang::get('account/complete.save') }}" />
                    </div>
                    {{ Form::close() }}
                </div>

            </div>
        </div>
    </div>

    @include('layout.copyright')
    @yield('content')

@include('account.complete-footer')
@yield('content')