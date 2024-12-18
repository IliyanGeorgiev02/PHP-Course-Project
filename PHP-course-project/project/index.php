<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Homepage</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <?php
            if (!isset($_SESSION['user_id'])) {
                header("Location: login.php");
                exit;
            }

            include('./handlers/db.php');

            $flash = [];
            if (isset($_SESSION['flash'])) {
                $flash = $_SESSION['flash'];
                unset($_SESSION['flash']);
            }

            $categorizedTasks = [
                'Not very important' => [],
                'Important' => [],
                'Very important' => []
            ];

$query = "SELECT *
          FROM tasks 
          WHERE completed = 0
          AND user_id = :user_id
          ORDER BY 
              CASE 
                  WHEN importance = 'Not very important' THEN 1
                  WHEN importance = 'Important' THEN 2
                  WHEN importance = 'Very important' THEN 3
                  ELSE 4
              END, 
              end_date DESC";

            $stmnt = $pdo->prepare($query);
            $stmnt->execute([':user_id' => $_SESSION['user_id']]);
            $tasks = $stmnt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($tasks as $task) {
                if (array_key_exists($task['importance'], $categorizedTasks)) {
                    $categorizedTasks[$task['importance']][] = $task;
                }
            }
            if (empty($tasks)) {
                $flash['message'] = "No tasks found for the selected criteria.";
            }
            ?>
            <script>
            $(function () {
                $(function () {
    $(document).on('click', '.delete_task', function (e) {
        e.preventDefault();

        let button = $(this);
        let taskId = button.data("task-id");

        if (!taskId) {
            console.error('Task ID is missing.');
            return;
        }

        $.ajax({
            url: './AJAX/delete_task.php',
            method: 'POST',
            data: { task_id: taskId },
            dataType: 'json', 
            success: function (response) {
                if (response.success) {
                    console.log(response.message);
                    button.closest('.list-group-item').remove();
                } else {
                    console.error('Error:', response.message);
                    alert('Error: ' + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                alert('An error occurred: ' + xhr.responseText);
            }
        });
    });
});

});

            </script>
            <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand ps-3" href="index.php">Task management app</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="./handlers/handleLogout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link" href="addTask.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Add a task
                            </a>
                            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Authentication
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="login.html">Login</a>
                                            <a class="nav-link" href="register.html">Register</a>
                                            <a class="nav-link" href="password.html">Forgot Password</a>
                                        </nav>
                                    </div>
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                        Error
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <?php if (isset($_SESSION['username'])) {
                            echo '<span>' .htmlspecialchars($_SESSION['username']) . '</span>';}?>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">All tasks</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Manage all tasks</li>
                        </ol>
                        <div class="row">
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Not very important</div>
                                    <ul class="list-group list-group-flush">
                                        <?php if (empty($categorizedTasks['Not very important'])): ?>
                                            <li class="list-group-item">No tasks available</li>
                                        <?php else: ?>
                                            <?php foreach ($categorizedTasks['Not very important'] as $task): ?>
                                            <li class="list-group-item">
                                                <a href="./task.php?task_id=<?= htmlspecialchars($task['id']) ?>" class="task-link">
                                                    <?= htmlspecialchars($task['name']) ?>
                                                </a>
                                                <br>
                                                <small class="text-muted">End Date: <?= htmlspecialchars($task['end_date']) ?></small>
                                                <div class="mt-2">
                                                    <form method="post" action="./handlers/handleCompletingTasks.php" class="d-inline">
                                                        <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                                        <button type="submit" name="action" value="complete" class="btn btn-success btn-sm">Complete</button>
                                                    </form>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>    
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">Important</div>
                                    <ul class="list-group list-group-flush">
                                        <?php if (empty($categorizedTasks['Important'])): ?>
                                            <li class="list-group-item">No tasks available</li>
                                        <?php else: ?>
                                            <?php foreach ($categorizedTasks['Important'] as $task): ?>
                                            <li class="list-group-item">
                                                <a href="./task.php?task_id=<?= htmlspecialchars($task['id']) ?>" class="task-link">
                                                    <?= htmlspecialchars($task['name']) ?>
                                                </a>
                                                <br>
                                                <small class="text-muted">End Date: <?= htmlspecialchars($task['end_date']) ?></small>
                                                <div class="mt-2">
                                                    <form method="post" action="./handlers/handleCompletingTasks.php" class="d-inline">
                                                        <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                                        <button type="submit" name="action" value="complete" class="btn btn-success btn-sm">Complete</button>
                                                    </form>
                                                
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">Very important</div>
                                    <ul class="list-group list-group-flush">
                                        <?php if (empty($categorizedTasks['Very important'])): ?>
                                            <li class="list-group-item">No tasks available</li>
                                        <?php else: ?>
                                            <?php foreach ($categorizedTasks['Very important'] as $task): ?>
                                                <li class="list-group-item">
                                                    <a href="./task.php?task_id=<?= htmlspecialchars($task['id']) ?>" class="task-link">
                                                        <?= htmlspecialchars($task['name']) ?>
                                                    </a>
                                                    <br>
                                                    <small class="text-muted">End Date: <?= htmlspecialchars($task['end_date']) ?></small>
                                                    <div class="mt-2">
                                                        <form method="post" action="./handlers/handleCompletingTasks.php" class="d-inline">
                                                            <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                                            <button type="submit" name="action" value="complete" class="btn btn-success btn-sm">Complete</button>
                                                        </form>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-success text-white mb-4">
                            <div class="card-body">Completed tasks</div>
                            <ul class="list-group list-group-flush">
                                <?php 
                                $userId = $_SESSION['user_id'];

                                $completedQuery = "SELECT * FROM tasks WHERE completed = 1 AND user_id = :user_id";
                                $completedStmt = $pdo->prepare($completedQuery);
                                $completedStmt->execute([':user_id' => $userId]);
                            
                                $completedTasks = $completedStmt->fetchAll(PDO::FETCH_ASSOC);
                                ?>
                                    <?php if (empty($completedTasks)): ?>
                                        <li class="list-group-item text-muted">No completed tasks found.</li>
                                    <?php else: ?>
                                        <?php foreach ($completedTasks as $task): ?>
                                            <li class="list-group-item">
                                            <a href="./task.php?task_id=<?= htmlspecialchars($task['id']) ?>" class="task-link">
                                                <?= htmlspecialchars($task['name'], ENT_QUOTES, 'UTF-8') ?> 
                                                </a>
                                                <br>
                                                <small class="text-muted">End Date: <?= htmlspecialchars($task['end_date'], ENT_QUOTES, 'UTF-8') ?></small>
                                                <form class="d-inline">
                                                    <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                                    <button type="button" class="delete_task btn btn-danger btn-sm" data-task-id="<?= $task['id'] ?>">Delete</button>
                                                </form>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>

                        </div>
                    </div>
                </div>

                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Generic task manager 2024</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>
