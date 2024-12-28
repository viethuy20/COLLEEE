@php
$base_css_type = 'friends';
@endphp
@extends('layouts.default')

@section('layout.head')

<!--tab-->
<script type="text/javascript">
$(function(){
    $(".friends__introduce__tab a").click(function(){
        $(this).parent().addClass("active").siblings(".active").removeClass("active");
        var credit_cards_list__order = $(this).attr("href");
        $(credit_cards_list__order).addClass("active").siblings(".active").removeClass("active");
        return false;
    });
});
</script>
@endsection

@section('layout.title', '友達紹介｜ポイントサイトならGMOポイ活')
@section('layout.keywords', 'GMOポイ活,友達紹介')
@section('layout.description', 'お友達がGMOポイ活に入会するごとに紹介ボーナスがもらえる！お友達を紹介すればするほど特典がアップ！お友達と一緒にお得にポイ活♪')
@section('og_type', 'website')

@php
$meta = new \App\Services\Meta;
$arr_breadcrumbs = $meta->setBreadcrumbs(null);
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
            友達紹介
        </li>
    </ol>
</section>
@endsection

@section('layout.content')
@php
$user = Auth::user();
$share_url = isset($user->id) ? route('entries.index').'?'.http_build_query([config('share.friend_key') => $user->friend_code, config('share.promotion_key') => 3]) : null;

$reward_condition_point             = $friend_referral_bonus['reward_condition_point'];
$format_reward_condition_point      = number_format($friend_referral_bonus['reward_condition_point']);
$friend_referral_bonus_point        = $friend_referral_bonus['friend_referral_bonus_point'];
$format_friend_referral_bonus_point = number_format($friend_referral_bonus['friend_referral_bonus_point']);
@endphp

