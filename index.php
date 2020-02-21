<?php

try {
    $db = new PDO('mysql:dbname=todo;host=localhost;charset=utf8','root','');
} catch (PODException $e) {
    echo 'DB接続エラー:' . $e -> getMessage();
}

$errors = "";

if(isset($_POST['submit'])){
    $task = ($_POST['task']);
    if(empty($task)){
        $errors =  'タスクを入力してください';
    } else {
        $statement = $db->prepare('insert into tasks set task=?');
        $statement->execute(array($_POST['task']));
    }
}

if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
    $id = $_REQUEST['id'];
    $del = $db->prepare('delete from tasks where id=?');
    $del->execute(array($id));
    header('Location: index.php');
    exit();
}

$tasks = $db->query('select * from tasks');

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Todo list application</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="heading">
        <h2>Todo list application</h2>
    </div>
    <form action="index.php" method="post">
        <?php if(isset($errors)): ?>
            <p><?php echo $errors; ?></p>
        <?php endif; ?>

        <input type="text" name="task" class="task_input">
        <button type="submit" class="task_btn" name="submit">登録する</button>
    </form>

    <table>
        <thead>
            <th>No</th>
            <th>Task</th>
            <th>Action</th>
        </thead>
        <tbody>
        <?php $i = 1; while($task = $tasks->fetch()): ?>
         <tr>
            <td><?php echo $i; ?></td>
            <td class="task"><?php echo htmlspecialchars($task['task'], ENT_QUOTES); ?></td>
            <td class="delete">
                <a href="index.php?id=<?php echo $task['id']; ?>">x</a>
            </td>
        </tr>
        <?php $i++; endwhile; ?>
        </tbody>
    </table>
</body>
</html>

