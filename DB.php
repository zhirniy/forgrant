<?php
$dbh = new PDO('mysql:host=localhost;dbname=forgrant', 'root', '21072013');
class Product{
	public $id;
    public $descriptions;
}
$sth = $dbh->prepare("SELECT id, descriptions FROM product");
$sth->execute();

$result = $sth->fetchAll(PDO::FETCH_CLASS, "Product");

?>