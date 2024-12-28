@php
    $base_css_type = 'api_login';
    $use_recaptcha = true;
@endphp
@extends('layouts.default')

@section('layout.head')

@endsection

@section('layout.title', 'GMOポイ活にログイン｜ポイントサイトならGMOポイ活')
@section('layout.keywords', 'GMOポイ活,ログイン')
@section('layout.description', '無料で簡単にお小遣いが貯められるポイントサイトGMOポイ活のログインページです。貯めたポイントの確認や、現金やギフト券への交換はこちらから！')
@section('og_type', 'website')

@section('layout.content')

<section class="inner u-mt-20">
    <h2 class="text--24 red">ログインしてください</h2>
    <div class="login__form">
        <h3 class="text--18">メールアドレスでログイン</h3>
        {{ Tag::formOpen(['url' => route('api.login.submit'), 'class' => \App\External\Google::getRecaptchaClass(), 'forGoogleRecaptchaAction' => 'login']) }}
        @csrf    
        {{ Tag::formHidden(\App\External\Google::getRecaptchaParamKey(), '') }}
            {{ Tag::formHidden('referer', $referer ?? route('website.index')) }}
            {{ Tag::formHidden('redirect_uri', $redirect_uri) }}
            {{ Tag::formHidden('ems_type', $ems_type) }}

            <div class="u-mt-20">
                {{ Tag::formText('email', '', ['required' => 'required', 'size' => '23', 'class' => 'field', 'placeholder' => 'メールアドレス']) }}<br />
                @if ($errors->has('email'))
                <p class="error_message"><span class="icon-attention"></span>{{ $errors->first('email') }}</p>
                @endif
            </div>

            <div class="u-mt-small">
                {{ Tag::formPassword('password', ['required' => 'required', 'size' => '23', 'autocomplete' => 'off', 'class' => 'field', 'placeholder' => 'パスワード']) }}
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
            {{ Tag::formClose() }}

            <p class="text--15 u-text-ac u-mt-20">{{ Tag::link('/support/?p=937', 'ログインできない場合', ['class' => 'textlink']) }}</p>
        </form>
    </div>
</section>
@endsection
