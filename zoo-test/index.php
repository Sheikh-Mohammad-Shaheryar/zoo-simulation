<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('app/Animals.php');

$animals = new App\Animals();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["feed"]))
    {
        $animals->feedAnimals();
    }
    else if (isset($_POST["pass"]))
    {
        $animals->updateTimeByHour(1);
        $animals->decreaseAnimalHealth();
    }
}
else
{
    $animals->resetData();
}

$animalStatus = $animals->getAnimalStatus();
//shuffle($animalStatus);

?>

<!DOCTYPE html>

<html>

<head>
    <title>Zoo Simulator</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>

    </style>
</head>

<body>
    <div class="container-fluid">

        <div class="row">

            <div class="col-sm-12 mb-5">
                <h1 class="text-center">Zoo Simulator</h1>
            </div>

            <div class="col-sm-12">
                <form action="" method="post">
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary" name="pass">Time Passed</button>
                        <button type="submit" class="btn btn-primary" name="feed">Feed</button>
                    </div>
                </form>
            </div>

            <div class="col-sm-12">
                <div class="table-responsive-sm">
                    <table class="table table-bordered">

                        <thead class="thead-light">
                            <tr>
                                <th>Name</th>
                                <th>Health</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach($animalStatus as $Status) { ?>
                            <tr>
                                <td><?php echo $Status['name']; ?></td>
                                <td><?php echo $Status['health']; ?></td>
                                <td><?php echo ($Status['alive']) ? 'live' : 'died'; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>

                    </table>
                </div>
            </div>

        </div>

    </div>
</body>

    </html>