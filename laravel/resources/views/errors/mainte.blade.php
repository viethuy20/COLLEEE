@extends('layouts.plane')

@section('layout.title', 'メンテナンス')

@section('layout.content')
<section class="maintenance">
    {!! Tag::image('/images/img_preparation.svg', '') !!}
    <p class="u-font-bold u-text-ac u-mt-20 text--18 red">
        @if (isset($message))
        {!! nl2br(e($message)) !!}
        @else
        現在準備中です。<br />
        ご不便をおかけし大変申し訳ございませんが、<br />
        終了まで今しばらくお待ちください。
        @endif
    </p>
    <div class="back__btn"><a href="javascript:history.back();">戻る</a></div>
</section><!--/maintenance-->
@endsection
