<?php require 'includes/header.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php $sql = "
SELECT 
    f.id,
    title,
    time,
    description,
    image,
    d.name AS director,
    d.surname AS director2,
    g.name AS genre
FROM Films as f
JOIN Directors as d ON f.director = d.id
JOIN Genres as g ON f.genre = g.id
";

$result = mysqli_query($conn, $sql);?>
<section>
<?php while($movie = mysqli_fetch_assoc($result)): ?>
    <div>
        <img src="https://<?= htmlspecialchars($movie['image']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
    </div>
<?php endwhile;?>
</section>


<?php require 'includes/footer.php';?>
