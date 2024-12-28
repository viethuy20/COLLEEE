<?php $base_css_type = 'friends'; ?>
@extends('layouts.default')

@section('layout.title', '友達紹介ブログ申請 | ポイントサイトならGMOポイ活')

@section('layout.content')
<div class="inner u-mt-20">
    <h2 class="contents__ttl">ブログ申請</h2>
</div>

<!-- contents -->
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

        <section id="send_url">
            @if (\Auth::user()->blog == 1)
            <div class="blog__form">
                <div class="blog__form__wrap">
                    <div class="blog__form__inner">
                        <h3 class="blog__form__ttl"><span class="red">あなたは既にURLを申請済みです。</span></h3>
                    </div>
                </div>
            </div>
            @else
            <div id="app_status">
                {!! Tag::formOpen(['url' => route('users.blog'),'class' => 'blog__form']) !!}
                @csrf    
                <div class="blog__form__wrap">
                        <div class="blog__form__inner">
                            <h3 class="blog__form__ttl">申請フォーム</h3>
                            {!! Tag::formText('url', '', ['required' => 'required', 'class' => 'input', 'maxlength' => 100, 'placeholder' => '紹介しているページのURL']) !!}<br />
                            <p class="text--15">※お一人様1回限り申請可能です。</p>
                            @if ($errors->has('url'))
                                <p class="text--15" id="error"><span class="red">{{ $errors->first('url') }}</span></p>
                            @endif
                            @if (Session::has('message'))
                                <p class="text--15" id="error"><span class="red">{{ Session::get('message') }}</span></p>
                            @endif
                        </div>
                    </div>
                    <div class="blog__form__btn">
                        {!! Tag::formSubmit('ブログの申請はこちら', ['class' => 'btn_blog']) !!}
                    </div>
                {!! Tag::formClose() !!}
            </div><!--/app_status-->
            @endif
        </section><!--/explanation-->
    </div>
</div>
@endsection