<div class="contents">
    <h1>{{ Tag::image('/images/friends/friends_ttl.png', '友達紹介でポイントが貯まる') }}</h1 >
    <div class="contents__box u-mt-20">
        @if (isset($user->id))
        <form class="blog__form u-mt-remove u-mb-20">
            <h2 class="contents__ttl u-text-ac orange">あなたの紹介URL</h2>
            {{ Tag::formText('url', $share_url, ['onclick' => "this.select();this.focus();this.selectionStart=0;this.selectionEnd=this.value.length;"]) }}
        </form>

        <form class="blog__form u-mt-remove u-mb-20">
            <h2 class="contents__ttl u-text-ac orange">あなたの紹介コード</h2>
            {{ Tag::formText('user_name', $user->name, ['onclick' => "this.select();this.focus();this.selectionStart=0;this.selectionEnd=this.value.length;"]) }}
        </form>
        @endif

        <h2 class="contents__ttl u-text-ac u-mt-40">簡単！ポイントがどんどんたまる2つの方法！</h2>
        <div class="friends__point">
           <ul>
                <li>
                    <p class="image">{{ Tag::image('/images/friends/friends_point2.png') }}</p>
                    <div class="ttl">1人登録する度に{{$format_friend_referral_bonus_point}}ポイント</div>
                    <div class="txt">お友達があなたの紹介URLからご登録、もしくはあなたの紹介コードを入会時にご入力すると、１人につき{{$format_friend_referral_bonus_point}}ポイントもらえます！</div>
                </li>
                <li>
                    <p class="image">{{ Tag::image('/images/friends/friends_point3.png', '友達が獲得したポイントの5～10%分のポイントをGET！') }}</p>
                    <div class="ttl">友達が獲得したポイントの<br>5～10%分のポイントをGET！</div>
                    <div class="txt">紹介した友達が広告利用で獲得したポイントの5～10%分ポイントがもらえます！</div>
                </li>
            </ul>
        </div>
    </div><!--/container01-->

    @if (isset($user->id))
    <div class="friends__list u-mt-20">
        <ul>
            <li>
                <a href="#ui-tab">
                    <div class="image"><img src="/images/friends/ico_blog.png" alt="ブログ・HP"></div>
                    <div class="txt">ブログ・HPで<br>紹介する</div>
                </a>
            </li>
            <li>{{ Tag::link(config('url.facebook_share').'?'.http_build_query(['u' => $share_url]), '<div class="image">'.Tag::image('/images/friends/ico_fb.png', 'facebook ロゴ').'</div><div class="txt">facebookで<br>紹介する</div>', ['target' => '_brank'], null, false) }}</li>
            <li>{{ Tag::link(config('url.twitter_share').'?'.http_build_query(['text' => '', 'url' => $share_url]), '<div class="image">'.Tag::image('/images/friends/ico_tw.png', 'Twitter ロゴ').'</div><div class="txt">Xで<br>紹介する</div>', ['target' => '_brank'], null, false) }}</li>
        </ul><!--/btns-->
    </div>
    @endif

    @if (!isset($user->id))
    <p id="not_login">
        <span>友達紹介には、ログインが必要です。</span><br />
        GMOポイ活に登録していない場合は、「新規会員登録」からご登録ください。
    </p>
    <p>{{ Tag::link(route('entries.index'), '新規会員登録', ['class' => 'btn_send u-mb-20'], null, false) }}</p>
    @endif

    @if (isset($user->id))
    <h2 class="contents__ttl u-mt-40" id="blog">ブログ・HPで紹介する</h2>
    <div class="friends__introduce__tab" id="ui-tab">
        <ul>
            <li class="active"><a href="#fragment-1">バナーを使って紹介</a></li>
            <li><a href="#fragment-2">そのまま使えるオススメ文で紹介</a></li>
        </ul><!--ui-tab-->
    </div>

    <!-- バナーを使って紹介 -->
    <div class="friends__introduce active" id="fragment-1">
        <div class="contents__box">
            <div class="friends__introduce__bnr">
                <ul>
                    @php
                    $img_list = [
                        Tag::image('/images/friends/gmo-poikatsu_01_300_250.jpg', 'はじめてのポイ活はGMOポイ活 利用料ずーっと0円 運営実績20年越の老舗サイト 運営会社GMOインターネットグループ会社', ['width' => 300, 'height' => 250]),
                        Tag::image('/images/friends/gmo-poikatsu_01_320_100.jpg', 'はじめてのポイ活はGMOポイ活 利用料ずーっと0円 運営実績20年越の老舗サイト 運営会社GMOインターネットグループ会社', ['width' => 320, 'height' => 100]),
                        Tag::image('/images/friends/gmo-poikatsu_01_728_90.jpg', 'はじめてのポイ活はGMOポイ活 利用料ずーっと0円 運営実績20年越の老舗サイト 運営会社GMOインターネットグループ会社', ['width' => 728, 'height' => 90]),
                    ];
                    $imgSize = [
                        '300 x 250px',
                        '320 x 100px',
                        '728 x 90px',
                    ];
                    @endphp
                    @foreach ($img_list as $i => $img)
                    @php
                    $div_id = 'ta3-'.$i;

                    @endphp
                    <li>
                        <p class="ttl">バナー: {{ $imgSize[$i] }}</p>
                        <div class="image">{{ $img }}</div>
                        <p class="copy_txt">コピー</p>
                        <div onclick="cClick('{{ $div_id }}')" id="{{ $div_id }}" class="copy_area">
                            {{ (string) Tag::link($share_url, $img, null, null, false) }}
                        </div>
                    </li>
                    @endforeach
                </ul>

                <ul>
                    @php
                    $img_list = [
                        Tag::image('/images/friends/gmo-poikatsu_02_300_250.jpg', 'お買い物で、旅行で、アプリDLで、ポイントを貯めてギフト券や現金と交換！はじめてのポイ活はGMOポイ活', ['width' => 300, 'height' => 250]),
                        Tag::image('/images/friends/gmo-poikatsu_02_320_100.jpg', 'お買い物で、旅行で、アプリDLで、ポイントを貯めてギフト券や現金と交換！はじめてのポイ活はGMOポイ活', ['width' => 320, 'height' => 100]),
                        Tag::image('/images/friends/gmo-poikatsu_02_728_90.jpg', 'お買い物で、旅行で、アプリDLで、ポイントを貯めてギフト券や現金と交換！はじめてのポイ活はGMOポイ活', ['width' => 728, 'height' => 90]),
                    ];
                    $imgSize = [
                        '300 x 250px',
                        '320 x 100px',
                        '728 x 90px',
                    ];
                    @endphp
                    @foreach ($img_list as $i => $img)
                    @php
                    $div_id = 'ta6-'.$i;
                    @endphp
                    <li>
                        <p class="ttl">バナー: {{ $imgSize[$i] }}</p>
                        <div class="image">{{ $img }}</div>
                        <p class="copy_txt">コピー</p>
                        <div onclick="cClick('{{ $div_id }}')" id="{{ $div_id }}" class="copy_area">
                            {{ (string) Tag::link($share_url, $img, null, null, false) }}
                        </div>
                    </li>
                    @endforeach
                </ul>
                <ul>
                    @php
                    $img_list = [
                        Tag::image('/images/friends/gmo-poikatsu_03_300_250.jpg', 'アプリダウンロード・ゲームプレイでポイントが貯まる！簡単・無料でおこづかい稼ぎ はじめてのポイ活はGMOポイ活', ['width' => 300, 'height' => 250]),
                        Tag::image('/images/friends/gmo-poikatsu_03_320_100.jpg', 'アプリダウンロード・ゲームプレイでポイントが貯まる！簡単・無料でおこづかい稼ぎ はじめてのポイ活はGMOポイ活', ['width' => 320, 'height' => 100]),
                        Tag::image('/images/friends/gmo-poikatsu_03_728_90.jpg', 'アプリダウンロード・ゲームプレイでポイントが貯まる！簡単・無料でおこづかい稼ぎ はじめてのポイ活はGMOポイ活', ['width' => 728, 'height' => 90]),
                    ];
                    $imgSize = [
                        '300 x 250px',
                        '320 x 100px',
                        '728 x 90px',
                    ];
                    @endphp
                    @foreach ($img_list as $i => $img)
                    @php
                    $div_id = 'ta3-'.$i;

                    @endphp
                    <li>
                        <p class="ttl">バナー: {{ $imgSize[$i] }}</p>
                        <div class="image">{{ $img }}</div>
                        <p class="copy_txt">コピー</p>
                        <div onclick="cClick('{{ $div_id }}')" id="{{ $div_id }}" class="copy_area">
                            {{ (string) Tag::link($share_url, $img, null, null, false) }}
                        </div>
                    </li>
                    @endforeach
                </ul>
                <ul>
                    @php
                    $img_list = [
                        Tag::image('/images/friends/gmo-poikatsu_04_300 _250.jpg', '推しを応援してポイントもらえる！貯めたポイントは現金・電子マネーなどに交換できる！はじめてのポイ活はGMOポイ活', ['width' => 300, 'height' => 250]),
                        Tag::image('/images/friends/gmo-poikatsu_04_320 _100.jpg', '推しを応援してポイントもらえる！貯めたポイントは現金・電子マネーなどに交換できる！はじめてのポイ活はGMOポイ活', ['width' => 320, 'height' => 100]),
                        Tag::image('/images/friends/gmo-poikatsu_04_ 728 _90.jpg', '推しを応援してポイントもらえる！貯めたポイントは現金・電子マネーなどに交換できる！はじめてのポイ活はGMOポイ活', ['width' => 728, 'height' => 90]),
                    ];
                    $imgSize = [
                        '300 x 250px',
                        '320 x 100px',
                        '728 x 90px',
                    ];
                    @endphp
                    @foreach ($img_list as $i => $img)
                    @php
                    $div_id = 'ta6-'.$i;
                    @endphp
                    <li>
                        <p class="ttl">バナー: {{ $imgSize[$i] }}</p>
                        <div class="image">{{ $img }}</div>
                        <p class="copy_txt">コピー</p>
                        <div onclick="cClick('{{ $div_id }}')" id="{{ $div_id }}" class="copy_area">
                            {{ (string) Tag::link($share_url, $img, null, null, false) }}
                        </div>
                    </li>
                    @endforeach
                </ul>


            </div>
        </div>
    </div>

    <!-- そのまま使えるオススメ文で紹介 -->
    <div class="friends__introduce" id="fragment-2">
        <div class="contents__box">
            <div class="friends__introduce__txt">
                <p class="txt">
                    はじめてのポイ活は「GMOポイ活」！<br>
                    <br>
                    「GMOポイ活」を経由していつものネットショッピングやサービスを利用するだけでポイントが貯まります！<br>
                    お買い物をしなくても、無料のゲームで遊んだりアンケートに答えたりするだけで、毎日コツコツ貯まります♪<br>
                    <br>
                    貯まったポイントは、現金やギフト券、各種ポイントなど、さまざまな交換先からお好きなものに交換！<br>
                    <br>
                    お得な日がわかる「お得なお買い物カレンダー」でキャンペーン情報やセール情報をいち早く知ることもできますよ♪<br>
                    <br>
                    ■「GMOポイ活」のおすすめポイント<br>
                    ・GMOインターネットグループが運営しているので安全性はおりがみ付き！初心者でも安心です。<br>
                    ・毎週100件以上の新規広告を掲載！<br>
                    ・ポイント交換は最低300ポイント（300円相当）から可能！<br>
                    ・ポイ活に役立つ記事コンテンツで、お得なキャンペーン情報がすぐにわかる！<br>
                    <br>
                    ▼無料会員登録はこちら<br>
                    {{ $share_url }}
                </p>
                <p class="copy_txt u-mt-40">コピー</p>
                <div class="copy_area">
                    <pre>
