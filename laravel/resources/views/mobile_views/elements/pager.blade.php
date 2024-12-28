@if ($paginator->lastPage() > 1)
<nav><div class="pager__wrap">
    <p class="text--12 gray u-text-ac">{{ number_format($paginator->count()) }}件を表示 / {{ number_format($paginator->total()) }}件中</p>
    <ul class="pager">
    <?php
    // 最大表示ブロック数
    $max_block = 7;
    ?>
    @if ($paginator->currentPage() == 1)
    <li class="pager__on">{{ $paginator->currentPage() }}</li>
    @else
    <li class="pager__off">{!! Tag::link($pageUrl($paginator->currentPage() - 1), '＜') !!}</li>
    <li class="pager__off">{!! Tag::link($pageUrl(1), 1) !!}</li>
    @endif

    @if ($paginator->lastPage() > 2)
    <?php
    $min_page = 2;
    $max_page = $paginator->lastPage() - 1;
    $d_block = ($max_block - 6);
    $pf = 1 + $d_block;
    $pl = $paginator->lastPage() - $d_block;
    $d = floor(($d_block - 1) / 2);
    if ($paginator->currentPage() == 1 || $paginator->currentPage() == $paginator->lastPage()) {
        if ($paginator->lastPage() >= $max_block) {
            // ページ番号がブロック数以内に収まらない場合
            if ($paginator->currentPage() == 1) {
                // 最初のページなので、後ろを省略させる
                $max_page = $pf + 2;
            } else {
                // 最後のページなので、前を省略させる
                $min_page = $pl - 2;
            }
        }
    } elseif ($paginator->lastPage() >= ($max_block - 1)) {
        // ページ番号がブロック数以内に収まらない場合
        if ($paginator->currentPage() <= $pf) {
            // 前方のページなので、後ろを省略させる
            $max_page = $pf + 1;
        } elseif($paginator->currentPage() >= $pl) {
            // 後方のページなので、前を省略させる
            $min_page = $pl - 1;
        } else {
            // 前後を省略させる
            $min_page = $paginator->currentPage() - $d;
            $max_page = $paginator->currentPage() + $d;
        }
    }
    ?>
    @if ($min_page > 2)
    <li class="pager__blank">…</li>
    @endif

    @for ($i = $min_page; $i <= $max_page; $i++)
    @if ($paginator->currentPage() == $i)
    <li class="pager__on">{{ $paginator->currentPage() }}</li>
    @else
    <li class="pager__off">{!! Tag::link($pageUrl($i), $i) !!}</li>
    @endif
    @endfor

    @if ($max_page < ($paginator->lastPage() - 1))
    <li class="pager__blank">…</li>
    @endif
    @endif

    @if ($paginator->currentPage() == $paginator->lastPage())
    <li class="pager__on">{{ $paginator->currentPage() }}</li>
    @else
    <li class="pager__off">{!! Tag::link($pageUrl($paginator->lastPage()), $paginator->lastPage()) !!}</li>
    <li class="pager__off">{!! Tag::link($pageUrl($paginator->currentPage() + 1), '＞') !!}</li>
    @endif
</ul></div></nav>
@endif