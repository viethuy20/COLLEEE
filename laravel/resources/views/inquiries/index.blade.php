@php
$base_css_type = 'support';
@endphp
@extends('layouts.plane')

@section('layout.head')
<script type="text/javascript"><!--
// 問い合わせ項目の選択に応じたフォームの表示制御
var changeFormView = function(inquiryId) {
    if (inquiryId == 3) {
        // 「ポイント獲得について」を選択
        $('.program_name').show();
        $('.payment_number').show();
        $('.name').show();
        $('.date').show();
        $('.mail_message').show();
    } else {
        $('.program_name').hide();
        $('.payment_number').hide();
        $('.name').hide();
        $('.date').hide();
        $('.mail_message').hide();
    }

    if (inquiryId == 10) {
        $('.email_header').html("メールアドレス&nbsp;<span class='necessary'>必須</span>");
    } else {
        $('.email_header').html("ご参加者様の<br />メールアドレス&nbsp;<span class='necessary'>必須</span>");
    }
};

$(function() {

    $('#InquiriesConfirmRequestTimestamp').val(Math.floor((new Date()).getTime()) / 1000);

    var inquiryId = $('input:radio[name="inquiry_id"]:checked').val();

    changeFormView(inquiryId);

    $('input:radio[name="inquiry_id"]').change(function() {
        var inquiryId = $(this).val();
        changeFormView(inquiryId);
    });

    $('.btn_send').click(function() {
        // 非表示のフォームはrequired属性を削除
        var inquiryId = $('input:radio[name="inquiry_id"]:checked').val();

        // 「ポイント獲得について」以外を選択
        if (inquiryId != 3) {
            $('[name=program_name]').removeAttr('required');
            $('[name=name]').removeAttr('required');
        }
    });
});
//-->
</script>
@endsection
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
            お問い合わせ
        </li>
    </ol>
</section>
@endsection

@section('layout.title', 'お問い合わせ｜ポイントサイトならGMOポイ活')
@section('layout.keywords', 'お問い合わせ,サポート')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。分からないことや困ったことなどがございましたら、こちらでも質問を承ります。')
@section('og_type', 'website')

@section('layout.content')

