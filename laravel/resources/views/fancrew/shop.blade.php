@php
$shop_attributes = $shop->attributes();
$shop_name = $shop_attributes->name;
$rate = $monitor->Rate;
$map_url = \App\External\Google::getMapUrl($shop_attributes->latitude ?? null, $shop_attributes->longitude ?? null, $shop_attributes->address ?? null);
@endphp
@extends('layouts.fancrew')

@section('layout.title', $shop_name.'がモニター体験でお得になる！ | ポイントサイトならGMOポイ活')
@section('layout.keywords', 'モニター,覆面調査')
@section('layout.description', 'お店・商品の紹介です。'.$shop_attributes->description)
@section('og_type', 'website')
@php
$meta = new \App\Services\Meta;
$arr_breadcrumbs = $meta->setBreadcrumbs(null);
$application_json = '';
$position = 1;
foreach($arr_breadcrumbs as $key => $val) {
    $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
    $position++;
}
$link = route('fancrew.pages');
$application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "モニター（お店でお得）", "item": "' . $link . '"},';
$position++;
$link = route('fancrew.pages');
$application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "'.$shop_name.'", "item": "' . $link . '"},';

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
            {{ Tag::link(route('fancrew.pages'), 'モニター（お店でお得）') }}
        </li>
        <li>
            {{ $shop_name }}
        </li>
    </ol>
</section>
@endsection
@section('fancrew.content')
<section class="head_detail">
    <h1 class="ttl_review">{{ $shop_name }}</h1>
    <h2>お店を検索</h2>
</section>

