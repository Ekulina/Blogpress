<?php

if (isLoggedIn()) {
    redirect('/');
}


$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

if (isset($action) && $action === 'login') {
    $errors = [];

    if (empty($email)) {
        array_push($errors, t('email_empty', true));
    }

    if (empty($password)) {
        array_push($errors, t('password_empty', true));
    }

    if (empty($errors)) {

        $user = User::findByEmail($email);

        if (is_object($user)) {

            if (password_verify($password, $user->password)) {
                $_SESSION[IS_LOGGED_IN] = true;
                $_SESSION['user_id'] = $user->id;
                $_SESSION['role'] = $user->role;
                redirect('/');
            }
        }
        array_push($errors, t('username_password_mismatch', true));
    }
}
echo empty($errors)
    ? ""
    : '<div class="alert alert-danger"><ul><li>' . join("</li><li>", $errors) . '</li></ul></div>';
?>
<div class= "items-center justify-center flex w-full">
<div class="bg-red-100 rounded py-16 px-12 m-16 flex flex-col items-center justify-center w-80">
  <form method="post" class="mt-8 mb-4">
    <div class="mb-4">
      <label for="userEmail" class="sr-only">Email address</label>
      <input class="border-solid border border-gray-400 rounded px-2 py-3"  id="email" name="email" placeholder="Email address" required />
    </div>
    <div>
      <label for="userEmail" class="sr-only">Password</label>
      <input class="border-solid border border-gray-400 rounded px-2 py-3" type="password" id="password"
            name="password" placeholder="Password" required />
    </div>
    <br>
    <button class="bg-gray-500 hover:bg-gray-600 text-white font-bold w-full rounded px-2 py-3" type="submit" name="action" value="login">Sign in</button>
  </form>

</div>
</div>