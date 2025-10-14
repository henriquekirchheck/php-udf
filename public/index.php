<?php $title = 'Home'; ?>
<?php require_once '../lib/layout.php'; ?>
<?php require_once '../src/models/Student.php'; ?>
<?php require_once '../src/db.php'; ?>

<?php
$student_model = new Student\Model($db);
if (isset($_POST["name"])) {
  $students = [];
} else {
  $students = $student_model->getAllStudents();
}
?>

<article>
  <header>Alunos</header>
  <form action="/" method="post">
    <fieldset role="group">
      <input type="text" placeholder="Pesquisar nome" aria-label="Nome" name="name"
        hx-post="/api/search"
        hx-trigger="keyup changed delay:500ms, search"
        hx-target="#results">
      <input type="submit" value="Pesquisar">
    </fieldset>
  </form>
  <table class="striped">
    <thead>
      <tr>
        <th scope="col">Nome Completo</th>
        <th scope="col">Data de Aniversario</th>
        <th scope="col">Endereço</th>
        <th scope="col">DDD</th>
        <th scope="col">Telefone</th>
      </tr>
    </thead>
    <tbody id="results">
      <?php foreach ($students as $student) { ?>
        <tr>
          <td><?= $student->full_name ?></td>
          <td><?= $student->birth_date ?></td>
          <td><?= $student->address ?></td>
          <td><?= $student->ddd ?></td>
          <td><?= $student->phone_number ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</article>