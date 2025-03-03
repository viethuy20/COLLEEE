@extends('layouts.mypage')

@section('layout.title', 'メールアドレス変更｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')

<section class="contents">
    <h2 class="contents__ttl">メールアドレス変更</h2>
    <section class="contents__box">
        <p class="text--15">新しいメールアドレスを入力して下さい。入力されたメールアドレス宛に変更用URLを記載したメールをお送りします。</p>
        {!! Tag::formOpen(['url' => route('users.edit_email'), 'class' => 'users__form custom-table u-mt-20']) !!}
        @csrf    
        <table>
                <tr>
                    <th>
                        <span>新しいメールアドレス</span>
                    </th>
                    <td>
                        {!! Tag::formText('email', '', ['placeholder' => 'メールアドレスを入力してください', 'class' => 'form01', 'required' => 'required']) !!}
                    </td>
                    @if ($errors->has('email'))
                    <!--エラーの場合はここに-->
                    <td>
                        <p class="error_message">
                            <span class="icon-attention"></span>{{ $errors->first('email') }}
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
    </section><!--/setting-->
    <div class="basic__change__btn">
        {!! Tag::link(route('users.edit'), '基本情報変更へ戻る') !!}
    </div>
</section>
@endsection
