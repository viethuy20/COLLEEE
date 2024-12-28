function historyView(item_id, csrf_token, type) {
    var api_url = '';
    var custom_headers = {
        'X-Recommend-Referer': document.referrer,
        'X-Recommend-Origin': window.location.origin,
    };
    var req_data = {
        item_id: item_id,
    };
    if (type == 'wp') {
        api_url = '/recommendwp/history/view';
    } else {
        api_url = '/recommend/history/view';
        req_data._token = csrf_token;
    }
    $.ajax({
        type: 'POST',
        url: api_url,
        headers: custom_headers,
        data: req_data,
        success: function (data) {
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
        }
    });
}

function kpiClick(item_id, type_name, csrf_token, type, spot_name) {
    var api_url = '';
    var custom_headers = {
        'X-Recommend-Referer': document.referrer,
        'X-Recommend-Origin': window.location.origin,
    };
    var req_data = {
        item_id: item_id,
        type_name: type_name,
        spot_name: spot_name,
    };
    if (type == 'wp') {
        api_url = '/recommendwp/kpi/click';
    } else {
        api_url = '/recommend/kpi/click';
        req_data._token = csrf_token;
    }
    $.ajax({
        type: 'POST',
        url: api_url,
        headers: custom_headers,
        data: req_data,
        success: function (data) {
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
        }
    });
}

function getRecommendArticles(item_id, csrf_token, type, output_id, num, device, purpose, page_name) {
    var api_url = '';
    var custom_headers = {
        'X-Recommend-Referer': document.referrer,
        'X-Recommend-Origin': window.location.origin,
    };
    var req_data = {
        item_id: item_id,
        num: num,
        device: device,
        purpose: purpose,
        page_name: page_name,
    };
    if (type == 'wp') {
        api_url = '/recommendwp/articles';
    } else {
        api_url = '/recommend/articles';
        req_data._token = csrf_token;
    }
    $.ajax({
        type: 'POST',
        url: api_url,
        headers: custom_headers,
        data: req_data,
        success: function (data) {
            if (data.status == true) {
                $('#'+output_id).html(data.html);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
        }
    });
}

function getRecommendPrograms(item_id, csrf_token, type, output_id, num, device, purpose, page_name) {
    var api_url = '';
    var custom_headers = {
        'X-Recommend-Referer': document.referrer,
        'X-Recommend-Origin': window.location.origin,
    };
    var req_data = {
        item_id: item_id,
        num: num,
        device: device,
        purpose: purpose,
        page_name: page_name,
    };
    if (type == 'wp') {
        api_url = '/recommendwp/programs';
    } else {
        api_url = '/recommend/programs';
        req_data._token = csrf_token;
    }
    $.ajax({
        type: 'POST',
        url: api_url,
        headers: custom_headers,
        data: req_data,
        success: function (data) {
            if (data.status == true) {
                $('#'+output_id).html(data.html);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
        }
    });
}
