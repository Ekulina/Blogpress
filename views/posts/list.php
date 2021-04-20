<?php
    
    if (isset($_SESSION["alert"])) {
        echo "
        <script>
            bootbox.alert('" . $_SESSION["alert"] . "');
        </script>";
        unset($_SESSION["alert"]);
    }

    $currentPage = $ID;

    if ($currentPage == 0) {
        $currentPage = 1;
    }

    $start = startQuery($currentPage);
    $search = $_POST["search"];

    if (isset($search) && strlen($search) > 2) {
        $posts = Post::all($start, MAX_ON_PAGE, $search, 'auth');
        $counter = Post::all($start, MAX_ON_PAGE, $search, 'auth', true);
        $maxPosts = count($counter);
    } elseif (strlen($search) > 0 && strlen($search) < 3) {
        $tooShort = 1;
    } elseif ($_SESSION['role'] == 'user') {
        $posts = Post::all($start, MAX_ON_PAGE, $search, 'auth');
        $counter = Post::all($start, MAX_ON_PAGE, $search, 'auth', true);
        $maxPosts = count($counter);
    } else {
        $posts = Post::all($start, MAX_ON_PAGE, $search, 'auth');  
        $maxPosts = Post::count();
    }

    $maxPages = ceil($maxPosts / MAX_ON_PAGE);

?>
<div class="row">
    <div class="col">
        <form method="post">
            <div class="input-group">
                <input type="text" class="form-control" placeholder=<?php echo t('search'); ?> name="search" value="<?php echo isset($search) ? $search : ""; ?>">
                <div class="input-group-prepend">
                    <button type="submit" class="btn btn-success"><?php echo t('search'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<br>
<table class="min-w-max w-full table-auto">
<thead>
    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal text-justify">
        <th class="py-3 px-6 text-left"><?php t('title');?></th>
        <th class="py-3 px-6 text-left"><?php t('body');?></th>
        <th class="py-3 px-6 text-left"><?php t('added');?></th>
        <th class="py-3 px-6 text-left"><?php t('edited');?></th>
        <th class="py-3 px-6 text-left"><?php t('edit_post');?></th>
        <th class="py-3 px-6 text-left"><?php t('delete_post');?></th>
    </tr>
</thead>
<?php

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

$oPost = Post::findById ($id);

if (isset($action) && $action === 'delete') {
    POST::delete($oPost, true);
    $bodyTranslations = Translation::findForModel('Post', $oPost->id, 'body', 'et');
    $titleTranslations = Translation::findForModel('Post', $oPost->id, 'title', 'et');
    Translation::delete($bodyTranslations[0]);
    Translation::delete($titleTranslations[0]);
    redirect('/posts');
}

if (!empty($posts)) : foreach ($posts as $post) { ?>
<tbody class="text-gray-600 text-sm font-light">
<tr class="border-b border-gray-200 hover:bg-gray-100 text-justify">

        <td class="py-3 px-6 text-left whitespace-nowrap"><?php echo $post->title; ?></td>
        <td class="py-3 px-6 text-left whitespace-nowrap"><?php echo strLimit($post->body, 30); ?></td>
        <td class="py-3 px-6 text-left whitespace-nowrap"><?php echo $post->added; ?></td>
        <td class="py-3 px-6 text-left whitespace-nowrap"><?php echo $post->edited; ?></td>
        <td>
            <a class="btn btn-success" href="<?php 
                if ($_SERVER['REQUEST_URI'] == DIRECTORY_SEPARATOR . "posts") {
                    echo "posts" . DIRECTORY_SEPARATOR . "edit" . DIRECTORY_SEPARATOR . $post->id;
                } else {
                    echo "edit" . DIRECTORY_SEPARATOR . $post->id;
                }
            ?>"><?php t('edit');?></a>
        </td>
        <td>
            <form id="deleteForm" method="post">
                <input type="hidden" name='id' id='id' value="<?php echo $post->id;?>">
                <input type="hidden" name='action' id='action' value="delete">
                <button type="submit" name="action" value="delete" class="btn btn-danger"><?php t('delete');?></button>
            </form>
        </td>
    </tr>
<?php } ?>
<?php else: ?>
    <div class="col">
        <div class="alert alert-info">
            <?php echo isset($search) || isset($tooShort) ? t('error_search_too_short') : t('error_no_posts'); ?></div>
    </div>
<?php endif; ?>
</table>

<div class="flex items-center justify-center">

    <ul class="flex list-reset border border-grey-light rounded w-auto font-sans">

        <li class="">

            <a class="<?php echo $currentPage == 1? 'pointer-events-none bg-gray-400' : 'pointer-events-auto bg-gray-700'; ?> block hover:bg-gray-600 text-white border-r border-grey-light px-3 py-2 " href="/posts/<?php echo $currentPage - 1; ?>">Previous</a>

        </li>

        <?php for ($i = 1; $i <= $maxPages; $i++) : ?>

            <li><a class="hidden sm:block <?php echo $currentPage == $i ? 'bg-gray-600 text-white' : 'text-gray-700'; ?> bg-gray-300 hover:bg-gray-400  border-r border-grey-light px-3 py-2" href="/posts/<?php echo $i; ?>"><?php echo $i; ?></a></li>

        <?php endfor; ?>

        <li><a class="<?php echo $currentPage + 1 > $maxPages ? 'pointer-events-none bg-gray-400' : 'pointer-events-auto bg-gray-700'; ?> block  hover:bg-gray-600 text-white px-3 py-2" href="/posts/<?php echo $currentPage + 1; ?>">Next</a></li>

    </ul>

    </div>