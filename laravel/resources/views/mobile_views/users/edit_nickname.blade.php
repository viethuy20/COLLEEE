<?php $base_css_type = 'mypage'; ?>
@extends('layouts.default')

@section('layout.title', 'ニックネーム変更｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')

<div class="inner u-mt-20">
    <h1 class="contents__ttl u-mt-20">ニックネーム変更</h1>
</div>

<section class="inner">
    <div class="contents__box u-mt-20">
        {!! Tag::formOpen(['url' => route('users.edit_nickname'), 'class' => 'users__form custom-table']) !!}
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
    </div><!--/setting-->
    <div class="basic__change__btn">
            {!! Tag::link(route('users.edit'), '基本情報変更へ戻る') !!}
    </div>
</section>
@endsection
