@php
$affiriate = $ads->affiriate;
// ポイント
$point = $ads->point;
@endphp
<div class="modal ad-modal">
    <div class="js-modal-overlay modal__overlay"></div>
        <div class="ad-modal__window">
            <div class="ad-modal__contents">
                <div class="ad-modal__close__wrap">
                    <a class="js-modal-close ad-modal__close"></a>
                </div>
                <div class="ad-modal__item">
                    <div class="ad-modal__balloon js-sale-ad-modal">
                        <p class="ad-modal__balloon__text">今だけ高還元タイムセール中！</p>
                    </div>
                    <p class="ad-modal__ttl">ポノスケ厳選<br>今日のイチオシ広告</p>
                </div>
                <div class="ad-modal__program-card__wrap">
                    <img src="/images/ad-modal/concentration-line.svg" alt="" class="ad-modal__bg ad-modal__bg--concentration-line js-modal-overlay"/>
                    <img src="/images/ad-modal/confetti.svg" alt="" class="ad-modal__bg ad-modal__bg--confetti js-modal-overlay"/>
                    <img src="/images/common/mascot/ponosuke_raisehand.png" alt="" class="ad-modal__bg--ponosuke" />
                    <a class="program-card ad-modal__program-card js-program-card" href="{{ route('programs.show', ['program'=> $ads->id]) }}" >
                        <div class="ad-modal__program-card__tag__wrap">
                            <p class="ad-modal__program-card__tag ad-modal__program-card__tag--red js-sale-ad-modal">
                                <svg width="9" height="9" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_929_40773)">
                                        <path d="M4.5 3.05176e-05C5.69347 3.05176e-05 6.83807 0.474136 7.68198 1.31805C8.52589 2.16196 9 3.30656 9 4.50003C9 5.6935 8.52589 6.8381 7.68198 7.68201C6.83807 8.52592 5.69347 9.00003 4.5 9.00003C3.30653 9.00003 2.16193 8.52592 1.31802 7.68201C0.474106 6.8381 0 5.6935 0 4.50003C0 3.30656 0.474106 2.16196 1.31802 1.31805C2.16193 0.474136 3.30653 3.05176e-05 4.5 3.05176e-05ZM4.07812 2.10941V4.50003C4.07812 4.64066 4.14844 4.77249 4.26621 4.85159L5.95371 5.97659C6.14707 6.10667 6.40898 6.05394 6.53906 5.85882C6.66914 5.6637 6.61641 5.40355 6.42129 5.27347L4.92188 4.27503V2.10941C4.92188 1.87562 4.73379 1.68753 4.5 1.68753C4.26621 1.68753 4.07812 1.87562 4.07812 2.10941Z" fill="white"/>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_929_40773">
                                            <rect width="9" height="9" fill="white"/>
                                        </clipPath>
                                    </defs>
                                </svg>
                                期間限定
                            </p>
                        </div>
                        <div class="program-card__img">
                            <img src="{{ $affiriate->img_url }}" alt="{{ $title }}" />
                        </div>
                        <div class="program-card__detail">
                            <div class="txt">
                                <p class="headline hidetext">{{ $title }}</p>
                                <p><span class="hidetext">{{ $ads->fee_condition }}</span></p>
                                <p class="ad-modal__program-card__tag js-sale-ad-modal">ポイントUP</p>
                            </div>
                            <div class="primary">
                                <div class="point">
                                    <p class="special">
                                        @if ($point->fee_type == 2)
                                            {{ str_replace('%', '', $point->fee_label_s) }}
                                        @else
                                            {{ $point->fee_label_s }}
                                        @endif
                                        @if ($point->fee_type == 2)
                                        <span class="unit">%P</span>
                                        @else
                                        <span class="unit">P</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="ad-modal__item">
                    <div class="ad-modal__object">
                        <img src="/images/ad-modal/present_top.svg" alt="" class="ad-modal__object--top"/>
                        <img src="/images/ad-modal/present_bottom.svg" alt="" class="ad-modal__object--bottom"/>
                    </div>
                    <a href="{{ route('programs.show', ['program'=> $ads->id]) }}" class="ad-modal__cv">
                        <p>さっそく確認する</p>
                    </a>
                    <p class="ad-modal__checkbox">
                        <input type="checkbox" name="consent" id="consent" />
                        <label for="consent">今日は表示しない</label>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>