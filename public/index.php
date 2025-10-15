<?php $title = 'Home'; ?>
<?php require_once '../lib/layout.php'; ?>
<?php require_once '../src/models/Note.php'; ?>
<?php require_once '../src/db.php'; ?>

<?php
$note_model = new Note\Model($db);

if (isset($_POST["name"])) {
  $notes = $note_model->search($_POST["name"]);
} else {
  $notes = $note_model->getAll();
}

$fmt = new IntlDateFormatter("pt_BR");
?>

<article>
  <header>Notas</header>
  <form action="/" method="post">
    <fieldset role="group">
      <input type="text" placeholder="Pesquise por suas notas..." aria-label="Nome" name="name"
        hx-post="/api/search"
        hx-trigger="keyup changed delay:500ms, search"
        hx-target="#results">
      <input type="submit" value="Pesquisar" class="contrast">
      <a role="button" href="/add">Adicionar</a>
    </fieldset>
  </form>
  <table class="striped">
    <thead>
      <tr>
        <th scope="col">Nota</th>
        <th scope="col">Titulo</th>
        <th scope="col">Ultima atualização</th>
        <th scope="col">Ações</th>
      </tr>
    </thead>
    <tbody id="results">
      <?php foreach ($notes as $note) { ?>
        <tr>
          <td><?= $note->id ?></td>
          <td><?= $note->title ?></td>
          <td><?= $fmt->format(date_create_immutable($note->updated_at)) ?></td>
          <td>
            <ul>
              <li><a href="/modify/?id=<?= $note->id ?>">Mod</a></li>
              <li><a href="/delete/?id=<?= $note->id ?>">Del</a></li>
              <li><a href="/view/?id=<?= $note->id ?>">View</a></li>
            </ul>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</article>