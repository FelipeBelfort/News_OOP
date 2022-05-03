<?php
require 'lib/auto-load.inc.php';

$db = DBFactory::getPDOConnexion();
$manager = new NewsManager($db);

if (isset($_GET['modified'])) {
    $news = $manager->getSingle((int) $_['modified']);
}

if (isset($_GET['delete'])) {
    $manager->delete((int) $_GET['delete']);
    $message = 'The news had been deleted.';
}

if (isset($_POST['author'])) {
    $news = new News(
        array(
            'author' => $_POST['author'],
            'title' => $_POST['title'],
            'content' => $_POST['content']
        )
    );
}

if (isset($_POST['id'])) {
    $news->setId($_POST['id']);
}

if ($news->isValid()) {
    $manager->save($news);

    $message = $news->isNew() ? 'The news has been recorded!' : 'The news has been updated!';
} else {
    $errors = $news->errors();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration Page</title>
</head>
<body>
    
    <p><a href=".">Go to HomePage</a></p> 

    <form action="admin.php" method="post">
        <p>
<?php
if (isset($message)) {
    echo $message, '<br>';
}
?>
        <?php if (isset($errors) && in_array(News::INVALID_AUTHOR, $errors)) {
            echo "The author is not valid. <br>"; 
        } ?>
        Author : <input type="text" name="author" value="<?php if (isset($news)) echo $news->author(); ?>" /> <br>

        <?php if (isset($errors) && in_array(News::INVALID_TITLE, $errors)) echo "The title is not valid. <br>"; ?>
        Title : <input type="text" name="title" value='<?php if (isset($errors) && in_array(News::INVALID_TITLE, $errors)) echo $news->title(); ?>' /> <br>

        <?php if (isset($errors) && in_array(News::INVALID_CONTENT, $errors)) echo "The content is not valid. <br>"; ?>
        Content : <br> <textarea rows='10' cols='60' name='content'>
            <?php if (isset($news)) echo $news->content(); ?>
        </textarea> <br>

<?php
if (isset($news) && !$news->isNew()) 
{
?>
        <input type="hidden" name="id" value= "<?php echo $news->id(); ?>">
        <input type="submit" value="modified" name="modified">
<?php
} else {
?>
<input type="submit" value="add">
<?php
}
?>
        </p>
    </form>
    <p>
        We have <?php echo $manager->count(); ?> news. 
    </p>

    <table>
        <tr><th>Author</th><th>Title</th><th>Creation Date</th><th>Last Update</th><th>Action</th></tr>
<?php
foreach ($manager->getList() as $news) {
    echo '<tr><td>', $news->author(), '</td><td>', 
    $news->title(), '</td><td>',
    $news->dateCreation(), '</td><td>',
    ($news->dateCreation() == $news->dateModif() ? '-' : $news->dateModif()), 
    '</td><td><a href="?modifier=', $news->id(), 
    '">Update</a> | <a href="?delete=', $news->id(),
    '">Delete</a></td></tr>', "\n";
}
?>
    </table>
</body>
</html>