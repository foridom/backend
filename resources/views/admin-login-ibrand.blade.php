<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>角马平台管理系统</title>
    <!-- Tell the browser to be responsive to screen width -->
{{--<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">--}}
<!-- Bootstrap 3.3.5 -->
{{--<link rel="stylesheet" href="{{ admin_asset("/vendor/laravel-admin/AdminLTE/bootstrap/css/bootstrap.min.css") }}">--}}
<!-- Font Awesome -->
    <link rel="stylesheet" href="{{ admin_asset("/vendor/laravel-admin/font-awesome/css/font-awesome.min.css") }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ admin_asset("/vendor/laravel-admin/AdminLTE/dist/css/AdminLTE.min.css") }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ admin_asset("/vendor/laravel-admin/AdminLTE/plugins/iCheck/square/blue.css") }}">

    <link rel="stylesheet" href="{{ admin_asset("/vendor/laravel-admin/toastr/build/toastr.min.css") }}">

    <link rel="stylesheet" href="{{ admin_asset("/vendor/css/login.css") }}">


    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script type="application/x-javascript"> addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        } </script>

</head>

<body>
<h1>角马平台管理系统</h1>
<div class="container w3">
    <h2>现在登录</h2>
    <form id="login_form" action="{{ admin_base_path('auth/login') }}" method="post" onsubmit="return check(this)">
        <div class="username">
            <span class="username" style="height:19px">用户:</span>
            <input type="text" name="username" class="name" placeholder="" required value="{{ old('username') }}">
            <div class="clear"></div>
        </div>
        <div class="password-agileits">
            <span class="username" style="height:19px">密码:</span>
            <input type="password" name="password" class="password" placeholder="" required>
            <div class="clear"></div>
        </div>
        @if(config('ibrand.backend.sms_login'))
            <div class="code">
                <span class="username" style="height:19px">验证码:</span>
                <input type="text" name="code" class="code" placeholder="" required value="{{ old('code') }}">
                <button type="button" id="send-verifi" style="border: none" class="username" data-target="login"
                        data-status=0>发送
                </button>
                <div class="clear"></div>
            </div>
        @endif
        {{--<div class="rem-for-agile">--}}
        {{--<input type="checkbox" name="remember" class="remember">记得我--}}
        {{--　　--}}
        {{--<br>--}}
        {{--<a href="#">忘记了密码</a><br>--}}
        {{--</div>--}}

        <input type="hidden" name="_token" value="{{ csrf_token() }}">


        <div class="login-w3">
            <input type="submit" class="login" value="登录">
        </div>
        <div class="clear"></div>
    </form>
</div>
<div class="footer-w3l">
    <p> 技术支持：上海百治科技有限公司</p>
</div>
</body>
</html>


<!-- jQuery 2.1.4 -->
<script src="{{ admin_asset("/vendor/laravel-admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js")}} "></script>
<!-- Bootstrap 3.3.5 -->
<script src="{{ admin_asset("/vendor/laravel-admin/AdminLTE/bootstrap/js/bootstrap.min.js")}}"></script>
<!-- iCheck -->
<script src="{{ admin_asset("/vendor/laravel-admin/AdminLTE/plugins/iCheck/icheck.min.js")}}"></script>

<script src="{{ admin_asset("/vendor/laravel-admin/toastr/build/toastr.min.js")}}"></script>

<script>
    function check(form) {
        if (form.username.value == '') {
            toastr.warning("请输入邮箱或用户名");
            return false;
        }
        if (form.password.value == '') {
            toastr.warning("请输入密码");
            return false;
        }
        return true;
    }

    @if($errors->has('username'))
    @foreach($errors->get('username') as $message)
    toastr.error("{{$message}}");
    @endforeach
    @endif

    @if($errors->has('code'))
    @foreach($errors->get('code') as $message)
    toastr.error("{{$message}}");
    @endforeach
    @endif

    @if($errors->has('password'))
    @foreach($errors->get('password') as $message)
    toastr.error("{{$message}}");
    @endforeach
            @endif

        window.AppUrl = "{{env('APP_URL')}}";
    window._token = "{{ csrf_token() }}";
    var postUrl = '{{env('APP_URL')}}/getMobile';

    @if(config('ibrand.backend.sms_login'))
    $(document).ready(function () {
        // 发送验证码
        $('#send-verifi').on('click', function () {
            var el = $(this);
            var target = el.data('target');
            var mobileReg = /^(?=\d{11}$)^1(?:3\d|4[57]|5[^4\D]|66|7[^249\D]|8\d|9[89])\d{8}$/;

            if (target == 'login') { //  如果是登录
                $.ajax({
                    type: 'post',
                    data: {
                        username: $('input[name="username"]').val(),
                        _token: _token
                    },
                    url: postUrl,
                    success: function (res) {
                        if (res.status) {
                            $('input[name="mobile"]').val(res.data.mobile);
                            sendCode(el, res.data.mobile);
                        } else {
                            toastr.error(res.msg);
                        }
                    },
                    error: function () {
                        toastr.error('账号验证失败');
                    }
                })

            } else {
                var mobile = $('input[data-type=' + target + ']').val();
                if (mobile == '') {
                    toastr.error('请输入手机号码');
                    return
                }
                if (!mobileReg.test(mobile)) {
                    toastr.error('请输入正确的手机号码');
                } else {
                    sendCode(el, mobile);
                }
            }
        });

        //发送验证码方法
        function sendCode(el, mobile) {
            if (el.data('status') != 0) {
                return
            }
            el.text('正在发送...');
            el.data('status', '1');
            $.ajax({
                type: 'POST',
                data: {
                    mobile: mobile,
                    access_token: _token
                },
                url: AppUrl + "/{{config('ibrand.sms.route.prefix')}}/verify-code?_token=" + _token,
                success: function (data) {
                    if (data.success) {
                        $('input[name="access_token"]').val(_token);
                        var total = 60;
                        var message = '{#counter#}秒';
                        el.text(message.replace(/\{#counter#}/g, total));
                        var timer = setInterval(function () {
                            total--;
                            el.text(message.replace(/\{#counter#}/g, total));

                            if (total < 1) {
                                el.data('status', '0');
                                el.text('发送验证码');
                                clearInterval(timer);
                            }
                        }, 1000)
                    } else {
                        el.data('status', '0');
                        el.text('发送验证码');
                        toastr.error('短信发送失败！');
                    }
                },
                error: function () {
                    el.data('status', '0');
                    el.text('发送验证码');
                    toastr.error('短信发送失败！');
                }
            })
        };

    });
    @endif

</script>


