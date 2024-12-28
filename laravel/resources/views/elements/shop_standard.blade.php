@php
$builder = \App\Content::ofSpot(\App\Content::SPOT_SHOP_STANDARD)
    ->get()
    ->filter(function ($content, $key) {
        return isset($content->program->id);
    });
$builder = isset($limit) ? $builder->take($limit) : $builder;
$standard_content_list = $builder->all();
@endphp

@if (!empty($standard_content_list))
<div class="top__ttl">
    <div class="top__ttl__l">
        <p class="top__ttl__jp">定番ショップ</p>
        <h2 class="top__ttl__en">STANDARD SHOPS</h2>
    </div>
    <div class="top__ttl__r">
        {{ Tag::link( \App\Search\ProgramCondition::getStaticListUrl((object)['content_spot_id' => \App\Content::SPOT_SHOP_STANDARD]), 'もっと見る', ['class' => 'top__ttl__link'], false, false)}}
    </div>
</div>

<ul class="storesbox">
    @foreach ($standard_content_list as $content)
    @php
    $program = $content->program;
    // ポイント
    $point = $program->point;
    // アフィリエイト
    $affiriate = $program->affiriate;
    @endphp
    <li>
        <a href="{{ route('programs.show', ['program' => $program]) }}">
            <div class="top__list__shopping__thumb">{{ Tag::image($affiriate->img_url, $content->title) }}</div>
            <p class="top__list__shopping__ttl">{{ $content->title }}</p>
            <p class="top__list__shopping__point">
                @if ($point->fee_type == 2)
                <span>{{ strval($point->rate_percent) }}</span>%P
                @else
                {{ $point->fee_label_s }}
                @endif
            </p>
        </a>
    </li>
    @endforeach
</ul>
<hr class="bd_orange u-mt-20">
@endif
