function forgot(email) {
    var mainForm = document.getElementById("mainForm");
    mainForm.onsubmit = function (e) {
        e.preventDefault();
        var formData = new FormData(mainForm);
        formData.append('action', 'create');
        formData.append('username', sessionStorage.getItem('username'));
        formData.append('session', sessionStorage.getItem('session'));
        $.ajax({
            type: "POST",
            url: "/server/posts.php",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            encode: true
        }).done(function (response) {
            console.log(response);
            if (response.hasOwnProperty('data')) {
                alert(response.data.detail);
                document.location = 'user.html';
            } else {
                alert(response.errors.detail);
            }
        });

    }
}

$(document).ready(function () {
    var forgotForm = document.getElementById("forgotForm");
    forgotForm.onsubmit = function (e) {
        e.preventDefault();
        var formData = new FormData(forgotForm);
        formData.append('action', 'forgot');
        $.ajax({
            type: "POST",
            url: "/server/users.php",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            encode: true
        }).done(function (response) {
            console.log(response);
            if (response.hasOwnProperty('data')) {
                alert(response.data.detail);
            } else {
                alert(response.errors.detail);
            }
        });
    }

    var resetForm = document.getElementById("resetForm");
    resetForm.onsubmit = function (e) {
        e.preventDefault();
        var formData = new FormData(resetForm);
        formData.append('action', 'forgot');
        $.ajax({
            type: "POST",
            url: "/server/users.php",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            encode: true
        }).done(function (response) {
            console.log(response);
            if (response.hasOwnProperty('data')) {
                alert(response.data.detail);
                window.location = ""
            } else {
                alert(response.errors.detail);
            }
        });
    }
});