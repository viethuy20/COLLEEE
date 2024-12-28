<div class="modal questions__history" data-modal="modal-questions-history">
    <div class="js-modal-overlay modal__overlay"></div>
    <div class="modal__window">
        <div class="modal__contents">
            <div class="modal__contents__head">
                <p class="modal__contents__ttl">アンケート回答履歴</p>
                <div class="modal__contents__head__item">
                    <div class="questions__history__select">
                        <select data-select="select-q-history" class="js-select">
                            <option value="all" selected>すべて</option>
                            @foreach($groupedSurveys as $yearMonth => $surveys)
                                <option value="{{ $yearMonth }}">{{ Carbon\Carbon::createFromFormat('Ym', $yearMonth)->format('Y年n月') }}分</option>
                            @endforeach
                        </select>
                    </div>
                    <a class="js-modal-close modal__close"></a>
                </div>
            </div>
            <div class="modal__contents__body">
                <ol class="js-select-list modal__contents__item questions__history__contents"
                    data-select-list="select-q-history">
                    @forelse ($groupedSurveys as $yearMonth => $surveys)
                    <li class="js-select-item {{ $yearMonth }}">
                        <dl>
                            <dt>{{ Carbon\Carbon::createFromFormat('Ym', $yearMonth)->format('Y年n月') }}</dt>
                            <dd>
                                <ol class="questions__history__list">
                                    @foreach($surveys as $survey)
                                    <li>
                                        <div class="questions__item__head">
                                            <p class="com">{{ config('survey.media')[$survey->media_id]['name'] }}</p>
                                            <p class="num">NO.{{ $survey->order_id }}</p>
                                        </div>
                                        <p class="questions__item__ttl">{{ $survey->title }}</p>
                                        <div class="questions__item__foot">
                                            <p class="point">最大<span>{{ $survey->point }}P</span></p>
                                            <div class="inquiry">
                                                <a href="{{ str_replace('{DYNAMIC_VALUE}', $hex_string, config('survey.media')[$survey->media_id]['contact_url']) }}" target="_blank">お問い合わせ</a>
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                </ol>
                            </dd>
                        </dl>
                    </li>
                    @empty
                    <li>
                        <p class='u-font-bold text--15 red'>回答履歴はありません</p>
                    </li>
                    @endforelse
                </ol>
            </div>
        </div>
    </div>
</div>