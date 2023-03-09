<?php
use Taskforce\Main\RatingValues;
/** @var integer $ratingValue */
/** @var string $ratingClass */
?>

<div class="stars-rating <?= $ratingClass; ?>">
<?php for ($counter = RatingValues::Min->value; $counter <= RatingValues::Max->value; $counter++): ?>
    <span <?= $counter <= $ratingValue ? 'class="fill-star"': '' ?>>&nbsp;</span>
<?php endfor; ?>
</div>