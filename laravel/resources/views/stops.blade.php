@php
$base_css_type = 'guide';
@endphp
@extends('layouts.plane')

@section('layout.title', '不正行為への取り組み｜ポイントサイトならGMOポイ活')
@section('layout.keywords', 'GMOポイ活,不正行為への取り組み')
@section('layout.description', 'でたらめな個人情報でのサービス利用・商品購入、他人のアカウントの使用、複数アカウントで多重サービスの利用、他人名義でのポイント交換など禁止されている不正行為に対して、GMOポイ活の取り組みをご紹介して...')
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
$link = route('stops');
$application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "不正行為への取り組み", "item": "' . $link . '"},';

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
            不正行為への取り組み
        </li>
    </ol>
</section>
@endsection
@section('layout.content')
    <!-- main contents -->
    <div class="contents">

        <!-- contents -->
        <div class="contents__box stop">
            <div class="guide__flex">
                <div class="guide__flex__l">
                    <h2 class="contents__ttl red">不正行為に対して厳正に対処しています！</h2>
                    <p class="text--15 u-mt-20">GMOポイ活では利用規約に反する行為や、<br>広告提携先に迷惑のかかる行為を禁止しています。</p>
                </div>
                <div class="guide__flex__r">
                    {{ Tag::image('/images/guide/guide_mv.png', 'STOP!不正行為') }}
                </div>
            </div>

            <!-- 不正行為とは -->
            <h3 class="text--24 red u-font-bold u-text-ac u-mt-40">不正行為とは</h3>
            <div class="illegal__list">
                <ul>
                    <li>
                        <div class="ttl">なりすまし</div>
                        <p class="txt">でたらめな個人情報でのサービス利用・商品購入</p>
                        <p class="image">{{ Tag::image('/images/guide/guide_illegal1.png', 'なりすまし') }}</p>
                    </li>
                    <li>
                        <div class="ttl">のっとり</div>
                        <p class="txt">他人のアカウントの使用</p>
                        <p class="image">{{ Tag::image('/images/guide/guide_illegal2.png', 'のっとり') }}</p>
                    </li>
                    <li>
                        <div class="ttl">ユーザーIDの重複</div>
                        <p class="txt">複数アカウントで多重サービスの利用</p>
                        <p class="txt_small">※1アカウント＝1ユーザーです。</p>
                        <p class="image">{{ Tag::image('/images/guide/guide_illegal3.png', 'ユーザーIDの重複') }}</p>
                    </li>
                    <li>
                        <div class="ttl">利用者以外の<br>ポイント交換</div>
                        <p class="txt">利用者以外の他人名義でのポイント交換</p>
                        <p class="image">{{ Tag::image('/images/guide/guide_illegal4.png', '利用者以外のポイント交換') }}</p>
                    </li>
                </ul>
            </div>
            <div class="illegal__textbox">
                <p class="text--15">不正行為が多発することで、広告提供先に被害が生じ、結果として広告の掲載が終了となり、皆さまの健全なご利用ができなくなってしまいます。<br>GMOポイ活はユーザーの皆さまに安心してご利用いただけるよう、不正行為の撲滅に取り組んでまいります。</p>
            </div>

            <!-- 不正が発覚した場合 -->
            <h3 class="text--24 red u-font-bold u-text-ac u-mt-40">不正が発覚した場合</h3>
            <p class="text--24 red u-font-bold u-text-ac u-mt-20"><span class="marker">アカウントおよび</span><br><span class="marker">所持ポイントの抹消を行います。</span></p>
            <p class="text--15 u-text-ac u-mt-small">悪質な不正行為に対しては法的な措置を含めて<br>厳正な対処を実施します。</p>
            <div class="illegal__info">
                <div class="image">
                    {{ Tag::image('/images/guide/guide_img1.png') }}
                </div>
                <p class="text--18 u-font-bold">GMOポイ活は皆さまの生活を<br>ちょっとお得にできるようサービスを提供していきます。<br>皆様の健全なご利用をお願いいたします。</p>
            </div>
        </div>

    </div>

@endsection