<section class="monitor_detail">
    <div class="contentsbox" id="apply">
        <div class="img_service">{{ Tag::image($shop_attributes->topImageUrl, $shop_name, ['width' => 300, 'height' => 210]) }}</div>
        <div class="conditionbox">
            @if (isset($rate))
            @php
            $rate_attributes = $rate->attributes();
            @endphp
            <p class="point">
                <span class="icon-point"></span>
                @if ($rate_attributes->type == '固定')
                {{ number_format($rate_attributes->value * 1) }}pt
                @else
                お代金の{{ $rate_attributes->value }}%(上限
                @if (is_null($rate_attributes->limit) || $rate_attributes->limit == '')
                なし
                @else
                {{ number_format($rate_attributes->limit * 1) }}pt)
                @endif
                @endif
            </p>
            @endif
            @php
            $monitor_attritures = $monitor->attributes();
            @endphp
            @if (isset($shop_attributes->viewMode) && $shop_attributes->viewMode != 0 && isset($monitor_attritures))
            <dl class="conditions">
                @if (isset($monitor_attritures->approvingPeriod))
                <dt><span class="icon-time"></span>ポイント獲得時期：</dt>
                <dd>{{ $monitor_attritures->approvingPeriod }}</dd>
                @endif
                @if (isset($monitor_attritures->enqueteSubmitExpires))
                <dt><span class="icon-deadline"></span>提出期限：</dt>
                <dd>当選確定した日から{{ $monitor_attritures->enqueteSubmitExpires }}日間</dd>
                @endif
                <dt><span class="icon-worksheet"></span>提出物：</dt>
                <dd>アンケート（10問）、来店証明</dd>
                <dt><span class="icon-friend"></span>月間派遣人数：</dt>
                <dd>
                    @if (isset($monitor_attritures->numOfDispatch))
                    {{ number_format(intval($monitor_attritures->numOfDispatch, 10)) }}名まで
                    @endif
                    &nbsp;
                </dd>
            </dl>
            @endif
        </div><!--/conditionbox-->
        <p class="ta_r totop mt_5"><span class="icon-question"></span>&nbsp;{{ Tag::link(route('abouts.fancrew'), 'ご利用ガイド') }}</p>
        @php
        $user = \Auth::user();
        @endphp
        @if (isset($user->fancrew_account_number))
        <p class="btn_next btn_more">{{ Tag::link($shopEntryURL, '応募する') }}</p>
        @else
        {{ Tag::formOpen(['url' => route('fancrew_accounts.create')]) }}
        @csrf    
        {{ Tag::formHidden('url', $shopEntryURL) }}
            <div class="monitor_enquate">
                <span class="monitor_enquate__description">
                    <p>「お店でお得」は、株式会社ファンくるの運営サービス「ファンくる」と連携しております。</p>
                    <p>GMOポイ活を通じてのご利用であることの確認のため<br>下記をご入力いただき、入力情報を株式会社ファンくるに提供致します。</p>
                </span>
                <dl>
                    <dt>性別：</dt>
                    <dd>
                        {{ Tag::formSelect('gender', ['0' => '男性', '1' => '女性'], ((isset($user->sex) && $user->sex == 2) ? 1 : 0), ['class' => 'gender_select', 'required' => 'required',]) }}
                    </dd>
                    <dt>生年月日：</dt>
                    <dd>
                        {{ Tag::formText('birthday', isset($user->birthday) ? $user->birthday->format('Ymd') : '', ['class' => 'birthday','pattern' => '[0-9]*', 'required' => 'required', 'minlength' => '8', 'maxlength' => '8',]) }}<br />
                        <p>※西暦から半角数字8桁でご入力ください&nbsp;例：1980年1月20日→19800120</p>
                    </dd>
                </dl>

                <p class="monitor_enquate__consent">
                    当社の
                    {{ Tag::link(config('url.privacy_policy'), 'プライバシーポリシー', ['target' => '_blank', 'class' => 'external', 'rel' => 'noopener',]) }}
                    プライバシーポリシー</a>に沿って、入力情報が取り扱われることに同意しますか？
                </p>
            </div>
            {{ Tag::formButton('同意して応募', ['class' => 'monitor_form__btn', 'type' => 'submit']) }}
        {{ Tag::formClose() }}
        @endif
    </div><!--/contentsbox-->

    <h2>店舗情報</h2>
    <div class="contentsbox">
        <div class="shop_data">
            <p class="pt_5">{!! nl2br(e($shop_attributes->description)) !!}</p>
            <dl class="monitor_element clearfix">
                <dt>ジャンル</dt>
                <dd>{{ $shop->Genre->attributes()->name ?? ''}}&nbsp;</dd>
                <dt>平均予算</dt>
                <dd>{{ $shop_attributes->averageBudget }}&nbsp;</dd>
                <dt>営業時間</dt>
                <dd>{{ $shop_attributes->businessHours }}&nbsp;</dd>
                <dt>休日</dt>
                <dd>{{ $shop_attributes->fixedHoliday }}&nbsp;</dd>
                <dt>電話番号</dt>
                <dd>{{ $shop_attributes->phoneNumber }}&nbsp;</dd>
                <dt>住所</dt>
                <dd>
                    @if (isset($map_url) && isset($shop_attributes->address))
                    {{ Tag::link($map_url, $shop_attributes->address, ['target' => '_blank', 'class' => !empty($shop_attributes->address) ? 'external' : '']) }}
                    @endif
                    &nbsp;
                </dd>
                <dt>アクセス</dt>
                <dd>{{ $shop_attributes->access }}&nbsp;</dd>
                <dt>ホームページ</dt>
                <dd>
                    @if (isset($shop_attributes->pcUrl))
                    {{ Tag::link($shop_attributes->pcUrl, $shop_attributes->pcUrl, ['target' => '_blank', 'class' => !empty($shop_attributes->pcUrl) ? 'external' : '']) }}
                    @endif
                    &nbsp;
                </dd>
            </dl>
            <p class="pb_5">※ホームページから予約された場合、ポイント獲得の対象外となりますのでご注意ください。</p>
            <p class="btn_next btn_more"><a href="#apply">応募する</a></p>
        </div><!--/shop_data-->
    </div><!--/contentsbox-->
</section><!--/monitor_detail-->

<section class="side_search_menu">
    <div class="search_menu_small">
        @include('elements.fancrew_form_small')
    </div>
    <div class="monitoir">
        @include('elements.fancrew_s_shop_list')
        @include('elements.fancrew_p_shop_list')
        <div class="flogo">{!! Tag::image('/images/logo_fancrew.png', 'ファンくる') !!}</div>
    </div>
</section><!--/side_search_menu-->

@endsection
