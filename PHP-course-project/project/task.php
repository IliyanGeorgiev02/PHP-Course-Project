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
            $task = null;
            if (isset($_GET['task_id'])) {
                $taskId = $_GET['task_id'];
                $userId = $_SESSION['user_id'];
            
                $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = :task_id AND user_id = :user_id");
                $stmt->execute([':task_id' => $taskId, ':user_id' => $userId]);
                $task = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            ?>
            <script>
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
                    button.closest('.card').remove();
                } else {
                    console.error(response.message);
                    alert(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                alert('An error occurred. Please try again.');
            }
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
                        <div class="row">
                        <div class="row">   
                        <div class="container mt-4">
                        <?php if ($task): ?>
                            <div class="card">
    <div class="card-header bg-primary text-white">
        <h3><?= htmlspecialchars($task['name']) ?></h3>
    </div>
    <div class="card-body">
        <p><strong>Description:</strong> <?= htmlspecialchars($task['description'] ?? 'No description available.') ?></p>
        <p><strong>Importance:</strong> <?= htmlspecialchars($task['importance'] ?? 'Not specified') ?></p>
        <p><strong>End Date:</strong> <?= htmlspecialchars($task['end_date'] ?? 'No end date set') ?></p>
        <p><strong>Status:</strong> <?= $task['completed'] ? 'Completed' : 'Not completed' ?></p>
    </div>
    <div class="card-footer">
        <?php if (!$task['completed']): ?>
            <form method="post" action="./handlers/handleCompletingTasks.php" class="d-inline">
                <input type="hidden" name="task_id" value="<?= htmlspecialchars($task['id']) ?>">
                <button type="submit" name="action" value="complete" class="btn btn-success">
                    Mark as Complete
                </button>
            </form>
        <?php endif; ?>

        <?php if ($task['completed']): ?>
            <form method="post" action="./AJAX/delete_task.php" class="d-inline">
                <input type="hidden" name="task_id" value="<?= htmlspecialchars($task['id']) ?>">
                <button type="submit" class="btn btn-danger btn-sm">
                    Delete Task
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php else: ?>
    <div class="alert alert-danger" role="alert">
        Task not found or unauthorized access.
    </div>
<?php endif; ?>

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
