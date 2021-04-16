
$(document).ready(function () {
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
                window.location.reload();
            } else {
                alert(response.errors.detail);
            }
        });

    }
});