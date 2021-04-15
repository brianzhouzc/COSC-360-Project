$(document).ready(function () {
    if ("username" in sessionStorage && "session" in sessionStorage) {
        var adminForm = document.getElementById("admin_search");
        adminForm.onsubmit = function (e) {
            e.preventDefault();
            var formData = new FormData(adminForm);
            formData.append('action', 'search');
            formData.append('username', sessionStorage.getItem('username'));
            formData.append('session', sessionStorage.getItem('session'));

            console.log(formData);
            if (formData.get('option') == 'post') {
                $('#posts_container').empty();
                $('#users_container').empty();
                $.ajax({
                    type: "POST",
                    url: "/server/admins.php",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    encode: true
                }).done(function (response) {
                    console.log(response);
                    if (response.hasOwnProperty('data')) {
                        $('#search_title').text('Posts:');
                        response.data.posts.forEach(function (element) {
                            var title = element.title;
                            var author = element.username;
                            var date = element.timestamp;
                            var content = element.content;
                            var id = element.id;

                            $("#post_template .post").attr("id", "post_" + id);
                            $("#post_template .post_title").val(title);
                            $("#post_template .post_description .post_author").text(author);
                            $("#post_template .post_description .post_date").text(date);
                            $("#post_template .post_content").val(content);

                            $("#post_template").children().clone().appendTo('#posts_container');
                            $("#post_template .post").attr("id", "post_id");

                            $('#post_' + id).submit(function (e) {
                                e.preventDefault();
                                var postForm = document.getElementById("post_" + id);
                                var formData = new FormData(postForm);
                                if (formData.get('post_remove') === null) {
                                    formData.append('action', 'edit');
                                } else {
                                    formData.append('action', 'remove');
                                }

                                formData.append('post_id', id);
                                formData.append('username', sessionStorage.getItem('username'));
                                formData.append('session', sessionStorage.getItem('session'));

                                $.ajax({
                                    type: "POST",
                                    url: "/server/admins.php",
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

                            });
                        });

                    } else if (response.hasOwnProperty('errors')) {
                        $('#search_title').text('No result');
                    }
                });
            } else if (formData.get('option') == 'username_email') {
                $('#posts_container').empty();
                $('#users_container').empty();
                $.ajax({
                    type: "POST",
                    url: "/server/admins.php",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    encode: true
                }).done(function (response) {
                    console.log(response);
                    if (response.hasOwnProperty('data')) {
                        $('#search_title').text('User:');
                        var username = response.data.user.username;
                        var email = response.data.user.email;
                        var enable = response.data.user.enable;

                        $("#user_template .user").attr("id", username);
                        $("#user_template #user_title #user_title_name").text(username);
                        $("#user_template #user_title #user_title_email").text(email);
                        $("#user_template #user_enable").prop('checked', enable);

                        $("#user_template").children().clone().appendTo('#users_container');
                        $("#user_template .user").attr("id", "user_template_form");


                        $('#' + username).submit(function (e) {
                            e.preventDefault();
                            var userForm = document.getElementById(username);

                            var formData = new FormData(userForm);
                            formData.append('action', 'user');
                            formData.append('username', sessionStorage.getItem('username'));
                            formData.append('session', sessionStorage.getItem('session'));
                            formData.append('edit_username', userForm.id);
                            if (formData.get('user_enable') === null)
                                formData.append('enable', 0);
                            else
                                formData.append('enable', 1);

                            $.ajax({
                                type: "POST",
                                url: "/server/admins.php",
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

                        });
                    } else if (response.hasOwnProperty('errors')) {
                        $('#search_title').text('No result');
                    }
                });
            }
        }
    }
})
