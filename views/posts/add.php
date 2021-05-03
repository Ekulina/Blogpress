<?php
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
$body = filter_input(INPUT_POST, 'body', FILTER_SANITIZE_STRING);
$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

if (isset($action) && $action === 'save') {
    
    $errors = [];

    if (!empty(Post::findByTitle($title['title']['en']))) {
        $errors['post_exists'] = t('post_exists', true);
    }


    if (empty($title)) {
        $errors['title'] = t('error_title_empty', true);
    }


    if (empty($errors)) {

        $post = new Post();
        $post->title = $title;
        $post->body= $body;
        $post->status= 'draft';
        $post->added = date("Y-m-d H:i:s");
        $post->added_by = $_SESSION['user_id'];
        $post->edited = date("Y-m-d H:i:s");
        $post->edited_by = $_SESSION['user_id'];

        
        $result = Post::save($post);
        

        if ($result['status']) {

            $post->id = $result['id'];
            redirect('/posts/edit/' . $post->id);
    
        } else {
            echo $result['message'];
            echo message(t('problem_creating_post'), 'danger');
        }

    }
}

echo empty($errors)
    ? ""
    : '<div class="p-1 bg-white text-red-500 rounded font-bold text-center"><ul><li>' . join("</li><li>", $errors) . '</li></ul></div>';
?>
<div class= "w-full flex items-center justify-center">
<div class= "w-72">
<h1 class= "text-xl pb-4 font-bold">Add Post</h1>

<form method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label class="text-xl text-gray-600" for="title">Title</label>
        <input
                type="text"
                class="border-2 border-gray-300 p-2 w-full"
                id="title" name="title"
                value=""
        >
    </div>
    <div class="form-group">
        <label class="text-xl text-gray-600" for="title">Body</label>
        <textarea name="body" class="border-2 border-gray-300 p-2 w-full"  rows="5"></textarea>
    </div>

    <br>
    <button class="bg-gray-500 hover:bg-gray-600 text-white font-bold w-full py-3 pb-6" type="submit" name="action" value="save">Save</button>
</form>
</div>
</div>