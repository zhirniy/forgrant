<?php
session_start();
if (isset($_POST['product_id'])) {
	$_SESSION['product_id'] = $_POST['product_id'];
}

$product_id = $_SESSION['product_id'];
$price_period = 0;


$dbh = new PDO('mysql:host=localhost;dbname=forgrant', 'root', '21072013');
class DatePrice{
	public $id;
    public $id_product;
    public $date_on;
    public $date_off;
}
if (isset($_POST['id'])){
	$date_on = $_POST['date_on'];
	$date_off = $_POST['date_off'];
	$price = $_POST['price'];
	$id = $_POST['id'];
	$sth = $dbh->prepare("UPDATE dateprice SET date_on = '".$date_on."', date_off = '".$date_off."', price = '".$price."'  WHERE id=".$id);
	$sth->execute();
}else if(isset($_GET['del_id'])){
	$del_id = $_GET['del_id'];
	$sth = $dbh->prepare("DELETE FROM dateprice WHERE id=".$del_id);
	$sth->execute();
}else if(isset($_POST['submit_new'])){
	var_dump($_POST);
	$date_on_new = $_POST['date_on_new'];
	$date_off_new = $_POST['date_off_new'];
	$price_new = $_POST['price_new'];
	$sth = $dbh->prepare("INSERT INTO dateprice (id_product, date_on, date_off, price) VALUES ('".$product_id."','".$date_on_new."', '".$date_off_new."', '".$price_new."')");
	$sth->execute();
}else if(isset($_POST['submit_price'])){
	if($_POST['sort'] === 'min_date'){
	$date_on_period = $_POST['date_on_period'];
	$date_off_period = $_POST['date_off_period'];
	$sth = $dbh->prepare("SELECT * FROM dateprice WHERE id_product=".$product_id.' AND date_on <= "'.$_POST['date_on_period'].'" AND date_off >= "'.$_POST['date_off_period'].'" ORDER BY date_on DESC LIMIT 1');
	$sth->execute();
	$result = $sth->fetchAll();
	$price_period = $result[0][4];

	}else if($_POST['sort'] === 'min_period') {
	$price_period = "7777";
	$date_on_period = $_POST['date_on_period'];
	$date_off_period = $_POST['date_off_period'];
	$sth = $dbh->prepare("SELECT * FROM dateprice WHERE id_product=".$product_id.' AND date_on <= "'.$_POST['date_on_period'].'" AND date_off >= "'.$_POST['date_off_period'].'" ORDER BY date_off - date_on LIMIT 1');
	$sth->execute();
	$result = $sth->fetchAll();
	$price_period = $result[0][4];
	}
	
}

$sth = $dbh->prepare("SELECT * FROM dateprice WHERE id_product=".$product_id);
$sth->execute();
$result = $sth->fetchAll(PDO::FETCH_CLASS, "DatePrice");



?>
<!DOCTYPE html>
<html>
<head>
	<title>Страница товара</title>
</head>
<body>


<?php 

foreach ($result as $product) { ?>
	<form method="post" action="product.php">
		<input type="hidden" name="id" value="<?php echo $product->id; ?>">
		<input type="date" name="date_on" value="<?php echo $product->date_on; ?>">
		<input type="date" name="date_off" value="<?php echo $product->date_off; ?>">
		<input type="text" name="price" value="<?php echo $product->price; ?>">
		<input type="submit" name ="submit" value="Изменить" >
		<a href="?del_id=<?php echo $product->id; ?>"><input type="button" value="Удалить"></a>
	</form>
	<br>
<?php 
} ?>

<h4>Добавление записи:</h4>

<form method="post" action="product.php">
		<input type="date" name="date_on_new" required value="">
		<input type="date" name="date_off_new" required value="">
		<input type="number" name="price_new" required value="0">
		<input type="submit" name ="submit_new" value="Добавить" >
</form>
<br>
<br>
<h4>Цена на период:</h4>
<form method="post" action="product.php">
		<input type="date" name="date_on_period" required value="<?php echo $date_on_period; ?>">
		<input type="date" name="date_off_period" required value="<?php echo $date_off_period; ?>"">
		<select name="sort">
			<option value="min_period">Период действия</option>
			<option value="min_date">Установленная позднее</option>
		</select>
		<input type="number" name="price_period" required value="<?php echo $price_period; ?>">
		<input type="submit" name ="submit_price" value="Определить" >
</form>
<br>
<br>
<a href="/"><input type="button" value="На главную"/></a>
</body>
</html>

