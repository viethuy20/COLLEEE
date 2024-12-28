
<div class="cv {{ false !== strpos($cv, 'min') ? 'min' : '' }}" id="js-fixed-dup">
    @if(false !== strpos($cv, 'deco'))
    <div class="cv__deco">
        <figure class="chara"></figure>
        <p>GMOポイ活を<span>はじめて利用する方</span>はこちら</p>
    </div>
    @endif
    <a href="{{ route('entries.index') }}">
        <p class="box"><i><img src="/images/top/intro/ico_alarm-clock.svg" alt=""></i>最短1分！</p>
        <p>無料でGMOポイ活に登録する</p>
    </a>
</div>
