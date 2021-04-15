var current_offset = 0;
var posts_ids = [];
function getPosts(order = "DESC") {
    var data;
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
            console.log(response.data);
            $('#posts_container').empty();
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

                $("#post_template").children().clone().appendTo('#posts_container');
            });
        } else if (response.hasOwnProperty('errors')) {
            alert(response.errors.detail);
        }
    });
}

$(document).ready(function () {
    getPosts("DESC");
});