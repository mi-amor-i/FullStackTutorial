<?

$id=$_GET['id']??'';
if(isset($id)){
	$sql="DELETE FROM students WHERE id=?";
	$stmt=$pdo->prepare($sql);
	$stmt->execute([$id]);
	    echo "Student deleted successfully!";
} else {
    echo "Invalid ID";
}

header("Location: index.php");
    exit;

?>