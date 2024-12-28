<?php
    $base_css_type = 'entries';
    $hidden_header = true;
?>
@extends('layouts.plane')

@section('layout.title', 'GMOポイ活新規会員登録｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はいつものショッピングや旅行予約、外食や、無料ゲームでポイントが貯まるポイントサイトです。無料会員登録して、ポイントを貯めて現金やギフトカードに交換しよう♪')

@section('layout.head')

@endsection
@php
$meta = new \App\Services\Meta;
$arr_breadcrumbs = $meta->setBreadcrumbs(null);

switch (config('app.env')) {
    case 'local':
        $p = 'L';
        break;
    case 'development':
        $p = 'D';
        break;
    default:
        $p = 'C';
        break;
}
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
		<h2 class="contents__ttl">会員情報入力</h2>

        <div class="contents__box">
            <div class="contents__box__inner">
                <ol class="entries__flow">
                    <li class="current"><i><img src="/images/entries/ico_flow_input.svg"></i><p>入力</p></li>
                    <li class=""><i><img src="/images/entries/ico_flow_confirm.svg"></i><p>確認</p></li>
                    <li class=""><i><img src="/images/entries/ico_flow_call.svg"></i><p>電話認証</p></li>
                    <li class=""><i><img src="/images/entries/ico_flow_success.svg"></i><p>完了</p></li>
                </ol>
                <div class="contents__box__txt">
                    <p>次の項目を入力し「入力内容の確認へ」ボタンをクリックしてください。</p>
                </div>

                <div class="entries__create">
                    {!! Tag::formOpen(['url' => route('entries.confirm'), 'class' => 'entries__form js-entry-form']) !!}
                    @csrf
                    @if (Session::has('message'))
                    <p class="form-error__message"><span class="icon-attention"></span>{{ Session::get('message') }}</p>
                    @endif

                    <div class="entries__form__inner">
                        <dl class="entries__form__table">
                            <dt><label for="email">メールアドレス</label><span class="tag required"></span></dt>
                            <dd>
                                <input type="email" name="email" required pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,4}" maxlength="64" value="{{ ($entry_user['email']) ?? '' }}" @if ((!empty($entry_user['line_id']) && !empty($entry_user['email'])) || (!empty($entry_user['google_id']) && !empty($entry_user['email']))) disabled @endif placeholder="例：gmo-poikatsu@sample.jp">
                                <ul class="notes">
                                    <li>半角文字のみ入力可能</li>
                                </ul>
                                @if ((!empty($entry_user['line_id']) && !empty($entry_user['email'])) || (!empty($entry_user['google_id']) && !empty($entry_user['email']) ))
                                <input name="email" type="hidden" value="{{ $entry_user['email'] }}">
                                @endif
                                <p class="form-error__message js-error-message"></p>
                            </dd>

                            @if (empty($entry_user['line_id']) && empty($entry_user['google_id']))
                            <dt><label for="password">パスワード</label><span class="tag required"></span></dt>
                            <dd>
                                @if (empty($entry_user['line_id']) && empty($entry_user['google_id']))
                                    {!! Tag::formPassword('password', ['required' => 'required', 'minlength' => 8, 'maxlength' => '20', 'autocomplete' => 'off', 'placeholder' => '', 'id' => 'password' , 'pattern' => '^[a-zA-Z0-9!\#$%&amp;+\-.&lt;\=&gt;?@^_~]+$']) !!}
                                @else
                                    {!! Tag::formPassword('password', [ 'minlength' => 8, 'maxlength' => '20', 'autocomplete' => 'off', 'placeholder' => '', 'id' => 'password', 'pattern' => '^[a-zA-Z0-9!\#$%&amp;+\-.&lt;\=&gt;?@^_~]+$']) !!}
                                @endif

                                <ul class="notes">
                                    <li>8文字以上、20文字以内で入力してください</li>
                                    <li>半角英数字、「!#$%&+-.<=>?@^_~」の記号のみ入力可能</li>
                                </ul>
                                <p class="form-error__message js-error-message"></p>
                                @if ($errors->has('password'))
                                <p class="form-error__message"><span class="icon-attention"></span>{{ $errors->first('password') }}</p>
                                @endif
                                <div class="password__level">
                                    <div class="bar" id="js-password-level">
                                        <div></div><div></div><div></div><div></div><div></div>
                                    </div>
                                    <div class="txt" id="js-password-level-txt">パスワード強度：</div>
                                </div>
                            </dd>

                            <dt><label for="password_confirmation">パスワード(再入力)</label><span class="tag required"></span></dt>
                            <dd>
                                @if (empty($entry_user['line_id']) && empty($entry_user['google_id']))
                                    {!! Tag::formPassword('password_confirmation', ['id' => 'password_confirmation', 'required' => 'required', 'pattern' => '^[a-zA-Z0-9!\#$%&amp;+\-.&lt;\=&gt;?@^_~]+$', 'minlength' => 8, 'maxlength' => '20', 'autocomplete' => 'off', 'placeholder' => '', 'value' => '']) !!}
                                @else
                                    {!! Tag::formPassword('password_confirmation', ['id' => 'password_confirmation', 'pattern' => '^[a-zA-Z0-9!\#$%&amp;+\-.&lt;\=&gt;?@^_~]+$', 'minlength' => 8, 'maxlength' => '20', 'autocomplete' => 'off', 'placeholder' => '', 'value' => '']) !!}
                                @endif

                                <ul class="notes">
                                    <li>確認のため、もう一度パスワードを入力してください</li>
                                </ul>
                                <p class="form-error__message js-error-message"></p>
                                @if ($errors->has('password'))
                                <p class="form-error__message"><span class="icon-attention"></span>{{ $errors->first('password') }}</p>
                                @endif
                            </dd>
                            @endif

                            <dt id="entry-tel"><label for="tel">電話番号</label><span class="tag required"></span></dt>
                            <dd>
                                {!! Tag::formText('tel', '', ['pattern' => '[0-9]*', 'id' => 'tel', 'required' => 'required', 'minlength' => '10', 'maxlength' => '11', 'placeholder' => '例：0123456789']) !!}

                                <ul class="notes">
                                    <li>ハイフンなし、半角数字のみ入力可能</li>
                                    <li>050から始まるIP電話番号（BBフォン等）での登録はできません</li>
                                    <li>本人確認のための「電話認証」で使用いたします</li>
                                </ul>
                                <p class="form-error__message js-error-message"></p>
                                @if ($errors->has('tel'))
                                <p class="form-error__message"><span class="icon-attention"></span>{{ $errors->first('tel') }}</p>
                                @endif
                            </dd>

                            <dt><label for="tel_confirmation">電話番号(再入力)</label><span class="tag required"></span></dt>
                            <dd>
                                {!! Tag::formText('tel_confirmation', '', ['pattern' => '[0-9]*', 'id' => 'tel_confirmation', 'required' => 'required', 'minlength' => '10', 'maxlength' => '11']) !!}
                                <ul class="notes">
                                    <li>確認のため、もう一度電話番号を入力してください</li>
                                </ul>
                                <p class="form-error__message js-error-message"></p>
                            </dd>

                            <dt><label for="birthday">生年月日</label><span class="tag required"></span></dt>
                            <dd>
                                <fieldset id="birthday" name="birthday" class="selects">
                                    <div class="selects__item">
                                        <div class="select-wrap">
                                            <select id="UserBirthdayYear" class="js-birthday-year" name="birthday[year]" val="{{ $entry_user['birthday']['year']??'' }}" required></select>
                                        </div>
                                        <span>年</span>
                                    </div>
                                    <div class="selects__item">
                                        <div class="select-wrap">
                                            <select id="UserBirthdayMonth" class="js-birthday-month" name="birthday[month]" val="{{ $entry_user['birthday']['month']??'' }}" required></select>
                                        </div>
                                        <span>月</span>
                                    </div>
                                    <div class="selects__item">
                                        <div class="select-wrap">
                                            <select id="UserBirthdayDay" class="js-birthday-day" name="birthday[day]" val="{{ $entry_user['birthday']['day']??'' }}" required></select>
                                        </div>
                                        <span>日</span>
                                    </div>
                                </fieldset>
                                <div class="checkbox">
                                    {!! Tag::formCheckbox('minor-consent',1, false, ['id' => 'minor-consent']) !!}
                                    <label for="minor-consent">保護者の同意を得ました</label>
                                    @if ($errors->has('consent'))
                                    <p class="form-error__message"><span class="icon-attention"></span>{{ $errors->first('consent') }}</p>
                                    @endif
                                    <p class="form-error__message js-error-message"></p>
                                </div>
                                <ul class="notes">
                                    <li>一度ご登録いただいた生年月日の変更はできませんので、正確に入力して下さい</li>
                                </ul>
                                <p class="form-error__message js-error-message test"></p>
                                @if ($errors->has('birthday'))
                                <p class="form-error__message"><span class="icon-attention"></span>{{ $errors->first('birthday') }}</p>
                                @endif
                            </dd>

                            <dt><label for="sex">性別</label><span class="tag"></span></dt>
                            <dd>
                                <div id="sex" class="radio">
                                    <div class="radio__item">
                                        <div class="radio__item__inner">
                                            <input id="select_m" name="sex" type="radio" value="1" @if (isset($entry_user['sex']) && $entry_user['sex'] == 1) checked @endif><label for="select_m">男性</label><br>
                                        </div>
                                    </div>
                                    <div class="radio__item">
                                        <div class="radio__item__inner">
                                            <input id="select_f" name="sex" type="radio" value="2" @if (isset($entry_user['sex']) && $entry_user['sex'] == 2) checked @endif><label for="select_f">女性</label><br>
                                        </div>
                                    </div>
                                    <div class="radio__item">
                                        <div class="radio__item__inner">
                                            <input id="select_o" name="sex" type="radio" value="0" @if (isset($entry_user['sex']) && $entry_user['sex'] == 0) checked @endif><label for="select_o">その他</label>
                                        </div>
                                    </div>
                                </div>
                            </dd>

                            <dt><label for="prefecture">居住地</label><span class="tag"></span></dt>
                            <dd>
                                <div id="prefecture" class="selects">
                                    <div class="selects__item">
                                        <div class="select-wrap">
                                            {!! Tag::formSelect('prefecture_id', config('map.prefecture'), $entry_user['prefecture_id'] ?? 1) !!}
                                        </div>
                                        @if ($errors->has('prefecture'))
                                        <div class="error">{{ $errors->first('prefecture_id') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </dd>
                            <dt><label for="carriers">ご使用のスマートフォン</label><span class="tag"></span></dt>
                            <dd>
                                <div id="carriers" class="radio">
                                    <div class="radio__item">
                                        <div class="radio__item__inner">
                                            <input type="radio" id="select_docomo" name="carriers" value="ドコモ" @if (isset($entry_user['carriers']) && $entry_user['carriers'] == 'ドコモ') checked @endif><label for="select_docomo">ドコモ</label>
                                        </div>
                                    </div>
                                    <div class="radio__item">
                                        <div class="radio__item__inner">
                                            <input type="radio" id="select_au" name="carriers" value="au" @if (isset($entry_user['carriers']) && $entry_user['carriers'] == 'au') checked @endif><label for="select_au">au</label>
                                        </div>
                                    </div>
                                    <div class="radio__item">
                                        <div class="radio__item__inner">
                                            <input type="radio" id="select_softbank" name="carriers" value="ソフトバンク" @if (isset($entry_user['carriers']) && $entry_user['carriers'] == 'ソフトバンク') checked @endif><label for="select_softbank">ソフトバンク</label>
                                        </div>
                                    </div>
                                    <div class="radio__item">
                                        <div class="radio__item__inner">
                                            <input type="radio" id="select_rakutenmobile" name="carriers" value="楽天モバイル" @if (isset($entry_user['carriers']) && $entry_user['carriers'] == '楽天モバイル') checked @endif><label for="select_rakutenmobile">楽天モバイル</label>
                                        </div>
                                    </div>
                                    <div class="radio__item">
                                        <div class="radio__item__inner">
                                            <input type="radio" id="select_ahamo" name="carriers" value="ahamo" @if (isset($entry_user['carriers']) && $entry_user['carriers'] == 'ahamo') checked @endif><label for="select_ahamo">ahamo</label>
                                        </div>
                                    </div>
                                    <div class="radio__item">
                                        <div class="radio__item__inner">
                                            <input type="radio" id="select_povo" name="carriers" value="povo" @if (isset($entry_user['carriers']) && $entry_user['carriers'] == 'povo') checked @endif><label for="select_povo">povo</label>
                                        </div>
                                    </div>
                                    <div class="radio__item">
                                        <div class="radio__item__inner">
                                            <input type="radio" id="select_uq" name="carriers" value="UQモバイル" @if (isset($entry_user['carriers']) && $entry_user['carriers'] == 'UQモバイル') checked @endif><label for="select_uq">UQモバイル</label>
                                        </div>
                                    </div>
                                    <div class="radio__item">
                                        <div class="radio__item__inner">
                                            <input type="radio" id="select_ymobile" name="carriers" value="ワイモバイル" @if (isset($entry_user['carriers']) && $entry_user['carriers'] == 'ワイモバイル') checked @endif><label for="select_ymobile">ワイモバイル</label>
                                        </div>
                                    </div>
                                    <div class="radio__item">
                                        <div class="radio__item__inner">
                                            <input type="radio" id="select_none" name="carriers" value="持っていない" @if (isset($entry_user['carriers']) && $entry_user['carriers'] == '持っていない') checked @endif><label for="select_none">持っていない</label>
                                        </div>
                                    </div>
                                    <div class="radio__item other">
                                        <div class="radio__item__inner">
                                            <input type="radio" id="select_other" name="carriers" value="その他" @if (isset($entry_user['carriers']) && $entry_user['carriers'] == 'その他') checked @endif><label for="select_other">その他：</label>
                                        </div>
                                        <input type="text" id="select_other_text" class="js-other-input" name="carriers_other" value="{{ old('carriers_other') ?? '' }}">
                                    </div>
                                </div>
                            </dd>

                            <dt><label for="invitation">紹介コード</label><span class="tag"></span></dt>
                            <dd>
                                <div class="checkbox invitation">
                                    <div class="checkbox__item">
                                        <p class="checkbox__item__inner">
                                            <input type="checkbox" name="invitation" id="invitation" value="1" {{ ($entry_user['invitation']??0)? 'checked' : '' }}>
                                            <label for="invitation" class="textlink">紹介コードをお持ちの方はこちら</label>
                                        </p>
                                        <div class="js-invitation_code invitation__code">
                                            <input type="text" name="invitation_code" id="invitation_code" value="{{ $entry_user['invitation_code']??'' }}" pattern="{{ $p }}+[0-9]*" minlength="16" maxlength="16" placeholder="例：{{ $p }}0000000000">
                                            <ul class="notes">
                                                <li>半角英数字のみ入力可能</li>
                                                <li>これ以降、紹介コードの入力はできません</li>
                                            </ul>
                                            <p class="form-error__message js-error-message"></p>
                                        </div>
                                    </div>
                                </div>
                            </dd>
                        </dl>
                        <div class="entries__form__terms terms__wrap">
                            @include('inc.terms-of-service')
                            <p class="terms__checkbox">
                                <input type="checkbox" id="consent" name="consent">
                                <label for="consent">「GMOポイ活会員利用規約」および「<a href="https://www.koukoku.jp/privacy/" target="_blank" class="textlink blank">個人情報の取扱いについて</a>」を確認し、同意します。</label>
                            </p>
                        </div>
                        <div class="contents__btn__wrap">
                            <div class="contents__btn orange">
                                <input type="hidden" name="line_id" value="@if(isset($entry_user['line_id']) && !empty($entry_user['line_id'])) {{$entry_user['line_id']}} @endif">
                                <input type="hidden" name="google_id" value="@if(isset($entry_user['google_id']) && !empty($entry_user['google_id'])) {{$entry_user['google_id']}} @endif">
                                <button id="submit" type="submit" disabled>入力内容の確認へ</button>
                            </div>
                        </div>
                    </div>
                    {!! Tag::formClose() !!}
                </div>
            </div>
        </div>
    </div>
{!! Tag::script('/js/passwordchecker.js', ['type' => 'text/javascript']) !!}
{!! Tag::script('/js/entries.js', ['type' => 'text/javascript']) !!}
@endsection

