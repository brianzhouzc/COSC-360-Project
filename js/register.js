
var mainForm = document.getElementById("mainForm");
mainForm.onsubmit = function (e) {
    e.preventDefault();
    var formData = new FormData(mainForm);
    $.ajax({
        type: "POST",
        url: "/server/users.php",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        encode: true
    }).done(function (response) {
        if (response.hasOwnProperty('data')) {
            $("#login_message").text("Succesfully registered! Please login");
            $('#login_toggle').html('Or Register');
            $("#main_load").load("login.html");
        } else if (response.hasOwnProperty('errors')) {
            $("#login_message").text(response.errors.detail);
            document.querySelectorAll(".required").forEach(function (element) {
                makeRed(element);
            });
        }
    });
}

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