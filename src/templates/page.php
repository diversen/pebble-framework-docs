<h3>Hi <?=$username?></h3>

<p>Here is a bunch of paragraphs just for you!</p>

<?php

foreach ($paragraphs as $paragraph): ?>
<p><?=$paragraph?></p>
<hr>
<?php

endforeach;