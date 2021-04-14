
function register(form) {
    var formData = new FormData(form);

    $.ajax({
        type: "POST",
        url: "/server/users.php",
        data: formData,
        dataType: "json",
        encode: true
    }).done(function (response) {
        console.log(response);
    });
}

$(document).ready(function () {
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
            console.log(response);
        });
    }
});