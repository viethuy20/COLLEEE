<?php
    $base_css_type = 'entries';
    $hidden_header = true
?>
@extends('layouts.default')
{!! Tag::style('/css/sp_common_20240613.css') !!}
@section('layout.title', 'GMOポイ活新規会員登録｜ポイントサイトならGMOポイ活')
@section('layout.keywords', 'GMOポイ活,会員登録,無料')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。無料で会員登録して、いつもの生活を賢くお得に！')
@section('url', route('entries.index'))
@php
$meta = new \App\Services\Meta;
$arr_breadcrumbs = $meta->setBreadcrumbs(null);
$application_json = '';
$position = 1;
foreach($arr_breadcrumbs as $key => $val) {
    $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
    $position++;
}
$link = route('entries.index');
$application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "新規会員登録", "item": "' . $link . '"},';

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
            新規会員登録
        </li>
    </ol>
</section>
@endsection
@section('layout.content')
@php
        $now   = date('Y-m-d H:i:s');
        $entries = \App\Entries::where('entries.start_at', '<=', $now)
        ->where('entries.stop_at', '>=', $now)->first(); 
        $lineService = new \App\Services\Line\LineService();
        $urlLine = $lineService->getLoginBaseUrl();

        $previousUrl = url()->previous();
        $parsedUrl = parse_url($previousUrl);

        // スキームとホストを組み合わせて完全なベースURLを取得
        $base_url = $parsedUrl['scheme'] . "://" . $parsedUrl['host'];

        // ポート番号がある場合は、ベースURLに追加
        if (isset($parsedUrl['port'])) {
            $base_url .= ":" . $parsedUrl['port'];
        }

        $path = $parsedUrl['path'] ?? '';
        $query = $parsedUrl['query'] ?? '';

        // パスがprograms/{数字}という形式（案件詳細ページのURL）とホストが同じ場合、セッションに保存
        if ($base_url == config('app.url') && ( preg_match('#^/programs/\d+$#', $path) || preg_match('#^/sp_program.*#', $path) )) {
            session()->put('last_visited_program_page', $path);
            session()->put('login_source', $previousUrl);
        }

        parse_str($query, $parameters);

        // 会員登録ページの前のページのURLを取得
        $previousPageURL = $parameters['referer'] ?? null;

        // 前のページのURLが存在する場合、セッションに保存
        if ($previousPageURL) {
            session()->put('referrer_url', $previousPageURL);
        }
@endphp

    <div class="contents">
        <!-- page title -->
        <!-- 新規会員登録 -->
        @if (isset($entries))
        <div class="entries__cmp">
            <p>{!! nl2br(e($entries['main_text_sp'])) !!} <br /><span>{!! nl2br(e($entries['sub_text_sp'])) !!}</span></p>
        </div>
        @endif
        <div class="contents__box pb-0">

            <div class="contents__box__inner">
                <h2 class="contents__ttl">GMOポイ活新規会員登録</h2>
                <div class="contents__box__txt">
                    <p>各種アカウントやメールアドレスで、簡単に無料会員登録ができます。</p>
                </div>
                <div class="entries__style has-hidden">
                    <ul class="entries__style__cont">
                        <li class="entries__sns__btn line"><a href="{{$urlLine}}" class=""><i><img src="/images/common/ico_line.svg"></i><p>LINEアカウントで登録する</p></a></li>
                        <li class="entries__sns__btn other"><a class="js-entry-open"><p>その他SNS・メールアドレスで登録する</p></a></li>
                    </ul>
                    <div class="entries__style__hidden js-entry-hidden">
                        <ul>
                            <li class="entries__sns__btn google"><a href="{{ route('users.create.google') }}" class=""><i><img src="/images/common/ico_google.svg"></i><p>Googleで登録する</p></a></li>
                        </ul>
                        @if($errors->has('error_login_gg'))
                        <p class="form-error__message js-error-message red">
                                <span class="icon-attention"></span> {{ $errors->first('error_login_gg') }}
                        </p>
                        @endif
                        <div class="entries__separator">または</div>
                        {!! Tag::formOpen(['url' => route('entries.send'), 'class' => 'entries__form js-entry-form', 'accept-charset' => 'UTF-8']) !!}
                        @csrf
                        <div class="entries__style__cont">
                                <dl>
                                    <dt class="js-bg-none"><label class="head" for="email" style="display:none;">メールアドレス</label></dt>
                                    <dd>
                                        {!! Tag::formText('email', '', ['placeholder' => 'メールアドレスをご入力ください', 'required' => 'required', 'pattern'=>'[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,4}', 'maxlength' => '64' ]) !!}
                                        <ul class="notes">
                                            <li>半角文字のみ入力可能</li>
                                        </ul>
                                        <p class="form-error__message js-error-message"></p>
                                        @if ($errors->has('email'))
                                        <!--エラーの場合はここに-->
                                        <p class="form-error__message"><span class="icon-attention"></span>{{ $errors->first('email') }}</p>
                                        @endif
                                        @if (Session::has('message'))
                                        <p class="form-error__message"><span class="icon-attention"></span>{{ Session::get('message') }}</p>
                                        @endif
                                    </dd>
                                </dl>
                                <div class="terms__wrap">
                                    @include('inc.personal-data')
                                    <p class="terms__checkbox">
                                        <input id="consent" name="consent" type="checkbox" value="1">
                                        <label for="consent">「<a href="{{ route('abouts.membership_contract') }}" target="_blank" class="textlink blank">GMOポイ活会員利用規約</a>」および「個人情報の取扱いについて」を確認し、同意します。</label>
                                    </p>
                                </div>
                                <div class="contents__btn__wrap">
                                    <div class="contents__btn">
                                        {!! Tag::formButton('メールアドレスで登録する', ['type' => 'submit', 'disabled' => 'disabled', 'id' => 'submit']) !!}
                                    </div>
                                </div>
                            </div>
                        {!! Tag::formClose() !!}
                    </div>
                </div>
                <div class="contents__textlink">すでに会員の方は<a href="{{ route('login', ['back' => 0]) }}" class="textlink">こちらからログイン</a></div>
            </div>
            @include('inc.beginner-guide')
        </div>
    </div>


