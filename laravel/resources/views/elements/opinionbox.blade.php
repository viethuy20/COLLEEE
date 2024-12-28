@if(Auth::check())
    <div class="contents__ttl">ご意見箱</h2>
    <div class="sidebar__box">
        @if (Session::has('opinionSended'))
        <!-- 送信成功開始 -->
        <p><b>ご意見ありがとうございました。</b><br />
        頂いたご意見は、運営スタッフが必ず目を通させて頂きますが、個々のご意見に返信できないことを予めご了承ください。<br />
        返信が必要な場合、大変お手数ですが、下記のお問い合わせフォームよりお問い合わせください。</p>
        <p class="inquiries__btn">{!! Tag::link(route('inquiries.index', ['inquiry_id' => 10]), 'お問い合わせ') !!}</p>
        <!-- 送信成功終了 -->
        @else
        <!-- 送信入力開始 -->
        <p>GMOポイ活へのご意見・ご要望をお聞かせください！<br />
        頂いた意見は今後のサイト運営、改善に役立てていけるよう、参考にさせていただきます。</p>
        <?php
        $body_attr = ['cols' => '', 'rows' => '7', 'placeholder' => 'ご意見を入力ください'];
        if (WrapPhp::count($errors) > 0) {
            $body_attr['class'] = 'error';
        }
        ?>
        {!! Tag::formOpen(['url' => route('users.opinion'), 'class' => 'sidebar__box__form']) !!}
        @csrf    
        {!! Tag::formHidden('scroll', '') !!}
            {!! Tag::formTextarea('body', '', $body_attr) !!}
            @if ($errors->has('body'))
            <p class="error_message"><span class="icon-attention"></span>&nbsp;{{ $errors->first('body') }}</p>
            @endif
            {!! Tag::formButton('意見を送る', ['type' => 'submit']) !!}
        {!! Tag::formClose() !!}
        <!-- 送信入力終了 -->
        @endif
    </div>
@endif
