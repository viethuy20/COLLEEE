@php
$for_ajax = $for_ajax ?? false;
$render_id = 'review_list';
$sort_map = [
    0=> ['title'=>'新着', 'url' => route('reviews.ajax_reviewer', ['user' => $reviewer, 'sort' => 0])],
    1=> ['title'=>'参考になった', 'url' => route('reviews.ajax_reviewer', ['user' => $reviewer, 'sort' => 1])],
    2=> ['title'=>'評価', 'url' => route('reviews.ajax_reviewer', ['user' => $reviewer, 'sort' => 2])],
];

$user = Auth::check() ? Auth::user() : null;
@endphp
@if (!$for_ajax)
    <div id="{{ $render_id }}"><div class="exist">
@endif
@php
$reviewer_name = $reviewer->nickname ?? $reviewer->name
@endphp
    <div class="revirew__title">
        <h2 class="contents__ttl">{{ $reviewer_name }}さんの口コミ一覧（{{ $review_total }}件）</h2>
        <div class="revirew__card__select">
            @include('elements.sort_ajax', ['sort_map' => $sort_map, 'sort' => $condition->sort, 'render_id' => $render_id, 'class' => 'sort_user_review'])
        </div>
    </div>
    <div class="revirew__card">
        <ul>
            @foreach($review_list as $review)
                <?php $program = $review->program; ?>
                <li id="review_{{ $review->id }}">
                    <div class="revirew__card__name">
                        [{{ $loop->iteration }}]
                        @if($program->is_enable)
                            {{ Tag::link(route('programs.show', ['program' => $program]), $program->title, ['class' => 'adlink']) }}
                        @else
                            {{ $program->title }}
                        @endif
                    </div>
                    <div class="revirew__card__head">
                        <div class="revirew__card__name_orange">
                            {{ $review->reviewer }}
                        </div>
                        <div class="revirew__card__star"><!--★-->
                            <ul>
                                @for ($i = 1; $i <= 5; $i++)
                                    <li>{{ Tag::image(($i <= $review->assessment) ? '/images/programs/ico_kuchikomi_star_yellow.svg' : '/images/programs/ico_kuchikomi_star_gray.svg', 'star') }}</li>
                                @endfor
                                <p class="revirew__card__star__txt">（{{ $review->assessment }}/5）</p>
                            </ul>
                        </div>
                    </div>
                    <p class="text--15">{{ $review->message }}</p>
                    <p class="revirew__card__data">{{ $review->created_at->format('Y-m-d H:i') }}</p>
                    <div id="reference_info_{{ $review->id }}" class="clearfix va_m"><!--削除するとajaxが動作しなくなる古いdevそのまま -->
                        <div class="revirew__card__bottom">
                            @if (isset($user->id))
                                @if ($review->helpfuls()->where('user_id', '=', $user->id)->exists())
                                    <div class="revirew__card__bottom__btn">
                                        <p class="was"><i><img src="/images/programs/ico_heart.svg"></i>参考になった</p>
                                    </div>
                                @else
                                    <div class="revirew__card__bottom__btn">
                                        <p class="pushed for_link" forUrl="{{ route('reviews.add_helpful', ['review' => $review]) }}">
                                            <i><img src="/images/programs/ico_heart.svg"></i>参考になった
                                        </p>
                                    </div>
                                @endif
                            @endif
                            <p class="revirew__card__bottom__txt">{{ $review->helpful_total }}人が参考にしました</p>
                        </div>
                    </div><!--/va_m-->
                </li>
            @endforeach
        </ul>
    </div><!--/r_list-->

@if (!$for_ajax)
</div></div><!--/exist--><!--/review_list-->
@endif


