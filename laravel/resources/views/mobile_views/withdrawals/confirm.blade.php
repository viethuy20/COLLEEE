<?php $base_css_type = 'withdrawal'; ?>
@extends('layouts.default')

@section('layout.title', '退会｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')

<?php $user = Auth::user(); ?>

<section class="contents__wrap">
    <div class="inner u-mt-20">
		<h2 class="text--24">退会確認</h2>
	</div>

    <section class="inner u-mt-20">
        <div class="contents__box">
            <div class="withdrawal__form">
                <table>
                    <tr>
                        <th><span>メールアドレス</span></th>
                        <td><p>{{ $user->email }}</p></td>
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
                        <th><span>退会の理由</span></th>
                        <td>
                            <ul class="list_reason mb_10">
                                <?php
                                $reasons = $withdrawal['reasons'];
                                $reasons_map = config('map.withdrawal_reasons');
                                ?>
                                @foreach($reasons as $key => $value)
                                <li>{{ $reasons_map[$value] }}</li>
                                @endforeach
                            </ul>
                            <p class="mb_20">{{ $withdrawal['free_reason'] }}</p>
                        </td>
                    </tr>
                </table>
            </div>
            {!! Tag::formOpen(['route' => 'withdrawals.store', 'method' => 'post']) !!}
            @csrf   
            {!! Tag::formSubmit('退会する', ['class' => 'withdrawal__auth__btn']) !!}
            {!! Tag::formClose() !!}

        </div>
    </section><!--/contentsbox--><!--/leaving-->
    <div class="btn_y">{!! Tag::link(route('withdrawals.index'), '戻る') !!}</div>
</section>
@endsection
