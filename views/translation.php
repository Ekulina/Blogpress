<?php

$translate = filter_input(INPUT_POST, 'translate', FILTER_SANITIZE_STRING);

if (isset($translate)){
    if ($translate === 'en') {
        $_SESSION['language'] = 'en';
    } elseif ($translate === 'et') {
        $_SESSION['language'] = 'et';
    }
    
    redirect($_SERVER['REQUEST_URI']);
}
?>

    <form method="post">
        <?php  
            
            echo '<input class="block mt-4 lg:inline-block lg:mt-0 hover:text-white mr-4 uppercase bg-red-100" type="submit" name="translate" value="';
            if ($_SESSION['language'] =='et') {
                echo 'en">';
                
            } else {
                echo 'et">';
            }
        ?>
    </form>
