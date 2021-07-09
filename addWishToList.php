<!DOCTYPE html>
<html lang="ru" dir="ltr">
<head>
  <meta charset="utf-8">

  <?php require_once 'dependence.php' ?>

  <style>
  body {
    background: url(images-background/newyear-pic.jpg) no-repeat center center fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
  }
  .WishList {
    background: #DEB887; /* Цвет фона */
    color: #000000; /* Цвет текста */
    padding: 10px; /* Поля вокруг текста */
    border-radius: 5px; /* Уголки */
  }

  </style>

  <title>Добавить новое желание</title>
</head>
<body>
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
    <br>
    <?php

    $data = $_POST;

    //если кликнули на button
    if (isset($data['addWishToList']) )
    {
      // проверка формы на пустоту полей
      $errors = array();
      if (trim($data['wish_name']) == '' )//trim-обрезка ненужных пробелов
      {
        $errors[] = 'Введите название желания';
      }

      if (trim($data['wish_price']) == '' )//trim-обрезка ненужных пробелов
      {
        $errors[] = 'Введите цену желания';
      }
      if (is_numeric(trim($data['wish_price']))==true)//trim-обрезка ненужных пробелов
      {
        if ($data['wish_price']<=0)
        {
          $errors[] = 'Цена должна быть положительным числом';
        }

      }
      else
      {
        $errors[] = 'Цена должна являться числом';
      }

      if (trim($data['wish_link']) == '' )//trim-обрезка ненужных пробелов
      {
        $errors[] = 'Введите ссылку на страницу желания';
      }

      if(isset($_FILES['file']))
      {
        $file=$_FILES['file'];
        if ($file['size']!=0)
        {
          $getMime = explode('.', $file['name']);
          // нас интересует последний элемент массива - расширение
          $mime = strtolower(end($getMime));
          // объявим массив допустимых расширений
          $types = array('jpg', 'jpeg');

          // если расширение не входит в список допустимых - return
          if(!in_array($mime, $types))
          {
            $errors[] = 'Недопустимый тип файла';
          }
          // if ( $file['size']>=2048000)
          // {
          //   $errors[] = 'Размер файла превышает 2 МБ';
          // }
        }
        else
        {
          $errors[] = 'Загрузите изображение желания';
        }

      }



      if ( empty($errors) )
      {
        $name = mt_rand(0, 10000) . $file['name'];
        $myFile='wishsImg/' .$name;
        copy($file['tmp_name'], $myFile);
        //ошибок нет, теперь регистрируем
        $addWish = R::dispense('wishs');//автоматическое создание таблицы пользователей
        //автоинкремент автоматически создается
        $addWish->wish_name = $data['wish_name'];
        $addWish->wish_info = $data['wish_info'];
        $addWish->wish_price = $data['wish_price'];
        $addWish->wish_wishlist_id = $data['selectWishlistToAdd'];
        $addWish->wish_link=$data['wish_link'];
        $addWish->wish_img_path=$myFile;
        $addWish->wish_currency_type=$data['currencyType'];
        $addWish->wish_was_taken="false";


        R::store($addWish);

        echo '<meta http-equiv="refresh" content="0;url= https://awishlist.herokuapp.com/upload.php "> ';

      }else
      {
        echo '
        <div class="alert alert-danger col-md-6 offset-md-3 alert-dismissible fade show" role="alert" text-center id="errors ">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4 class="alert-heading">Ошибка добавления желания</h4>
        <p>'.array_shift($errors).'</p>
        </div>
        ';
      }
    }
    ?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-4 offset-md-4 text-center  text-center WishList">
          <h2>Добавить новое желание</h2>
          <form action="https://awishlist.herokuapp.com/addWishToList.php" method="post" enctype="multipart/form-data">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-white"><i class="fa fa-gift"></i></span>
              </div>
              <input type="text" name="wish_name" class="form-control" placeholder="Введите название желания" value="<?php echo @$data['wish_name']; ?>">
            </div>
            <br>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-white"><i class="fa fa-info"></i></span>
              </div>
              <textarea type="text" style="resize:vertical" name="wish_info" class="form-control" placeholder="Введите информацию о  желании" value=""><?php echo @$data['wish_info']; ?></textarea>
            </div>
            <br>
            <div class="form-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-white"><i class="fa fa-list-ol"></i></span>
                <select class="form-control" id="exampleFormControlSelect1" name="selectWishlistToAdd" data-toggle="tooltip" data-placement="right" title="Выберите список, в который нужно добавить желение">
                  <?php
                  $user_login_int=$_SESSION['logged_user']->id;
                  $wishlists = R::find('newwishlists',"user_login_id=?",array($user_login_int));
                  $wishlistsCount = R::count('newwishlists',"user_login_id=?",array($user_login_int));
                  $i=0;
                  foreach ($wishlists as $oneWishList){
                    $wishlistId=$oneWishList->id;
                    $wishlistName=$oneWishList->wishlist_name;
                    echo '  <option  value='.$wishlistId.'>'.$wishlistName.'</option>';
                  }
                  ?>
                </select>
              </div>
            </div>

            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-white"><i class="fa fa-money-bill-wave-alt"></i></span>
              </div>
              <input type="text" name="wish_price" class="form-control" placeholder="Введите цену желания" value="<?php echo @$data['wish_price']; ?>">
              <select class="form-control" name="currencyType"  data-toggle="tooltip" data-placement="right" title="Выберите тип валюты">
                <option selected value="₽">₽</option>
                <option value="€">€</option>
                <option value="$">$</option>
              </select>
            </div>
            <br>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-white"><i class="fa fa-link"></i></span>
              </div>
              <input type="text" name="wish_link" class="form-control" placeholder="Введите ссылку на страницу желания" value="<?php echo @$data['wish_link']; ?>"><br/>
            </div>
            <br>
            <div class="custom-file">
              <input type="file"  name=file class="custom-file-input" data-toggle="tooltip" data-placement="right" title="Формат файла: jpeg или jpg">
              <label class="custom-file-label">Выберите фотографию желания</label>
            </div>
            <br>
            <br>
            <button class="btn btn-warning btn-block" id="do_signup" type="submit" name="addWishToList">Добавить желание в список</button>
          </form>
        </div>
      </div>
    </div>
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
  <h4 class="alert-heading">Данная страница недоступна!</h4>
  <p>Воспользуйтесь <a href="https://awishlist.herokuapp.com/login.php ">входом в систему</a> или <a href="https://awishlist.herokuapp.com/signup.php ">зарегистрируйтесь</a> для получения доступа к этой странице. </p>
  <hr>
</div>

<?php endif; ?>
</body>
</html>


<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});

$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>

<script type="text/javascript">
$('#do_signup').click(function() {
	$('#do_signup').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Пожалуйста, подождите...').addClass('disabled');
});
</script>
