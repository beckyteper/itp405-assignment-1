<?php

$host = 'itp460.usc.edu';
$database_name = 'dvd';
$username = 'student';
$password = 'ttrojan';

$pdo = new PDO("mysql:host=$host;dbname=$database_name", $username, $password);

$sql = "
  SELECT title, genre_name, format_name, rating_name
  FROM dvds
  INNER JOIN genres
  ON dvds.genre_id = genres.id
  INNER JOIN formats
  ON dvds.format_id = formats.id
  INNER JOIN ratings
  ON dvds.rating_id = ratings.id
  WHERE title LIKE ?
";

if ($_GET['dvd'] == '') :
  header('Location: index.php');
endif;

$statement = $pdo->prepare($sql);
$like = '%' . $_GET['dvd'] . '%';
$statement->bindParam(1, $like);
$statement->execute();
$dvds = $statement->fetchAll(PDO::FETCH_OBJ);

?>

<?php if ($statement->rowCount() == 0) : ?>
  <p>Nothing was found.</p>
  <a href="index.php">
    Return to search.
  </a>
<?php else: ?>
  <p>You searched for '<?=$_GET['dvd'] ?>'.</p>
  <?php foreach ($dvds as $dvd) : ?>
    <div>
      <h2><?= $dvd->title ?></h2>
      <p>Genre: <?= $dvd->genre_name ?></p>
      <p>Format: <?= $dvd->format_name ?></p>
      <p>Rating: <?= $dvd->rating_name ?></p>
      <a href="ratings.php?rating=<?= $dvd->rating_name ?>">
        View other <?= $dvd->rating_name ?> rated movies.
      </a>
    </div>
  <?php endforeach; ?>
<?php endif; ?>
