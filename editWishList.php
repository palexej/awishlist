<!DOCTYPE html>
<html lang="ru" dir="ltr">
<head>
	<meta charset="utf-8">


	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="/resources/demos/style.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" crossorigin="anonymous">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

	<?php
	require 'db.php';
	?>

	<style>
	body {
		background: url(images-background/bungalo.jpg) no-repeat center center fixed;
		-webkit-background-size: cover;
		-moz-background-size: cover;
		-o-background-size: cover;
		background-size: cover;
	}

	.createWishList {
		background: #87CEFA; /* Цвет фона */
		color: #fff; /* Цвет текста */
		padding: 10px; /* Поля вокруг текста */
		border-radius: 5px; /* Уголки */
	}
	</style>
	<title>Редактирование списка желаний</title>
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
		<?php
		$user_login_int=$_SESSION['logged_user']->id;
		$editWishListId=$_SESSION['editWishListId'];
		$editOnewishlist = R::load('newwishlists', $editWishListId);
		$wishlistsCount=R::count('newwishlists',"user_login_id=?",array($user_login_int));
		if ($wishlistsCount==0){
			echo 'Список желаний не найден';
		}
		else {

			$editWishListName=$editOnewishlist->wishlist_name;
			$editWishListInfo=$editOnewishlist->wishlist_info;
			$editWishListData=$editOnewishlist->wishlist_data;
			$editWishListAddress=$editOnewishlist->address;
		}
		$data = $_POST;
		//если кликнули на button
		if ( isset($data['saveWishListChanges']) )
		{
			// проверка формы на пустоту полей
			$errors = array();
			if (trim($data['wishlist_name']) == '' )//trim-обрезка ненужных пробелов
			{
				$errors[] = 'Введите название списка желаний';
			}

			if (strlen($data['wishlist_name'])>60)
			{
				$errors[] = 'Длина названия списка желаний не должна превышать 60 символов';
			}

			if ( empty($errors) )
			{

				$wishlist = R::load('newwishlists', $editWishListId);
				$wishlist->wishlist_name = $data['wishlist_name'];
				$wishlist->wishlist_info = $data['wishlist_info'];
				$wishlist->wishlist_data = $data['wishlist_data'];
				$wishlist->address = $data['address'];

				//$wishlist->wishlistsImgPath = $data['wishlistsImgPath'];
				R::store($wishlist);

				echo '<meta http-equiv="refresh" content="0;url=https://awishlist.herokuapp.com/upload.php "> ';
				unset($_SESSION['editWishListId']);
			}else
			{
				echo '
				<div class="alert alert-danger col-md-6 offset-md-3 alert-dismissible fade show" role="alert" text-center id="errors ">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<h4 class="alert-heading">Ошибка формы</h4>
				<p>'.array_shift($errors).'</p>
				</div>
				';
				//	echo '<div id="errors" style="color:red;">' .array_shift($errors). '</div><hr>';//вывод первой ошибки из массива errors
			}
		}

		?>
		<br>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-4 offset-md-4 text-center  text-center createWishList">
					<h2>Редактирование списка желаний</h2>
					<form action="https://awishlist.herokuapp.com/editWishList.php " method="post">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text bg-white"><i class="fa fa-user"></i></span>
							</div>
							<input type="text" name="wishlist_name" class="form-control" placeholder="Введите название списка желаний" value='<?php echo $editWishListName ?>'><br/>
						</div>
						<br>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text bg-white"><i class="fa fa-info"></i></span>
							</div>
							<textarea type="text" style="resize:vertical" name="wishlist_info" class="form-control" placeholder="Опишите детали мероприятия" value=''><?php 	echo $editWishListInfo; ?></textarea>
						</div>
						<br>
						<div class="input-group" >
							<div class="input-group-prepend">
								<span class="input-group-text bg-white"><i class="fa fa-calendar-alt"></i></span>
							</div>
							<input type="text" readonly  name="wishlist_data" id="datepicker" autocomplete="off" class="form-control" placeholder="Выберите дату проведения мероприятия" value='<?php echo $editWishListData ?>'>
						</div>
						<br>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text bg-white"><i class="fa fa-map-marker"></i></span>
							</div>
							<input type="text" name="address" class="form-control" placeholder="Введите адрес проведения мероприятия"  value='<?php echo $editWishListAddress ?>'><br/>
						</div>
						<br>
						<button id="do_signup" class="btn btn-info  btn-block" type="submit" name="saveWishListChanges">Сохранить изменения</button>
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
			<a class="nav-link" href="https://awishlist.herokuapp.com/logjn.php ">
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


<script type="text/javascript">

/* Локализация datepicker */
$.datepicker.regional['ru'] = {
	closeText: 'Закрыть',
	prevText: 'Предыдущий',
	nextText: 'Следующий',
	currentText: 'Сегодня',
	monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
	monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'],
	dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
	dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
	dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
	weekHeader: 'Не',
	dateFormat: 'dd.mm.yy',
	firstDay: 1,
	isRTL: false,
	showMonthAfterYear: false,
	yearSuffix: ''
};
$.datepicker.setDefaults($.datepicker.regional['ru']);
$(function(){
	$("#datepicker").datepicker({
		minDate: 0
	});
});
$('#do_signup').click(function() {
	$('#do_signup').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Пожалуйста, подождите...').addClass('disabled');
});
</script>