<code>はじめてのポイ活は「GMOポイ活」！

「GMOポイ活」を経由していつものネットショッピングやサービスを利用するだけでポイントが貯まります！
お買い物をしなくても、無料のゲームで遊んだりアンケートに答えたりするだけで、毎日コツコツ貯まります♪

貯まったポイントは、現金やギフト券、各種ポイントなど、さまざまな交換先からお好きなものに交換！

お得な日がわかる「お得なお買い物カレンダー」でキャンペーン情報やセール情報をいち早く知ることもできますよ♪

■「GMOポイ活」のおすすめポイント
・GMOインターネットグループが運営しているので安全性はおりがみ付き！初心者でも安心です。
・毎週100件以上の新規広告を掲載！
・ポイント交換は最低300ポイント（300円相当）から可能！
・ポイ活に役立つ記事コンテンツで、お得なキャンペーン情報がすぐにわかる！

▼無料会員登録はこちら
{{ $share_url }}</code>
                        </pre>
                    </div>
                </div><!--/exam-->
            </div>
        </div><!--/fragment-2-->
        @endif
        <!-- <p class="returntotop"><a href="#">▲ページ上へ戻る</a></p> -->

    <!-- お友達紹介ボーナス解説 -->
    <h2 class="contents__ttl u-mt-40">お友達紹介ボーナス解説</h2>
    <div class="contents__box">
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
        <p class="friends__bonus__caution text--15 u-mt-40"><img src="/images/questions/ico_caution.svg">友達紹介ボーナスについての注意事項</p>
        <p class="text--15">※1 対象の広告とは広告詳細ページの「ボーナス」に友達紹介の記載がされている広告になります。<br>
        ※2 友達獲得ポイントは、当月の紹介人数によって報酬利率が変動いたします。<br>
        （0～10人：5%　11～20人：6% 　21～30人：7%　31～40人：8%　41～50人：9%　51人以上：10%）<br>
        ご紹介いただいたお友達が、1週間以内に退会もしくはメール不達になった場合、「友達紹介ボーナスポイント」及び「お友達獲得ポイント」の対象外となりますのでご注意下さい。</p>
    </div>

    <!-- <p class="returntotop"><a href="#">▲ページ上へ戻る</a></p> -->

    <section class="notes">
        <h2 class="contents__ttl u-mt-40">注意事項</h2>
        <div class="contents__box u-mt-small">
            <ul class="contents__note">
                <li class="text--14">ご紹介いただいたお友達が、初めてGMOポイ活に登録した場合のみ、友達紹介成立となります。</li>
                <li class="text--14">ご紹介いただいたお友達が、1週間以内に退会もしくはメール不達になった場合、友達紹介ボーナスポイント及びお友達獲得ポイントの対象外となりますのでご注意下さい。</li>
                <li class="text--14">友達紹介ボーナスの「配布ポイント」および「配布条件の合計獲得ポイント数」は、予告なく変動する場合があります。それぞれ入会月のポイント数が適用となりますので、予めご了承ください。</li>
                <li class="text--14">お友達獲得ポイントは、ご紹介いただいたお友達が、対象の広告に参加し当月配布されたポイントの5～10%を翌月の10日にポイントで付与致します。</li>
                <li class="text--14">お友達獲得ポイントは、当月の紹介人数によって報酬利率が変動いたします。<br />(0～10人：5%　11～20人：6% 　21～30人：7%　31～40人：8%　41～50人：9%　51人以上：10%)</li>
                <li class="text--14">お友達獲得ポイントは1人のお友達につき登録完了後1年間有効となります。</li>
                <li class="text--14">お友達獲得ポイントはお友達紹介対象広告のみが対象となります。(「会員ボーナスポイント」「紹介ボーナスポイント」「友達獲得ポイント」「アンケート」「ゲーム」「メールクリック」などは対象外)</li>
                <li class="text--14">紹介を行う際に発行されたURLや紹介タグは変更しないでください。変更されますと友達紹介と認定されない場合があります。</li>
                <li class="text--14">お友達がスマートフォン端末からご登録をされる場合には、必ず標準ブラウザ(Safari等)にてご登録をお願いいたします。アプリ内ブラウザからご登録を行われた場合には、紹介が正常に完了しない場合がございます。</li>

                <li class="text--14">
                    下記記載事項にあてはまる場合には、予告なく強制退会または、ポイントの没収となる場合がございますので、ご注意ください。
                    <ul class="example">
                        <li class="text--14">※スパムや虚偽内容による不正な紹介</li>
                        <li class="text--14">※自分で複数のメールアドレスを使用し登録を行った場合</li>
                        <li class="text--14">※同一IPからのご登録（ご家族で同居されている場合など、同一IPでの登録は友達紹介と認定されません。）</li>
                        <li class="text--14">※登録したらパスワードを教えるなど、交換条件を提示し登録を促している場合やパスワード付き記事でのご紹介</li>
                        <li class="text--14">※サービスのご紹介以外の文言にて登録を促している場合</li>
                        <li class="text--14">※アダルト要素を含むものなど、当サイトイメージを著しく損なう様な内容・表現方法での紹介を行った場合</li>
                        <li class="text--14">※ご登録されたお友達にご獲得の意思がない方が多数いらっしゃる場合</li>
                    </ul>
                </li>
            </ul>
        </div><!--/contentsbox-->
    </section><!--/notes-->
</div>
@endsection
