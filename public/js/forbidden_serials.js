let inputSerial = $('[name="forbidden_serial"]');

clearInput();

$('.refresh_btn').on('click', function () {
    refreshSerials();
    hideSpinner();
});

inputSerial.on('keypress', function (press) {
    if (press.keyCode === 13) {
        addSerial($(this).val());
    }
});

$('#addSerial').on('click', function () {
    addSerial(inputSerial.val());
});

$(document).on('click', '.delete_serial', function () {
        deleteForbidden($(this).parent().prev().text());
});

$(document).on('click', '.serialsWithProduct_item', function () {
        deleteForbidden($($(this).children('.serialsWithProduct')[2]).text());
});

function clearInput()
{
    inputSerial.val('');
}

function refreshSerials()
{
    let post = {
        'action': 'refreshForbidden'
    };
    showSpinner();
    let response = sendAjax(post, '/forbidden_serials');
    if (response.staged) {
        $('.with_product').replaceWith(response.staged);
    }
    if (response.forbidden) {
        $('.free').replaceWith(response.forbidden);
    }
    clearInput();
    hideSpinner();
}

function addSerial(serial)
{
    if (!/^[0-9]{6,}$/.test(serial)) {
        alert('Введите корректные данные.');
    } else {
        let post = {
            'action': 'addForbidden',
            'serial': serial
        };
        showSpinner();
        let response = sendAjax(post, '/forbidden_serials');
        if (response['status']) {
            alert(response['status']);
            refreshSerials();
        }
    }
}

function deleteForbidden(serial)
{
    if (confirm('Вы действительно хотите удалить ' + serial + '?')) {
        let post = {
            'action': 'deleteForbidden',
            'serial': serial
        };
        let response = sendAjax(post, '/forbidden_serials');
        if (response['status']) {
            alert(response['status']);
            refreshSerials();
        }
    }
}