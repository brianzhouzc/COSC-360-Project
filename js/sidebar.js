getPopularPosts();

function search() {
    term = $('#search_input').val();
    var data = {
        action: "search",
        term: term
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
            $('#search_container_title').attr("style", "");
            $('#search_container').empty();
            if (response.data.posts.length > 0) {
                response.data.posts.forEach(function (element) {
                    var title = element.title;
                    var id = element.id;
                    var views = element.views;

                    $("#search_template .search_title").attr("id", "search_" + id);
                    $("#search_template .search_title .search_title_link").html(title + " - " + views + " views");
                    $("#search_template .search_title .search_title_link").attr("href", "post.html?post_id=" + id);

                    $("#search_template").children().clone().appendTo('#search_container');
                });
            } else {
                $('#search_container').append("<h3>No Result</h3>");
            }
        } else {
            $('#search_container_title').attr("style", "");
            $('#search_container').empty();
            $('#search_container').append("<h3>No Result</h3>");
        }
    });
}

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
        console.log(response);
        if (response.hasOwnProperty('data')) {
            //display success message
            response.data.posts.forEach(function (element) {
                var title = element.title;
                var id = element.id;
                var views = element.views;

                $("#popular_template .popular_title").attr("id", "popular_" + id);
                $("#popular_template .popular_title .popular_title_link").html(title + " - " + views + " views");
                $("#popular_template .popular_title .popular_title_link").attr("href", "post.html?post_id=" + id);

                $("#popular_template").children().clone().appendTo('#popular_posts');
            });
        } else if (response.hasOwnProperty('errors')) {
            alert(response.errors.detail);
        }
    });
}

$('#search_button').click(function () {
    search()
});
