let url = window.location.href;
let protocol = window.location.protocol
let host = window.location.host;
let pathname = window.location.pathname;
let active;

$('.nav-link').removeClass('active');

if (/\/post/.test(url)) {
    active = $(".nav-link[href='" + protocol + "//" + host + "/']");
    active.addClass('active');
} else {
    active = $(".nav-link[href='" + url + "']");
    active.addClass('active');
}

if (active.text() === 'Главная') {
    let crumbs = pathname.substring(1).split('/');
    let bc = '<li class="breadcrumb-item"><a href="/">Главная</a></li>\n';
    if (crumbs.length > 1) {
        $.each(crumbs, function (key, value) {
            bc = bc + '<li class="breadcrumb-item active">' + value + '</li>\n';
        });
    }
    $('.breadcrumb').html(bc);
} else {
    $('.breadcrumb').html('<li class="breadcrumb-item"><a href="/">Главная</a></li>\n' +
        '        <li class="breadcrumb-item active">' + active.text() + '</li>');
}

function sendAjax(post, url, async = false)
{
    let response = [];
    $.ajax({
        type: 'POST',
        async: async,
        url: url,
        data: post,
        dataType: 'json',
        success: function (data) {
            if (data) {
                response = data;
            }
        }
    });
    return response;
}

function showSpinner()
{
    $('.spinner-modal').show();
}

function hideSpinner()
{
    $('.spinner-modal').hide();
}