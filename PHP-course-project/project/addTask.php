<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Add task</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Add task</h3></div>
                                    <div class="card-body">
                                        <div class="small mb-3 text-muted">Enter the task you want to track.</div>
                                        <?php 
                                        require_once('../session/session.php');
                                        if (!isset($_SESSION['user_id'])) {
                                            header("Location: login.php");
                                            exit;
                                        }
                                        if (isset($_SESSION['flash']['message'])) {
                                            echo '
                                            <div class="alert alert-' . $_SESSION['flash']['message']['type'] . '" role="alert">
                                                ' . $_SESSION['flash']['message']['text'] . '
                                            </div>
                                            ';
                                            unset($_SESSION['flash']['message']);
                                        }
                                        ?>
                                        <form method="post" action="./handlers/handleAddingTasks.php">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputName" type="text" placeholder="Name" name="taskName" />
                                                <label for="inputName">Name</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputDescription" type="text" placeholder="Description" name="description" />
                                                <label for="inputDescription">Description</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <select class="form-select" id="importance" name="importance">
                                                    <option>Not very important</option>
                                                    <option>Important</option>
                                                    <option>Very important</option>
                                                </select>
                                                <label for="importance">Importance</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputDate" type="date" placeholder="End date" name="endDate"/>
                                                <label for="inputDate">End date</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <button class="btn btn-primary" type="submit">Add task</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
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
    </body>
</html>
