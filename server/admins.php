<?php
require_once 'database.php';
require_once 'helper.php';
require_once 'admins_functions.php';
require_once 'users_functions.php';
require_once 'posts_functions.php';


if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $username = getValueFromKey($_POST, 'username');
    $session = getValueFromKey($_POST, 'session');

    if (authenticateAdmin($connection, $username, $session)) {
        switch ($action) {
            case "search":
                $option = getValueFromKey($_POST, 'option');
                $term = getValueFromKey($_POST, 'term');

                switch ($option) {
                    case "username_email":
                        $user = getUserByNameOrEmail($connection, $term, $term);
                        if (isset_notempty($user)) {
                            $data = array(
                                "user" =>
                                array(
                                    "username" => $user['username'], "email" => $user['email'], "enable" => $user['enable']
                                )
                            );
                            exitandclose(dataResponse(200, "Success", $data), $connection);
                        } else {
                            exit(errorResponse(400, "No user found"));
                        }
                        break;
                    case "post":
                        $results = getPostsBySearch($connection, $term);
                        if ($results->num_rows > 0) {
                            $posts = array();
                            while ($row = $results->fetch_assoc()) {
                                array_push($posts, array("id" => $row['id'], "username" => $row['username'], "content" => $row['content'], "timestamp" => $row['timestamp'], "views" => $row['views'], "title" => $row['title']));
                            }
                            exit(dataResponse(200, "Success", array("posts" => $posts)));
                        } else {
                            exit(errorResponse(400, "No posts found"));
                        }
                        break;
                }
                break;
            case "edit":
                $post_id = getValueFromKey($_POST, 'post_id');
                $post_title = getValueFromKey($_POST, 'post_title');
                $post_content = getValueFromKey($_POST, 'post_content');
                if (isset_notempty($post_title, $post_content, $post_id)) {
                    adminEditPost($connection, $post_id, $post_title, $post_content);
                    exit(dataResponse(200, "Success!"));
                } else {
                    exit(errorResponse(400, "Missing post id/title/content"));
                }
                break;
            case "remove":
                $post_id = getValueFromKey($_POST, 'post_id');
                if (isset_notempty($post_id)) {
                    adminRemovePost($connection, $post_id);
                    exit(dataResponse(200, "Success!"));
                } else {
                    exit(errorResponse(400, "Missing post id"));
                }
            case "user":
                $edit_username = getValueFromKey($_POST, 'edit_username');
                $enable = getValueFromKey($_POST, 'enable');
                if (isset_notempty($edit_username)) {
                    setUserEnable($connection, $edit_username, $enable);
                    exit(dataResponse(200, "Success!"));
                } else {
                    exit(errorResponse(400, "Missing username/enable" . $edit_username . $enable));
                }
        }
    } else {
        exit(errorResponse(400, "Unauthroize user"));
    }
}
