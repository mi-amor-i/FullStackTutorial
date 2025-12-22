<?php
include 'header.php';
include 'functions.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
try {
$fileName = uploadPortfolioFile($_FILES['portfolio']);
echo "<p>File uploaded: $fileName</p>";
} catch (Exception $e) {
echo "<p>Error: " . $e->getMessage() . "</p>";
}
}
?>


<form method="post" enctype="multipart/form-data">
<label>Upload Portfolio (PDF/JPG/PNG):</label>
<input type="file" name="portfolio" required><br><br>
<button type="submit">Upload</button>
</form>


<?php include 'footer.php'; ?>

