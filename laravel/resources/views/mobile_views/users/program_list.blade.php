@php
$base_css_type = 'mypage';
@endphp
@extends('layouts.default')

@section('layout.title', 'お気に入り広告一覧｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活は無料で簡単にお小遣いが貯められる安心・安全なポイントサイトです。お気に入りの広告を登録してください。')


@section('layout.content')

<section class="inner">
    @if($paginator->total() < 1)
    <h2 class="contents__ttl u-mt-small">お気に入りに追加した広告</h2>
    <p class="u-font-bold text--18 red">お気に入りに追加した広告はありません。</p>
    @else
    <h2 class="contents__ttl u-mt-small">お気に入りに追加した広告</h2>
    <div class="favorite__list">
        <ul>
            @foreach($paginator as $program)
            @php
            // アフィリエイト情報
            $affiriate = $program->affiriate;
            @endphp
            <li>
                <div class="favorite__flex__custom">
                    <div class="favorite__flex__l">
                        <div class="favorite__thumb">
                            <a href="{{ route('programs.show', ['program' => $program]) }}">
                                {{ Tag::image($affiriate->img_url, $program->title) }}
                            </a>
                        </div>
                    </div>
                    <div class="favorite__flex__r">
                        <div class="favorite__ttl__custom">
                        {{ Tag::link(route('programs.show', ['program' => $program]), $program->title, [], null, false) }}
                        </div>
                    </div>
                </div>
                <p class="favorite__txt">{{ $program->description }}</p>
                <div class="favorite__delete">
                    <a class="favorite__delete__custom" href="{{ route('users.remove_program', ['program' => $program]) }}">
                        <i class="favorite__delete__custom__link">{{ Tag::image('/images/users/ico_dustbox.svg', '削除') }}</i>
                        お気に入り削除
                    </a>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    {!! $paginator->render('elements.pager', ['pageUrl' => function($page) { return route('users.program_list', ['page' => $page]); }]) !!}
    @endif
    <div class="mypage__btn">
        {{ Tag::link(route('programs.list'), 'お気に入り広告を探す', null, null, false) }}
    </div>
</section>
@endsection
