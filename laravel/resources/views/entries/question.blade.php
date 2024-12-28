<?php
    $base_css_type = 'signup';
    $hidden_header = true;
?>
@extends('layouts.plane')

@section('layout.title', 'GMOポイ活新規会員登録｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はいつものショッピングや旅行予約、外食や、無料ゲームでポイントが貯まるポイントサイトです。無料会員登録して、ポイントを貯めて現金やギフトカードに交換しよう♪')
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
            新規会員登録
        </li>
    </ol>
</section>
@endsection
@section('layout.content')
    <div class="contents">
        <!-- page title -->
        <h2 class="contents__ttl">メールマガジン受信設定</h2>
        <div class="contents__box">
            <div class="contents__box__inner">
                <div class="entries__box">
                    <p class="text--15 done">電話番号発信確認が<br />完了しました。</p>
                    <h2 class="contents__ttl">メールマガジンの受信</h2>
                    <p class="text--15">ポイント付きメールをはじめ、様々な特典があるメールマガジンが受け取れます。</p>
                    <p class="text--15">メールマガジンの受信は、ご登録完了時「受信する」設定となっています。<br>
                        メールマガジンの受信設定は、マイページの「メールマガジン受信設定」にて可能です。
                    </p>
                    <p class="text--15 mb_20">メールマガジンには、失効ポイントに関する通知メールが含まれます。こちらはメールマガジンの受信をされていない方には配信されません。</p>
                    <h2 class="contents__ttl" >よろしければ下記のアンケートにお答え下さい（任意）</h2>
                    {!! Tag::formOpen(['url' => route('entries.store'), 'class' => 'entries__form']) !!}
                    @csrf    
                    <table>
                            <?php $question_list = config('question.entry'); ?>
                            @foreach($question_list as $i => $question)
                            <?php
                            $answer_map = $question['answer_map'];
                            $key_name = 'q'.($i + 1);
                            ?>
                            <tr>
                                <th><span class="">{{ $question['text'] }}</span></th>
                                <td>
                                    <div class="select_radio">
                                        @foreach($answer_map as $key => $label)
                                        <label>{!! Tag::formRadio($key_name, $key, false) !!}<span>{{ $label }}</span></label><br />
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </table>

                        <div class="entries__box">
                        <p class="text--15">ご回答頂いたアンケート内容は、お客様へのサービスの一環として、より良いコンテンツ・キャンペーンのご案内等を提供させていただく為に利用致します。</p>
                        </div>

                        <div class="contents__btn orange">
                            <button class="btn_send" id="" type="submit">会員登録を完了する</button>
                        </div>
                    {!! Tag::formClose() !!}
                </div><!--/container02-->
            </div>
        </div>
    </div>
@endsection
