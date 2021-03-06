<?php
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
$body = filter_input(INPUT_POST, 'body', FILTER_SANITIZE_STRING);
$oPost = Post::findById ($ID);

if (!is_object($oPost)) {
    echo message('User missing', 'danger');
}


$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

if (isset($action) && $action === 'update') {


    if (empty($errors)) {

        $post = $oPost;
        $post->title = $title;
        $post->body= $body;
        $post->edited = date("Y-m-d H:i:s");
        $post->edited_by = $_SESSION['user_id'];

        $result = Post::save($post);


        if ($result['status']) {
            
            $_SESSION['alert']['message'] = t('post_edit', true);
            $_SESSION['alert']['action'] = 'success';
            
            redirect('/posts');
            
        } else {
            $message = $result['message'];
        }

    }
}

echo message($message, 'danger');

?>
<div class= "w-full flex items-center justify-center">
<div class= "w-72">
<h1 class= "text-xl pb-4 font-bold"><?php t('edit_post') ?></h1>

<form method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label class="text-xl text-gray-600" for="title"><?php t('title') ?></label>
        <input
                type="text"
                class="border-2 border-gray-300 p-2 w-full"
                id="title" name="title"
                value="<?php echo is_object($oPost) ? $oPost->title : ""; ?>"
        >
    </div>
    <div class="form-group">
        <label class="text-xl text-gray-600" for="title"><?php t('body') ?></label>
        <textarea name="body" class="border-2 border-gray-300 p-2 w-full"  rows="5"><?php echo $oPost->body; ?></textarea>
    </div>

    <br>
    <button class="p-3 bg-blue-100 text-blue-500 rounded font-bold w-full" type="submit" name="action" value="update"><?php t('edit') ?></button>
</form>
</div>
</div>
