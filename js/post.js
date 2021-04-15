
var urlParams = new URLSearchParams(window.location.search);

console.log(urlParams.has('post_id')); // true


function getPost(post_id) {
    var data = {
        action: "get",
        post_id: post_id
    };

    $.ajax({
        type: "POST",
        url: "/server/posts.php",
        data: data,
        dataType: "json",
        encode: true
    }).done(function (response) {
        if (response.hasOwnProperty('data')) {

            var title = response.data.post.title;
            var author = response.data.post.username;
            var date = response.data.post.timestamp;
            var content = response.data.post.content;
            var id = response.data.post.id;

            $(".post").attr("id", "post_" + id);
            $(".post_title").text(title);
            $(".post_description .post_author").text(author);
            $(".post_description .post_date").text(date);
            $(".post_content").text(content);
        }
    });
}

function getComments(post_id) {
    console.log('wat');

    var data = {
        action: "get",
        post_id: post_id
    };

    $.ajax({
        type: "POST",
        url: "/server/comments.php",
        data: data,
        dataType: "json",
        encode: true
    }).done(function (response) {
        console.log(response.data);
        if (response.hasOwnProperty('data')) {
            //display success message

            response.data.comments.forEach(function (element) {
                var author = element.username;
                var date = element.timestamp;
                var content = element.content;
                var id = element.id;

                $("#comment_template .comment").attr("id", "post_" + id);
                $("#comment_template .comment .comment_description .comment_author").text(author);
                $("#comment_template .comment .comment_description .comment_date").text(date);
                $("#comment_template .comment .comment_content").text(content);

                $("#comment_template").children().clone().appendTo('.main');
            });
        } else if (response.hasOwnProperty('errors')) {
            alert(response.errors.detail);
        }
    });
}
$(document).ready(function () {
    if (urlParams.has('post_id')) {
        getPost(urlParams.get('post_id'));
        getComments(urlParams.get('post_id'));
    }
});