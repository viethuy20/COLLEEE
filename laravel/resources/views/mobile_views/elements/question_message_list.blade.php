@php
$for_ajax = $for_ajax ?? false;
$render_id = 'comments';
$limit = $limit ?? 10;

$builder = \App\UserAnswer::ofMessageList($question->id)
    ->take($limit);

// 総件数取得
$total = $builder->count();
// メッセージリスト取得
$message_list = $builder->get();
@endphp
@if ($message_list->isEmpty())
<!--コメントがない場合 (下のulとコメント一覧を表示ボタンまでは非表示)-->
<section class="inner u-mt-7">
    <div class="contents__box">
        <div class="users__center__box">
            <div class="users__center__box__main"><h3>コメントはまだありません</h3></div>
        </div>
    </div>
</section>
@else
@php
$sex_map = config('map.sex');
$generation_map = config('map.generation');
$answer_map = $question->answer_map;
@endphp

@if (!$for_ajax)
<section class="inner u-mt-7">
    <div class="questions__ttl">
        <h2 id="tocomments" class="contents__ttl">このアンケートについてのコメント一覧（{{ number_format($question->message_total) }}件）</h2>
	</div>
    <div class="contentsbox"><div id="{{ $render_id }}">
    @endif
    <!--コメントがある場合-->
    <ul class="questions__answer__list">
        @foreach($message_list as $answer_message)
        <li class="questions__answer">
            <p class="questions__answer__txt">{!! nl2br(e($answer_message->message)) !!}</p>
			<p class="questions__answer__profile">{{ $sex_map[$answer_message->sex] ?? '' }} {{ $generation_map[$answer_message->generation] ?? '' }} {{ $answer_map[$answer_message->answer_id] }}</p>
			<p class="questions__answer__date">{{ $answer_message->updated_at->format('Y-m-d H:i') }}</p>
        </li>
        @endforeach
    </ul>

    @if ($total > $limit)
    <button class="AjaxContent more questions__answer__more" forUrl="{{ route('questions.ajax_message', ['question' => $question, 'limit' => $limit + 10]) }}" forRender="{{ $render_id }}">もっと見る</button>
    @endif

@if (!$for_ajax)
</section>

</div></div><!--/contentsbox-->
@endif
@endif
