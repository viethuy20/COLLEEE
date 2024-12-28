var getScrollPos = function(event) {
    return $(window).scrollLeft() + ',' + $(window).scrollTop();// + $(window).height()
    //return ($(window).scrollLeft() + event.clientX) + ',' + ($(window).scrollTop() + event.clientY);// + $(window).height()
};
var getScrollUrl = function(url, event) {
    var scroll = 'scroll=' + getScrollPos(event);
    if (purl(url).attr('query')) {
        return url + '&' + scroll;
    }
    return url + '?' + scroll;
};

var lockForm = function(formId) {
    var form = $('#' + formId);
    form.submit(function() {
        var self = this;
        $(":submit", self).prop("disabled", true);
        setTimeout(function() {
            $(":submit", self).prop("disabled", false);
        }, 3000);
    });
};
var lockEvent = function(elementId) {
    var eventElement = $('#' + elementId);
    eventElement.on('click', function() {
        eventElement.prop("disabled", true);
        setTimeout(function() {
           eventElement.prop("disabled", false);
        }, 3000);
    });
};
function convertToHiragana(src) {
    var hiragana = src.replace(/[\u30A1-\u30F6]/g, function(match) {
        var chr = match.charCodeAt(0) - 0x60;
        return String.fromCharCode(chr);
    });
    
    return hiragana;
}
function convertToHurigana(src) {
    // To hiragana
    var hiragana = convertToHiragana(src);
    
    // To Large
    hiragana = hiragana.replace(/[\u3041|\u3043|\u3045|\u3047|\u3049|\u3063|\u3083|\u3085|\u3087]/g, function(match) {
        var chr = match.charCodeAt(0) + 0x1;
        return String.fromCharCode(chr);
    });
    
    // Voiced sound mark
    hiragana = hiragana.replace(/[\u304C|\u304E|\u3050|\u3052|\u3054|\u3056|\u3058|\u305A|\u305C|\u305E|\u3060|\u3062|\u3065|\u3067|\u3069|\u3070|\u3073|\u3076|\u3079|\u307C]/g, function(match) {
        var chr = match.charCodeAt(0) - 0x1;
        return String.fromCharCode(chr);
    });
    hiragana = hiragana.replace(/[\u3071|\u3074|\u3077|\u307A|\u307D]/g, function(match) {
        var chr = match.charCodeAt(0) - 0x2;
        return String.fromCharCode(chr);
    });
    hiragana = hiragana.replace(/[\u3094]/g, function(match) {
        var chr = match.charCodeAt(0) - 0x4E;
        return String.fromCharCode(chr);
    });
    
    return hiragana;
}
function executeAjax(ajaxUrl, render) {
    $.ajax({
        type: 'GET',
        url: ajaxUrl,
        scriptCharset: 'utf-8',
        dataType: 'html',
        beforeSend: function (xhr) {
            var token = $('meta[name="csrf_token"]').attr('content');
            if (token) {
                return xhr.setRequestHeader('X-XSRF-TOKEN', token);
            }
        },
        success: function(data) {
            render.html(data);
        },
        error:function(xhr) {
            render.html('データ取得に失敗しました');
        }
    });
}