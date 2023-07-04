clearAccess();

$('.list-group-item').on('click', function () {
    $('.list-group-item').removeClass('active');
    $(this).addClass('active');
    $('.active_user #user').html($(this).children('#name').text());
    $('.active_user #id').html($(this).children('#id').text());
    $('#save_access').prop('disabled', false);
    clearAccess();
    getAccess();
})

$('#save_access').on('click', function () {

    setAccess();
})

function setAccess()
{
    let post = {
        'action': 'setAccess',
        'idUser': $('#id').text(),
        'idPages': {}
    };
    let checkboxes = $('.checkbox input');

    let i = 0;
    $.each(checkboxes, function () {
        if ($(this).is(':checked')) {
            post.idPages[i] = $(this).attr('id');
            i++;
        }
    })
    showSpinner();
    let response = sendAjax(post, '/access_list');
    if (response['status']) {
        if (response['status'] !== '') {
            hideSpinner();
            alert(response['status']);
        }
    }
}

function getAccess()
{
    let post = {
        'action': 'getAccess',
        'idUser': $('#id').text(),
    };

    showSpinner();
    let response = sendAjax(post, '/access_list');
    $.each($('.checkbox input'), function (index, input) {
        $.each(response, function (item, id) {
            if (+$([input]).attr('id') === +id) {
                $([input]).prop('checked', 'true');
            }
        })
    })
    hideSpinner();
}

function clearAccess()
{
    $.each($('.checkbox input'), function () {
        if ($(this).is(':checked')) {
            $(this).prop('checked', false);
        }
    })
    hideSpinner();
}

$(document).ready(function () {
    if ($('#id').text() === '-') {
        $('#save_access').prop('disabled', true);
    }
})

