<?php $base_css_type = 'status'; ?>
@extends('layouts.default')

@section('layout.title', '会員ランク特典・条件｜ポイントサイトならGMOポイ活')
@section('layout.keywords', 'GMOポイ活,会員ランク,特典')
@section('layout.description', '使えば使うほどお得になるGMOポイ活のランク制度について紹介しています。ランクアップ条件をクリアして、様々な特典を受け取りましょう！')
@section('og_type', 'website')
@section('url', route('abouts.member_rank') )
@php
$meta = new \App\Services\Meta;
$arr_breadcrumbs = $meta->setBreadcrumbs(null);
$application_json = '';
$position = 1;
foreach($arr_breadcrumbs as $key => $val) {
    $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
    $position++;
}
$link = route('abouts.member_rank');
$application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "会員ランク特典・条件", "item": "' . $link . '"},';

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
            会員ランク特典・条件
        </li>
    </ol>
</section>
@endsection
@section('layout.content')

<!-- main contents -->
<div class="contents">

    <!-- page title -->
    <h1>
        {!! Tag::image('/images/member_rank/rank_ttl.png', '使えば使うほどお得！ランクアップ条件をクリアして嬉しい特典を受け取ろう！') !!}
    </h1>


    @if(Auth::check())
    <!-- 会員ランク -->
    <div class="contents__box u-mt-20">
        <div class="member_rank__box">
            <h2 class="contents__ttl u-text-ac">会員ランク</h2>
            <div class="u-mt-40">
                {!! Tag::image('/images/member_rank/rank_img.png', '会員ランク') !!}
            </div>
            <?php $user = Auth::user(); ?>
            @if (isset($user->id))
            <div class="member_rank__txt">あなたは現在<span>{{ config('map.user_rank')[$user->rank] }}会員</span>です</div>
            @endif
        </div>
    </div>
    @endif

    <!-- 会員ランク特典 -->
    <div class="contents__box u-mt-20">
        <h2 class="contents__ttl u-text-ac">会員ランク特典</h2>
        <div class="member_rank__list u-mt-40">
            <ul>
                <li>
                    <div class="image">
                        {!! Tag::image('/images/member_rank/special_1.png', 'ボーナスポイント') !!}
                    </div>
                    <p class="txt">参加した広告の報酬の最大10%をボーナスとしてプレゼント！</p>
                </li>
                <li>
                    <div class="image">
                        {!! Tag::image('/images/member_rank/special_2.png', 'ポイント交換の手数料がお得に！') !!}
                    </div>
                    <p class="txt">ポイント交換時の手数料が最大無料に！申込みポイントをまるっと交換可能♪</p>
                </li>
                <li>
                    <div class="image">
                        {!! Tag::image('/images/member_rank/special_3.png', '誕生日ポイント') !!}
                    </div>
                    <p class="txt">誕生日に最大500ポイントの誕生日ポイントをプレゼント！</p>
                </li>
            </ul>
        </div>
    </div>


    <!-- ランク認定条件 -->
    <div class="contents__box u-mt-20">
        <h2 class="contents__ttl u-text-ac">ランク認定条件</h2>
        <h3 class="text--18 orange u-font-bold u-mt-40">ランク特典を受けるための必須条件</h3>
        <p class="text--15 u-mt-small">一般会員からシルバー会員以上にランクアップするためには、以下の条件を満たしている必要があります。</p>

        @php
        $bonus_rate_map = config('bonus.bonus_rate');
        $birthday_map = config('bonus.birthday');
        @endphp
        <h3 class="text--18 orange u-font-bold u-mt-40">ランク特典</h3>
        <div class="member_rank__tb u-mt-20">
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>一般{!! Tag::image('/images/member_rank/ico_general.png', '一般アイコン') !!}</th>
                        <th>シルバー{!! Tag::image('/images/member_rank/ico_silver.png', 'シルバーアイコン') !!}</th>
                        <th>ゴールド{!! Tag::image('/images/member_rank/ico_gold.png', 'ゴールドアイコン') !!}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>誕生日ポイント</th>
                        <td></td>
                        <td><div class="pt">{{ $birthday_map[\App\UserRank::SILVER] }}<span>P</span></div></td>
                        <td><div class="pt">{{ $birthday_map[\App\UserRank::GOLD] }}<span>P</span></div></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td></td>
                        <td><span class="plus">＋</span></td>
                        <td><span class="plus">＋</span></td>
                    </tr>
                    <tr>
                        <th>ボーナスポイント</th>
                        <td></td>
                        <td><div class="pt">{{ $bonus_rate_map[\App\UserRank::SILVER]*100 }}<span>%</span></div></td>
                        <td><div class="pt">{{ $bonus_rate_map[\App\UserRank::GOLD]*100 }}<span>%</span></div></td>
                </tr>
                    <tr>
                        <th></th>
                        <td></td>
                        <td><span class="plus">＋</span></td>
                        <td><span class="plus">＋</span></td>
                </tr>
                    <tr>
                        <th>交換手数料</th>
                        <td></td>
                        <td><div class="fee">半額<br>（月1回無料）</div></td>
                        <td><div class="fee">無料<br>（無制限）</div></td>
                    </tr>
                    <tr class="each">
                        <th>条件</th>
                        <td>期間中に広告へ<br>3回未満参加</td>
                        <td>広告に3回参加<br>または2,000pt獲得</td>
                        <td>広告に5回参加<br>かつ10,000pt（1万円分）以上獲得<br><br>※90日以上継続は廃止</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="text--14 u-text-right u-mt-20">※「広告へ参加」は、ポイントを獲得した時点を指します。</p>
    </div>


    <!-- 会員ランク特典・条件についての注意事項 -->
    <div class="contents__box u-mt-20">
        <h2 class="contents__ttl ico_ttl"><i><img src="/images/questions/ico_caution.svg"></i>会員ランク特典・条件についての注意事項</h2>

        <h3 class="text--18 orange u-font-bold u-mt-40">ランクアップについて</h3>
        <p class="text--15 u-mt-small">ランク特典を受けるための必須条件を満たしていない場合、会員ランク認定条件をクリアしていたとしても、「一般会員」のままとなります。</p>

        <h3 class="text--18 orange u-font-bold u-mt-40">会員ランク認定条件について</h3>
        <p class="text--15 u-mt-small">毎月、過去6ヶ月間の獲得ポイント数または広告参加数に応じて会員ランクの認定が行われます。対象期間中に会員ランク認定条件を満たすと、1日4:00以降から新しい会員ランクが有効となります。<br>
        <br>
        ※1：同じ広告（ショップなど）を複数回利用した場合も、広告参加数としてカウントされます。<br>
        ※2：「獲得履歴 ＞ 広告参加履歴」に反映されている獲得済みのポイントを獲得ポイント数としてカウントします。<br>
        会員ランクおよびボーナスポイントの適用期間は、当月1日4:00～翌月1日3:59までとなります。新規入会完了時点で、会員ランクは「一般会員」となります。</p>

        <h3 class="text--18 orange u-font-bold u-mt-40">会員ランク特典について</h3>
        <p class="text--15 u-mt-small">ボーナスポイントの対象となる広告は、広告詳細にて「ランク特典対象」の表記があるもののみとなります。<br>
        誕生日ポイントについて、お誕生日当日にGMOポイ活にご登録頂いているメールアドレス宛にメールをお送りします。そのメール内に記載されているURLをクリックすることで誕生日ポイントを受け取ることが可能です。<br>
        受け取りの有効期限が誕生日から30日以内となっておりますので、ご注意ください。</p>

        <h3 class="text--18 orange u-font-bold u-mt-40">その他</h3>
        <p class="text--15 u-mt-small">・ポイント交換手数料の詳細はこちらをご覧ください。<br>
        ・ランク特典および条件は、予告なく変更される場合がありますのでご了承下さい。<br>
        ・会員様の不正が発覚した場合、会員ランクを変更させていただくことがございます。</p>
    </div>
</div>

@endsection
