<?php $base_css_type = 'friends'; ?>
@extends('layouts.default')

@section('layout.title', 'ブログ申請｜ポイントサイトならGMOポイ活')

@section('layout.content')
<div class="inner u-mt-20">
    <h2 class="contents__ttl">ブログ申請</h2>
</div>

<div class="inner">
    <div class="contents__box u-mt-20">
        <div class="blog__howto">
            <ul>
                <li>
                    <div><img src="{{ asset('/images/blog/blog_1.png')}}" alt="ブログに紹介記事を掲載する"></div>
                    <p class="ttl">ブログに紹介記事を掲載する</p>
                    <p class="txt">バナーやオススメ文を使って、あなたのブログやサイトでGMOポイ活を紹介しよう！</p>
                </li>
                <li>
                    <div><img src="{{ asset('/images/blog/blog_2.png')}}" alt="紹介ページのURLを申請"></div>
                    <p class="ttl">紹介ページのURLを申請</p>
                    <p class="txt">申請フォームに、紹介したページのURLを入力して申請してね！</p>
                </li>
                <li>
                    <div><img src="{{ asset('/images/blog/blog_3.png')}}" alt="承認されると5ポイントがもらえる！"></div>
                    <p class="ttl">承認されると5ポイントがもらえる！</p>
                    <p class="txt">GMOポイ活で承認されると、ポイントがもらえるよ！<br><span class="red">※弊社の判断により承認されない場合があります。</span></p>
                </li>
            </ul>
        </div>
        <div class="blog__form">
            <div class="blog__form__wrap">
                <div class="blog__form__inner">
                    <h3 class="blog__form__ttl">URLを申請しました！<br />順次審査致しますので、完了までお待ち下さい。</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
