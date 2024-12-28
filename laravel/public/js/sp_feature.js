document.addEventListener('DOMContentLoaded', function() {
    // 6個以上の広告は隠す
    if (document.getElementsByClassName('js-feature-sec') != null) {
        const sec = Array.from(document.getElementsByClassName('js-feature-sec'));
        const limit = 6;
        sec.forEach(function(target) {
            const item = Array.from(target.getElementsByClassName('js-feature-item'));
            const itemLength = target.getElementsByClassName('js-feature-item').length;

            if (target.querySelector('.js-feature-sec-ttl')) {
                const category =  target.querySelector('.js-feature-sec-ttl').innerText;
                if(itemLength > limit) {
                    let moreBtnInner = '<p class="feature__list__btn js-more-btn">'+ category +'をすべて見る</p>'
                    target.insertAdjacentHTML('beforeend', moreBtnInner);
                    const moreBtn = target.querySelector('.js-more-btn');
                    for(let i = limit; i < itemLength; i++) {
                        item[i].classList.add('is-hidden');
                    }
                    moreBtn.addEventListener('click', function(){
                        for(let i = limit; i < itemLength; i++) {
                            item[i].classList.remove('is-hidden');
                            item[i].animate({
                                marginTop: [0, item[i].style.marginTop + 'px'],
                                opacity: [ 0, .3, 1 ]
                            }, {
                                duration: 400,
                                easing: 'ease',
                            });
                        }
                        moreBtn.classList.add('is-hidden');
                    });
                }
            }
            
        });
    }
    // スムーススクロース
    const smoothScrollTrigger = document.querySelectorAll('a[href^="#"]');
    for (let i = 0; i < smoothScrollTrigger.length; i++) {
        smoothScrollTrigger[i].addEventListener('click', (e) => {
            e.preventDefault();
            let href = smoothScrollTrigger[i].getAttribute('href');
            let targetElement = document.getElementById(href.replace('#', ''));
            const rect = targetElement.getBoundingClientRect().top;
            const offset = window.pageYOffset;
            const gap = document.querySelector('.js-index').clientHeight;
            const target = rect + offset - gap;
            window.scrollTo({
                top: target,
                duration: 1000,
                behavior: 'smooth',
            });
        });
    }
    // サブカテゴリ固定
    //ヘッダー追従関数（上スクロール時、ページ最下部到達時のみ表示）
    function headerScrollFunc() {
        'use strict';
        const header = document.querySelector('.js-index');
        const headerPos = document.querySelector('.js-index').getBoundingClientRect().top;
        const pickup = document.getElementById('pickup');
        const pickupPos = pickup.getBoundingClientRect();
        const py = window.pageYOffset + pickupPos.top;
        let pickupMt = window.getComputedStyle(pickup).marginTop;
        const options = {
        fixClass: 'is-fix',
        hideClass: 'is-hide',
        showClass: 'is-show',
        showDelay: 500
        };
        if(!header) {
            return false;
        }
        //スクロール開始位置
        options.startPosi = 0;
        //上スクロール判別フラグ
        options.upFlg = false;
        //表示タイマー用設定
        options.setTimeoutFlg = false;
        options.setTimeoutId = 0;
        window.addEventListener('resize', function() {
            headerFix(options);
        });
        window.addEventListener('scroll', function() {
            headerFix(options);
        });
        //追従設定
        function headerFix(options) {
            const headerBasePosi = 0;
        /*
            ヘッダーを追従させる位置
                画面の高さ、特定の要素の位置から追従開始など、色々パターンがあると思います。
                今回はヘッダーの高さを超えたら追従処理を開始
        */
        const headerFixPosi = header.clientHeight;
        //スクロール量（ウィンドウの上端）取得
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        const fixClass = options.fixClass;
        const showClass =options.showClass;
        const hideClass =options.hideClass;

        if (scrollTop > options.startPosi) {
            //上スクロール判別フラグを無効化
            options.upFlg = false;
            //表示タイマーリセット
            clearTimeout(options.setTimeoutId);
            options.setTimeoutFlg = false;
            if(scrollTop > py) {
                header.classList.add(fixClass);
                pickup.style.marginTop = (parseInt(pickupMt) + header.clientHeight) + 'px';
            }
            const pageHeight = document.documentElement.scrollHeight;
            const scrollBottom = window.innerHeight + scrollTop;
            if (pageHeight <= scrollBottom) {
                headerShow(hideClass,showClass);
            //ページの途中だった場合
            } else {
            //表示されている場合ヘッダーを非表示
            if(header.classList.contains(showClass)) {
                headerHide(hideClass,showClass);
            }
            }
        //上スクロール（新規取得したスクロール量がoptions.startPosi以下）
        } else {
            options.upFlg = true;
            if(scrollTop <= py) {
                header.classList.remove(fixClass,showClass,hideClass);
                pickup.style.marginTop = pickupMt;
            } else {
            //表示タイマーが設定されていなければタイマーを設定
            if(!options.setTimeoutFlg) {
                //表示タイマーセット
                options.setTimeoutFlg = true;
                //指定秒数以上、下スクロールしていない場合のみヘッダーを表示
                options.setTimeoutId = setTimeout(function() {
                if(options.upFlg && header.classList.contains(fixClass)) {
                    headerShow(hideClass,showClass);
                }
                }, options.showDelay);
            }
            }
        }
        //スクロール開始位置を更新
        options.startPosi = scrollTop;
        }
        //追従ヘッダー表示関数
        function headerShow(hideClass,showClass) {
            header.classList.remove(hideClass);
            header.classList.add(showClass);
        }
        //追従ヘッダー非表示関数
        function headerHide(hideClass,showClass) {
            header.classList.remove(showClass);
            header.classList.add(hideClass);
        }
    }
    headerScrollFunc() ;
});

