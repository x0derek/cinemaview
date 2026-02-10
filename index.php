<?php require 'includes/header.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php $sql = "
SELECT f.id, title, time, description, banner, 
d.name AS director, 
d.surname AS director2, 
g.name AS genre
FROM Films as f
JOIN Directors as d ON f.director = d.id
JOIN Genres as g ON f.genre = g.id LIMIT 5
";

$sql_t = "
SELECT f.id, title, time, description, banner, cover, date,
d.name AS director, 
d.surname AS director2, 
g.name AS genre
FROM Films as f
JOIN Directors as d ON f.director = d.id
JOIN Genres as g ON f.genre = g.id
JOIN Tickets as t ON f.id = t.film
";

$result = mysqli_query($conn, $sql);?>
<section class="slider">
    <div class="slides">
        <?php while ($movie = mysqli_fetch_assoc($result)): ?>
            <div class="slide">
                <img src="https://<?= htmlspecialchars($movie['image']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
            </div>
        <?php endwhile; ?>
    </div>
</section>
<script>
const slides = document.querySelector('.slides');
const totalSlides = document.querySelectorAll('.slide').length;
let index = 0;

setInterval(() => {
    index++;
    if (index >= totalSlides) {
        index = 0;
    }
    slides.style.transform = `translateX(-${index * 100}%)`;
}, 4000);
</script>




<?php require 'includes/footer.php';?>
<?php require 'includes/header.php';?>
<?php require 'includes/footer.php';?>