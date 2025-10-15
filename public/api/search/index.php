<?php require_once '../../../src/models/Note.php'; ?>
<?php require_once '../../../src/db.php'; ?>
<?php
if (!isset($_POST["name"])) {
  die("Please add a search query");
}
$note_model = new Note\Model($db);
$notes = $note_model->search($_POST["name"]);
?>

<?php foreach ($notes as $note) { ?>
  <tr>
    <td><?= $note->id ?></td>
    <td><?= $note->title ?></td>
    <td><?= $note->updated_at ?></td>
    <td>TODO</td>
  </tr>
<?php } ?>