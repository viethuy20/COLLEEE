@extends('layouts.mypage')

@section('layout.title', 'ニックネーム変更｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')
<section class="contents">
    <h2 class="contents__ttl">ニックネーム変更</h2>

    <section class="contents__box">
        {!! Tag::formOpen(['url' => route('users.edit_nickname'), 'class' => 'users__form custom-table u-mt-20']) !!}
        @csrf    
        <table>
                <tr>
                    <th>
                        <span>ニックネームを入力</span>
                    </th>
                    <td>
                        {!! Tag::formText('nickname', Auth::user()->nickname ?? '', ['required' => 'required', 'size' => '10', 'class' => 'form01']) !!}
                        <p class="stint">2文字以上10文字以内</p>
                    </td>
                    @if ($errors->has('nickname'))
                    <!--エラーの場合はここに-->
                    <td>
                        <p class="error_message">
                            <span class="icon-attention"></span>{{ $errors->first('nickname') }}
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
                {!! Tag::formButton('変更', ['type' => 'submit']) !!}
            </div>
        {!! Tag::formClose() !!}
    </section><!--/setting-->
    <div class="basic__change__btn">
        {!! Tag::link(route('users.edit'), '基本情報変更へ戻る') !!}
    </div>
</section><!--/contents-->
@endsection
