<?php

class TasksList
{
    //Путь к файлу, где будет храниться список задач
    const SAVE_FILE_NAME = 'tasks-list.json';

    //Список задач
    private $tasks = [];

    public function __constructor()
    {
        //При создании объекта списка - загружаем задачи из файла
        if (file_exists(self::SAVE_FILE_NAME)) {
            if ($jsonContent = json_decode(file_get_contents(self::SAVE_FILE_NAME), JSON_OBJECT_AS_ARRAY)) {
                $this->tasks = $jsonContent;
            }
        }
    }

    //Сохранить список задач в файл
    protected function saveList()
    {
        $jsonContent = json_encode($this->tasks);
        file_put_contents(self::SAVE_FILE_NAME, $jsonContent);
    }

    //Изменить статус задачи
    public function changeTaskStatus($id)
    {
        if (isset($this->tasks[$id])) {
            $this->tasks[$id]['done'] =  ! $this->tasks[$id]['done'];
        }
        $this->saveList();
    }
    //Удалить задачу
    public function removeTask($id)
    {
        if (isset($this->tasks[$id])) {
            unset($this->tasks[$id]);
        }

        $this->saveList();
    }

    //Добавить задачу
    public function addNewTasks($title)
    {
        $this->tasks[] = ['title' => $title, 'done' => false ];

        $this->saveList();
    }

    //Вернуть список задач
    public function getTasks()
    {
        return $this->tasks;
    }
}

$tasksList = new TasksList();
if (isset($_GET['change_status']) && isset($_GET['id'])) {
    $tasksList->changeTaskStatus($_GET['id']);
} elseif (isset($_GET['remove_task']) && isset($_GET['id'])) {
    $tasksList->removeTask($_GET['id']);
} elseif (isset($_POST['task_name'])) {
    $tasksList->addNewTasks($_POST['task_name']);
}

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List</title>
    <link rel="stylesheet" type="text/css" href="./style/todolist.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h1 class="display-6">Список моих задач: </h1>
        <table class="table table-striped table-hover align-middle text-start">
            <thead>
                <tr>
                    <th class="col"></th>
                    <th class="col-11"></th>
                    <th class="col"></th>
                </tr>
            </thead>
            <tbody>
                <!-- <tr>
                    <td>
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked>
                    </td>
                    <td>
                        <label class="form-check-label text-start" for="flexCheckDefault">Пример задачи</label>
                    </td>
                    <td>
                        <a href=""><input class="btn btn-primary btn-sm text-end" type="button" value="X"></a>
                    </td>
                </tr> -->
                <?php foreach ($tasksList->getTasks() as $key => $task) { ?>
            <tr><td><a href="/tasks_list.php?change_status&id=<?php echo $key; ?>"><input type="checkbox" class="form-check-input" <?php echo $task['done'] ? 'checked' : ''; ?>></a></td>
                <td><label class="form-check-label text-start" for="flexCheckDefault"><?php echo $task['title'];?></label></td>
            <td><a href="/tasks_list.php?remove_task&id=<?php echo $key; ?>"><input class="btn btn-primary btn-sm text-end" type="button" value="X"></a></td></tr>
                <?php  } ?>

            </tbody>
        </table>
        <form action="/tasks_list.php" method="post">
            <div class="mb-3">
                <label for="inputTask" class="form-label">Добавить задачу:</label>
                <div class="input-group">
                    <input class="form-control" type="text" id="inputTask">
                    <input class="btn btn-outline-primary" type="submit" value="Добавить">
                </div>
            </div>
        </form>
    </div>
</body>

</html>