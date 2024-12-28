@php
$query = \App\Review::ofProgram($condition->program_id)
    ->ofSort($condition->sort);
$review_list = $query->get();
$user = Auth::check() ? Auth::user() : null;
@endphp

	<ul class="review__list js-review">
        @foreach($review_list as $review)
            <li id="review_{{ $review->id }}"  class="review__list__item">
                <div class="review__list__item__inner">
                    <div class="head">
                        <div class="program__evaluation">
                            <ul class="star js-star">
                                @for ($i = 1; $i <= 5; $i++)
                                    <li></li>
                                @endfor
                            </ul>
                            <p class="star__count js-star-count">{{ $review->assessment }}</p>
                        </div>
                        <div class="review__like">
                            @if (isset($user->id))
                                @if ($review->helpfuls()->where('user_id', '=', $user->id)->exists())
                                    <a class="js-like is-active" ><i></i><span class="js-like-count">{{ $review->helpful_total }}</span></a>
                                @else
                                    <a class="js-like for_link" forUrl="{{ route('reviews.add_helpful', ['review' => $review]) }}"><i></i><span class="js-like-count">{{ $review->helpful_total }}</span></a>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="txt js-txt-limit">
                        <p>{{ $review->message }}</p>
                    </div>
                    <div class="foot">
                        <p class="user-name">
                        @if (isset($review->user))
                            {{ Tag::link(route('reviews.reviewer', ['user' => $review->user]), $review->reviewer) }}
                        @else
                            {{ $review->reviewer }}
                        @endif
                        </p>
                        <time datetime="{{ $review->created_at->format('Y-m-d') }}" class="time">{{ $review->created_at->format('Y-m-d H:i') }}</time>
                    </div>

                </div>
            </li>
        @endforeach
    </ul>
    <div class="btn__wrap">
        <a href="javascript:void(0);" class="btn solid down js-review-more">クチコミをもっとみる</a>
    </div>

