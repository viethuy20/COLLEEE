@if(!((false !== strpos($footNotes, 'seo') || false !== strpos($footNotes, 'guide')) && Auth::check()))
<div class="foot-notes__wrap">
	<ul class="foot-notes">
        @endif
        @if(false === strpos($footNotes, 'guide') && !(false !== strpos($footNotes, 'seo') && Auth::check()))
            <li>本キャンペーンはGMO NIKKO株式会社による提供です。<br>本キャンペーンについてのお問い合わせはAmazonではお受けしておりません。<br>【<a class="textlink" href="/inquiries/10">問合せフォーム</a>】までお願いいたします。</li>
            <li>Amazon、Amazon.co.jpおよびそれらのロゴはAmazon.com, Inc.またはその関連会社の商標です。</li>
            <li>「EdyギフトID」は、楽天Edy株式会社との発行許諾契約により、株式会社NTTカードソリューションが発行する電子マネーギフトサービスです。</li>
            <li>「楽天Edy（ラクテンエディ）」は、楽天グループのプリペイド型電子マネーサービスです。</li>
            <li>「プレイステーション ファミリーマーク」および 「PlayStation」は株式会社ソニー・インタラクティブエンタテインメントの登録商標または商標です。</li>
        @endif
        @if(!((false !== strpos($footNotes, 'seo') || false !== strpos($footNotes, 'guide')) && Auth::check()))
        <li>&copy; 2024 iTunes K.K. All rights reserved.</li>
        <li>Google Play は Google LLC の商標です。</li>
        <li>「WAONポイントID」は、イオンリテール株式会社との発行許諾契約により、株式会社NTTカードソリューションが発行する電子マネーギフトです。</li>
        <li>「WAON（ワオン）」は、イオン株式会社の登録商標です。</li>
        <li>「Ponta」は、株式会社ロイヤリティ マーケティングの登録商標です。</li>
        <li>「Pontaポイント コード」は、株式会社ロイヤリティ マーケティングとの発行許諾契約により、株式会社NTTカードソリューションが発行するサービスです。</li>
        <li>デジタルギフト&#x1F12C;は、株式会社デジタルプラスの商標です。</li>
        @endif
        @if(false !== strpos($footNotes, 'exchange'))
            {{-- <li>「LINE Pay」は、LINE Pay株式会社との発行許諾契約により、LINE Pay株式会社が発行する電子マネーギフトです。</li> --}}
            <li>「nanaco(ナナコ)」と「nanacoギフト」は株式会社セブン・カードサービスの登録商標です。</li>
            <li>「nanacoギフト」は、株式会社セブン・カードサービスとの発行許諾契約により、株式会社NTTカードソリューションが発行する電子マネーギフトサービスです。</li>
            {{-- <li>「LINE Pay」は、LINE Pay株式会社の登録商標です。</li> --}}
            <li>「dポイント」は、ドコモ株式会社との発行許諾契約により、株式会社NTTカードソリューションが発行する電子マネーギフトです。</li>
            <li>「dポイント」は、ドコモ株式会社の登録商標です。</li>
            <li>デジタルギフト&#x1F12C;は、株式会社デジタルプラスの商標です。</li>
        @endif
	</ul>
    @if(false !== strpos($footNotes, 'seo') && !Auth::check())
        <p>『GMOポイ活』は、運営実績20年以上、利用・登録料がずーっと0円のポイ活サイト（ポイントサイト）です。<br>ポイントサイトでは「お買い物」「無料会員登録」、「無料アプリダウンロード」など様々なサービスのご利用で簡単・無料でポイントを貯めることができます。<br>貯まったポイントは現金や電子マネー・他社ポイントに1ポイント=1円分で交換することができます。</p>
    @endif
    @if(!((false !== strpos($footNotes, 'seo') || false !== strpos($footNotes, 'guide')) && Auth::check()))
</div>
@endif