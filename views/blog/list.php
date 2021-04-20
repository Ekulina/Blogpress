<?php

if ($currentPage == 0) {
    $currentPage = 1;
}

$counter = 0;

$start = startQuery($currentPage);
$search = $_POST["search"];

if (isset($search) && strlen($search) > 2) {
    $posts = Post::all($start, MAX_ON_PAGE, $search);
    $maxPosts = count($posts);
} elseif (strlen($search) > 0 && strlen($search) < 3) {
    $tooShort = 1;
} else {
    $posts = Post::all($start, MAX_ON_PAGE);
    $maxPosts = Post::count();
}

$maxPages = ceil($maxPosts / MAX_ON_PAGE);

?>

<div class="row">
    <div class="p-4">
        <form method="post">
            <div class="bg-white flex items-center rounded-full shadow-xl">
                <input type="text" class="bg-white flex items-center rounded-full shadow-xlrounded-l-full w-full py-4 px-6 text-gray-700 leading-tight focus:outline-none" placeholder=<?php echo t('search'); ?> name="search" value="<?php echo isset($search) ? $search : ""; ?>">
                <div class="p-4">
                    <button type="submit" class="bg-red-100 rounded-full p-2 hover:bg-red-100 focus:outline-none w-12 h-12 flex items-center justify-center"><?php echo t('search'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<br>
<div class="flex w-full flex-wrap">
<?php if (!empty($posts)) : ?>
    <?php foreach ($posts as $post) { ?>
     
    <div class="w-64 border m-12 rounded shadow-lg ">
        <div class=" w-full flex flex-col justify-center">
            <div class="">
                <h5 class="text-center pb-4"><?php echo $post->title; ?>(<?php echo $post->id; ?>)</h5>
                <p class="text-center truncate pb-4"><?php echo $post->body; ?></p>
                <div class= "flex justify-center pb-4">
                <a href="/post/<?php echo $post->id; ?>" class="px-2 py-1 bg-red-100 rounded"><?php t('read_more'); ?></a>
                </div>
            </div>
        </div>
        
    </div>
    <?php } ?>
<?php else: ?>
    <div class="col">
        <div class="alert alert-info"><?php echo isset($search) ? 'No results or search to short' : 'No posts'; ?></div>
    </div>
<?php endif; ?>
</div>

</div>

<div class="flex items-center justify-center">

    <ul class="flex list-reset border border-grey-light rounded w-auto font-sans">

        <li class="">

            <a class="<?php echo $currentPage == 1? 'pointer-events-none bg-gray-400' : 'pointer-events-auto bg-gray-700'; ?> block hover:bg-gray-600 text-white border-r border-grey-light px-3 py-2 " href="/<?php echo $currentPage - 1; ?>">Previous</a>

        </li>

        <?php for ($i = 1; $i <= $maxPages; $i++) : ?>

            <li><a class="hidden sm:block <?php echo $currentPage == $i ? 'bg-gray-600 text-white' : 'text-gray-700'; ?> bg-gray-300 hover:bg-gray-400  border-r border-grey-light px-3 py-2" href="/<?php echo $i; ?>"><?php echo $i; ?></a></li>

        <?php endfor; ?>

        <li><a class="<?php echo $currentPage + 1 > $maxPages ? 'pointer-events-none bg-gray-400' : 'pointer-events-auto bg-gray-700'; ?> block  hover:bg-gray-600 text-white px-3 py-2" href="/<?php echo $currentPage + 1; ?>">Next</a></li>

    </ul>

    </div>