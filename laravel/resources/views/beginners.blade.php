@php
$base_css_type = 'guide';
@endphp
@extends('layouts.plane')
{!! Tag::style('/css/common_20240613.css') !!}
@section('layout.title', 'GMOポイ活の使い方｜ポイントサイトならGMOポイ活')
@section('layout.keywords', 'GMOポイ活,GMOポイ活の使い方')
@section('layout.description', 'GMOポイ活ではお得に利用できるサービスや商品、情報をたくさん紹介！毎日の生活がお得になるGMOポイ活の使い方、ポイントの貯め方や活用方法をご案内♪')
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
$link = route('beginners');
$application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "GMOポイ活の使い方", "item": "' . $link . '"},';

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
            GMOポイ活の使い方
        </li>
    </ol>
</section>
@endsection
@section('layout.content')

    <!-- main contents -->
    <div class="contents">

        <!-- page title -->
        <h2 class="guide__ttl">GMOポイ活の使い方</h2>

        <!-- page nav -->
        <div class="contents__box beginer u-mt-40">
            <div class="guide__nav">
                <ul>
                    <li><a class="anchor" href="#step">ポイント獲得のステップ</a></li>
                    <li><a class="anchor" href="#schedule">ポイント獲得の時期</a></li>
                    <li><a class="anchor" href="#howto">ポイント交換方法</a></li>
                </ul>
            </div>
        </div>

        <!-- ポイント獲得のステップ -->
        <div class="contents__box u-mt-small" id="step">
            <h2 class="text--24 orange">ポイント獲得のステップ</h2>
            <div class="guide__step">
                <ul>
                    <li>
                        <p class="num"><span>1</span>サービスを選択</p>
                        <div class="image">{{ Tag::image('/images/guide/guide_step_1.png') }}</div>
                    </li>
                    <li>
                        <p class="num"><span>2</span>「ポイントを獲得」をクリック</p>
                        <div class="image">{{ Tag::image('/images/guide/guide_step_2.png') }}</div>
                    </li>
                    <li>
                        <p class="num"><span>3</span>サービスページにて獲得条件を満たす</p>
                        <div class="image">{{ Tag::image('/images/guide/guide_step_3.png') }}</div>
                    </li>
                </ul>
            </div>
            <div class="guide__step__img">
                {{ Tag::image('/images/guide/guide_step_img.png', 'GMOポイ活を経由するだけでお得にポイント獲得♪') }}
            </div>
            <p class="text--15 u-mt-40">ポイントが正常に反映されるよう、下記を必ずご確認ください。</p>
            <p class="text--15"><a class="textlink" href="/support/?p=916">ポイント獲得に関する利用環境の確認</a></p>
        </div>

        <!-- 主なポイントの獲得例 -->
        <div class="contents__box u-mt-small">
            <h2 class="text--24 orange">主なポイントの獲得例</h2>
            <div class="guide__example">
                <ul>
                    <li>{{ Tag::image('/images/guide/guide_example1.png', 'ネットショップでのお買い物') }}</li>
                    <li>{{ Tag::image('/images/guide/guide_example2.png', 'クレジットカード発行') }}</li>
                    <li>{{ Tag::image('/images/guide/guide_example3.png', 'ホテルや航空券などの予約') }}</li>
                    <li>{{ Tag::image('/images/guide/guide_example4.png', '無料会員登録・セミナーへの参加') }}</li>
                    <li>{{ Tag::image('/images/guide/guide_example5.png', 'ゲームアプリで') }}</li>
                    <li>{{ Tag::image('/images/guide/guide_example6.png', 'お友達にGMOポイ活を紹介') }}</li>
                </ul>
            </div>
            <p class="text--15 u-mt-40">などなど、普段の生活がもっとお得になります。<br>
            その他アンケートやゲーム、街中のお店のご利用などでもポイントを貯める事ができます。</p>
        </div>

        <!-- ポイント獲得の時期 -->
        <div class="contents__box u-mt-small" id="schedule">
            <h2 class="text--24 orange">ポイント獲得の時期</h2>
            <p class="text--16 u-font-bold u-mt-20">サービス詳細画面</p>
            <div class="guide__schedule">
                <div class="guide__schedule__imege">{{ Tag::image('/images/guide/guide_service_screen.png') }}</div>
                <div class="guide__schedule__content">
                    <ul>
                        <li>
                            <p class="ttl"><span>A</span>獲得ポイントが反映される時期</p>
                            <p class="text--15">記載の時期にマイページ「現在の獲得予定ポイント」にて確認可能になります。<br>※この時点でのポイント交換はできません。</p>
                        </li>
                        <li>
                            <p class="ttl"><span>B</span>実際にポイントが貰える時期</p>
                            <p class="text--15">記載の時期にマイページ「所持ポイント」に追加されます。<br>※獲得時期目安は、若干前後する場合があります。</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- ポイント交換方法 -->
        <div class="contents__box u-mt-small" id="howto">
            <h2 class="text--24 orange">ポイント交換方法</h2>
            <div class="guide__howto">
                <ul>
                    <li>
                        <p class="ttl"><span>1</span>交換したいポイント・ギフトサービスまたは現金を選択</p>
                        <p class="txt">獲得したポイントは300円分から電子マネーや現金などに交換ができます♪</p>
                        <ul class="notes">
                            <li>KDOLのみ100円分から交換可能</li>
                        </ul>
                        <div class="list">
                            <ul>
                                <li>{{ Tag::image('/images/exchanges/img_bank.png', '現金（銀行振込）') }}</li>
                                <li>{{ Tag::image('/images/exchanges/img_amazon.png', 'Amazonギフトカード') }}</li>
                                <li>{{ Tag::image('/images/exchanges/img_pex.png', 'PeXポイントギフト') }}</li>
                                <li>{{ Tag::image('/images/exchanges/img_money.png', 'ドットマネー') }}</li>
                                <li>{{ Tag::image('/images/exchanges/img_paypay.png', 'PayPayポイント') }}</li>
                                <li>{{ Tag::image('/images/exchanges/img_edy.png', 'EdyギフトID') }}</li>
                                <li>{{ Tag::image('/images/exchanges/img_waon.png', 'WAONポイントID') }}</li>
                                <li>{{ Tag::image('/images/exchanges/img_dpoint.png', 'd POINT') }}</li>
                                <li>{{ Tag::image('/images/exchanges/img_digital-gift.png', 'デジタルギフト') }}</li>
                                <li>{{ Tag::image('/images/exchanges/img_jal.png', 'JALマイル') }}</li>
                                <li>{{ Tag::image('/images/exchanges/img_paypal.png', 'PayPal') }}</li>
                                {{-- <li>{{ Tag::image('/images/exchanges/img_linepay.png', 'LINE Pay') }}</li> --}}
                                <li>{{ Tag::image('/images/exchanges/img_google.png', 'Google Play ギフトコード') }}</li>
                                <li>{{ Tag::image('/images/exchanges/img_apple.png', 'Apple Gift Card') }}</li>
                                <li>{{ Tag::image('/images/exchanges/img_ponta.png', 'Pontaポイント') }}</li>
                                <li>{{ Tag::image('/images/exchanges/img_pssticket.png', 'プレイステーション ストアチケット') }}</li>
                                <li>{{ Tag::image('/images/exchanges/img_kdol.png', 'KDOL') }}</li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <p class="ttl"><span>2</span>説明・注意事項の確認</p>
                        <p class="txt">※現金交換の場合、振込先の銀行を選択、支店名を入力</p>
                        <div class="image">
                            {{ Tag::image('/images/guide/guide_howto_2.png') }}
                        </div>
                    </li>
                    <li>
                        <p class="ttl"><span>3</span>交換したいポイント・ギフト金額を選択、入力情報を確認</p>
                        <p class="txt">※現金交換の場合、口座番号、氏名、交換金額を入力、入力情報を確認</p>
                        <div class="image">
                            {{ Tag::image('/images/guide/guide_howto_3.png') }}
                        </div>
                    </li>
                    <li>
                        <p class="ttl"><span>4</span>電話発信認証を行い申請完了</p>
                        <p class="txt">※電話発信認証に関するご質問は、下記お問い合わせ画面にてお問い合わせください。<br>
                        <a class="textlink" href="{{ route('inquiries.index', ['inquiry_id' => 10]) }}">お問い合わせはこちら</a></p>
                        <div class="image">
                            {{ Tag::image('/images/guide/guide_howto_4.png') }}
                        </div>
                    </li>
                </ul>
            </div>
            
        </div>

        <!-- その他注意事項 -->
        <div class="contents__box u-mt-small">
            <h2 class="text--24">その他注意事項</h2>
            <div class="u-mt-20">
                <p class="text--15 red u-font-bold">獲得条件・注意事項をよくお読みください</p>
                <p class="text--15">獲得予定ポイントを保有していても、「獲得条件・注意事項」に記載のあるポイント配布対象外の行為を行った場合、ポイントは配布されません。</p>
            </div>
            <div class="u-mt-20">
                <p class="text--15 red u-font-bold">ご不明点がございましたら、まずはヘルプセンターをご覧ください</p>
                <p class="text--15">基本的な事項はヘルプセンターにて記載しています。<br>
                <a class="textlink" href="/help">ヘルプセンターはこちら</a></p>
            </div>
            <div class="u-mt-20">
                <p class="text--15">ヘルプセンターをご覧になってもわからない・対処できない場合は、下記お問い合わせ画面にてお問合せください。<br>
                <a class="textlink" href="{{ route('inquiries.index', ['inquiry_id' => 10]) }}">お問い合わせはこちら</a></p>
            </div>
        </div>

    </div>
@endsection
@section('layout.footer_notes')
@php
    $footNotes = '';
@endphp
@include('inc.foot-notes', ['footNotes' => $footNotes])
@endsection