<?php
require 'lib/auto-load.inc.php';

$db = DBFactory::getPDOConnexion();
$manager = new NewsManager($db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News HomePage</title>
</head>
<body>
    <p><a href="admin.php">Go to admin page.</a></p>    
<?php
if (isset($_GET['id'])) {
    $news = $manager->getSingle((int) $_GET['id']);

    echo "<p> By <strong>", $news->author(), '</strong>, ', $news->dateCreation(), '</p>', "\n", 
            '<h2>', $news->title(), '</h2>', "\n",
            '<p>', nl2br($news->content()), '</p>', "\n";

    if ($news->dateCreation() != $news->dateModif()) {
        echo '<p><em>Last Update: ', $news->dateModif(), '</em></p>';
    }
} 
else {
    echo '<h2>Last 5 news.</h2>';
    foreach ($manager->getList(0, 5) as $news) {
        if (strlen($news->content()) <= 200) {
            $content = $news->content();
        }
        else {
            $textBegin = substr($news->content(), 0, 200);
            $textBegin = substr($textBegin, 0, strrpos($textBegin, " ")) . "...";

            $content = $textBegin;
        }
        echo '<h4><a href="?id=', $news->id(), '">', $news->title(),
                '</a></h4>', "\n",
                '<p>', nl2br($content), '</p>';
    }
}
?>

</body>
</html>