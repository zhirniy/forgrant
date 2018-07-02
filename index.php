<?php include "DB.php"; ?>
<!DOCTYPE html>
<html>
<head>
	<title>Forgrant task</title>
</head>
<body>
<form method="post" action="product.php">
<select name="product_id">
<?php 
foreach ($result as $product) { ?>
	<option value="<?php echo $product->id; ?>"><?php echo $product->descriptions; ?>
	</option>
<?php 
} ?>
?>
</select>
<input type="submit" name ="submit" value="Выбрать" >
</form>
</body>
</html>
