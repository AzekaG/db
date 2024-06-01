<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>

<body>
    <?php
    require_once './connect.php';

    $stmt = $pdo->prepare('SELECT * FROM books WHERE id=?');
    $stmt->execute([$_GET['id']]);
    $book =  $stmt->fetch(PDO::FETCH_OBJ);
    

    ?>


    <h1>Edit Book</h1>
    <form action="./index.php" method="post">
        <div>
            <label for="">Title</label>
            <input type="text" name="title" value="<?= $book->title ?>">
            <input type="hidden" name="id" value="<?= $book->id ?>">
        </div>
        <div>
            <label for="">Price</label>
            <input type="number" name="price" value="5">
        </div>
        <button name="update">Save</button>

    </form>







    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>

</html>