<?php

if (!isLoggedIn() || $_SESSION['role'] != 'admin') {
    redirect("/");
}

$oUser = User::findById ($ID);

if (!is_object($oUser)) {
    echo message(t('user_missing'), 'danger');
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
$userRole = filter_input(INPUT_POST, 'userrole', FILTER_SANITIZE_STRING);

if (isset($action) && $action === 'role-update') {
    $user = $oUser;

    if (!in_array($userRole, ['user','moderator', 'admin'], true )) {
        $userRole = 'user';
    }
    
    $user->role = $userRole;
    $result = User::save($user);

    if ($result['status']) {
        $_SESSION['alert']['message'] = t('role_edit', true);
        $_SESSION['alert']['action'];
        
        redirect('/users');

    } else {
        $message = $result['message'];
    }
}



if (isset($action) && $action === 'update') {
    $errors = [];

    if (empty($email)) {
        $errors['email'] = t('email_empty', true);
    }

     if (empty($password)) {
        $errors['password'] = t('password_empty', true);
    }

    if (empty($errors)) {
        $user = $oUser;
        $user->email = $email;
        $user->password = createPassword($password);
        $user->edited = date("Y-m-d H:i:s");;
        $user->edited_by = $_SESSION['user_id'];

        $result = User::save($user);

        if ($result['status']) {
            $_SESSION['alert']['message'] = t('user_edit', true);
            $_SESSION['alert']['action'] = 'success';

            redirect('/users');

        } else {
            $message = $result['message'];
        }
    }
} elseif (isset($action) && $action === 'delete') {
    USER::delete($oUser);
    $_SESSION['alert']['message'] = t('user_delete', true);
    $_SESSION['alert']['action'] = 'success';

    redirect('/users');
}

echo empty($errors)
    ? ""
    : '<div class="alert alert-danger"><ul><li>' . join("</li><li>", $errors) . '</li></ul></div>';

?>
<div class= "w-full flex items-center justify-center">
<div class= "w-72">
<h1 class= "text-xl pb-4 font-bold">Edit User</h1>

<form method="post"enctype="multipart/form-data">

    <div class="form-group">
        <label class="text-xl text-gray-600" for="email"><?php t('emailaddress');?></label>
        <input
            type="email"
            class="border-2 border-gray-300 p-2 w-full"
            id="email" name="email"
            value="<?php echo is_object($oUser) ? $oUser->email : ""; ?>"
        >
    </div>
    <div class="form-group">
        <label class="text-xl text-gray-600" for="password"><?php t('password');?></label>
        <input
            type="password"
            class="border-2 border-gray-300 p-2 w-full"
            id="password"
            name="password"
        >
    </div>

    <input type="hidden" name='action' id='action' value="update">
    <button type="submit" name="action" value="update" class="p-3 bg-blue-100 text-blue-500 rounded font-bold w-full mt-3"><?php t('edit');?></button>
</form>

<form id="roleForm" method="post" class="mt-2">
        <label class="text-xl text-gray-600" for="userrole"><?php t('userrole');?></label>
    <div class="border-2 border-gray-300 p-2 w-full">
        <select name="userrole" id="userrole">
            <option value="user" <?php if ($oUser->role == 'user') { echo "selected"; }?>><?php t('user');?></option>
            <option value="moderator" <?php if ($oUser->role == 'moderator') { echo "selected"; }?>><?php t('moderator');?></option>
            <option value="admin" <?php if ($oUser->role == 'admin') { echo "selected"; }?>><?php t('admin');?></option>
        </select>
    </div>
    <input type="hidden" name='action' id='action' value="role-update">
    <button type="submit" name="action" value="role-update" class="p-3 bg-blue-100 text-blue-500 rounded font-bold w-full mt-3"><?php t('role_update');?></button>
</form>

<form id="deleteForm" method="post" class="mt-2">
        <input type="hidden" name='id' id='id' value="<?php echo $user->id;?>">
        <input type="hidden" name='action' id='action' value="delete">
        <button type="submit" name="action" value="delete" class="p-3 bg-red-100 text-red-500 rounded w-full font-bold"><?php t('delete');?></button>
</form>
