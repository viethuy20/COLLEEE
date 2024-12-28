@php
$base_css_type = 'login';
$use_recaptcha = true;
@endphp
@extends('layouts.plane')
@section('layout.head')
{!! Tag::style('/css/common_20240613.css') !!}
@endsection
@php
$meta = new \App\Services\Meta;
$arr_breadcrumbs = $meta->setBreadcrumbs(null);
$application_json = '';
$position = 1;
foreach($arr_breadcrumbs as $key => $val) {
    $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
    $position++;
}
$link = route('login');
$application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "ログイン", "item": "' . $link . '"},';

@endphp
@section('layout.breadcrumbs')
<section class="header__breadcrumb">
    <ol>
        @foreach($arr_breadcrumbs as $item)
            <li>
                <a href="{{ $item['link'] }}">{{ $item['title'] }}</a>
            </li>
        @endforeach
        <li>
            ログイン
        </li>
    </ol>
</section>

@endsection

@section('layout.title', 'GMOポイ活にログイン｜ポイントサイトならGMOポイ活')
@section('layout.keywords', 'GMOポイ活,ログイン')
@section('layout.description', '無料で簡単にお小遣いが貯められるポイントサイトGMOポイ活のログインページです。貯めたポイントの確認や、現金やギフト券への交換はこちらから！')
@section('og_type', 'website')

@section('layout.content')
@php
    if (!session()->has('login_source')) {
        $previousUrl = url()->previous();
        $parsedUrl = parse_url($previousUrl);

        $path = $parsedUrl['path'] ?? '';

        // パスがprograms/{数字}という形式（案件詳細ページのURL）であるかを確認
        if (preg_match('#^/programs/\d+$#', $path) || preg_match('#^/sp_program.*#', $path)) {
            session()->put('login_source', $path);
        }
    }
@endphp
<!-- main contents -->
	<!-- main contents -->
	<div class="contents">
		<!-- page title -->
		<h2 class="contents__ttl">GMOポイ活ログイン</h2>
		<!-- 新規会員登録 -->
		<div class="contents__box pb-0">
            <div class="contents__box__inner">
                <div class="entries__style">
                    <div class="entries__mail">
                        {{ Tag::formOpen(['url' => route('login'), 'class' => \App\External\Google::getRecaptchaClass().' entries__form js-entry-form', 'forGoogleRecaptchaAction' => 'login']) }}
                        @csrf
                        {{ Tag::formHidden(\App\External\Google::getRecaptchaParamKey(), '') }}
                            {{ Tag::formHidden('referer', $referer ?? route('website.index')) }}
                            <div class="contents__box__txt">
                                <h3 class="contents__box__ttl">メールアドレスでログイン</h3>
                            </div>
                            <div class="entries__style__cont">
                                <dl>
                                    <dt class="js-bg-none"><label class="head" for="email">メールアドレス</label></dt>
                                    <dd>
                                        <input required="required" id="email" name="email" pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,4}" maxlength="64" type="email" value="{{ old('email') }}" placeholder="">
                                        <p class="form-error__message js-error-message"></p>
                                        @if ($errors->has('email'))
                                        <p class="form-error__message"><span class="icon-attention"></span>{{ $errors->first('email') }}</p>
                                        @endif
                                    </dd>
                            </dl>
                            <dl>
                                <dt class="js-bg-none"><label class="head" for="password">パスワード</label></dt>
                                <dd>
                                    <input required="required" id="password" name="password" pattern="^[a-zA-Z0-9!\#$%&amp;+\-.&lt;\=&gt;?@^_~]+$" maxlength="64" type="password" value="{{ old('password') }}" placeholder="">
                                    <p class="form-error__message js-error-message"></p>
                                    @if ($errors->has('password'))
                                    <p class="form-error__message"><span class="icon-attention"></span>{{ $errors->first('password') }}</p>
                                    @endif
                                </dd>
                            </dl>
                                @if (session()->has('message'))
                                <p class="form-error__message"><span class="icon-attention"></span>{{ session('message') }}</p>
                                @endif
                                <div class="terms__wrap">
                                    <p>「<a href="{{ route('abouts.membership_contract') }}" target="_blank" class="textlink">GMOポイ活会員利用規約</a>」および「<a href="https://www.koukoku.jp/privacy/" target="_blank" class="textlink blank">個人情報の取り扱いについて</a>」に同意の上、ログインしてください。 </p>
                                </div>
                                <div class="contents__btn__wrap">
                                    <div class="contents__btn">
                                        <button id="mailsubmit" type="submit">メールアドレスでログイン</button>
                                    </div>
                                </div>
                            </div>
                        {{ Tag::formClose() }}
                        <div class="contents__textlink"><a target="_blank" href="{{ route('reminders.index') }}" class="textlink">パスワードをお忘れの場合</a><a target="_blank" href='/support/?p=937' class="textlink">ログインできない場合</a></div>
                    </div>
                    <div class="entries__sns">
                        <div class="contents__box__txt">
                            <h3 class="contents__box__ttl">他サービスでログイン</h3>
                        </div>
                        <ul class="entries__style__cont">
                            <li class="entries__sns__btn line"><a href="{{$urlLine}}" class=""><i><img src="/images/common/ico_line.svg"></i><p>LINEアカウントでログイン</p></a></li>
                            <li class="entries__sns__btn google"><a href="{{ route('users.create.google') }}" class=""><i><img src="/images/common/ico_google.svg"></i><p>Googleでログイン</p></a></li>
                        </ul>
                        <ul class="notes">
                            <li>既にGMOポイ活にご登録済みの方で他サービスでログインに変更したい場合は、メールアドレスでログイン後、マイページの「基本情報変更」から各サービスとの連携をお願いいたします。</li>
                        </ul>
                        @if($errors->has('error_login_gg'))
                        <p class="form-error__message js-error-message red">
                            <span class="icon-attention"></span> {{ $errors->first('error_login_gg') }}
                        </p>
                    @endif
                    </div>

                </div>
                <div class="contents__box__cv">
                    @php
                    $cv = 'deco min';
                    @endphp
                    @include('inc.cv-btn',['cv' => $cv])
                </div>
            </div>
            @include('inc.beginner-guide')
        </div>
    </div>

<!-- /main contents -->
{!! Tag::script('/js/entries.js', ['type' => 'text/javascript']) !!}
@endsection
@section('layout.footer_notes')
@php
    $footNotes = 'guide';
@endphp
@include('inc.foot-notes', ['footNotes' => $footNotes])
@endsection