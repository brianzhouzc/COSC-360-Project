var current_offset = 0;
var curr_posts = [];
var curr_order = "DESC";

function getPosts(order = "DESC", notify = false) {
    var data;
    curr_order = order;
    if (order == "POPULAR") {
        data = {
            action: "popular",
            limit: 65535,
            offset: 0
        };
    } else {
        data = {
            action: "get",
            order: order,
            limit: 65535,
            offset: 0
        };
    }

    $.ajax({
        type: "POST",
        url: "/server/posts.php",
        data: data,
        dataType: "json",
        encode: true
    }).done(function (response) {
        if (response.hasOwnProperty('data')) {
            //display success message
            console.log(response);

            $('#posts_container').empty();
            response.data.posts.forEach(function (element) {
                var title = element.title;
                var author = element.username;
                var date = element.timestamp;
                var content = element.content;
                var id = element.id;

                var new_post = false;
                if (!curr_posts.includes(id)) {
                    if (notify)
                        new_post = true;
                    curr_posts.push(id);
                }

                if (new_post)
                    alert('There are new posts!');

                $("#post_template .post").attr("id", "post_" + id);
                $("#post_template .post_title .post_title_link").text(title);
                $("#post_template .post_title .post_title_link").attr("href", "post.html?post_id=" + id);
                $("#post_template .post_description .post_author").text(author);
                $("#post_template .post_description .post_date").text(date);
                $("#post_template .post_content").text(content);

                $("#post_template").children().clone().appendTo('#posts_container');
            });


        } else if (response.hasOwnProperty('errors')) {
            alert(response.errors.detail);
        }
    });
}

function displayPosts(posts) {
    $('#posts_container').empty();
    posts.forEach(function (element) {
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

        $("#post_template").children().clone().appendTo('#posts_container');
    });
}

$(document).ready(function () {
    getPosts("DESC");
    setInterval(function () {
        getPosts(curr_order, true);
    }, 10000);

});