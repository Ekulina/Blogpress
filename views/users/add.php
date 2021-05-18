<?php



$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$passwordAgain = filter_input(INPUT_POST, 'password_again', FILTER_SANITIZE_STRING);
$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
$userRole = filter_input(INPUT_POST, 'userrole', FILTER_SANITIZE_STRING);


if (isset($action) && $action === 'save') {

    $errors = [];

    if (empty($email)) {
        $errors['email'] = t('email_empty', true);
    }

    if (empty($password)) {
        $errors['password_empty'] = t('password_empty', true);
    }

    if ($password != $passwordAgain) {
        $errors['password_mismatch'] = t('password_mismatch', true);
    }

    if (!empty(User::findByEmail($email))) {
        $errors['user_exists'] = t('user_exists', true);
    }

    if (empty($errors)) {
        $user = new User();
        $user->email = $email;
        $user->password = createPassword($password);
        $user->added = date("Y-m-d H:i:s");
        $user->added_by = $_SESSION['user_id'];
        $user->edited = date("Y-m-d H:i:s");;
        $user->edited_by = $_SESSION['user_id'];
        if (!in_array($userRole, ['user','moderator', 'admin'], true )) {
            $userRole = 'user';
        }
        $user->role = $userRole;


        $result = User::save($user);

        if ($result['status']) {
           $user->id = $result['id']; 
           $_SESSION['alert']['message'] = t('user_add', true);
           $_SESSION['alert']['action'] = 'success';
            
           redirect('/users');
        } else {
            echo $result['message'];
            echo message(t('problem_creating_user'), 'danger');
        }

    }
}
echo empty($errors)
    ? ""
    : '<div class="p-1 bg-white text-red-500 rounded font-bold text-center"><ul><li>' . join("</li><li>", $errors) . '</li></ul></div>';
?>
<div class= "w-full flex items-center justify-center">
<div class= "w-72">
<h1 class= "text-xl pb-4 font-bold"><?php t('add_user') ?></h1>

<form method="post" enctype="multipart/form-data" novalidate>

    <div class="form-group">
        <label class="text-xl text-gray-600" for="email"><?php t('emailaddress');?></label>
        <input
                type="email"
                class="border-2 border-gray-300 p-2 w-full"
                id="email" name="email"
                value=""
        >
    </div>
    <div class="form-group">
        <label class="text-xl text-gray-600"for="password"><?php t('password');?></label>
        <input
                type="password"
                class="border-2 border-gray-300 p-2 w-full"
                id="password"
                name="password">
    </div>

    <div class="form-group">
        <label class="text-xl text-gray-600" for="password_again"><?php t('passwordagain');?></label>
        <input
                type="password"
                class="border-2 border-gray-300 p-2 w-full"
                id="password_again"
                name="password_again">
    </div>
    <br>
    <div class="form-group">
        <label class="text-xl text-gray-600" for="userrole"><?php t('userrole');?></label>
        <select name="userrole" id="userrole">
            <option value="user"><?php t('user');?></option>
            <option value="moderator"><?php t('moderator');?></option>
            <option value="admin"><?php t('admin');?></option>
        </select>
    </div>
    <br>

    <button class="bg-gray-500 hover:bg-gray-600 text-white font-bold w-full rounded px-2 py-3" type="submit" name="action" value="save" class="btn btn-success"><?php t('save');?></button>
</form>