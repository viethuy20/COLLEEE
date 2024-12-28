@php
$base_css_type = 'detail';
@endphp
@extends('layouts.default')
@section('layout.content')

<div class="contents">
    <div class="contents__box">
        <h1 class="programs_detail__ttl">三井ダイレクト損保の「強くてやさしいクルマの保険」</h1>
        <div class="programs_detail__box">
            <div class="programs_detail__box__l">
                <div class="programs_detail__box__thumb">
                    {{ Tag::image('images/mitsui-direct-icon.png', '三井ダイレクト損保の「強くてやさしいクルマの保険」') }}
                </div>
                <div class="programs_detail__box__twitter">
                    <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-lang="ja"
                        data-text="三井ダイレクト損保の「強くてやさしいクルマの保険」| GMOポイ活のオススメ広告" data-hashtags="ポイ活,ポイントサイト,お得,GMOポイ活"
                        data-show-count="false" data-size="large">Tweet</a>
                    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                </div><!-- programs_detail__box__twitter -->
            </div><!-- programs_detail__box__l -->
            <div class="programs_detail__box__r">
                <p class="programs_detail__box__txt">新規成約</p>
                <p class="special_programs_detail__box__point">
                    <span class="point-title">サーティワン アイスクリーム</span>
                    <span class="point-value">500円ギフト券プレゼント</span>
                </p>
            </div><!-- programs_detail__box__r -->
        </div><!-- programs_detail__box -->
        <div class="programs_detail__btn__pink u-mt-40">
            <a href="{{ $url }}" target="_blank">サービスを利用する</a>
        </div>
        <dl class="programs_detail__chart">
            <dt>予定反映目安</dt>
            <dd>即時</dd>
            <dt>獲得までの期間</dt>
            <dd>1か月以上</dd>
            <dt>獲得条件</dt>
            <dd>新規成約 ※本ページ下部に詳細あり</dd>
            <dt>注意事項</dt>
            <dd>本ページ下部に記載</dd>
        </dl>
    </div><!-- contents__box -->

    <div class="contents__box">
        <h2 class="contents__ttl">この広告について</h2>
        <div class="contents__box__ad">
            <div>ネット型自動車保険初！（注1）</div>
            <div>レスキュードラレコ（ドラレコ特約）</div>
            <div>ドライブレコーダーが一定以上の衝撃を検知すると、安否確認デスクに自動でつながるので安心です。（注2）</div>
            <div><br />（注1）2022年9月時点 三井ダイレクト損保調べ</div>
            <div>（注2）衝撃の程度や通信状況等によっては、事故の場合でも自動でつながらない場合があります。</div>
        </div>
    </div><!-- contents__box -->
    <div class="contents__box">
        <h2 class="contents__ttl">キャンペーン概要</h2>
        <div class="contents__box__ad">
            <div>
                ※<span>このキャンペーンはポイント対象外です。マイページの獲得予定ポイント、獲得履歴には反映されません。</span>
                <br />
                ※<span>新規成約で電子ギフト「サーティワン アイスクリーム 500円ギフト券」がもらえます。</span>
                <br />
                ※<span>お問い合わせ不可となります。あらかじめご了承ください。</span>
            </div>
            <div>&nbsp;</div>
            <div>【キャンペーン期間】</div>
            <div>・2023年9月15日（金）～2024年3月31日（日）</div>
            <div>&nbsp;</div>
            <div>【キャンペーン対象】</div>
            <div>・キャンペーン期間中にGMOポイ活経由にて、新規で三井ダイレクト損保の自動車保険・バイク保険のいずれかをご契約（お申込手続き完了）された方</div>
            <div>※三井ダイレクト損保から書類の提出をお願いされている方は、2024年3月31日（日）までに当該書類が到着していない場合、本キャンペーンの対象外となります。</div>
            <div>&nbsp;</div>
            <div>【キャンペーン賞品】</div>
            <div>・電子ギフト「サーティワン アイスクリーム 500円ギフト券」をプレゼント</div>
            <div>・契約計上の確認ができましたら、電子ギフト「サーティワン アイスクリーム 500円ギフト券」をお受け取りいただく際のメールを、GMOポイ活にご登録のメールアドレス宛にお送りいたします。そのメールの内容にしたがって、お手続きください。</div>
            <div>・賞品について予告なく変更する場合がございます。変更の際は本キャンペーン賞品と同額程度の賞品となります。</div>
            <div>&nbsp;</div>
            <div>【キャンペーン賞品のお届け時期】</div>
            <div>・【賞品のご案内】メールの送信は、ご契約（お申し込み手続き完了）月の翌月中旬以降、GMOポイ活にご登録のメールアドレス宛にメールで送信いたします。</div>
            <div>※ご契約時のメールアドレスではございませんので、ご注意ください。</div>
            <div>※契約計上の確認から最大で2ヶ月程度かかる場合がございます。あらかじめご了承ください。</div>
            <div>&nbsp;</div>
            <div>【キャンペーン対象外】</div>
            <div>・三井ダイレクト損保（もしくは代理店）が主催する他のキャンペーンの対象となる場合は、対象外となります。</div>
            <div>・三井ダイレクト損保の自動車保険もしくはバイク保険にご加入中で、継続契約となる場合は、対象外となります。</div>
            <div>・虚偽、不正、いたずら、重複、キャンセル、1世帯2回目以降のご契約</div>
            <div>・ギフト等について直接「三井ダイレクト損保」に問い合わせされた場合</div>
            <div>&nbsp;</div>
            <div>【キャンペーンに関するご注意事項】</div>
            <div>・本キャンペーンは、予告なく変更・終了する場合がございます。あらかじめご了承ください。</div>
            <div>・【賞品のご案内】のメールは「@colleee.net」のドメインから送信いたします。メールが正しく受信できるように、ドメイン指定受信、または迷惑メールの設定をされている場合は、メールを受信可能に設定してください。</div>
            <div>・メールアドレスの変更や誤登録等による配信先不明等の事由で、キャンペーン賞品の有効期限内にお送りできない場合は、キャンペーン賞品のお受け取りの権利を無効とさせていただきます。</div>
            <div>・キャンペーン賞品の交換・換金・返品等はできません。</div>
            <div>&nbsp;</div>
            <div>【免責事項】</div>
            <div>・本キャンペーンは、予告なしにキャンペーン画面や情報等の変更、中断または中止とさせていただく場合がございます。あらかじめご了承ください。</div>
            <div>・なお、当社は理由の如何に関わらず、画面や情報等の変更および中断または中止によって生じるいかなる損害についても責任を負うものではありません。</div>
            <div><br /><br /></div>
            ※サーティワン アイスクリーム 500円ギフト券の獲得は、上記「獲得までの期間」から若干前後することがあります。<br>
            この広告は問い合わせ不可となります。直接「三井ダイレクト損保」にお問い合わせされないようお願いいたします。<br>
            広告サイトより送付される登録完了・購入確認メール等は、会員様が広告に参加されたという重要な証拠書類となります。サーティワン アイスクリーム 500円ギフト券獲得完了まで大切に保管いただきますようお願いいたします。<br>
            <div>&nbsp;</div>
            <div>引受保険会社</div>
            <div>三井ダイレクト損害保険株式会社</div>
            <div>〒112-0004 東京都文京区後楽2丁目5番1号</div>
        </div>
        <div class="programs_detail__btn__wrap">
            <div class="programs_detail__btn__yellow">
                <a href="/support?p=86">【重要】参加に際してのご注意</a>
            </div>
            <div class="programs_detail__btn__pink">
                <a
                    href="/inquiries/3?title=%E4%B8%89%E4%BA%95%E3%83%80%E3%82%A4%E3%83%AC%E3%82%AF%E3%83%88%E6%90%8D%E4%BF%9D%E3%81%AE%E3%80%8C%E5%BC%B7%E3%81%8F%E3%81%A6%E3%82%84%E3%81%95%E3%81%97%E3%81%84%E3%82%AF%E3%83%AB%E3%83%9E%E3%81%AE%E4%BF%9D%E9%99%BA%E3%80%8D%E3%80%90%E5%88%9D%E5%9B%9E%E3%81%8A%E8%A6%8B%E7%A9%8D%E3%82%82%E3%82%8A%E4%BF%9D%E5%AD%98%E3%80%91">この広告についてのお問合せ</a>
            </div>
        </div>
    </div><!-- contents__box -->
</div>
@endsection