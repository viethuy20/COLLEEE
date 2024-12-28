@php
    $base_css_type = 'api_login';
    $use_recaptcha = true;
@endphp

@extends('layouts.plane')

@section('layout.head')
@endsection

@section('layout.title', 'GMOポイ活にログイン｜ポイントサイトならGMOポイ活')
@section('layout.keywords', 'GMOポイ活,ログイン')
@section('layout.description', '無料で簡単にお小遣いが貯められるポイントサイトGMOポイ活のログインページです。貯めたポイントの確認や、現金やギフト券への交換はこちらから！')
@section('og_type', 'website')

@section('layout.content')

    <!-- main contents -->
    <section class="contents">

    <!-- page title -->
    <h2 class="contents__ttl red">ログインしてください</h2>

    <!-- ご利用の流れ -->
    <div class="contents__box u-mt-20">
        <div class="login__flex">
            <div class="login__flex__l">
                <div class="">
                    <div class="login__image">
                        {{ Tag::image('/images/login/login_img.png', 'GMOポイ活ってどんなサービス？')}}
                    </div>
                    <div class="login__point">
                        <ul>
                            <li>
                                <div class="num">1</div>
                                <div class="txt">GMOポイ活を経由して<br>ショップやサービスを利用</div>
                                <div class="image">
                                    {{ Tag::image('/images/login/login_point1.png')}}
                                </div>
                            </li>
                            <li>
                                <div class="num">2</div>
                                <div class="txt">GMOポイ活の<br>ポイントが貯まる！</div>
                                <div class="image">{{ Tag::image('/images/login/login_point2.png')}}</div>
                            </li>
                            <li>
                                <div class="num">3</div>
                                <div class="txt">貯まったポイントを<br>現金やギフト券に交換！</div>
                                <div class="image">{{ Tag::image('/images/login/login_point3.png')}}</div>
                            </li>
                        </ul>
                    </div>
                    <p class="text--15 u-text-ac u-mt-20"><a href="{{ route('entries.about') }}" class="textlink">GMOポイ活についてもっと詳しく</a></p>
                </div>
            </div><!-- /.login__flex__l -->
            <div class="login__flex__r">
                <div class="login__form">
                    <h3 class="text--18">メールアドレスでログイン</h3>
                    {{ Tag::formOpen(['url' => route('api.login.submit'), 'class' => \App\External\Google::getRecaptchaClass(), 'forGoogleRecaptchaAction' => 'login']) }}
                    @csrf    
                    {{ Tag::formHidden(\App\External\Google::getRecaptchaParamKey(), '') }}
                        {{ Tag::formHidden('referer', $referer ?? route('website.index')) }}
                        {{ Tag::formHidden('redirect_uri', $redirect_uri) }}
                        {{ Tag::formHidden('ems_type', $ems_type) }}

                        <div class="u-mt-40">
                            {{ Tag::formText('email', '', ['required' => 'required', 'size' => '23', 'class' => 'field', 'placeholder' => 'メールアドレス']) }}
                            @if ($errors->has('email'))
                            <p class="error_message"><span class="icon-attention"></span>{{ $errors->first('email') }}</p>
                            @endif            
                        </div>

                        <div class="u-mt-small">
                            {{ Tag::formPassword('password', ['required' => 'required', 'size' => '23', 'placeholder' => 'パスワード']) }}
                            @if ($errors->has('password'))
                            <p class="error_message"><span class="icon-attention"></span>{{ $errors->first('password') }}</p>
                            @endif
                        </div>

                        @if (session()->has('message'))
                        <p class="error_message"><span class="icon-attention"></span>{{ session('message') }}</p>
                        @endif
    
                        <div class="login__form__privacy">
                            <p>「{{ Tag::link(route('abouts.membership_contract'), '会員利用規約', ['target' => '_blank', 'class' => 'textlink']) }}」および「{{ Tag::link('https://www.koukoku.jp/privacy/', '個人情報の取扱いについて', ['target' => '_blank', 'class' => 'external']) }}」に同意の上、ログインしてください。</p>
                        </div>

                        <div class="login__form__btn">
                            {{ Tag::formButton('ログイン', ['type' => 'submit']) }}
                        </div>
                        <p class="text--15 u-text-ac u-mt-20">{{ Tag::link('/support/?p=937', 'ログインできない場合', ['class' => 'textlink'], false, false) }}</p>
                    {{ Tag::formClose() }}
                    <p class="text--15 u-text-right u-mt-40">
                        <a href="{{ route('entries.index') }}" class="textlink">
                            <i>
                                {{ Tag::image('/images/login/ico_entry.svg')}}
                            </i>
                            新規登録はこちら
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
