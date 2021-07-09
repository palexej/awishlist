<!DOCTYPE html>
<html lang="en">
<head>
  <title>Личный кабинет</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php require_once 'dependence.php' ?>
  <style>
  body {
    background: url(images-background/basketball.jpg) no-repeat center center fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
  }

  .userProfile {
    background: #2F4F4F; /* Цвет фона */
    color: #fff; /* Цвет текста */
    padding: 10px; /* Поля вокруг текста */
    border-radius: 5px; /* Уголки */
  }
  </style>

</head>
<body  style="background-attachment: fixed">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="https://awishlist.herokuapp.com">awishlist</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="https://awishlist.herokuapp.com/cataloge.php ">Каталог</a>
        </li>
      </ul>
      <?php if ( isset ($_SESSION['logged_user']) ) :
        ?>
        <ul class="navbar-nav  mx-md-n20">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
              <?php echo "Добро пожаловать, ". $_SESSION['logged_user']->login; ?>
              <i class="fa fa-fw fa-user"></i>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="https://awishlist.herokuapp.com/upload.php ">
                <i class="fa fa-fw fa-list-alt"></i>
                Мои списки желаний
              </a>
              <a class="dropdown-item" href="https://awishlist.herokuapp.com/userProfile.php ">
                <i class="fa fa-fw fa-address-card"></i>
                Настройки профиля
              </a>
              <a class="dropdown-item" href="https://awishlist.herokuapp.com/logout.php ">
                <i class="fa fa-fw fa-sign-out-alt"></i>
                Выйти
              </a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <?php

    $user_login_int=$_SESSION['logged_user']->id;

    //  $wishlists = R::find('newwishlists',"user_login_id=?",array($user_login_int));

    $editUserInfo = R::load('users', $user_login_int);
    $editUserName=$editUserInfo->name;
    $editUserSurname=$editUserInfo->surname;
    $editUserMiddlename=$editUserInfo->middlename;
    $editUserPassword=$editUserInfo->password;

    $data = $_POST;

    $errors = array();
    if (isset($data['change_password']))
    {
      if ( trim($data['currentPassword']) == '' )//trim-обрезка ненужных пробелов
      {
        $errors[] = 'Введите текущий пароль!';
      }
      if ( trim($data['newPassword']) == '' )//trim-обрезка ненужных пробелов
      {
        $errors[] = 'Введите новый пароль!';
      }
      if ( trim($data['repeatNewPassword']) == '' )//trim-обрезка ненужных пробелов
      {
        $errors[] = 'Введите новый пароль снова!';
      }

      if (password_verify($data['currentPassword'], $editUserPassword)==false )//обратная дешифровка
      {
        $errors[] = 'Текущий пароль введен неверно!';
      }

      if ($data['newPassword'] != $data['repeatNewPassword'] )
      {
        $errors[] = 'Повторный пароль введен неверно!';
      }
      if ( empty($errors) )
      {
        $changeUserPassword = R::load('users', $user_login_int);

        $changeUserPassword->password = password_hash($data['newPassword'], PASSWORD_DEFAULT); //пароль нельзя хранить в открытом виде, мы его шифруем при помощи функции password_hash для php > 5.6
        R::store($changeUserPassword);
        unset($data['change_password']);
        echo '<meta http-equiv="refresh" content="0;url= https://awishlist.herokuapp.com/userProfile.php "> ';
        echo '
        <div class="alert alert-success col-md-6 offset-md-3 alert-dismissible fade show" role="alert" text-center id="errors ">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4 class="alert-heading">Данные сохранены</h4>
        <p>Ваш пароль изменён</p>
        </div>
        ';
      }else
      {
        echo '
        <div class="alert alert-danger col-md-6 offset-md-3 alert-dismissible fade show" role="alert" text-center id="errors ">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4 class="alert-heading">Ошибка изменения пароля</h4>
        <p>'.array_shift($errors).'</p>
        </div>
        ';
        //	echo '<div id="errors" style="color:red;">' .array_shift($errors). '</div><hr>';//вывод первой ошибки из массива errors
      }
    }



    //если кликнули на button
    if ( isset($data['do_signup']) )
    {
      // проверка формы на пустоту полей
      $errors1 = array();
      if ( trim($data['surname']) == '' )//trim-обрезка ненужных пробелов
      {
        $errors1[] = 'Введите фамилию';
      }
      if ( trim($data['name']) == '' )//trim-обрезка ненужных пробелов
      {
        $errors1[] = 'Введите имя';
      }


      if ( empty($errors1) )
      {
        //ошибок нет, теперь регистрируем
        $saveUserInfo = R::load('users', $user_login_int);
        //автоинкремент автоматически создается
        $saveUserInfo->name= $data['name'];
        $saveUserInfo->surname= $data['surname'];
        $saveUserInfo->middlename = $data['middlename'];
        R::store($saveUserInfo);
        //хэширование back crypt, надежднее md5
        unset($data['do_signup']);
        echo '<meta http-equiv="refresh" content="0;url= https://awishlist.herokuapp.com/userProfile.php "> ';
        echo '
        <div class="alert alert-success col-md-6 offset-md-3 alert-dismissible fade show" role="alert" text-center id="errors ">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4 class="alert-heading">Данные сохранены</h4>
        <p>Ваши изменения сохранены</p>
        </div>
        ';
      }else
      {
        echo '
        <div class="alert alert-danger col-md-6 offset-md-3 alert-dismissible fade show" role="alert" text-center id="errors ">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4 class="alert-heading">Ошибка сохранения пользовательских данных</h4>
        <p>'.array_shift($errors1).'</p>
        </div>
        ';
        //	echo '<div id="errors" style="color:red;">' .array_shift($errors). '</div><hr>';//вывод первой ошибки из массива errors
      }
    }




    ?>
    <br>
    <div class="container-fluid">
      <div class="row" >

        <div class="col-md-4 offset-md-4 text-center userProfile">
          <h2>Настройка информации профиля</h2>

          <form action="https://awishlist.herokuapp.com/userProfile.php " method="POST">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-white"><i class="fa fa-info"></i></span>
              </div>
              <input type="text" name="surname" class="form-control" placeholder="Введите фамилию" value='<?php echo $editUserSurname ?>'>
            </div>
            <br>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-white"><i class="fa fa-info"></i></span>
              </div>
              <input type="text" name="name" class="form-control" placeholder="Введите имя" value='<?php echo $editUserName ?>'><br/>
            </div>
            <br>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-white"><i class="fa fa-info"></i></span>
              </div>
              <input type="text" name="middlename" class="form-control" placeholder="Введите отчество" value='<?php echo $editUserMiddlename?>'><br/>
            </div>
            <br>
            <button id="do_signup" class="btn btn-success btn-block" type="submit" name="do_signup">Сохранить изменения</button>
          </form>

          <br>
          <h2>Изменение пароля</h2>
          <form action="https://awishlist.herokuapp.com/userProfile.php " method="POST">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-white"><i class="fa fa-unlock"></i></span>
              </div>
              <input type="password" name="currentPassword" class="form-control" placeholder="Введите текущий пароль" value=''>
            </div>
            <br>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-white"><i class="fa fa-key"></i></span>
              </div>
              <input type="password" name="newPassword" class="form-control" placeholder="Введите новый пароль" value=''><br/>
            </div>
            <br>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-white"><i class="fa fa-redo"></i></span>
              </div>
              <input type="password" name="repeatNewPassword" class="form-control" placeholder="Введите новый пароль снова" value=''><br/>
            </div>
            <br>
            <button id="do_signup" class="btn btn-success btn-block" type="submit" name="change_password">Сменить пароль</button>
          </form>
        </div>
      </div>
    </div>

  </body>
  </html>


<?php else : ?>
  <ul class="navbar-nav ">
    <li class="nav-item">
      <a class="nav-link" href="https://awishlist.herokuapp.com/signup.php ">
        <i class="fa fa-fw fa-user-plus"></i>
        Регистрация
      </a>
    </li>
  </ul>
  <ul class="navbar-nav ">
    <li class="nav-item">
      <a class="nav-link" href="https://awishlist.herokuapp.com/login.php ">
        <i class="fa fa-fw fa-sign-in-alt"></i>
        Авторизация
      </a>
    </li>
  </ul>
</div>
</nav>
<br>
<div class="alert alert-danger" role="alert">
  <h4 class="alert-heading">Данная страница недоступна</h4>
  <p>Воспользуйтесь <a href="https://awishlist.herokuapp.com/login.php ">входом в систему</a> или <a href="https://awishlist.herokuapp.com/signup.php ">зарегистрируйтесь</a> для получения доступа к этой странице. </p>
  <hr>
</div>

<?php endif; ?>

<script type="text/javascript">
$('#do_signup').click(function() {
  $('#do_signup').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Пожалуйста, подождите...').addClass('disabled');
});
</script>
