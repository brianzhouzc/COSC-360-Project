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
            alert(response.data.detail);
        } else if (response.hasOwnProperty('errors')) {
            alert(response.errors.detail);
        }
    });
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

function createPost(content) {
    if ("username" in sessionStorage && "session" in sessionStorage) {
        var data = {
            action: "create",
            username: sessionStorage.getItem("username"),
            session: sessionStorage.getItem("session"),
            content: content
        };

        $.ajax({
            type: "POST",
            url: "/server/posts.php",
            data: data,
            dataType: "json",
            encode: true
        }).done(function (response) {
            if (response.hasOwnProperty('data')) {
                //display success message
                alert(response.data.detail);
            } else if (response.hasOwnProperty('errors')) {
                alert(response.errors.detail);
            }
        });
    } else {

    }
}

function updatePost() {

}

function removePost() {

}

function getPosts(order = "DESC", limit = 5, offset = 0) {
    var data = {
        action: "get",
        order: order,
        limit: limit,
        offset: offset
    };

    $.ajax({
        type: "POST",
        url: "/server/posts.php",
        data: data,
        dataType: "json",
        encode: true
    }).done(function (response) {
        if (response.hasOwnProperty('data')) {
            //display success message
            console.log(response.data);

            response.data.posts.forEach(function (element) {
                var title = element.title;
                var author = element.username;
                var date = element.timestamp;
                var content = element.content;
                var id = element.id;

                $("#post_template .post_title").text(title);
                $("#post_template .post_description .post_author").text(author);
                $("#post_template .post_description .post_date").text(date);
                $("#post_template .post_content").text(content);

                var post = $("#post_template").children().clone().appendTo('.main');
            });
        } else if (response.hasOwnProperty('errors')) {
            alert(response.errors.detail);
        }
    });
}

function getPostsBySearch() {

}

function getPostById() {

}

function getPostsByUsername() {

}

function getCommentById() {

}

function getCommentsByPostId() {

}