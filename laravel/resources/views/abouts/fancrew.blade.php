@extends('layouts.fancrew')

@section('layout.title', 'モニター（お店でお得）ご利用ガイド｜ポイントサイトならGMOポイ活')
@section('layout.keywords', 'モニター,覆面調査,利用ガイド')
@section('layout.description', 'モニター（お店でお得）のご利用ガイドです。モニターの流れや参加のルール・注意事項が書いてありますので、必ずお読みの上ご参加下さい。')
@section('og_type', 'website')

@section('fancrew.content')
<h1 class="ttl_review">ご利用ガイド</h1>

<section class="monitor_guide">
    <div class="contentsbox">
        <h2 id="flow">モニターの流れ</h2>

        <h3>STEP1&nbsp;お店を選ぶ</h3>
        <p>お好きなお店を選んでください。当選しやすい店舗・新着・キーワード検索等、さまざまな検索方法でお選びください。</p>

        <h3>STEP2&nbsp;抽選</h3>
        <p>
            注意事項をしっかり読んで、応募してください。<br />
            すぐに抽選が行われます。<br />
            当選者にのみモニターの権利があります。<br />
            必ず当選された後に行ってください。<br />
            当選される前に行なわれた場合、謝礼のお支払いはいたしかねます。<br />
            ※必ず有効期限内にご来店ください。<br />
            落選の場合、24時間後より同一店舗への応募も可能となりますので、諦めず応募してみてください。
        </p>

        <h3>STEP3&nbsp;お店に行く</h3>
        <p>
            事前確認でモニターの注意事項とアンケート内容を確認後、モニターに行ってください。<br />
            予約が必要なお店や、定休日・営業時間等の確認もお願いします。満席等で入店が出来ない場合、謝礼はお支払いできません。<br />
            ※体調、または既往症により、サービスをお受けいただけない場合、謝礼はお支払いできません。<br />
            ※『モニターで当選した』『GMOポイ活から』といった表現をされ、モニターであることが店舗に知られた場合、謝礼はお支払いできません。<br />
            ※ご利用の際のお支払いはお客様のご負担となります。
        </p>

        <h3>STEP4&nbsp;提出物</h3>
        <dl class="guide01">
            <dt><span class="number">1</span>. アンケート</dt>
            <dd>
                来店後、「お店でお得ページ＞ご利用履歴」からアンケート記入をお願いします。<br />
                入力の際に顔文字は入力しないでください。また、記号の多用もおやめください。<br />
                <a href="#questionnaire">アンケート提出ルール</a>
            </dd>
            <dt><span class="number">2</span>. レシート</dt>
            <dd>
                アンケート提出後、レシートの全体画像をアップロードしてください。<br />
                以下に該当する場合、謝礼はお支払いできません。ご注意ください。
                <ul class="u-font-bold">
                    <li>・そもそも提出がない</li>
                    <li>・レシートの一部のみの画像 ・画像が不鮮明で内容が確認ができない</li>
                    <li>・レシート画像の一部を黒く伏せたり、加工が施されている</li>
                    <li>・他サイトのモニターで既に謝礼をもらったレシートをアップロード（レシートはモニターとして来店した際の１枚のみ有効です。） </li>
                </ul>
                ※上記のような例に該当していた場合、レシートの再提出をお願いすることがあります。<br />
                <br />
                <span class="fc_c73338">レシートは謝礼が付与されるまで必ず保管してください。</span>その他詳細、具体的なレシートの提出方法は、以下よりご確認ください。 <br />
                <a href="#receipt">画像アップロードについて</a><br />
                ※提出物がレシート以外の店舗もございますので、来店前に必ず事前確認ページで提出物をご確認ください。
            </dd>
        </dl>

        <h3>STEP5&nbsp;謝礼</h3>
        <p>
            提出物の確認・承認が済むと、謝礼が支払われます。<br />
            謝礼は実際にお支払いいただいた金額を元に算出いたします。
        </p>
    </div><!--/contentsbox-->

    <div class="contentsbox">
        <h2 id="confirmation">事前確認について</h2>

        <h3>「モニター」を行う際のルール・注意事項はこちらで確認をしてください</h3>
        <dl class="guide02">
            <dt><span class="number">1</span>. まず、必ずモニターの基本的な流れを確認してください。</dt>
            <dd>※こちらがモニターの基本ルールとなります。</dd>
            <dt><span class="number">2</span>. 次に「モニターの注意事項」を確認してください。</dt>
            <dd>
                ※この店舗でモニターを行う上でのルールになります。<br />
                ※通常のモニターと流れの異なる店舗には「実施詳細」が表示されますので、必ずご確認ください。
            </dd>
            <dt><span class="number">3</span>. 提出物の確認をしてください。</dt>
            <dd>
                <p class="mt_5"><a href="#questionnaire">アンケート提出ルール</a></p>
                <p><a href="#receipt">レシート画像作成方法</a></p>
                ※提出物がレシート以外の店舗もございますので、来店前に必ず事前確認ページで提出物をご確認ください。
            </dd>
            <dt><span class="number">4</span>. アンケート内容を確認してください。</dt>
            <dd>
                ※アンケートを事前に確認しなかった為に回答できない場合は謝礼はお支払いできませんので、ご了承ください。<br />
                ※『モニターで当選した』『GMOポイ活から』『ファンくるから』といった表現をされ、モニターであることが店舗に知られた場合、謝礼はお支払いできません。
            </dd>
        </dl>
        <p>以上で、モニターに行く前の事前確認は終了です。 </p>
        <p class="fc_c73338">上記のルールを守ってないもの、及びアンケートの内容により再提出、又は謝礼をお支払いできない場合がございます。 </p>
    </div><!--/contentsbox-->

    <div class="contentsbox">
        <h2 id="winning">当選について</h2>

        <h3>仮当選について</h3>
        <p>
            グルメモニターの当選は、同時に3店舗までとなっております。<br />
            この3店舗を超えて当選した場合、４店舗目を「仮当選」として、既に当選しているいずれかの店舗と当選を入れ替えることが可能です。
        </p>
        <br />
        <p>但し、仮当選の期限は30分間です。<br />この時間内に入れ替えを行わない場合、仮当選店舗が自動的にキャンセルとなります。</p>

        <h3>仮当選店舗をキャンセルする場合</h3>
        <p>
            上部に表示されている「仮当選店舗」をキャンセルする場合は、「仮当選キャンセル」ボタンをクリックしてください。<br />
            既に当選している3店舗が、そのまま当選となります。
        </p>

        <h3>当選中店舗をキャンセルする場合</h3>
        <p>
            「当選中店舗」のいずれか1店舗をキャンセルし、「仮当選店舗」を当選にする場合、下部に表示されている「当選中店舗」のいずれかの「キャンセル」ボタンをクリックしてください。<br />
            但し、既にアンケートを提出、レシート提出、承認中の店舗はキャンセルできません。
        </p>

        <h3>当選店舗の確定</h3>
        <p>
            上部に表示されている店舗が、キャンセル店舗です。下部の３店舗が当選確定となります。よろしければ「この店舗をキャンセル」ボタンをクリックしてください。<br />
            また、キャンセル店舗を変更したい場合には「キャンセル店舗を変更」ボタンをクリックすると、前ページに戻り、再びキャンセル店舗の選択することができます。
        </p>

        <h3>落選ばかりで、がっかりしているみなさんへ！</h3>
        <p>
            落選しても【繰り上げ当選】の可能性が！応募枠がいっぱいで落選してしまった応募者は、応募した店舗のウェイティングリストに追加されます。<br />
            当選者にキャンセルが出た場合、優先的にウェイティングリストから繰り上げで当選します！<br />
            繰上げ当選すると、メールと「お店でお得ページ＞ご利用履歴」で「再応募」のお知らせが届きます。<br />
            すでに、事前質問にお答え頂いていますので、期限内に「再応募する」ボタンをワンクリックするだけで当選確定です。<br>
            ※グルメモニターの場合、すでに3店舗当選している場合、繰上げ当選は発生しません。<br />
            ※ウェイティングリストの有効期限は月毎です！
        </p>
    </div><!--/contentsbox-->

    <div class="contentsbox">
        <h2 id="questionnaire">アンケートについて</h2>

        <h3>提出ルール</h3>
        <ul class="u-font-bold">
        <li><span class="number">1</span>. 提出期日までにご提出ください。</li>
        <li><span class="number">2</span>. 設問に対する適切な解答をお願いします。</li>
        <li><span cu-font-boldlass="number">3</span>. 絵文字、顔文字、口語での回答はご遠慮ください。</li>
        </ul>
        <p class="fc_c73338">上記のルールを守ってないもの、及びアンケートの内容により再提出、又は謝礼をお支払いできない場合がございます。 </p>

        <h3>アンケート保存機能</h3>
        <ul class="u-font-bold">
        <li><span class="number">1</span>. アンケートを保存したい場合、「アンケート保存」ボタンをクリックしてください。</li>
        <li><span class="number">2</span>. 「アンケートが保存されました。」とメッセージウィンドウが開いたら保存が完了です。</li>
        <li><span class="number">3</span>. 後日アンケートの続きを書くときは、「お店でお得ページ＞ご利用履歴」トップのモニターステータスでアンケートの提出するボタンをクリックしてください。</li>
        </ul>
    </div><!--/contentsbox-->

    <div class="contentsbox">
        <h2 id="receipt">レシートについて</h2>

        <h3>提出方法</h3>
        <ul class="u-font-bold">
            <li><span class="number">1</span>. レシート画像を用意してください。</li>
            <li><span class="number">2</span>. レシート画像提出フォームから画像をアップロードしてください。 <br />
                <ul>
                    <li>　a.選択ボタンをクリックして画像を選択して下さい。</li>
                    <li>
                        　b.アップロードボタンをクリック<br />
                        <div class="fw_normal">　※複数枚のレシート画像がある場合 「a」「b」の行程を繰り返してください。</div>
                    </li>
                    <li>
                        <span class="number">3</span>. 下記に表示されたアップロードした画像をチェックしてください。<br />
                        <div class="fw_normal">　※別ウィンドウが開きます。</div>
                    </li>
                    <li><span class="number">4</span>. 画像確認が済みましたらレシート提出ボタンをクリックしてください。</li>
                </ul>
            </li>
        </ul>

        <h3>レシート画像作成方法</h3>
        <p>レシート全体が写るように撮影してください。</p>
        <p>撮影いただいた画像をご自分で見て、特に下記項目が鮮明に読み取れるように撮影してください。</p>
        <br />
        <ul class="u-font-bold">
            <li>・店舗名</li>
            <li>・ご利用日時</li>
            <li>・ご利用明細</li>
        </ul>
        <p>※一枚で撮影しきれないレシートは複数枚にわけて撮影してください。 </p>
        <hr class="bd_dot_gray u-mt-20">

        <dl class="guide03">
            <dt class="u-mt-20">デジカメ・携帯でレシートを撮影するときのポイント</dt>
            <dd><ul class="u-mt-10">
                <li>レンズは綺麗にしておく。</li>
                <li>フラッシュを使用しないで、なるべく明るい所で撮影する。</li>
                <li>
                    なるべくレシートの近くから撮影する。<br />
                    ※ただし近すぎるとピントがあいません
                </li>
                <li>デジタルズームは使わない。 ※画質が荒れます</li>
                <li>シャッターを押すとき動かない。 ※ブレ防止</li>
                <li>携帯で撮影の場合にはそのカメラの最高画質を使う。</li>
            </ul></dd>
            <hr class="bd_dot_gray u-mt-20">

            <dt class="u-mt-20">再提出をお願いしている悪い例</dt>
            <dd><ul class="u-mt-10">
                <li>明るすぎる</li>
                <li>暗すぎる</li>
                <li>ぼやけている</li>
                <li>ぶれている</li>
                <li>無理に一枚で撮影</li>
                <li>レシートの一部のみの画像</li>
                <li>レシートや画像に手が加えられている（黒く伏せたり、上書きしたり）</li>
            </ul></dd>
        </dl>

    </div><!--/contentsbox-->

</section><!--/monitor_guide-->

<section class="list_guide">
    <div class="contentsbox_r2"><ul>
        <li><a href="#flow">モニターの流れ</a></li>
        <li><a href="#confirmation">事前確認について</a></li>
        <li><a href="#winning">当選について</a></li>
        <li><a href="#questionnaire">アンケートについて</a></li>
        <li><a href="#receipt">レシートについて</a></li>
    </ul></div><!--/contentsbox_r2-->
</section><!--/list_guide-->

@endsection

