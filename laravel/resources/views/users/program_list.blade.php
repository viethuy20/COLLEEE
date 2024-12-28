@extends('layouts.mypage')

@section('layout.title', 'お気に入り広告一覧｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活は無料で簡単にお小遣いが貯められる安心・安全なポイントサイトです。お気に入りの広告を登録してください。')

@section('layout.content')

<section class="contents">
    <h2 class="contents__ttl">お気に入りに追加した広告一覧</h2>
    <div class="favorite able_list__description">
        {{-- <h2><span class="icon-plus"></span>お気に入りに追加した広告</h2> --}}
        @if($paginator->total() < 1)
        <p class="u-font-bold text--18 red">お気に入りに追加した広告はありません。</p>
        @endif
    </div>
    @if($paginator->total() >= 1)
    <div class="favorite__list">
        <ul>
            @foreach($paginator as $program)
            @php
            // アフィリエイト情報
            $affiriate = $program->affiriate;
            @endphp
            <li>
                <div class="favorite__flex">
                    <div class="favorite__flex__l">
                        <div class="favorite__thumb">
                            <a class="clip__thumb" href="{{ route('programs.show', ['program' => $program]) }}">
                                {{ Tag::image($affiriate->img_url, $program->title) }}
                            </a>
                        </div>
                    </div>
                    <div class="favorite__flex__c">
                        <p class="favorite__ttl">{{ Tag::link(route('programs.show', ['program' => $program]), $program->title, [], null, false) }}</p>
                        <div class="favorite__txt">
                            <p class="favorite__txt__custom">{{ $program->description }}</p>
                        </div>
                    </div>
                    <div class="favorite__flex__r">
                        <div class="favorite__ico">
                        {{ Tag::link(route('users.remove_program', ['program' => $program]), Tag::image('/images//users/ico_dustbox.svg', '削除'), ['class' => 'save_scroll'], null, false) }}
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    {!! $paginator->render('elements.pager', ['pageUrl' => function($page) { return route('users.program_list', ['page' => $page]); }]) !!}
    @endif
    <div class="mypage__btn">
            {{ Tag::link(route('programs.list'), 'お気に入り広告を探す') }}
    </div>
</section><!--/contents-->
@endsection
