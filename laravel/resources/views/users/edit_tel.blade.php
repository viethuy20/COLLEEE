@extends('layouts.mypage')

@section('layout.title', '電話番号変更｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')

<!-- main contents -->
<section class="contents">

    <!-- page title -->
    <h2 class="contents__ttl">電話番号変更</h2>

    <section class="contents__box">
        <h3 class="contents__ttl">新しい電話番号を入力して下さい。<br/>登録されたメールアドレス宛に変更用URLを記載したメールをお送りします。</h3>
        {!! Tag::formOpen(['url' => route('users.edit_tel'), 'class' => 'users__form u-mt-20']) !!}
        @csrf
        <table>
            <tr>
                <th><span>新しい電話番号</span></th>
                <td>
                    {!! Tag::formText('tel', '', ['pattern' => '[0-9]*', 'class' => 'form01', 'required' => 'required', 'minlength' => '10', 'maxlength' => '11', 'placeholder' => '電話番号を入力してください']) !!}
                </td>
            </tr>
            <tr>
                <th>
                    <span>電話番号（確認用）</span>
                </th>
                <td>
                    {!! Tag::formText('tel_confirmation', '', ['pattern' => '[0-9]*', 'class' => 'form01', 'required' => 'required', 'minlength' => '10', 'maxlength' => '11']) !!}
                </td>
                @if ($errors->has('tel'))
                <td>
                    <p class="error_message">
                        <span class="icon-attention"></span>{{ $errors->first('tel') }}
                    </p>
                </td>
                @endif
            </tr>
            @if (Session::has('message'))
            <tr>
                <td>
                    <p class="error_message">
                        <span class="icon-attention"></span>{{ Session::get('message') }}
                    </p>
                </td>
            </tr>
            @endif
        </table>
        <div class="users__change__btn__pink">
            {!! Tag::formButton('送信', ['type' => 'submit']) !!}
        </div>
        {!! Tag::formClose() !!}
    </section><!-- /.contents__box -->
    <div class="basic__change__btn">
        {!! Tag::link(route('users.edit'), '基本情報変更へ戻る') !!}
    </div>
</section>
@endsection
