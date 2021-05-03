<?php
session_start();
require_once 'autoload.php';

$p = trim($_SERVER['REQUEST_URI'], '/');
$currentPage = 0;

if (is_numeric($p)) {
    $currentPage = $p;
    $p = 'blog';
}

$pos = strpos($p, '?search');

if ($pos !== false) {
    $explode = explode("=", $p);
    $search = $explode[1];
    $p = 'blog';
}

checkAccess($p);

?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <title>Blogpress</title>
</head>
<nav class="flex items-center justify-between flex-wrap bg-red-100 p-4 text-center sm:text-left">
  <div class="flex items-center flex-shrink-0 mr-6">
    <span class="font-semibold text-3xl tracking-tight">Blogpress</span>
  </div>

  <label class="block lg:hidden cursor-pointer flex items-center px-3 py-2 border rounded text-teal-200 border-teal-400 hover:text-gray-700 hover:border-gray-700" for="menu-toggle"><svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg></label>
  <input class="hidden" type="checkbox" id="menu-toggle" />

  <div class="hidden w-full block flex-grow lg:flex lg:items-center lg:w-auto" id="menu">
    <div class="text-sm lg:flex-grow">
      <a href="/" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">
      <?php t('home'); ?>
      </a>
      <?php if (isLoggedIn()) : ?>
                    <?php if ($_SESSION['role'] === 'admin') : ?>
      <a href="/users/" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">
      <?php t('users'); ?>
      </a>
      <a href="/users/add/" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">
      <?php t('add_user'); ?>
      </a>
      <?php endif; ?>
      <a href="/posts/" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">
      <?php t('posts'); ?>
      </a>
      <a href="/posts/add/" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">
      <?php t('add_post'); ?>
      </a>
      <?php endif; ?> 
                
    </div>
    <?php require_once 'views' . DIRECTORY_SEPARATOR . 'translation.php';?>
  </div>
</nav>

<body>
<div class="bg-red-100">
        <div class="flex justify-center items-center px-6">
        </div>
        <div class="flex justify-start flex-row mt-4 ml-10">
        <?php if (isLoggedIn()) : ?>
        <a class="hover:bg-red-200 py-2 px-4 rounded" href="/logout">Logout</a>
        <?php else: ?>
        <a class="hover:bg-red-200 py-2 px-4 rounded" href="/login">Login</a>
        <?php endif; ?>
        

        </div>
        
        </div>
   
        <div class="col-8">

           
            <?php
        
            if (empty($p)) {
                require_once $routes['blog']['file_location'];
            } elseif (isset($routes[$p])) {
                require_once $routes[$p]['file_location'];
            } else {

                $explode = explode("/", $p);
            
                if (!empty($explode) && count($explode) > 1) {

                    $ID = $explode[count($explode)-1];

                    unset($explode[count($explode)-1]);

                    $p = join("/", $explode);
                    if (isset($routes[$p])) {
                        require_once $routes[$p]['file_location'];
                    } else {
                        require_once $routes[404]['file_location'];
                    }
                } else {
                    require_once $routes[404]['file_location'];
                }
            }

            ?>
        </div>
    </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>

<style>
#menu-toggle:checked+#menu {
  display: block;
}
</style>
</body>
</html>