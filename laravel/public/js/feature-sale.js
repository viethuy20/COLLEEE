document.addEventListener('DOMContentLoaded', function() {
    // セールの時にタグ追加
    if (document.getElementsByClassName('js-sale') != null) {
        const sale = Array.from(document.getElementsByClassName('js-sale'));
        sale.forEach(function(target) {
            const saleTag = target.querySelector('.js-sale-tag');
            let original = target.querySelector('.point .original').textContent.replace(/[^0-9.]/g, '');
            original = parseFloat(original);
            let special = target.querySelector('.point .special').textContent.replace(/[^0-9.]/g, '');
            special = parseFloat(special);
            let rate = Math.floor((special - original) / original * 100) + '%';
            let tagElm = document.createElement('span');
            tagElm.textContent = rate + 'UP';
            tagElm.className = 'point__rate';
            target.querySelector('.js-sale-tag').appendChild(tagElm);
        });
    }
});