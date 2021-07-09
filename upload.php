<!DOCTYPE html>
<html lang="en">
<head>
  <title>Списки желаний</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

  <?php
  require_once 'db.php';
  ?>

  <style>
  body {
    background: url(images-background/infowishlist.jpg) no-repeat center center fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
  }
  .WishList {
    background: #87CEFA; /* Цвет фона */
    color: #fff; /* Цвет текста */
    padding: 10px; /* Поля вокруг текста */
    border-radius: 5px; /* Уголки */
  }

  </style>

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
    <div class="container-fluid">
      <div class="row ">
        <div class="col-md-6 offset-md-3 ">
          <br>
          <h2 style="text-align:center">Ваши списки желаний</h2>
          <br>
          <form  action="https://awishlist.herokuapp.com/upload.php " method="post">
            <a href="https://awishlist.herokuapp.com/createWishList.php" name="createWishList" class="btn btn-success btn-block" role="button" aria-pressed="true"><i class="fa fa-fw fa-plus-square"></i>Создать новый список желаний</a>
          </form>
          <br>
          <br>


          <form action="https://awishlist.herokuapp.com/upload.php " method="POST">
            <?php

            $user_login_int=$_SESSION['logged_user']->id;
            $wishlists = R::find('newwishlists',"user_login_id=?",array($user_login_int));
            $wishlistsCount=R::count('newwishlists',"user_login_id=?",array($user_login_int));

            if ($wishlistsCount==0){
              echo '
              <div class="alert alert-info" role="alert">
              <h4 class="alert-heading">У Вас отсутствуют списки желаний</h4>
              <p>Нажмите на кнопку "Создать новый список желаний"</p>
              <hr>
              </div>
              <br>';
            }
            else {
              $i=0;
              foreach ($wishlists as $oneWishList){
                $id=$oneWishList->id;
                $showInfo= $oneWishList->wishlist_info;
                $showData=  $oneWishList->wishlist_data;
                $showAddress = $oneWishList->address;
                echo '
                <div class="card border-dark">
                <div class="card-header" style="text-align:center">
                <h5>'.$oneWishList->wishlist_name.'</h5>
                <div class="text-right">
                <div class="btn-group text-right role="group" aria-label="Basic example">
                <button id="icon" class="btn btn-outline-primary text-right copyValue"  data-toggle="tooltip" title="Нажмите, чтобы скопировать ссылку на список желаний в буфер обмена" type="button" id="getLink" name="getLink" value="https://awishlist.herokuapp.com/showUserWishlist.php?ID='.$id.'"  ><i class="fa fa-fw fa-link"></i></button>
                <button id="icon"  class="btn btn-outline-success text-right"  data-toggle="tooltip" title="Добавить желание" type="submit" name="addWishToList" value='.$id.'  ><i class="fa fa-fw fa-plus"></i></button>
                <button id="icon"  class="btn btn-outline-primary text-right"  data-toggle="tooltip" title="Редактировать список желаний" type="submit" name="editWishList" value='.$id.'  ><i class="fa fa-fw fa-edit"></i></button>
                <button id="icon"  class="btn btn-outline-danger" type="submit" data-toggle="tooltip" title="Удалить список желаний" name="deleteWishList" value='.$id.' ><i class="fa fa-fw fa-trash-alt"></i></button>
                </div>
                </div>
                </div>
                <div class="card-body">
                <div class="panel-group">
                <div class="panel panel-default">
                <div class="panel-heading">
                <h6 class="panel-title">

                <div class="row">
                <div class="col-6 text-left">
                <a data-toggle="collapse" href="#showInfo'.$i.'">Подробнее...</a>
                </div>
                <div class="col-6  text-right">
                <a style="text-align:right" data-toggle="collapse" href="#showMyWishs'.$i.'">Посмотреть мои желания...</a>
                </div>
                </div>

                </h6>
                </div>
                <div id="showInfo'.$i.'" class="panel-collapse collapse">
                <ul class="list-group">
                <li class="list-group-item"><b>Детали мероприятия:</b> '.$showInfo.'</li>
                <li class="list-group-item"><b>Дата проведения мероприятия:</b> '.$showData.'</li>
                <li class="list-group-item"><b>Адрес проведения мероприятия:</b> '.$showAddress.'</li>

                </ul>
                </div>';
                $wishs = R::find('wishs',"wish_wishlist_id=?",array($id));
                $wishsCount = R::count('wishs',"wish_wishlist_id=?",array($id));

                if ($wishsCount==0) {
                  echo '
                  <div id="showMyWishs'.$i.'" class="panel-collapse collapse">
                  <ul class="list-group">
                  <li class="list-group-item">Кажется, у Вас нет желаний в этом списке:(</li>
                  </ul>
                  </div>
                  ';
                }
                else {
                  // echo '<div class="card-desk">';
                  foreach ($wishs as $oneWish){
                    $oneWishId=$oneWish->id;
                    $oneWishName=$oneWish->wish_name;
                    $oneWishInfo=$oneWish ->wish_info;
                    $oneWishPrice=$oneWish->wish_price;
                    $oneWishLink=$oneWish->wish_link;
                    $oneWishImg=$oneWish->wish_img_path;
                    $oneWishCurrency =$oneWish->wish_currency_type;


                    $oneWishWasTaken=$oneWish->wish_was_taken;
                    $oneWishGuestComment=$oneWish->wish_guest_comment;

                    echo '

                    <div id="showMyWishs'.$i.'" class="panel-collapse collapse">';
                    // <ul class="list-group">
                    //<img class="card-img-top  w-50 h-50 p-3" src="cataloge-images/notebook.png" alt='.$i.' >
                    echo '
                    <div class="card border-dark text-left w-100" style="  display: inline-block">';
                    if ($oneWishImg!='') {

                      echo '
                      <div class="text-center">
                      <img class="card-img-top w-50 h-50 p-2 img-thumbnail" src="'.$oneWishImg.'">
                      </div>
                      ';
                    }
                    echo'
                    <div class="card-header"><b>Название желания:</b> '.$oneWishName.'';
                    if ($oneWishWasTaken=="true")
                    {
                      echo '<i class="text-success fa fa-fw fa-check-circle" data-toggle="tooltip" data-placement="right" title="Ваше желание будет выполнено. Комментарий : '.$oneWishGuestComment.'"></i>';
                    }
                    elseif($oneWishWasTaken=="false")
                    {
                      echo '<i class="text-danger fa fa-fw fa-times-circle" data-toggle="tooltip" data-placement="right" title="Пока что никто не выполнил Ваше желание. Если кто-то из гостей выберет это желание, Вы увидите это здесь. "></i>';
                    }
                    echo '
                    </div>
                    <ul class="list-group list-group-flush">
                    <li class="list-group-item"><b>Информация о желании:</b> '.$oneWishInfo.'</li>
                    <li class="list-group-item"><b>Цена:</b> '.$oneWishPrice.''.$oneWishCurrency.'</li>
                    <li class="list-group-item"><b>Ссылка:</b><br> <a href='.$oneWishLink.' target="_blank">'.$oneWishLink.'</a></li>
                    </ul>


                    <div class="card-footer bg-transparent ">
                    <button class="btn btn-outline-primary btn-block" data-toggle="tooltip" data-placement="right" title="Редактировать желание: '.$oneWishName.'" type="submit" name="editMyWish" value='.$oneWishId.'  ><i class="fa fa-fw fa-list-ol"></i><i class="fa fa-fw fa-edit"></i></button>
                    <button class="btn btn-outline-danger btn-block" type="submit" data-toggle="tooltip" data-placement="right" title="Удалить желание: '.$oneWishName.'" name="deleteMyWish" value='.$oneWishId.' ><i class="fa fa-fw fa-list-ol"></i><i class="fa fa-fw fa-trash-alt"></i></button>
                    </div>
                    </div>
                    <br>

                    <br>
                    </ul>
                    </div>
                    ';

                  }
                }
                echo '

                </div>
                </div>
                </div>
                </div>
                <br>
                ';
                $i++;
              }
            }
            ?>
          </form>
        </div>
      </div>
    </body>
    </html>


    <?php
    $data = $_POST;
    if (isset( $data['deleteWishList'])) {
      $id = $data['deleteWishList'];
      $newwishlists = R::load('newwishlists', $id);//удаление элемента листа желаний по id
      R::trash($newwishlists);

      echo '<meta http-equiv="refresh" content="0">';
      unset($data['deleteWishList']);
      //обновление страницы
    }

    if (isset($data['createWishList'])) {
      $_SESSION['logged_user'];
    }

    if (isset($data['addWishToList'])) {
      $_SESSION['addWishToList']=$data['addWishToList'];
      echo '<meta http-equiv="refresh" content="0;url= https://awishlist.herokuapp.com/addWishToList.php "> ';

    }

    if (isset( $_POST['editWishList']))
    {
      $editWishListSession=$data['editWishList'];
      $_SESSION['editWishListId']=$editWishListSession;
      echo '<meta http-equiv="refresh" content="0;url=https://awishlist.herokuapp.com/editWishList.php "> ';
    }

    if (isset( $_POST['editMyWish']))
    {
      $editMyWishSession=$data['editMyWish'];
      $_SESSION['editMyWish']=$editMyWishSession;
      echo '<meta http-equiv="refresh" content="0;url= https://awishlist.herokuapp.com/editMyWishInfo.php "> ';


    }

    if (isset( $_POST['deleteMyWish']))
    {
      $id = $data['deleteMyWish'];
      $deleteTheWish = R::load('wishs', $id);//удаление элемента листа желаний по id
      $deleteImg=$deleteTheWish->wish_img_path;
      unlink($deleteImg);//удаление картинки из папки
      R::trash($deleteTheWish);
      echo '<meta http-equiv="refresh" content="0">';
      unset($data['deleteMyWish']);

    }

    ?>



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

<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});


$(".copyValue").click(function() {
  var getBntVal=$(this).val();

  var copytext2 = document.createElement('input');
  copytext2.value = getBntVal;
  document.body.appendChild(copytext2);
  copytext2.select();
  document.execCommand("copy");
  document.body.removeChild(copytext2);
  $(this).tooltip().text("Скопировано");
  setTimeout(function() {window.location.reload();}, 1000);

});
$('#icon').click(function() {
	$('#icon').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>').addClass('disabled');
});
</script>
