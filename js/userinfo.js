if ("username" in sessionStorage && "session" in sessionStorage) {
    getUserInfo(sessionStorage.getItem("username"), sessionStorage.getItem("session"));
    getUserPosts(sessionStorage.getItem("username"));
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

function getUserPosts(username) {
    var data = {
        action: "get",
        username: username
    };

    $.ajax({
        type: "POST",
        url: "/server/posts.php",
        data: data,
        dataType: "json",
        encode: true
    }).done(function (response) {
        console.log(response);
        if (response.hasOwnProperty('data')) {
            //display success message

            response.data.posts.forEach(function (element) {
                var title = element.title;
                var author = element.username;
                var date = element.timestamp;
                var content = element.content;
                var id = element.id;

                $("#post_template .post").attr("id", "post_" + id);
                $("#post_template .post_title .post_title_link").text(title);
                $("#post_template .post_title .post_title_link").attr("href", "post.html?post_id=" + id);
                $("#post_template .post_description .post_author").text(author);
                $("#post_template .post_description .post_date").text(date);
                $("#post_template .post_content").text(content);

                $("#post_template").children().clone().appendTo('.main');
            });
        } else if (response.hasOwnProperty('errors')) {
            alert(response.errors.detail);
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