@php
$base_css_type = 'friends';
@endphp
@extends('layouts.default')

@section('layout.breadcrumbs')
<section class="header__breadcrumb">
    <ol>
        <li>{{ Tag::link(route('website.index'), 'ホーム') }}</li>
        <li>友達紹介</li>
    </ol>
</section>
@endsection

@section('layout.head')
<!-- スムーズスクロール部分の記述 -->
<script type="text/javascript"><!--
$(function () {
    $('.accordion dt.like_h').on('click', function (event) {
        $(this).next("dd.inside").slideToggle();
        $(this).next("dd.inside").siblings("dd.inside").slideUp();
        $(this).toggleClass("open");
        $(this).siblings("dt.like_h").removeClass("open");
    });
});
        //-->
    </script>
    @endsection

    @section('layout.title', '友達紹介｜ポイントサイトならGMOポイ活')
    @section('layout.keywords', 'GMOポイ活,友達紹介')
    @section('layout.description', 'お友達がGMOポイ活に入会するごとに紹介ボーナスがもらえる！お友達を紹介すればするほど特典がアップ！お友達と一緒にお得にポイ活♪')
    @section('og_type', 'website')

    @section('layout.content')
    @php
    $user = Auth::user();
    $share_url = isset($user->id) ? route('entries.index').'?'.http_build_query([config('share.friend_key') => $user->friend_code, config('share.promotion_key') => 3]) : null;

    $reward_condition_point             = $friend_referral_bonus['reward_condition_point'];
    $friend_referral_bonus_point        = $friend_referral_bonus['friend_referral_bonus_point'];
    $format_reward_condition_point      = number_format($friend_referral_bonus['reward_condition_point']);
    $format_friend_referral_bonus_point = number_format($friend_referral_bonus['friend_referral_bonus_point']);
    @endphp

    <div class="inner">
        <h1 class="contents__ttl">
            <img src="../images/friends/friends_ttl_sp.png" alt="友達紹介でポイントが貯まる">
        </h1>
    </div>

    <section class="inner" id="getpoint">
        <div class="contents__box u-mt-20">
        @if (isset($user->id))
            <h2 class="contents__ttl u-text-ac orange">あなたの紹介URL</h2>
            <div class="blog__form u-mt-remove">
                {{ Tag::formText('url', $share_url, ['onclick' => "this.select();this.focus();this.selectionStart=0;this.selectionEnd=this.value.length;"]) }}
            </div>

            <h2 class="contents__ttl u-text-ac orange">あなたの紹介コード</h2>
            <div class="blog__form u-mt-remove">
                {{ Tag::formText('user_name', $user->name, ['onclick' => "this.select();this.focus();this.selectionStart=0;this.selectionEnd=this.value.length;"]) }}
            </div>
        @endif
            <h2 class="contents__ttl u-text-ac u-mt-40">簡単！ポイントがどんどんたまる<br>2つの方法！</h2>
            <div class="friends__point">
                <ul>
                    <li>
                        <div class="imege"><img src="../images/friends/friends_point2.png"></div>
                        <div class="ttl">1人登録する度に{{$format_friend_referral_bonus_point}}ポイント</div>
                        <div class="txt">お友達があなたの紹介URLからご登録、もしくはあなたの紹介コードを入会時にご入力すると、１人につき{{$format_friend_referral_bonus_point}}ポイントもらえます！</div>
                    </li>
                    <li>
                        <div class="imege"><img src="../images/friends/friends_point3.png" alt="友達が獲得したポイントの5～10%分のポイントをGET！"></div>
                        <div class="ttl">友達が獲得したポイントの<br>5～10%分のポイントをGET！</div>
                        <div class="txt">紹介した友達が広告利用で獲得したポイントの5～10%分ポイントがもらえます！</div>
                    </li>
                </ul>
            </div>
        </div>
    </section><!--/getpoint-->

    <section class="inner">
        <h2 class="contents__ttl u-mt-20">お友達紹介ボーナス解説</h2>
        <div class="contents__box u-mt-small">
            <h3 class="text--18 orange">お友達紹介ボーナスポイント</h3>
            <div class="friends__bonus__list">
                <dl class="">
                    <dt>配布条件</dt>
                    <dd>紹介したお友達が入会し、入会月の翌々月末までに{{$format_reward_condition_point}}ポイント以上獲得した場合</dd>
                    <dt>配布時期</dt>
                    <dd>紹介したお友達の合計獲得ポイントが{{$format_reward_condition_point}}ポイントに到達した翌月の10日（集計期間は最大で入会日～入会月の翌々月末まで）</dd>
                    <dt>配布数</dt>
                    <dd>紹介お一人につき{{$format_friend_referral_bonus_point}}ポイント</dd>
                </dl>
            </div>
            <h3 class="text--18 orange u-mt-40">お友達獲得ポイント</h3>
            <div class="friends__bonus__list">
                <dl class="">
                    <dt>配布条件</dt>
                    <dd>紹介されたお友達が前月に対象（※1）の広告で配布されたポイント</dd>
                    <dt>配布時期</dt>
                    <dd>毎月10日</dd>
                    <dt>配布数</dt>
                    <dd>5%～10%（※2）</dd>
                </dl>
            </div>
            <p class="friends__bonus__caution text--15 u-mt-40"><img src="../images/friends/ico_caution.svg">友達紹介ボーナスについての注意事項</p>
            <p class="text--15">※1 対象の広告とは広告詳細ページの「ボーナス」に友達紹介の記載がされている広告になります。<br>
                ※2 友達獲得ポイントは、当月の紹介人数によって報酬利率が変動いたします。<br>
                （0～10人：5%　11～20人：6% 　21～30人：7%　31～40人：8%　41～50人：9%　51人以上：10%）<br>
            ご紹介いただいたお友達が、1週間以内に退会もしくはメール不達になった場合、「友達紹介ボーナスポイント」及び「お友達獲得ポイント」の対象外となりますのでご注意下さい。</p>
        </div>
    </section>

    @if (isset($user->id))
    <nav class="whichway">
        <h2>選べる紹介方法</h2>
        <div class="friends__list">
            <ul>
                <li>
                    {{ Tag::link(config('url.line_share').'?'.rawurlencode($share_url), '
                    <div class="image"><img src="../images/friends/ico_line.png" alt="LINE"></div>
                    <div class="txt">LINEで<br>紹介する</div>', ['target' => '_brank'], null, false) }}
                </li>
                <li>
                    {{ Tag::link(config('url.twitter_share').'?'.http_build_query(['text' => '', 'url' => $share_url]), '
                    <div class="image"><img src="../images/friends/ico_tw.png" alt="Twitter"></div>
                    <div class="txt">Xで<br>紹介する</div>', ['target' => '_brank'], null, false) }}
                </li>
                <li>
                    {{ Tag::link(config('url.facebook_share').'?'.http_build_query(['u' => $share_url]), '
                    <div class="image"><img src="../images/friends/ico_fb.png" alt="facebook"></div>
                    <div class="txt">facebookで<br>紹介する</div>', ['target' => '_brank'], null, false) }}
                </li>
                <li><a href="#something">
                    <div class="image"><img src="../images/friends/ico_blog.png" alt="ブログ・HP"></div>
                    <div class="txt">ブログ・HPで<br>紹介する</div></a>
                </li>
                <li><a href="#barcode">
                    <div class="image"><img src="../images/friends/ico_qr.png" alt="QRコード"></div>
                    <div class="txt">QRコードで<br>紹介する</div></a>
                </li>
                <li>{{ Tag::link('mailto:'.'?'.http_build_query(['body' => '「GMOポイ活」を使うだけでいつもの買い物がちょっとお得になるよ♪'.$share_url]), '
                    <div class="image"><img src="../images/friends/ico_mail.png" alt="メール"></div>
                    <div class="txt">メールで<br>紹介する</div>', null, null, false) }}
                </li>
            </ul>
        </div>
    </nav>
    <section class="inner" id="u_info">
        <div class="contents__box u-mt-small p_url p_url_content mr_15 ml_15 noborder-t">
            <h2 class="contents__ttl u-text-ac orange">あなたの紹介URL</h2>
            {{ Tag::formText('url', $share_url, ['onclick' => "this.select();this.focus();this.selectionStart=0;this.selectionEnd=this.value.length;"]) }}

            <h2 class="contents__ttl u-text-ac orange">あなたの紹介コード</h2>
            {{ Tag::formText('user_name', $user->name, ['onclick' => "this.select();this.focus();this.selectionStart=0;this.selectionEnd=this.value.length;"]) }}
        </div>
    </section>

    <!--/p_url-->

    <section id="something">
        <h2 class="contents__ttl u-mt-20" id="blog">ブログ・HPで紹介する</h2>
        <div class="something_bnr">
            <div class="friends__introduce__accordion">
                <!-- そのまま使えるテキスト文 -->
                <input id="block-01" type="checkbox" class="toggle">
                <label class="label" for="block-01">そのまま使えるテキスト文</label>
                <div class="content">
                    <p class="copy_txt">コピー</p>
                    <div class="copy_area">
                        @php $clip = <<< EOM
                        はじめてのポイ活は「GMOポイ活」！

                        「GMOポイ活」を経由していつものネットショッピングやサービスを利用するだけでポイントが貯まります！
                        お買い物をしなくても、無料のゲームで遊んだりアンケートに答えたりするだけで、毎日コツコツ貯まります♪

                        貯まったポイントは、現金やギフト券、各種ポイントなど、さまざまな交換先からお好きなものに交換！

                        お得な日がわかる「お得なお買い物カレンダー」でキャンペーン情報やセール情報をいち早く知ることもできますよ♪

                        ■「GMOポイ活」のおすすめポイント
                        ・GMOインターネットグループが運営しているので安全性はおりがみ付き！初心者でも安心です。
                        ・毎週100件以上の新規広告を掲載！
                        ・ポイント交換は最低300ポイント（300円相当）から可能！
                        ・ポイ活に役立つ記事コンテンツで、お得なキャンペーン情報がすぐにわかる！
                        {$share_url}
                        EOM;
                        @endphp
                        {{ Tag::formTextarea('clip', $clip, ['readonly' => 'readonly', 'class' => 'put_code', 'onclick' => "this.select();this.focus();this.selectionStart=0;this.selectionEnd=this.value.length;"]) }}
                    </div>
                </div>

                <!-- バナー画像（300 x 250） -->
                <input id="block-02" type="checkbox" class="toggle">
                <label class="label" for="block-02">バナー画像（300 x 250）</label>
                <div class="content">
                    @php
                    $img_list = [
                    Tag::image('/images/friends/gmo-poikatsu_01_300_250.jpg', 'はじめてのポイ活はGMOポイ活 利用料ずーっと0円 運営実績20年越の老舗サイト 運営会社GMOインターネットグループ会社！', ['width' => 300, 'height' => 250]),
                    Tag::image('/images/friends/gmo-poikatsu_02_300_250.jpg', 'お買い物で、旅行で、アプリDLで、ポイントを貯めてギフト券や現金と交換！はじめてのポイ活はGMOポイ活', ['width' => 300, 'height' => 250]),
                    Tag::image('/images/friends/gmo-poikatsu_03_300_250.jpg', 'アプリダウンロード・ゲームプレイでポイントが貯まる！簡単・無料でおこづかい稼ぎ はじめてのポイ活はGMOポイ活', ['width' => 300, 'height' => 250]),
                    Tag::image('/images/friends/gmo-poikatsu_04_300 _250.jpg', '推しを応援してポイントもらえる！貯めたポイントは現金・電子マネーなどに交換できる！はじめてのポイ活はGMOポイ活', ['width' => 300, 'height' => 250]),
                    ];
                    @endphp
                    @foreach ($img_list as $i => $img)
                    {{ $img }}
                    <p class="copy_txt">コピー</p>
                    {{ Tag::formTextarea('clip', (string) Tag::link($share_url, $img, null, null, false), ['class' => 'put_code_bnr', 'onclick' => "this.select();this.focus();this.selectionStart=0;this.selectionEnd=this.value.length;"]) }}
                    @endforeach
                </div>

                <!-- バナー画像（320 x 100） -->
                <input id="block-03" type="checkbox" class="toggle">
                <label class="label" for="block-03">バナー画像（320 x 100）</label>
                <div class="content">
                    @php
                    $img_list = [
                    Tag::image('/images/friends/gmo-poikatsu_01_320_100.jpg', 'はじめてのポイ活はGMOポイ活 利用料ずーっと0円 運営実績20年越の老舗サイト 運営会社GMOインターネットグループ会社', ['width' => 320, 'height' => 100]),
                    Tag::image('/images/friends/gmo-poikatsu_02_320_100.jpg', 'お買い物で、旅行で、アプリDLで、ポイントを貯めてギフト券や現金と交換！はじめてのポイ活はGMOポイ活', ['width' => 320, 'height' => 100]),
                    Tag::image('/images/friends/gmo-poikatsu_03_320_100.jpg', 'アプリダウンロード・ゲームプレイでポイントが貯まる！簡単・無料でおこづかい稼ぎ はじめてのポイ活はGMOポイ活', ['width' => 320, 'height' => 100]),
                    Tag::image('/images/friends/gmo-poikatsu_04_320 _100.jpg', '推しを応援してポイントもらえる！貯めたポイントは現金・電子マネーなどに交換できる！はじめてのポイ活はGMOポイ活', ['width' => 320, 'height' => 100]),
                    ];
                    @endphp
                    @foreach ($img_list as $i => $img)
                    {{ $img }}
                    <p class="copy_txt">コピー</p>
                    {{ Tag::formTextarea('clip', (string) Tag::link($share_url, $img, null, null, false), ['class' => 'put_code_bnr', 'onclick' => "this.select();this.focus();this.selectionStart=0;this.selectionEnd=this.value.length;"]) }}
                    @endforeach
                </div>

                <!-- バナー画像（728 x 90） -->
                <input id="block-04" type="checkbox" class="toggle">
                <label class="label" for="block-04">バナー画像（728 x 90）</label>
                <div class="content">
                    @php
                    $img_list = [
                    Tag::image('/images/friends/gmo-poikatsu_01_728_90.jpg', 'はじめてのポイ活はGMOポイ活 利用料ずーっと0円 運営実績20年越の老舗サイト 運営会社GMOインターネットグループ会社', ['width' => 728, 'height' => 90]),
                    Tag::image('/images/friends/gmo-poikatsu_02_728_90.jpg', 'お買い物で、旅行で、アプリDLで、ポイントを貯めてギフト券や現金と交換！はじめてのポイ活はGMOポイ活', ['width' => 728, 'height' => 90]),
                    Tag::image('/images/friends/gmo-poikatsu_03_728_90.jpg', 'アプリダウンロード・ゲームプレイでポイントが貯まる！簡単・無料でおこづかい稼ぎ はじめてのポイ活はGMOポイ活', ['width' => 728, 'height' => 90]),
                    Tag::image('/images/friends/gmo-poikatsu_04_ 728 _90.jpg', '推しを応援してポイントもらえる！貯めたポイントは現金・電子マネーなどに交換できる！はじめてのポイ活はGMOポイ活', ['width' => 728, 'height' => 90]),
                    ];
                    @endphp
                    @foreach ($img_list as $i => $img)
                    {{ $img }}
                    <p class="copy_txt">コピー</p>
                    {{ Tag::formTextarea('clip', (string) Tag::link($share_url, $img, null, null, false), ['class' => 'put_code_bnr', 'onclick' => "this.select();this.focus();this.selectionStart=0;this.selectionEnd=this.value.length;"]) }}
                    @endforeach
                </div>
            </div>
            <div id="barcode">
                <h2 class="contents__ttl u-mt-40" id="qr">QRコードで紹介</h2>
                <div class="barcode_qr">
                    <p class="ta_c mb_15 userbarcode">{{ Tag::image('/images/friends/qr.png'.'?'.http_build_query(['d' => $share_url, 's' => 164]), '') }}</p>
                    <p>表示されているQRコードを、アプリなどを使用して読み取ってもらうか、コピーしてお友達に送信してください。</p>
                    <p class="tm mb_20">QRコードの商標はデンソーウェーブの登録商標です。</p>
                </div>
            </div><!--/barcode-->
            @else
            <div class="notlogin">
                <div class="login_member">
                    <p class="done">友達紹介には、ログインが必要です。</p>
                    <p class="mb_15">GMOポイ活に登録していない場合は、「新規会員登録」からご登録ください。</p>
                    {{ Tag::link(route('entries.index'), '<p class="btn_blog">新規会員登録</p>', null, null, false) }}
                </div>
            </div>
            @endif
        </div>
    </section><!--/something-->
    <div class="f_note">
        <div class="text_note">
            <p class="friends__introduce__caution text--15 u-mt-40"><img src="../images/friends/ico_caution.svg">注意事項</p>
            <ul>
                <li>ご紹介いただいたお友達が、初めてGMOポイ活に登録した場合のみ、友達紹介成立となります。</li>
                <li>ご紹介いただいたお友達が、1週間以内に退会もしくはメール不達になった場合、友達紹介ボーナスポイント及びお友達獲得ポイントの対象外となりますのでご注意下さい。</li>
                <li>友達紹介ボーナスの「配布ポイント」および「配布条件の合計獲得ポイント数」は、予告なく変動する場合があります。それぞれ入会月のポイント数が適用となりますので、予めご了承ください。</li>
                <li>お友達獲得ポイントは、ご紹介いただいたお友達が、対象の広告に参加し当月配布されたポイントの5～10%を翌月の10日にポイントで付与致します。</li>
                <li>お友達獲得ポイントは、当月の紹介人数によって報酬利率が変動いたします。<br />(0～10人：5%　11～20人：6% 　21～30人：7%　31～40人：8%　41～50人：9%　51人以上：10%)</li>
                <li>お友達獲得ポイントは1人のお友達につき登録完了後1年間有効となります。</li>
                <li>お友達獲得ポイントはお友達紹介対象広告のみが対象となります。(「会員ボーナスポイント」「紹介ボーナスポイント」「友達獲得ポイント」「アンケート」「ゲーム」「メールクリック」などは対象外)</li>
                <li>紹介を行う際に発行されたURLや紹介タグは変更しないでください。変更されますと友達紹介と認定されない場合があります。</li>
                <li>お友達がスマートフォン端末からご登録をされる場合には、必ず標準ブラウザ(Safari等)にてご登録をお願いいたします。アプリ内ブラウザからご登録を行われた場合には、紹介が正常に完了しない場合がございます。</li>

                <li>
                    下記記載事項にあてはまる場合には、予告なく強制退会または、ポイントの没収となる場合がございますので、ご注意ください。
                    <ul class="example">
                        <li>※スパムや虚偽内容による不正な紹介</li>
                        <li>※自分で複数のメールアドレスを使用し登録を行った場合</li>
                        <li>※同一IPからのご登録（ご家族で同居されている場合など、同一IPでの登録は友達紹介と認定されません。）</li>
                        <li>※登録したらパスワードを教えるなど、交換条件を提示し登録を促している場合やパスワード付き記事でのご紹介</li>
                        <li>※サービスのご紹介以外の文言にて登録を促している場合</li>
                        <li>※アダルト要素を含むものなど、当サイトイメージを著しく損なう様な内容・表現方法での紹介を行った場合</li>
                        <li>※ご登録されたお友達にご獲得の意思がない方が多数いらっしゃる場合</li>
                    </ul>
                </li>
            </ul>
        </div>
    </div><!--/f_note-->
    @endsection
