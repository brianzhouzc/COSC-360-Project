getPopularPosts();
function getPopularPosts(limit = 5, offset = 0) {
    var data = {
        action: "popular",
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
                current_offset++;

                var title = element.title;
                var id = element.id;
                var views = element.views;

                $("#popular_template .popular_title").attr("id", "popular_" + id);
                $("#popular_template .popular_title .popular_title_link").html(title + " - " + views + " views");
                $("#popular_template .popular_title .popular_title_link").attr("href", "./post?id=" + id);

                $("#popular_template").children().clone().appendTo('#popular_posts');
            });
        } else if (response.hasOwnProperty('errors')) {
            alert(response.errors.detail);
        }
    });
}