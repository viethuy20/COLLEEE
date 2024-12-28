document.addEventListener('DOMContentLoaded', function() {
    // 6個以上の広告は隠す
    if (document.getElementsByClassName('js-feature-sec') != null) {
        const sec = Array.from(document.getElementsByClassName('js-feature-sec'));
        const limit = 6;
        sec.forEach(function(target) {
            const item = Array.from(target.getElementsByClassName('js-feature-item'));
            const itemLength = target.getElementsByClassName('js-feature-item').length;
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
        });
    }
    // スムーススクロース
    const smoothScrollTrigger = document.querySelectorAll('a[href^="#"]');
    for (let i = 0; i < smoothScrollTrigger.length; i++) {
        smoothScrollTrigger[i].addEventListener('click', (e) => {
            e.preventDefault();
            let href = smoothScrollTrigger[i].getAttribute('href');
            let targetElement = document.getElementById(href.replace('#', ''));
            let rect = 0;
            let target = 0;
            if (targetElement) {
                rect = targetElement.getBoundingClientRect().top;
                const offset = window.pageYOffset;
                // const gap = 60;
                target = rect + offset;
            }
            window.scrollTo({
                top: target,
                duration: 1000,
                behavior: 'smooth',
            });
        });
    }
});