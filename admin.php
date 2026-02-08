<?php
include 'includes/header.php';

if (!isset($_SESSION['is_admin'])) {
    header('Location: login.php');
    exit;

}

$table = $_GET['table'] ?? null;
$error = '';
$edit_id = $_GET['edit_id'] ?? null;

function tableExists($conn, $table) {
    $result = $conn->query("SHOW TABLES LIKE '" . $conn->real_escape_string($table) . "'");
    return $result && $result->num_rows > 0;
}

function getTableColumns($conn, $table) {
    $columns = [];
    $res = $conn->query("SHOW COLUMNS FROM `$table`");
    if ($res) {
        while ($col = $res->fetch_assoc()) {
            $columns[] = $col;
        }
    }
    return $columns;
}

if ($table && isset($_GET['delete_id']) && tableExists($conn, $table)) {
    $del_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM `$table` WHERE id = $del_id");
    header("Location: admin.php?table=" . urlencode($table));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_record'])) {
    $table_post = $_POST['table'] ?? '';
    if (tableExists($conn, $table_post)) {
        $columns = getTableColumns($conn, $table_post);
        $fields = [];
        $values = [];

        foreach ($columns as $col) {
            $name = $col['Field'];
            if ($col['Extra'] == 'auto_increment') continue;

            if (isset($_POST[$name])) {
                $fields[] = "`$name`";
                $values[] = "'" . $conn->real_escape_string($_POST[$name]) . "'";
            } else {
                $fields[] = "`$name`";
                $values[] = "NULL";
            }
        }
        $fields_str = implode(',', $fields);
        $values_str = implode(',', $values);

        $sql = "INSERT INTO `$table_post` ($fields_str) VALUES ($values_str)";
        if (!$conn->query($sql)) {
            $error = "Błąd dodawania rekordu: " . $conn->error;
        } else {
            header("Location: admin.php?table=" . urlencode($table_post));
            exit;
        }
    } else {
        $error = "Tabela nie istnieje.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_record'])) {
    $table_post = $_POST['table'] ?? '';
    $edit_id_post = intval($_POST['edit_id'] ?? 0);

    if (tableExists($conn, $table_post) && $edit_id_post > 0) {
        $columns = getTableColumns($conn, $table_post);
        $sets = [];

        foreach ($columns as $col) {
            $name = $col['Field'];
            if ($col['Extra'] == 'auto_increment') continue;

            if (isset($_POST[$name])) {
                $val = $conn->real_escape_string($_POST[$name]);
                $sets[] = "`$name` = '$val'";
            }
        }
        $sets_str = implode(',', $sets);
        $sql = "UPDATE `$table_post` SET $sets_str WHERE id=$edit_id_post";
        if (!$conn->query($sql)) {
            $error = "Błąd aktualizacji rekordu: " . $conn->error;
        } else {
            header("Location: admin.php?table=" . urlencode($table_post));
            exit;
        }
    } else {
        $error = "Nieprawidłowa tabela lub ID rekordu.";
    }
}

$result = $conn->query("SHOW TABLES");
$tables = [];
while ($row = $result->fetch_array()) {
    $tables[] = $row[0];
}

$edit_data = null;
$columns = [];

if ($table && tableExists($conn, $table)) {
    $columns = getTableColumns($conn, $table);
    if ($edit_id) {
        $id = intval($edit_id);
        $res = $conn->query("SELECT * FROM `$table` WHERE id=$id LIMIT 1");
        if ($res && $res->num_rows > 0) {
            $edit_data = $res->fetch_assoc();
        } else {
            $edit_data = null;
            $error = "Nie znaleziono rekordu do edycji.";
        }
    }
} else {
    $table = null;
}

?>
<h1>Panel Administratora</h1>

<nav>
    <strong>Wybierz tabelę:</strong>
    <?php foreach ($tables as $t): ?>
        <a href="?table=<?= htmlspecialchars($t) ?>"><?= htmlspecialchars($t) ?></a>
    <?php endforeach; ?>
</nav>

<?php if ($table): ?>
    <h2>Tabela: <?= htmlspecialchars($table) ?></h2>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($edit_data): ?>
        <h3>Edytuj rekord ID <?= $edit_data['id'] ?></h3>
        <form method="post" action="admin.php?table=<?= urlencode($table) ?>">
            <input type="hidden" name="table" value="<?= htmlspecialchars($table) ?>" />
            <input type="hidden" name="edit_record" value="1" />
            <input type="hidden" name="edit_id" value="<?= intval($edit_data['id']) ?>" />
            <?php foreach ($columns as $col):
                $name = $col['Field'];
                if ($col['Extra'] == 'auto_increment') continue;
                $val = $edit_data[$name] ?? '';
                ?>
                <label><?= htmlspecialchars($name) ?>:
                    <input type="text" name="<?= htmlspecialchars($name) ?>" value="<?= htmlspecialchars($val) ?>" required />
                </label>
            <?php endforeach; ?>
            <button type="submit">Zapisz zmiany</button>
            <a href="admin.php?table=<?= urlencode($table) ?>">Anuluj</a>
        </form>

    <?php else: ?>

        <?php
        $res = $conn->query("SELECT * FROM `$table` LIMIT 20");
        if ($res && $res->num_rows > 0):
        ?>
        <table>
            <tr>
                <?php foreach ($columns as $col): ?>
                    <th><?= htmlspecialchars($col['Field']) ?></th>
                <?php endforeach; ?>
                <th>Akcje</th>
            </tr>
            <?php while ($row = $res->fetch_assoc()): ?>
                <tr>
                    <?php foreach ($columns as $col):
                        $field = $col['Field'];
                        echo '<td>' . htmlspecialchars($row[$field]) . '</td>';
                    endforeach; ?>
                    <td class="actions">
                        <a href="?table=<?= urlencode($table) ?>&edit_id=<?= $row['id'] ?>">Edytuj</a>
                        <a href="?table=<?= urlencode($table) ?>&delete_id=<?= $row['id'] ?>" onclick="return confirm('Usunąć rekord?')">Usuń</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
            <p>Brak rekordów w tabeli.</p>
        <?php endif; ?>

        <h3>Dodaj nowy rekord</h3>
        <form method="post" action="admin.php?table=<?= urlencode($table) ?>">
            <input type="hidden" name="table" value="<?= htmlspecialchars($table) ?>" />
            <input type="hidden" name="add_record" value="1" />
            <?php foreach ($columns as $col):
                $name = $col['Field'];
                if ($col['Extra'] == 'auto_increment') continue;
                ?>
                <label><?= htmlspecialchars($name) ?>:
                    <input type="text" name="<?= htmlspecialchars($name) ?>" required />
                </label>
            <?php endforeach; ?>
            <button type="submit">Dodaj</button>
        </form>

    <?php endif; ?>

<?php else: ?>
    <p>Wybierz tabelę, aby zobaczyć jej zawartość i zarządzać rekordami.</p>
<?php endif;

include 'includes/footer.php';
?>