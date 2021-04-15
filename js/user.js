if ("username" in sessionStorage && "session" in sessionStorage) {
    $("#main_load").load("userinfo.html");//brings in the footer
} else {
    $('#login_message_area').attr('style', '');
    $("#main_load").load("login.html");//brings in the footer
}

function toggleLoginRegister() {
    if ($('#login_message').text() == "Please Login") {
        $("#main_load").load("register.html");
        $('#login_message').text("Please Register");
        $('#login_toggle').html('Or Login');
    } else {
        $("#main_load").load("login.html");
        $('#login_message').text("Please Login");
        $('#login_toggle').html('Or Register');
    }
}