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
<div class="contents__box mt-20">
    <div class="users__center__box">
        <div class="users__center__box__main"><h3>コメントはまだありません</h3></div>
    </div>
</div>
@else
@php
$sex_map = config('map.sex');
$generation_map = config('map.generation');
$answer_map = $question->answer_map;
@endphp

<div class="mt-20">
    @if (!$for_ajax)
    <h2 class="contents__ttl">このアンケートについてのコメント一覧（{{ number_format($question->message_total) }}件）</h2>
    <div class="contentsbox"><div id="{{ $render_id }}">
    @endif
    <!--コメントがある場合-->
    <ul class="questions__comment__list js_accordion">
        @foreach($message_list as $answer_message)
        <li><dl>
            <dt class="questions__comment__list__txt">{!! nl2br(e($answer_message->message)) !!}</dt>
            <dd class="questions__comment__list__name">
                <span>{{ $sex_map[$answer_message->sex] ?? '' }}</span>
                <span>{{ $generation_map[$answer_message->generation] ?? '' }}</span>
                <span>{{ $answer_map[$answer_message->answer_id] }}</span>
            </dd>
            <dd class="questions__comment__list__data">
                {{ $answer_message->updated_at->format('Y-m-d H:i') }}
            </dd>
        </dl></li>
        @endforeach
    </ul>
</div>

@if ($total > $limit)
<div class="js_more">
    <button class="AjaxContent more btn_more" forUrl="{{ route('questions.ajax_message', ['question' => $question, 'limit' => $limit + 10]) }}" forRender="{{ $render_id }}">もっと見る</button>
</div>
@endif

@if (!$for_ajax)
</div></div><!--/contentsbox-->
@endif
@endif