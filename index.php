<?
require_once 'db.class.php';
require_once 'user.class.php';

// Pagination variables
$page = $_GET['page'] ?? 1;
$rowsPerPage = 15;

$query = new SQLQuery();
$query->table = 'tbl_persons';
$personsCount = count(DB::instance()->getRows($query));
$numOfPages = ceil($personsCount / $rowsPerPage);

$query->limit = $rowsPerPage;
$query->offset = $rowsPerPage * ($page - 1);
$persons = DB::instance()->getRows($query);

// Current user
$user = User::currentLoggedIn();
?>

<html lang="en">
<head>
    <title>Star Wars Persons</title>
</head>
<body>

<div class="user-section">
    <? if ($user === false): ?>
        Hello Guest, <a href="login.php">Login</a>
    <? else: ?>
        Hello <?= $user->username ?>, <a href="logout.php">Logout</a>
    <? endif; ?>
</div>

<div class="main-section">
    <table>
        <thead>
        <tr>
            <td>Person name</td>
            <td>View/Update</td>
            <td>Delete</td>
        </tr>
        </thead>
        <tbody>
        <? foreach ($persons as $person): ?>
            <tr>
                <td><?= $person['name'] ?></td>
                <td><a href="update.php?id=<?= $person['person_id'] ?>">View/Update</a></td>
                <td>
                    <!-- Only logged in user can delete -->
                    <? if ($user !== false): ?>
                        <a class="toggle-confirm" href="delete.php?id=<?= $person['person_id'] ?>">Delete</a>
                    <? endif; ?>
                </td>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>

    <div class="pagination">
        <? for($i=1; $i<=$numOfPages; $i++): ?>
            <a <?= $i == $page ? "class='active'" : "" ?> href="index.php?page=<?= $i ?>">
                <?= $i ?>
            </a>
        <? endfor; ?>
    </div>
</div>

<script src="jquery.js"></script>
<script src="app.js"></script>
</body>
</html>