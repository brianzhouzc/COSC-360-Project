func
function login(username, password) {
    var data = {
        action = "login",
        username = username,
        password = password
    };

    $.ajax({
        type: "POST",
        url: "/server/users.php",
        data: data,
        dataType: "json",
        encode: true
    }).done(function (response) {
        if (response.hasOwnProperty('data')) {
            //display logged in message
            sessionStorage.setItem("username", response.data.username)
            sessionStorage.setItem("session", response.data.session);
            alert(response.data.detail);
        } else if (response.hasOwnProperty('errors')) {
            alert(response.errors.detail);
        }
    });
}

function logout() {
    if ("username" in sessionStorage && "session" in sessionStorage) {
        var data = {
            action = "lougout",
            username = sessionStorage.getItem("username"),
            session = sessionStorage.getItem("session")
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
            } else if (response.hasOwnProperty('errors')) {
                alert(response.errors.detail);
            }
        });
    } else {
        //something's wrong
    }
}

function register() {

}

function forgot() {

}

function createPost() {
    
}