<!-- error connective line -->
@if (session()->has('error_line') || session()->has('error_google'))
    {{ Tag::style('https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css') }}
    <style type="text/css">
        .modal {
            max-width: 500px;
            font-size: 16px;
            border-radius: 0px;
            padding: 10px 31px;
            max-height: calc(200vh - 400px);
        }
        .access_condition {
            color: #f39800;
            font-size: 2rem;
            text-decoration: underline;
            text-decoration-color: #f39800;
        }

        .modal .block-img img {
            width: 80px;
            display: block;
            margin: auto;
        }

        .modal h5 {
            line-height: 1.6;
            text-align: center;
        }

        .modal .pb-14 {
            font-size: 14px;
        }
    </style>

    <div id="model_error" class="modal">
        <div class="block-img img179 mt-10 mb-10">
            {{Tag::image("/images/icon_caution.svg")}}
        </div>
        @if (session()->has('error_line'))
        <div class="pb-14 pt-9">
            <br>
            <h5 class="modal-title text-bold fs-15 m-0 text1">LINE上に登録されている</h5>
            <h5 class="modal-title text-bold fs-15 m-0 text1">メールアドレスが確認できないため、</h5>
            <h5 class="modal-title text-bold fs-15 m-0 text1">LINE連携を利用した会員登録が</h5>
            <h5 class="modal-title text-bold fs-15 m-0 text1">できません。</h5><br>
            <h5 class="modal-title text-bold fs-15 m-0 text1">LINEの設定内容をご確認</h5>
            <h5 class="modal-title text-bold fs-15 m-0 text1">いただくか、通常の会員登録を</h5>
            <h5 class="modal-title text-bold fs-15 m-0 text1">ご利用ください。</h5>
        </div>
        @endif
        @if (session()->has('error_google'))
        <div class="pb-14 pt-9">
            <br>
            <h5 class="modal-title text-bold fs-15 m-0 text1">通常の会員登録をご利用ください。</h5>
        </div>
        @endif
    </div>
    {{ Tag::script('https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js', ['type' => 'text/javascript']) }}
    <script type="text/javascript"> $('#model_error').modal('show');</script>
@endif
<!-- end error -->
{!! Tag::script('/js/sp_entries.js', ['type' => 'text/javascript']) !!}


@endsection
@section('layout.footer_notes')
@php
    $footNotes = 'guide';
@endphp
@include('inc.foot-notes', ['footNotes' => $footNotes])
@endsection