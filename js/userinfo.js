if ("username" in sessionStorage && "session" in sessionStorage) {
    getUserInfo(sessionStorage.getItem("username"), sessionStorage.getItem("session"));
}

function getUserInfo(username, session) {
    var data = {
        action: "info",
        username: username,
        session: session
    };

    $.ajax({
        type: "POST",
        url: "/server/users.php",
        data: data,
        dataType: "json",
        encode: true
    }).done(function (response) {
        if (response.hasOwnProperty('data')) {
            console.log(response);
            $('#input_username').val(response.data.user.username);
            $('#input_email').val(response.data.user.email);
        }
    });
}

var mainForm = document.getElementById("mainForm");
mainForm.onsubmit = function (e) {
    e.preventDefault();
    var formData = new FormData(mainForm);
    formData.append('username', sessionStorage.getItem("username"));
    formData.append('session', sessionStorage.getItem("session"));

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
            alert('Profile updated!');
            window.location.reload();
        } else if (response.hasOwnProperty('errors')) {
            alert('Error!');
        }
    });
}