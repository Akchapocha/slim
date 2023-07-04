let uid = $('#uid');
clearForm();
$('.btn').on('click', function () {
    addUser();
})
uid.on('keypress', function (press) {
    if (press.keyCode === 13) {
        addUser();
    }
});

function addUser()
{
    let post = {
        'action': 'addUser',
        'uid': uid.val()
    };

    if ( !/^[0-9]{4}$/.test(post.uid) ) {
        alert('Введите корректный ID');
    } else {
        let response = sendAjax(post, '/add_users');
        if (response['status']) {
            alert(response['status']);
            clearForm();
        }
    }
}
function clearForm()
{
    uid.val('');
}

$(document).ready(function () {
    hideSpinner();
})