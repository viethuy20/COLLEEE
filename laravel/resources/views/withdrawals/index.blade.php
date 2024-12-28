<?php $base_css_type = 'withdrawal'; ?>
@extends('layouts.plane')

@section('layout.title', '退会｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')
<?php $user = Auth::user(); ?>

<section class="contents">
    <h2 class="contents__ttl">退会</h2>

    <div class="contents__box">
        <div class="clearfix caution">
            <p class="cap">{!! Tag::image('/images/ico_withdrawal.svg', '') !!}ご注意ください</p>
            <p>退会処理が完了しますと、現在お持ちのポイントは全て無くなってしまいます。またGMOポイ活では、<span>原則お1人様1アカウント</span>となっておりますので再入会はできません。予めご了承ください。<br />
            メールマガジンのみの配信停止も可能ですので、回数が気になる方は是非お試しください。</p>
            {!! Tag::link(route('users.edit_email_setting'), 'メールマガジンの配信設定はこちら', null, null, false) !!}
        </div><!--/caution-->

        <h3 class="contents__ttl u-mb-10">現在の所持ポイント</h3>
        <div class="withdrawal__center__box">
            <div class="withdrawal__center__box__main">
                <h3 class="howmuch">{{ number_format($user->point) }}</h3><h3 class="unit">ポイント</h3>
            </div>
        </div>

        {!! Tag::formOpen(['route' => 'withdrawals.confirm', 'method' => 'post', 'class' => 'withdrawal__form']) !!}
        @csrf
        <table>
                <tr>
                    <th><span class="necessary">メールアドレス</span></th>
                    <td>
                        {!! Tag::formText('email', '', ['placeholder' => 'メールアドレスを入力してください', 'class' => 'inquiry_textbox', 'required' => 'required']) !!}
                        @if ($errors->has('email'))
                        <!--エラーの場合はここに-->
                        <p class="error_message"><span class="icon-attention"></span>{{ $errors->first('email') }}</p>
                        @endif
                        @if (Session::has('message'))
                        <p class="error_message"><span class="icon-attention"></span>{{ Session::get('message') }}</p>
                        @endif
                    </td>
                </tr>
                
                @php
                    $googleType = 2;
                    $lineType = 1;
                    $cookie = null;
                    if (Cookie::get('cookie_login')) {
                        $cookie = Crypt::decryptString(Cookie::get('cookie_login')) ?? null;
                    }
                    
                    if ($cookie == null) Auth::logout();
                @endphp

                @switch(true)
                    @case($user->google_id && $cookie == $googleType)
                        <tr>
                            <th><span class="necessary">GOOGLEユーザーID</span></th>
                            <td>
                                {!! Tag::formText('google_id', $user->google_id, ['required' => 'required', 'size' => '23', 'autocomplete' => 'off', 'class' => 'inquiry_textbox', 'disabled']) !!}<br />
                                {{ Tag::formHidden('google_id', $user->google_id) }}
                            </td>
                        </tr>
                        @break

                    @case($user->line_id && $cookie == $lineType)
                        <tr>
                            <th><span class="necessary">LINEユーザーID</span></th>
                            <td>
                                {!! Tag::formText('line_id', $user->line_id, ['required' => 'required', 'size' => '23', 'autocomplete' => 'off', 'class' => 'inquiry_textbox', 'disabled']) !!}<br />
                                {{ Tag::formHidden('line_id', $user->line_id) }}
                            </td>
                        </tr>
                        @break

                    @default
                        <tr>
                            <th><span class="necessary">パスワード</span></th>
                            <td>
                                {!! Tag::formPassword('password', ['required' => 'required', 'size' => '23', 'autocomplete' => 'off', 'class' => 'inquiry_textbox', 'placeholder' => 'パスワードを入力してください']) !!}<br />
                                @if ($errors->has('password'))
                                <!--エラーの場合はここに-->
                                <p class="error_message"><span class="icon-attention"></span>{{ $errors->first('password') }}</p>
                                @endif
                                <div class="withdrawal__form__privacy">
                                    <p>パスワードをお忘れになった方へ</p>
                                    <p>「パスワード」がおわかりにならない場合は、お手数ですが{!! Tag::link(route('users.edit'), '登録内容変更', ['class' => 'textlink external'], null, false) !!}にアクセスし、パスワードの再設定を行った上で退会を お願いいたします。</p>
                                </div>
                            </td>
                        </tr>
                @endswitch

                <tr>
                    <th><span class="">退会の理由<br />（複数回答可）</span></th>
                    <td>
                        <?php $reasons_map = config('map.withdrawal_reasons'); ?>
                        <div class="clearfix">
                            @foreach($reasons_map as $key => $label)
                            @if ($loop->index % 6 == 0)
                            <p class="checkboxes">
                            @endif
                                <label>{!! Tag::formCheckbox('reasons[]', $key, '') !!}&nbsp;{{ $label }}</label><br />
                            @if ($loop->index % 6 == 5 || $loop->last)
                            </p>
                            @endif
                            @endforeach
                            @if ($errors->has('reason'))
                            <p class="error_message"><span class="icon-attention"></span>{{ $errors->first('reason') }}</p>
                            @endif
                        </div>
                        {!! Tag::formTextarea('free_reason', null, ['class' => 'inquiry_textarea', 'placeholder' => 'その他ご意見ありましたらご入力下さい。']) !!}
                    </td>
                </tr>
            </table>
            <div class="withdrawal__form__btn">
                <button type="submit">確認</button>
            </div>
            <div class=""></div>
        {!! Tag::formClose() !!}
    </div><!--/contentsbox-->
</section><!--/leaving-->
@endsection
