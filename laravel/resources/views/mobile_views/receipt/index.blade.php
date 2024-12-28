@php
    $base_css_type = 'receipt';
@endphp
@extends('layouts.default')

@section('layout.head')
    <script type="text/javascript"><!--
        $(function(){
            $('.txt_ureview').collapser({
                mode: 'chars',
                truncate: 70,
                showText: '続きを読む',
                hideText: '閉じる'
            });
        });
        //-->
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
            モニター(レシ活)
        </li>
    </ol>
</section>
@endsection

@section('layout.title', 'モニター(レシ活) | はじめてのポイ活はGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')
    <?php $user = Auth::user(); ?>
    <!-- contents wrap -->
    <section class="contents__wrap">

        <!-- page title -->
        <div class="inner">
            <h2 class=""><img src="/images/receipt/receipt_fv.png" alt="レシート投稿でポイントゲット！"></h2>

            <!-- menu -->
            <div class="receipt__menu">
                <ul>
                    <li><a href="{{config('receipt.URL_ABOUT').config('receipt.CLIENT_KEY')}}&secret={{$secret}}" target="_blank"><img src="/images/receipt/logo_tentame.svg" alt="テンタメ！">とは</a></li>
{{--                    <li><a href="{{config('receipt.URl_HOW_TO').config('receipt.CLIENT_KEY')}}" target="_blank">参加方法</a></li>--}}
                    <li><a href="{{config('receipt.URL_FAQ').config('receipt.CLIENT_KEY')}}" target="_blank">FAQ</a></li>
                </ul>
            </div>
        </div>

        <!-- list -->
        <div class="inner">

            @php
                $sort = $condition->getParam('sort') ?? 1;
                $sort_map = [1 => '募集中', 2 => '参加中', 3 => '回答済'];

                $point_label_map = [1 => ['class' => 'ico_status_t', 'initial' => 'T', ],
                    2 => ['class' => 'ico_status_r', 'initial' => 'R', ],
                    3 => ['class' => 'ico_status_j', 'initial' => 'J', ],
                    4 => ['class' => 'ico_status_a', 'initial' => 'A', ]];
            @endphp
                <!-- list -->
            <ul class="receipt__order__tab">
                @foreach ($sort_map as $key => $label)
                    <li {!! $key == $sort ? 'class="active"' : '' !!}>
                        {{ Tag::link($condition->getListUrl((object) ['sort' => $key, 'page' => 1]), $label, null, null, false) }}
                    </li>
                @endforeach
            </ul>

            @if (!empty($paginator) && $paginator->total() > 0)
            <!-- 募集中 -->
            <div class="receipt__order active" id="order_request">
                <ul class="receipt__order__list">
                    @foreach ($paginator as $product)
                    <li>
                        <a href="{{ $product['url'] }}" target="_blank">
                            <div class="item">
                                <div class="item__img">
                                    @if ($product['image'])
                                        {{ Tag::image($product['image'], $product['item_name']) }}
                                    @else
                                        <img src="/images/receipt/dummy.png" alt="デフォルトの領収書画像">
                                    @endif
                                </div>
                                <div class="item__txt">
                                    <p class="ttl">{{ $product['item_name'] }}</p>
                                    <p class="point">{{ number_format($product['point']) }}P</p>
                                </div>
                                <div class="item__data">
                                    <dl>
                                        <dt>回答期限</dt>
                                        <dd>{{ !empty($product['endDate']) ? date('Y/m/d', strtotime($product['endDate'])) : '' }}</dd>
                                        <dt>購入個数</dt>
                                        <dd>{{ !empty($product['quantity']) ? $product['quantity'] : 0 }}個</dd>
                                        <dt>金額目安</dt>
                                        <dd>{{ !empty($product['price']) ? number_format($product['price']) : 0 }}円（税込み）</dd>
                                    </dl>
                                </div>
                            </div>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- 参加中 -->
            <div class="receipt__order" id="order_join">
                <ul class="receipt__order__list">
                    @foreach ($paginator as $product)
                        <li>
                            <a href="{{ $product['url'] }}" target="_blank">
                                <div class="item">
                                    <div class="item__img">
                                        @if ($product['image'])
                                            {{ Tag::image($product['image'], $product['item_name']) }}
                                        @else
                                            <img src="/images/receipt/dummy.png" alt="デフォルトの領収書画像">
                                        @endif
                                    </div>
                                    <div class="item__txt">
                                        <p class="ttl">{{ $product['item_name'] }}</p>
                                        <p class="point">{{ number_format($product['point']) }}P</p>
                                    </div>
                                    <div class="item__data">
                                        <dl>
                                            <dt>回答期限</dt>
                                            <dd>{{ !empty($product['endDate']) ? date('Y/m/d', strtotime($product['endDate'])) : '' }}</dd>
                                            <dt>購入個数</dt>
                                            <dd>{{ !empty($product['quantity']) ? $product['quantity'] : 0 }}個</dd>
                                            <dt>金額目安</dt>
                                            <dd>{{ !empty($product['price']) ? number_format($product['price']) : 0 }}円（税込み）</dd>
                                        </dl>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- 回答済 -->
            <div class="receipt__order" id="order_answer">
                <ul class="receipt__order__list">
                    @foreach ($paginator as $product)
                        <li>
                            <a href="{{ $product['url'] }}" target="_blank">
                                <div class="item">
                                    <div class="item__img">
                                        @if ($product['image'])
                                            {{ Tag::image($product['image'], $product['item_name']) }}
                                        @else
                                            <img src="/images/receipt/dummy.png" alt="デフォルトの領収書画像">
                                        @endif
                                    </div>
                                    <div class="item__txt">
                                        <p class="ttl">{{ $product['item_name'] }}</p>
                                        <p class="point">{{ number_format($product['point']) }}P</p>
                                    </div>
                                    <div class="item__data">
                                        <dl>
                                            <dt>回答期限</dt>
                                            <dd>{{ !empty($product['endDate']) ? date('Y/m/d', strtotime($product['endDate'])) : '' }}</dd>
                                            <dt>購入個数</dt>
                                            <dd>{{ !empty($product['quantity']) ? $product['quantity'] : 0 }}個</dd>
                                            <dt>金額目安</dt>
                                            <dd>{{ !empty($product['price']) ? number_format($product['price']) : 0 }}円（税込み）</dd>
                                        </dl>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {!! $paginator->render('elements.pager', ['pageUrl' => function($page) use($condition) { return $condition->getListUrl((object) ['page' => $page]); }]) !!}

        @else
            <section class="list__receipt__message u-mt-20">
                <p class="u-font-bold u-text-ac text--18 red">対象のアンケートがありません</p>
            </section>
        @endif

    </section>
@endsection
