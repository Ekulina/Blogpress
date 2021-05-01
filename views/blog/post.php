<?php

$post = Post::findById($ID);

if (!is_object($post)) {
    echo message('Post is missing', 'danger');
}

$titleTranslations = Translation::findForModel('Post', $post->id, 'title', 'et');
$titleTranslation = reset($titleTranslations);
$bodyTranslations = Translation::findForModel('Post', $post->id, 'body', 'et');
$bodyTranslation = reset($bodyTranslations);

?>
<div class="h-96 w-screen bg-white">
    <?php if ($_SESSION['language'] == 'et') { ?>
        <div class="flex flex-wrap max-w-2xl px-4 py-4 mx-auto mb-4">
        <div class="max-w-2xl mx-auto px-6 my-16">
            <h2 class="mt-16 text-5xl font-bold"><?php echo is_object($titleTranslation) ? $titleTranslation->translation : ""; ?></h2>
            <p class="w-full mt-16 text-gray-500 description md:w-2/3"><?php echo $bodyTranslation->translation; ?></p>
        </div>
        </div>
    <?php } else { ?>
        <div class="flex flex-wrap max-w-2xl px-4 py-4 mx-auto mb-4">
        <div class="max-w-2xl mx-auto px-6 my-16">
            <h2 class="mt-16 text-5xl font-bold"><?php echo $post->title; ?></h3>
            <p class="w-full mt-16 text-gray-500 description md:w-2/3"><?php echo $post->body; ?></p>
        </div>
        </div>
    <?php  } ?>
</div>
