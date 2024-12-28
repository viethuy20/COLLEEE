@php
$base_css_type = 'question';
@endphp
@extends('layouts.default')

@section('layout.head')
{!! Tag::script('/js/modal.js', ['type' => 'text/javascript']) !!}
<script type="text/javascript">
    var hasErrorOccurred = false;

    function handleAjaxError(errorDetails) {
        if (!hasErrorOccurred) { 
            alert("アンケート取得に失敗しました。お問い合わせください。");
            hasErrorOccurred = true;
        }
        console.error(errorDetails);
    }
    // TODO: 外部ファイル化
    // GMOリサーチアンケート取得
    $(function() {
        var gmoResearchApiHost = @json(config('survey.media.1.api_host'));

        $.ajax({
            type: 'get',
            url: gmoResearchApiHost +
                '/pollon/jp/gmor/research/pollon/enqueteList/facade/EnqueteList.json',
            dataType: "jsonp",
            data: {
                panelType: '436',
                crypt: $('#crypt').val(),
            },
            success: doSuccess,
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                handleAjaxError("GMOリサーチのエラー: " + textStatus);
            }
        });

        function doSuccess(response) {
            var listHtml = '';
            for (var i = 0; i < response.length; i++) {
                var listItem = response[i];

                // 回答済み場合はスキップ
                if (listItem.status == '02') {
                    continue;
                }
                // アンケート終了の場合はスキップ
                if (listItem.situation == '終了') {
                    continue;
                }

                if (listItem.status == '05' && listItem.situation == '未回答') {
                    listHtml += '<li class="questions__item">' +
                        '<a href="' + listItem.redirectSt + listItem.id + '=' + listItem.encryptId +
                        '" target="_blank" rel="noopener">' +
                        '<div class="questions__item__head">' +
                        '<p class="com">GMOリサーチ株式会社</p>' +
                        '<p class="num">NO.' + listItem.research_id + '</p>' +
                        '</div>' +
                        '<p class="questions__item__ttl">' + listItem.title + '</p>' +
                        '<div class="questions__item__foot">' +
                        '<p class="point">最大<span>' + listItem.point + 'P</span></p>' +
                        '<p class="btn">回答する</p>' +
                        '</div>' +
                        '</a>' +
                        '</li>';
                }
            }

            $('.questions__list').append(listHtml);

            // questions__itemの数をカウント
            var count = $('.questions__item').length;
            // 件数を表示
            $('.contents__sec__ttl span').text('（' + count + '件）');
        }
    });

    // セレスアンケート取得
    $(function() {
        var user_id = @json($user_id);

        $.ajax({
            type: 'get',
            url: '/api/user/' + user_id + '/items',
            dataType: "json",
            data: {
            },
            success: function(response) {
                var items = response.items;
                var listHtml = '';
                for (var i = 0; i < items.length; i++) {
                    var listItem = items[i];

                    // 回答済みの場合はスキップ
                    if (listItem.status == 1) {
                        continue;
                    }

                    listHtml += '<li class="questions__item">' +
                        '<a href="' + listItem.location + '" target="_blank" rel="noopener">' +
                        '<div class="questions__item__head">' +
                        '<p class="com">株式会社セレス</p>' +
                        '<p class="num">NO.' + listItem.research_id + '</p>' +
                        '</div>' +
                        '<p class="questions__item__ttl">' + listItem.research_name + '</p>' +
                        '<div class="questions__item__foot">' +
                        '<p class="point">最大<span>' + listItem.commission + 'P</span></p>' +
                        '<p class="btn">回答する</p>' +
                        '</div>' +
                        '</a>' +
                        '</li>';
                }

                $('.questions__list').append(listHtml);

                // questions__itemの数をカウント
                var count = $('.questions__item').length;
                // 件数を表示
                $('.contents__sec__ttl span').text('（' + count + '件）');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                handleAjaxError("セレスアンケートのエラー: " + textStatus);
            }
        });
    });

    //アイブリッジアンケート取得
    $(function() {
        var user_id = @json($user_id);
        var today = @json($today);

        $.ajax({
            type: 'get',
            url: '/api/user/' + user_id + '/i_bridge_items',
            dataType: "json",
            data: {
            },
            success: function(response) {
                var items = response.response.research;
                var listHtml = '';

                for (var i = 0; i < items.length; i++) {
                    var listItem = items[i];

                    // 回答済みの場合はスキップ
                    if (listItem.answered == 1 || listItem.flag_end == 1 || (listItem.term_end != "" && listItem.term_end < today)){
                        continue;
                    }

                    listHtml += '<li class="questions__item">' +
                        '<a href="' + listItem.url + '" target="_blank" rel="noopener">' +
                        '<div class="questions__item__head">' +
                        '<p class="com">株式会社アイブリッジ</p>' +
                        '<p class="num">NO.' + listItem.id + '</p>' +
                        '</div>' +
                        '<p class="questions__item__ttl">' + listItem.enquete_name + '</p>' +
                        '<div class="questions__item__foot">' +
                        '<p class="point">最大<span>' + listItem.point + 'P</span></p>' +
                        '<p class="btn">回答する</p>' +
                        '</div>' +
                        '</a>' +
                        '</li>';
                }

                $('.questions__list').append(listHtml);

                // questions__itemの数をカウント
                var count = $('.questions__item').length;

                // 件数を表示
                $('.contents__sec__ttl span').text('（' + count + '件）');
            },
            error: function(response) {console.log(response);
                alert("error");
            }
        });
    });

    // 回答履歴絞り込み
    $(document).ready(function() {
        // アンケート履歴モーダル表示の切り替え
        $(".questions__history__btn.js-modal-open").on("click", function() {
            $(".js-select-item").addClass("show");
        });
        $(".js-modal-close.modal__close").on("click", function() {
            $(".js-select-item").removeClass("show");
        });

        $(".js-select").on("change", function() {
            let selectedValue = $(this).val();
            if (selectedValue == "all") {
                $(".js-select-item").show();
            } else {
                $(".js-select-item").hide();
                $("." + selectedValue).show();
            }
        });
    });
