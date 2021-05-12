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
        $users = User::all($start, MAX_ON_PAGE, $search, 'auth');
        $maxUsers = count($users);
    } elseif (strlen($search) > 0 && strlen($search) < 3) {
        $tooShort = 1;
    } else {
        $users = User::all($start, MAX_ON_PAGE, $search, 'auth');
        $maxUsers = User::count();
    }
    $maxPages = ceil($maxUsers / MAX_ON_PAGE);
    
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
<div class="overflow-x-auto">
    <table class="min-w-max w-full table-auto">
        <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal text-justify">
                <th class="py-3 px-6 text-left"><?php t('email');?></th>
                <th class="py-3 px-6 text-left"><?php t('added');?></th>
                <th class="py-3 px-6 text-left"><?php t('edited');?></th>
                <th class="py-3 px-6 text-left"><?php t('edit_user');?></th>
                <th class="py-3 px-6 text-left"><?php t('delete_user');?></th>
            </tr>
        </thead>
        <?php

    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    $oUser = User::findById ($id);

    if (isset($action) && $action === 'delete') {
        USER::delete($oUser);
        redirect('/users');
    }

    if (!empty($users)) : foreach ($users as $user) { 
        
        if ($user->id == $_SESSION['user_id']) {
            continue;
        }
        
        ?>
        <tbody class="text-gray-600 text-sm font-light">
        <tr class="border-b border-gray-200 hover:bg-gray-100 text-justify">
            <td class="py-3 px-6 text-left whitespace-nowrap"><?php echo $user->email; ?></td>
            <td class="py-3 px-6 text-left whitespace-nowrap"><?php echo $user->added; ?></td>
            <td class="py-3 px-6 text-left whitespace-nowrap"><?php echo $user->edited; ?></td>
            <td class="py-3 px-6 text-left whitespace-nowrap">
                <a class="p-1 bg-blue-100 text-blue-500 rounded font-bold" href="<?php 
                    if ($_SERVER['REQUEST_URI'] == DIRECTORY_SEPARATOR . "users") {
                        echo "users" . DIRECTORY_SEPARATOR . "edit" . DIRECTORY_SEPARATOR . $user->id;
                    } else {
                        echo "edit" . DIRECTORY_SEPARATOR . $user->id;
                    }
                ?>"><?php t('edit');?></a>
            </td>
            <td>
                <form id="deleteForm" method="post">
                    <input type="hidden" name='action' id='action' value="delete">
                    <input type="hidden" name='id' id='id' value="<?php echo $user->id;?>">
                    <button class="p-1 bg-red-100 text-red-500 rounded font-bold" type="submit" name="action" value="delete" class="btn btn-danger"><?php t('delete');?></button>
                </form>
            </td>
        </tr>
        <?php } ?>
    <?php else: ?>
        <div class="col">
            <div class="p-1 bg-white text-red-500 rounded font-bold text-center">
                <?php echo isset($search) || isset($tooShort) ? t('error_search_too_short') : t('error_no_posts'); ?></div>
        </div>
    <?php endif; ?>
    </table>
</div>

<div class="flex items-center justify-center">

    <ul class="flex list-reset border border-grey-light rounded w-auto font-sans">

        <li class="">

            <a class="<?php echo $currentPage == 1? 'pointer-events-none bg-gray-400' : 'pointer-events-auto bg-gray-700'; ?> block hover:bg-gray-600 text-white border-r border-grey-light px-3 py-2 " href="/users/<?php echo $currentPage - 1; ?>">Previous</a>

        </li>

        <?php for ($i = 1; $i <= $maxPages; $i++) : ?>

            <li><a class="hidden sm:block <?php echo $currentPage == $i ? 'bg-gray-600 text-white' : 'text-gray-700'; ?> bg-gray-300 hover:bg-gray-400  border-r border-grey-light px-3 py-2" href="/users/<?php echo $i; ?>"><?php echo $i; ?></a></li>

        <?php endfor; ?>

        <li><a class="<?php echo $currentPage + 1 > $maxPages ? 'pointer-events-none bg-gray-400' : 'pointer-events-auto bg-gray-700'; ?> block  hover:bg-gray-600 text-white px-3 py-2" href="/users/<?php echo $currentPage + 1; ?>">Next</a></li>

    </ul>

    </div>