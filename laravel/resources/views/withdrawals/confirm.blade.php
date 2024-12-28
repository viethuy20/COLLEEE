<?php $base_css_type = 'withdrawal'; ?>
@extends('layouts.plane')

@section('layout.title', '退会｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')
<?php $user = Auth::user(); ?>

<section class="contents">
    <h1 class="contents__ttl">退会確認</h1>

    <div class="contents__box"><!--/caution-->
        <div class="withdrawal__form">
            <table>
                <tr>
                    <th><span>メールアドレス</span></th>
                    <td>
                        <p>{{ $user->email }}</p>
                    </td>
                </tr>
                
                @php
                    $googleType = 2;
                    $lineType = 1;
                    $cookie = null;
                    if (Cookie::get('cookie_login')) {
                        $cookie = Crypt::decryptString(Cookie::get('cookie_login')) ?? null;
                    }
                    
                    if ($cookie == null) Auth::logout();
                @endphp

                @switch(true)
                    @case($user->google_id && $cookie == $googleType)
                        <tr>
                            <th><span>GOOGLEユーザーID	</span></th>
                            <td><p></p><span class="note">{{$user->google_id}}</span></td>
                        </tr>
                        @break

                    @case($user->line_id && $cookie == $lineType)
                        <tr>
                            <th><span>LINEユーザーID</span></th>
                            <td><p></p><span class="note">{{$user->line_id}}</span></td>
                        </tr>
                        @break

                    @default
                        <tr>
                            <th><span>パスワード</span></th>
                            <td><p></p><span class="note">パスワードは安全の為表示されません</span></td>
                        </tr>
                @endswitch

                <tr>
                    <th><span>退会の理由<br />（複数回答可）</span></th>
                    <td>
                        <?php
                        $reasons = $withdrawal['reasons'];
                        $reasons_map = config('map.withdrawal_reasons');
                        ?>
                        <ul class="list_checked">
                            @foreach($reasons as $key => $value)
                            <li>{{ $reasons_map[$value] }}</li>
                            @endforeach
                        </ul>
                        <p class="txt_reason">{{ $withdrawal['free_reason'] }}</p>
                    </td>
                </tr>
            </table>
        </div>
        <div class="clearfix btns">
            {!! Tag::formOpen(['route' => 'withdrawals.store', 'method' => 'post']) !!}
            @csrf    
            {!! Tag::formSubmit('退会する', ['class' => 'withdrawal__auth__btn']) !!}
            {!! Tag::formClose() !!}
        </div><!--/clearfix btns-->
    </div><!--/contentsbox-->
    <div class="btn_y">{!! Tag::link(route('withdrawals.index'), '戻る') !!}</div>
</section><!--/leaving-->
@endsection