</script>
@endsection
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
            アンケート
        </li>

    </ol>
</section>
@endsection

@section('layout.title', 'アンケート | ポイントサイトならGMOポイ活')
@section('layout.keywords', 'アンケート,ポイント,無料,簡単,毎日')
@section('layout.description',
'GMOポイ活はいつものショッピングや旅行予約、外食や、無料ゲームでポイントが貯まるポイントサイトです。貯まったポイントは現金やギフト券に交換！コツコツお小遣い稼ぎができます♪')
@section('og_type', 'website')

@section('layout.content')
<div class="contents">
    <h2 class="contents__thumb">
        <img src="{{ asset('/images/questions/thumb_sp.png') }}" alt="答えるだけ！アンケートで貯める">
    </h2>
    <input type="hidden" id="crypt" value="{{ $hex_string}}">
    <section class="contents__sec">
        <div class="contents__sec__head">
            <h3 class="contents__sec__ttl">毎日1ポイント貯まる！</h3>
        </div>
        <div class="contents__sec__item">
            <ul class="contents__sec__bnr">
                <li><a href="/questions/list"><img src="{{ asset('/images/questions/GMOquestions_sp.jpg') }}"
                            alt="毎日更新!GMOポイ活アンケート かんたんアンケートで1日1ポイント 今日の分をチェック"></a></li>
            </ul>
        </div>
    </section>
    <section class="contents__sec">
        <div class="contents__sec__head">
            <h3 class="contents__sec__ttl">あなたへの無料アンケート<span></span></h3>
            <a class="questions__history__btn js-modal-open" data-modal-open="modal-questions-history">回答履歴</a>
        </div>
        <div class="contents__sec__item">
            <ul class="questions__list">
            </ul>
        </div>
    </section>
    <section class="contents__sec">
        <div class="contents__sec__head">
            <h3 class="contents__sec__ttl">注意事項</h3>
        </div>
        <div class="contents__sec__item">
            <ul class="contents__note__list">
                <li>アンケート回答完了時にポイントが獲得できます。</li>
                <li>不正行為があったとみなされた場合はポイント獲得の権利を失います。ご注意ください。</li>
                <li>アンケートは予告なく終了する場合がございます。回答はお早めにお願いいたします。</li>
            </ul>
        </div>
    </section>
</div>

@include('questions.modal.history')
@endsection