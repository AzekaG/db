<?php
require_once('./connect.php');
require_once('./Book.php');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <?php
    // через обьект pdo->query получаем обьект , который будет иметь данные нашей выборки Нашего селекта
    $stmt = $pdo->query('SELECT * FROM books');
    //fetchAll возвращает массив всего  .что будет в селекте, и дальше мы можем указать масств чего у нас будет  : в виде обьект , обьекта какогото класса, массива, ассотиативной коллекции и т.д.
    $books = $stmt->fetchAll(PDO::FETCH_CLASS, 'Book');

    // echo '<pre>' . print_r($books, true) . '</pre>';
    ?>
    <?php

    use PhpOffice\PhpSpreadsheet\IOFactory;

    if (isset($_POST['create'])) {
        $title = $_POST['title'];
        $price = $_POST['price'];


        //$pdo->query("INSERT INTO books(title,price) VALUES('$title' , $price)");


        //делаем подготовленный запрос. Переменные ??  - называется неименованые плэйсхолдеры. 

        //на етом етапе проверяется сам запрос 
        //$stmt = $pdo->prepare("INSERT INTO books(title,price) VALUES(? , ?)");
        //здесь передаем массив плэйсхолдеров в том порядке , в котором мы хотим изменять ?? .
        //$stmt->execute([$title, $price]);

        //именованые плэйсхолдеры : 
        $stmt = $pdo->prepare("INSERT INTO books(title,price) VALUES(:t , :price)");
        //здесь передаем массив плэйсхолдеров в те переменные , которые мы хотим передать ?? .
        $stmt->execute([
            't' => $title,
            'price' => $price
        ]);
        redirect('index.php');
    }

    if (isset($_POST['update'])) {
        $title = $_POST['title'];
        $price = $_POST['price'];
        $id = $_POST['id'];
        echo "<h1>$title</h1>";
        //самое главное , что при таком запросе происходит проверка передаваемых типов , ето защизает от инъекций
        $stmt = $pdo->prepare('UPDATE books SET title=? , price=? WHERE id=?');
        $stmt->execute([$title,  $price, $id]);
        redirect('index.php');
    }

    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare('DELETE FROM  books  WHERE id=?');
        $stmt->execute([$id]);
        redirect('index.php');
    }


    if (isset($_POST['xlsx'])) {


        if (file_exists('database_data.xlsx')) {
            unlink('database_data.xlsx');
        } 
            
        $spreadsheet  = new Spreadsheet();


        $sheet = $spreadsheet->getActiveSheet();


        $sheet->setCellValue('A' . (1), "Id");
        $sheet->setCellValue('B' . (1), "TItle");
        $sheet->setCellValue('C' . (1), "Price");
        $sheet->setCellValue('D' . (1), "Created_at");



        foreach ($books as $el => $data) {

            $sheet->setCellValue('A' . ($el + 2), $data->id);
            $sheet->setCellValue('B' . ($el + 2), $data->title);
            $sheet->setCellValue('C' . ($el + 2), $data->price);
            $sheet->setCellValue('D' . ($el + 2), $data->created_at);
        };

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('database_data.xlsx');
        unset($_POST['xlsx']);


        echo "<script>
        const button = document.getElementById('myButton');
         button.setAttribute('name', '');
         </script>";
        redirect('index.php');
    }

    function redirect($page)
    {
        header("Location:/$page");
        exit;
    }

    ?>








    <h1>CRUD</h1>
    <a href="./create.php">CreateBook</a>
    <table>
        <thead>
            <tr>
                <th>id</th>
                <th>Title</th>
                <th>Price</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($books as $book) :  ?>
                <tr>
                    <td><?= $book->id ?></td>
                    <td><?= $book->title ?></td>
                    <td><?= $book->getPrice() ?></td>
                    <td><?= $book->created_at ?></td>
                </tr>
                <td>
                    <a href="./edit.php?id=<?= $book->id ?>">Edit</a>
                    <form action="./index.php" method="post">
                        <input type="hidden" name="id" value="<?= $book->id ?>">
                        <button name="delete">Delete</button>
                    </form>
                </td>
            <?php endforeach ?>
        </tbody>
    </table>

    <form action="./index.php" method="post">
        <button id="myButton">Скачать в xlsx</button>
    </form>
</body>


<script>
    const button = document.getElementById('myButton');
    button.addEventListener('click', () => {
        button.setAttribute('name', 'xlsx');
    });
</script>

</html>