<section class="inquiry">
    <h1>お問い合わせ</h1>
    <div class="contentsbox">
        {{ Tag::formOpen(['route' => 'inquiries.confirm', 'method' => 'post']) }}
        @csrf    
        {{ Tag::formHidden('request_timestamp', '', ['id' => 'InquiriesConfirmRequestTimestamp']) }}
            <table><tr>
                <th>お問い合わせ項目&nbsp;<span class="necessary">必須</span></th>
                <td>
                    @php
                    $inquiries_map = config('map.inquiries');
                    @endphp
                    @foreach($inquiries_map as $key => $label)
                    @php
                    $html_id = sprintf("radio%d", $key);
                    @endphp
                    {{ Tag::formRadio('inquiry_id', $key, $inquiry_id == $key, ['id' => $html_id]) }}<label for="{{ $html_id }}">{{ $label }}</label><br />
                    @endforeach
                    @if ($errors->has('inquiry_id'))
                    <p class="e_message"><span class="icon-attention"></span>{{ $errors->first('inquiry_id') }}</p>
                    @endif
                </td>
            </tr></table>

            <table class='program_name'><tr>
                <th>対象広告名&nbsp;<span class="necessary">必須</span></th>
                <td>
                    {{ Tag::formText('program_name', $title, ['required' => 'required', 'class' => 'inquiry_textbox']) }}
                    @if ($errors->has('program_name'))
                    <p class="e_message"><span class="icon-attention"></span>{{ $errors->first('program_name') }}</p>
                    @endif
                </td>
            </tr></table>

            <table class='payment_number'><tr>
                <th>決済情報番号<br />（月額広告の場合）</th>
                <td>
                    {{ Tag::formText('payment_number', '', ['class' => 'inquiry_textbox']) }}
                    @if ($errors->has('payment_number'))
                    <p class="e_message"><span class="icon-attention"></span>{{ $errors->first('payment_number') }}</p>
                    @endif
                </td>
            </tr></table>

            <table class='email'><tr>
                <th class='email_header'>{!! ($inquiry_id == 10) ? '' : 'ご参加者様の<br />' !!}メールアドレス&nbsp;<span class="necessary">必須</span></th>
                <td>
                    {{ Tag::formText('email', '', ['required' => 'required', 'class' => 'inquiry_textbox']) }}
                    @if ($errors->has('email'))
                    <p class="e_message"><span class="icon-attention"></span>{{ $errors->first('email') }}</p>
                    @endif
                </td>
            </tr></table>

            <table class='name'><tr>
                <th>ご参加者様の<br />お名前&nbsp;<span class="necessary">必須</span></th>
                <td>
                    {{ Tag::formText('name', '', ['required' => 'required', 'class' => 'inquiry_textbox']) }}
                    @if ($errors->has('name'))
                    <p class="e_message"><span class="icon-attention"></span>{{ $errors->first('name') }}</p>
                    @endif
                </td>
            </tr></table>

            <table class='date'><tr>
                <th>参加日時</th>
                <td class="date">
                    {{ Tag::formText('joined_at[year]', '', ['class' => 'inquiry_textbox']) }}年
                    {{ Tag::formText('joined_at[month]', '', ['class' => 'inquiry_textbox']) }}月
                    {{ Tag::formText('joined_at[day]', '', ['class' => 'inquiry_textbox']) }}日
                    {{ Tag::formText('joined_at[hour]', '', ['class' => 'inquiry_textbox']) }}時頃
                    @if ($errors->has('joined_at'))
                    <p class="e_message"><span class="icon-attention"></span>{{ $errors->first('joined_at') }}</p>
                    @endif
                </td>
            </tr></table>

            <table class='inquiry_detail'><tr>
                <th>お問い合わせ詳細&nbsp;<span class="necessary">必須</span></th>
                <td>
                    {{ Tag::formTextarea('inquiry_detail', null, ['required' => 'required', 'class' => 'inquiry_textarea']) }}
                    @if ($errors->has('inquiry_detail'))
                    <p class="e_message"><span class="icon-attention"></span>{{ $errors->first('inquiry_detail') }}</p>
                    @endif
                </td>
            </tr></table>

            <table class='mail_message'><tr>
                <th>購入・登録<br />完了メール</th>
                <td>
                    {{ Tag::formTextarea('mail_message', null, ['class' => 'inquiry_textarea']) }}
                    <p>※お申し込み先から受信した確認メールの内容をそのまま貼り付けて下さい。</p>
                    @if ($errors->has('mail_message'))
                    <p class="e_message"><span class="icon-attention"></span>{{ $errors->first('mail_message') }}</p>
                    @endif
                </td>
            </tr></table>

            <div class="note_inquiry">
                <dl>
                    <dt>■お問い合わせ対応時間</dt>
                    <dd>
                        　月～金（10：00～18：00）<br />
                        ※土日・祝日・年末年始及び受付時間外は対応を行っておりません。<br />
                        ※返信はお問い合わせの内容により、2週間程度お時間をいただく場合がございます。<br />
                        　なお、「報酬の未配布」に関するお問い合わせにつきましては、関係各所への確認が必要となる為、1～3ヶ月程度のお時間をいただく場合がございます。<br />
                        ※ご質問の内容によってはお答えできない場合がございますので、あらかじめご了承ください。<br />
                        ※お電話でのサポートは提供しておりません。ご了承ください。
                    </dd>
                    <dt>■メールの受信設定について</dt>
                    <dd>
                        インターネットプロバイダや携帯キャリア、メールサービスなどの設定によりGMOポイ活のメールが届かない場合がございます。<br />
                        support@colleee.net&nbsp;からのメールを受信できるようにメール受信設定のご確認をお願いいたします。
                    </dd>
                </dl>
                <div>
                    <p>
                        入力・送信頂いた個人情報は、
                        {{ Tag::link(config('url.gmo_nikko'), 'GMO NIKKO株式会社', ['target' => '_blank', 'class' => 'lnk_external']) }}
                        が適切に管理し、「個人情報の取り扱いについて」に記載する利用目的の範囲内で利用いたします。
                    </p>
                    <p class="p_center">
                        {{ Tag::formCheckbox('consent', 1, false) }}
                        「個人情報の取り扱いについて」に同意の上、入力した情報を送信
                    </p>
                    <textarea class="contentTerm" name="content" rows="13" id="contentTerm" disabled>{{ config('text.inquiry_form') }}</textarea>
                    @if ($errors->has('consent'))
                    <p class="error"><span class="icon-attention"></span>{{ $errors->first('consent') }}</p>
                    @endif
                </div>
            </div><!--/note_inquiry-->

            {{ Tag::formSubmit('送信内容の確認', ['class' => 'btn_send btn_more']) }}
        {{ Tag::formClose() }}
    </div><!--/contentsbox-->
</section><!--/inquiry-->
@endsection
