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