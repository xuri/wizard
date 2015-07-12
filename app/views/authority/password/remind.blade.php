@include('authority.password.header')
@yield('content')

    @include('layout.navigation')
    @yield('content')

    <div class="fp_box">
        <h1>{{ Lang::get('authority.reset_password') }}</h1>
        <a href="{{ route('signin') }}" class="fp_back">{{ Lang::get('navigation.signin') }}</a>
        <a href="{{ route('home') }}" class="fp_back">{{ Lang::get('navigation.go_home') }}</a>
        <div class="fp_passWord">
            <a href="javascript:;" class="fp_tab fp_tab2" id="tab2">{{ Lang::get('authority.email_reset_password') }}</a>
            <a href="javascript:;" class="fp_tab fp_tab1" id="tab1">{{ Lang::get('authority.phone_reset_password') }}</a>
            <span class="fp_bottom" id="bottom"></span>
            <div style="width:820px;">
                <div class="fp_emile" id="emile">
                    @if( Session::get('error') )
                        <p style="text-align: center; margin: 20px 0 0 0;">
                            {{ Session::get('error') }}

                        </p>
                    @elseif( Session::get('status') )
                        <p style="text-align: center; margin: 20px 0 0 0;">
                            {{ Session::get('status') }}

                        </p>
                    @endif
                    {{ Form::open() }}

                    <p class="fp_p">{{ Lang::get('authority.email') }}:</p>
                    <input type="text" name="email" class="fp_text"/>
                    <input type="submit" value="{{ Lang::get('authority.next') }}" class="fp_next" />
                    {{ Form::close() }}

                </div>
                <div class="fp_phone" id="phone">
                    <p style="text-align: center;  margin: 20px 0 -10px 0;">
                        <strong class="phone_error"></strong>
                        {{ $errors->first('phone', '<strong class="error">:message</strong>') }}

                        {{ $errors->first('sms_code', '<strong class="error">:message</strong>') }}

                        {{ $errors->first('email', '<strong class="error">:message</strong>') }}

                        {{ $errors->first('password', '<strong class="error">:message</strong>') }}

                    </p>
                    {{ Form::open(array(
                        'autocomplete' => 'off',
                        'action'       => 'HomeController@getIndex',
                        'id'           => 'fp_form'
                        )) }}

                    <p id="push_error" style="text-align: center;"></p>
                    <input type="hidden" id="forgot_password" value="forgot_password" />
                    <div>
                        <p class="fp_p">{{ Lang::get('authority.phone') }}:</p>
                        <input type="text" class="fp_text" id="phone_number" name="phone" required="required" placeholder="{{ Lang::get('authority.phone_input') }}"/>
                    </div>
                    <div>
                        <p class="fp_p">{{ Lang::get('authority.verify_code') }}:</p><input type="text" class="fp_code" name="sms_code" required="required" value="{{ Input::old('sms_code') }}" />
                        <input type="button" class="fp_button count-send" style="height: 2.6em; color: #fff;" value="{{ Lang::get('authority.send') }}" />
                    </div>
                    <div class="rgs_li">
                        <span>{{ Lang::get('authority.new_password') }}:</span>
                        <input type="password" name="password" required="required" placeholdr="{{ Lang::get('authority.password_input') }}">
                    </div>
                    <div class="rgs_li">
                        <span>{{ Lang::get('authority.repet_password') }}:</span>
                        <input type="password" name="password_confirmation" required="required" placeholder="{{ Lang::get('authority.repet_password_input') }}">
                    </div>
                    <input type="button" value="{{ Lang::get('authority.update_password') }}" id="submit" class="fp_next" />
                    {{ Form::close() }}

                </div>
            </div>
        </div>
    </div>

    @include('layout.copyright')
    @yield('content')

<script>
    var oTab1=document.getElementById('tab1'),
        oTab2=document.getElementById('tab2'),
        oBottom=document.getElementById('bottom');
    oTab2.onclick=function(){
        oBottom.style.marginLeft=15+'px';
        sw(2);
    }
    oTab1.onclick=function(){
        oBottom.style.marginLeft=128+'px';
        sw(1);
    }
    function sw(num){
        var arr={'phone':['-430px','0','1'],'emile':['0px','1','0']};
        var oPhone=document.getElementById('phone'),
            oEmile=document.getElementById('emile');
        if(num==1){
            oEmile.style.marginLeft=arr.phone[0];
            oEmile.style.opacity=arr.phone[1];
            oPhone.style.opacity=arr.phone[2];
        }else if(num==2){
            oEmile.style.marginLeft=arr.emile[0];
            oEmile.style.opacity=arr.emile[1];
            oPhone.style.opacity=arr.emile[2];
        }
    }

    $(function(){
        {{-- 验证码读秒 --}}
        var times=60; //60秒后重新发送，保存到变量
        $('.count-send').click(function(){
            // this 指向
            var _this = this;
            // 获取手机号码
            var phone = $('#phone_number').val();
            var forgot_password = $('#forgot_password').val();
            $.post('{{ route("verifycode") }}',
            {
              phone : phone,
              forgot_password : true
            },function(jdata){
                // send message success
                if(jdata.length != undefined){
                    var that=$(_this);
                    timeSend(that);
                } else {
                    if(jdata.errors) {
                        // send error
                        $('#push_error').html(jdata.errors.phone);
                    } else {
                        var that=$(_this);
                        timeSend(that);
                        $('#push_error').html(jdata.success_info);
                    }
                }
            });

        });

        function timeSend(that){
            if(times==0){
                that.removeAttr('disabled').val('重新发送验证码');
                times=60;
            }else{
                that.attr('disabled',true).val(times+'秒后重新发送');
                times--;
                setTimeout(function(){
                 timeSend(that);
                },1000);
            }
        }

        {{-- 修改密码Ajax请求 --}}


        // 获取提交按钮
        var pwd_submit = $("#submit");

        pwd_submit.click(function(){
            // 获取表单值
            var token_val = $("input[name=_token]").val();
            var phone_val = $('#phone_number').val();
            var sms_code_val = $("input[name=sms_code]").val();
            var password_val = $("input[name=password]").val();
            var password_conf_val = $("input[name=password_confirmation]").val();
            // 发送post请求
            $.post("{{ route('postsmsreset') }}", {
                "_token" : token_val,
                "phone" : phone_val,
                "sms_code" : sms_code_val,
                "password" : password_val,
                "password_confirmation" : password_conf_val
            }, function(jdata){
                if(jdata.success){
                    location.href = "{{ route('home') }}";

                }else{
                    if(jdata.errors.hasOwnProperty("phone")){
                        $('#push_error').html(jdata.errors.phone);
                    }else if(jdata.errors.hasOwnProperty("sms_code")){
                        $('#push_error').html(jdata.errors.sms_code);
                    }else if(jdata.errors.hasOwnProperty("password_confirmation")){
                        $('#push_error').html(jdata.errors.password_confirmed);
                    }else if(jdata.errors.hasOwnProperty("password")){
                        $('#push_error').html(jdata.errors.password);
                    }
                }

            });
        });


    })

</script>
</body>
</html>