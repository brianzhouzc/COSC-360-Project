if ("username" in sessionStorage && "session" in sessionStorage) {
    $('#avatar').attr('src', './server/avatar.php?username=' + sessionStorage.getItem('username'));
    var link = '<a href="javascript:logout();">Logout</a>';
    $('#info_username').text("Hello, " + sessionStorage.getItem('username'));
    $('#info_login').html(link);
    displayAdminPannel();
    $('#4').attr('style', '');
}

function displayAdminPannel() {
    if ("username" in sessionStorage && "session" in sessionStorage) {
        var data = {
            action: "isadmin",
            username: sessionStorage.getItem("username"),
            session: sessionStorage.getItem("session")
        };

        $.ajax({
            type: "POST",
            url: "/server/admins.php",
            data: data,
            dataType: "json",
            encode: true
        }).done(function (response) {
            if (response.hasOwnProperty('data'))
                $('#3').attr('style', '');
        });
    }

}
function logout() {
    if ("username" in sessionStorage && "session" in sessionStorage) {
        var data = {
            action: "logout",
            username: sessionStorage.getItem("username"),
            session: sessionStorage.getItem("session")
        };

        $.ajax({
            type: "POST",
            url: "/server/users.php",
            data: data,
            dataType: "json",
            encode: true
        }).done(function (response) {
            if (response.hasOwnProperty('data')) {
                //display success message
                sessionStorage.removeItem("username")
                sessionStorage.removeItem("session");
                alert(response.data.detail);
                location.reload();
            } else if (response.hasOwnProperty('errors')) {
                alert(response.errors.detail);
            }
        });
    } else {
        //something's wrong
    }
}