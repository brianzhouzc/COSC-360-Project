function isBlank(inputField) {
    if (inputField.value == "") {
        return true;
    }
    return false;
}

function makeRed(inputDiv) {
    inputDiv.style.borderColor = "#AA0000";
}

function makeClean(inputDiv) {
    inputDiv.style.borderColor = "#FFFFFF";
}

window.onload = function () {
    var mainForm = document.getElementById("mainForm");

    mainForm.onsubmit = function (e) {
        e.preventDefault();
        console.log($('#username').val(), $('#password').val());
        var requiredInputs = document.querySelectorAll(".required");
        var err = false;

        for (var i = 0; i < requiredInputs.length; i++) {
            if (isBlank(requiredInputs[i])) {
                err = true;
                makeRed(requiredInputs[i]);
            }
            else {
                makeClean(requiredInputs[i]);
            }
        }
        if (!err) {
            console.log($('#username').val(), $('#password').val());
            login($('#username').val(), $('#password').val());
        }
        else {
            $("#message").text("Missing username/password");
        }
    }
}


function login(username, password) {
    var data = {
        action: "login",
        username: username,
        password: password
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
            $("#message").text("Successfully logged in! Redirecting to home page...");
            document.querySelectorAll(".required").forEach(function (element) {
                makeClean(element);
            });
            setTimeout(function () {
                document.location = 'reddit.html';
            }, 2000);
        } else if (response.hasOwnProperty('errors')) {
            $("#message").text(response.errors.detail);
            document.querySelectorAll(".required").forEach(function (element) {
                makeRed(element);
            });
        }
    });
}

function loadRegister(){
    $("#mainbody").load("register.html");
}