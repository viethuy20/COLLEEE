<h2 class="contents__ttl">みんなの新着口コミ</h2>
<div class="">
    <ul class="review">
    @foreach($review_list as $review)
    @php
    $program = $review->program;
    @endphp
    <li>
        <div class="review__head">
            <a class="review__name" href="{{ route('programs.show', ['program' => $program, 'rid' => '28']) }}">{{ $program->title }}</a>
        </div>
        <div class="review__star">
            <ul>
                @for ($i = 1; $i <= 5; $i++)
                <li>{{ Tag::image(($i <= $review->assessment) ? '/images/programs/ico_kuchikomi_star_yellow.svg' : '/images/programs/ico_kuchikomi_star_gray.svg', 'star') }}</li>
                @endfor
            </ul>
            <p class="review__star__txt">（{{$review->assessment}}/5）</p>
        </div>
        <p class="text--15">{{ $review->message }}</p>
        <p class="review__data">{{ $review->created_at->format('Y/m/d H:i') }}</p>
    </li>
    @endforeach
    </ul>
</div><!--/contentsbox-->
