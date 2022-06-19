


<!-- Bootstrap core CSS -->
<?php require('dependence.php'); ?>

<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <style>
  /* Make the image fully responsive */
  .carousel-inner img {
    width: 100%;
    height: 95%;
  }

  </style>
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
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
  <?php else :?>
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
<?php endif; ?>

<br>
<br>

 <div id="carousel" class="carousel slide carousel-fade d-inline-block" data-ride="carousel">
  <!-- Индикаторы -->
  <ol class="carousel-indicators">
    <li data-target="#carousel" data-slide-to="0" class="active"></li>
    <li data-target="#carousel" data-slide-to="1"></li>
    <li data-target="#carousel" data-slide-to="2"></li>
  </ol>
  <div class="carousel-inner ">
    <div class="carousel-item active">
      <div class="d-flex min-vh-100">
                
      <img class="img-fluid" src="infoPage/infoPage-newyear.jpg" alt="Новый год картинка">
      <div class="carousel-caption text-left">
        <h5>Впервые здесь?</h5>
        <p> Зарегистрируйтесь в системе, чтобы создать свой первый список желаний</p>
        <a href="https://awishlist.herokuapp.com/signup.php">
          <button type="button" class="btn btn-info" name="button">Зарегистрироваться</button>
        </a>
      </div>
      </div>

    </div>
    <div class="carousel-item ">
      <div class="d-flex align-items-center justify-content-center min-vh-100">
      <img class="img-fluid" src="infoPage/infoPage-salute.jpg" alt="Картинка салюта">
      <div class="carousel-caption text-left">
        <h5>Нет идей для подарков?</h5>
        <p>Советуем посмотреть товары в каталоге</p>
        <a href="https://awishlist.herokuapp.com/cataloge.php">
          <button type="button" class="btn btn-info" name="button">Перейти в каталог</button>
        </a>
      </div>
    </div>
    </div>
    <div class="carousel-item">
      <div class="d-flex align-items-center justify-content-center min-vh-100">
      <img class="img-fluid" src="infoPage/infoPage-wedding.jpg" alt="Картинка свадьбы" >
      <div class="carousel-caption text-left">
        <h5>В нашем каталоге нет нужного Вам желания?</h5>
        <p>Вы можете это исправить, добавив собственное желание в список. Это просто :)</p>
        <a href="https://awishlist.herokuapp.com/upload.php">
          <button type="button" class="btn btn-info" name="button">Добавить своё желание в список</button>
        </a>
      </div>
    </div>
    </div>
  </div>
  <!-- Элементы управления -->
  <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Предыдущий</span>
  </a>
  <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Следующий</span>
  </a>
</div>



</body>

</html>