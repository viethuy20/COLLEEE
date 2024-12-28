@extends('layouts.mypage')

@section('layout.title', 'お住まいの都道府県変更｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')

<section class="contents">
    <h2 class="contents__ttl">お住まいの都道府県変更</h2>

    <section class="contents__box">
        {!! Tag::formOpen(['url' => route('users.edit_prefecture'), 'class' => 'users__form custom-table']) !!}
            @csrf
            <table>
                <tr>
                    <th>
                        <span>都道府県を選択</span>
                    </th>
                    <td>
                        <div class="prefectures">
                            {!! Tag::formSelect('prefecture_id', config('map.prefecture'), Auth::user()->prefecture_id) !!}
                        </div>
                    </td>
                    @if ($errors->has('prefecture_id'))
                    <!--エラーの場合はここに-->
                    <td>
                        <p class="error_message">
                            <span class="icon-attention"></span>{{ $errors->first('prefecture_id') }}
                        </p>
                    </td>
                    @endif
                </tr>
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
