<?php
$base = '../';
require_once __DIR__ . '/../includes/functions.php';
$admin = require_admin($base);

$selected = (int) ($_GET['course'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    $cid    = (int) ($_POST['course_id'] ?? 0);

    switch ($action) {
        case 'add_course':
            $slug  = strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($_POST['slug'] ?? '')));
            $slug  = trim($slug, '-');
            $title = trim($_POST['title'] ?? '');
            $color = preg_match('/^#[0-9a-fA-F]{6}$/', $_POST['color'] ?? '') ? $_POST['color'] : '#4f46e5';
            $level = in_array($_POST['level'] ?? '', ['Beginner', 'Intermediate', 'Advanced'], true) ? $_POST['level'] : 'Beginner';
            if ($slug === '' || $title === '') {
                flash('err', 'Slug and title are required.');
            } elseif (q('SELECT 1 FROM courses WHERE slug = ?', [$slug])->fetch()) {
                flash('err', 'A course with that slug already exists.');
            } else {
                $sort = (int) q('SELECT COALESCE(MAX(sort), 0) + 1 FROM courses')->fetchColumn();
                q('INSERT INTO courses (slug, title, icon, color, level, description, sort) VALUES (?,?,?,?,?,?,?)',
                  [$slug, $title, trim($_POST['icon'] ?? '') ?: '📘', $color, $level, trim($_POST['description'] ?? ''), $sort]);
                $selected = (int) db()->lastInsertId();
                flash('ok', 'Course created. Now add lessons and quiz questions.');
            }
            break;

        case 'delete_course':
            q('DELETE FROM courses WHERE id = ?', [$cid]);
            flash('ok', 'Course deleted.');
            $selected = 0;
            break;

        case 'add_lesson':
            $title = trim($_POST['title'] ?? '');
            if ($title === '' || !$cid) {
                flash('err', 'Lesson title is required.');
            } else {
                $pos = (int) q('SELECT COALESCE(MAX(position), 0) + 1 FROM lessons WHERE course_id = ?', [$cid])->fetchColumn();
                q('INSERT INTO lessons (course_id, title, content, code, try_it, position) VALUES (?,?,?,?,?,?)',
                  [$cid, $title, $_POST['content'] ?? '', $_POST['code'] ?? '', empty($_POST['try_it']) ? 0 : 1, $pos]);
                flash('ok', 'Lesson added.');
            }
            $selected = $cid;
            break;

        case 'delete_lesson':
            q('DELETE FROM lessons WHERE id = ?', [(int) ($_POST['id'] ?? 0)]);
            flash('ok', 'Lesson deleted.');
            $selected = $cid;
            break;

        case 'add_question':
            $opts = [trim($_POST['a'] ?? ''), trim($_POST['b'] ?? ''), trim($_POST['c'] ?? ''), trim($_POST['d'] ?? '')];
            $ans  = (int) ($_POST['answer'] ?? 0);
            if (trim($_POST['question'] ?? '') === '' || in_array('', $opts, true) || $ans < 0 || $ans > 3) {
                flash('err', 'Question and all four options are required.');
            } else {
                q('INSERT INTO questions (course_id, question, opt_a, opt_b, opt_c, opt_d, answer, explanation) VALUES (?,?,?,?,?,?,?,?)',
                  [$cid, trim($_POST['question']), $opts[0], $opts[1], $opts[2], $opts[3], $ans, trim($_POST['explanation'] ?? '')]);
                flash('ok', 'Question added.');
            }
            $selected = $cid;
            break;

        case 'delete_question':
            q('DELETE FROM questions WHERE id = ?', [(int) ($_POST['id'] ?? 0)]);
            flash('ok', 'Question deleted.');
            $selected = $cid;
            break;
    }
    redirect('courses.php' . ($selected ? '?course=' . $selected : ''));
}

$courses = q('SELECT c.*,
    (SELECT COUNT(*) FROM lessons   WHERE course_id = c.id) AS lc,
    (SELECT COUNT(*) FROM questions WHERE course_id = c.id) AS qc,
    (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) AS ec
  FROM courses c ORDER BY sort, id')->fetchAll();

$course = $selected ? q('SELECT * FROM courses WHERE id = ?', [$selected])->fetch() : null;
$lessons = $questions = [];
if ($course) {
    $lessons   = course_lessons((int) $course['id']);
    $questions = q('SELECT * FROM questions WHERE course_id = ? ORDER BY id', [$course['id']])->fetchAll();
}

$pageTitle = 'Manage courses';
include __DIR__ . '/../includes/header.php';
?>
<main class="container">
  <p><a href="index.php">← Admin</a></p>
  <h1>Courses</h1>

  <div class="table-wrap"><table class="table">
    <tr><th>Course</th><th>Level</th><th>Lessons</th><th>Questions</th><th>Students</th><th></th></tr>
    <?php foreach ($courses as $c): ?>
      <tr>
        <td><?= h($c['icon']) ?> <strong><?= h($c['title']) ?></strong> <span class="muted">/<?= h($c['slug']) ?></span></td>
        <td><?= h($c['level']) ?></td><td><?= (int) $c['lc'] ?></td><td><?= (int) $c['qc'] ?></td><td><?= (int) $c['ec'] ?></td>
        <td><div class="row-actions">
          <a class="btn small" href="courses.php?course=<?= (int) $c['id'] ?>#editor">Edit content</a>
          <form method="post" class="inline" onsubmit="return confirm('Delete this course, its lessons, quiz and all student progress?')">
            <?= csrf_field() ?><input type="hidden" name="action" value="delete_course"><input type="hidden" name="course_id" value="<?= (int) $c['id'] ?>">
            <button class="btn small danger">Delete</button></form>
        </div></td>
      </tr>
    <?php endforeach; ?>
  </table></div>

  <details class="card" style="margin-top:24px" <?= $course ? '' : 'open' ?>>
    <summary>➕ Add a new course</summary>
    <form method="post">
      <?= csrf_field() ?><input type="hidden" name="action" value="add_course">
      <label>Title</label><input type="text" name="title" required placeholder="e.g. TypeScript">
      <label>Slug (used in the URL)</label><input type="text" name="slug" required placeholder="typescript">
      <label>Icon (emoji)</label><input type="text" name="icon" value="📘" maxlength="4">
      <label>Colour</label><input type="text" name="color" value="#4f46e5" pattern="#[0-9a-fA-F]{6}">
      <label>Level</label><select name="level"><option>Beginner</option><option>Intermediate</option><option>Advanced</option></select>
      <label>Description</label><textarea class="input" name="description" style="font-family:inherit"></textarea>
      <p><button class="btn">Create course</button></p>
    </form>
  </details>

  <?php if ($course): $cid = (int) $course['id']; ?>
  <section id="editor" style="margin-top:34px">
    <h2><?= h($course['icon']) ?> <?= h($course['title']) ?> – content</h2>

    <h3>Lessons (<?= count($lessons) ?>)</h3>
    <div class="table-wrap"><table class="table">
      <tr><th>#</th><th>Title</th><th>Live editor</th><th></th></tr>
      <?php foreach ($lessons as $i => $l): ?>
        <tr><td><?= $i + 1 ?></td><td><a href="../lesson.php?id=<?= (int) $l['id'] ?>"><?= h($l['title']) ?></a></td><td><?= $l['try_it'] ? 'Yes' : 'No' ?></td>
          <td><form method="post" class="inline" onsubmit="return confirm('Delete this lesson?')"><?= csrf_field() ?>
            <input type="hidden" name="action" value="delete_lesson"><input type="hidden" name="course_id" value="<?= $cid ?>"><input type="hidden" name="id" value="<?= (int) $l['id'] ?>">
            <button class="btn small danger">Delete</button></form></td></tr>
      <?php endforeach; ?>
    </table></div>

    <details class="card" style="margin-top:16px">
      <summary>➕ Add lesson</summary>
      <form method="post"><?= csrf_field() ?>
        <input type="hidden" name="action" value="add_lesson"><input type="hidden" name="course_id" value="<?= $cid ?>">
        <label>Title</label><input type="text" name="title" required>
        <label>Lesson text (HTML allowed)</label><textarea class="input" name="content" style="min-height:160px"><p>Explain the concept here.</p></textarea>
        <label>Code example</label><textarea class="input" name="code" style="min-height:160px"></textarea>
        <label><input type="checkbox" name="try_it" value="1"> Run in the browser (code is a complete HTML page – for HTML/CSS/JS lessons)</label>
        <p><button class="btn">Add lesson</button></p>
      </form>
    </details>

    <h3 style="margin-top:30px">Quiz questions (<?= count($questions) ?>)</h3>
    <div class="table-wrap"><table class="table">
      <tr><th>Question</th><th>Correct</th><th></th></tr>
      <?php foreach ($questions as $qu): $o = [$qu['opt_a'], $qu['opt_b'], $qu['opt_c'], $qu['opt_d']]; ?>
        <tr><td><?= h($qu['question']) ?></td><td><?= ['A', 'B', 'C', 'D'][(int) $qu['answer']] ?>. <?= h($o[(int) $qu['answer']]) ?></td>
          <td><form method="post" class="inline" onsubmit="return confirm('Delete this question?')"><?= csrf_field() ?>
            <input type="hidden" name="action" value="delete_question"><input type="hidden" name="course_id" value="<?= $cid ?>"><input type="hidden" name="id" value="<?= (int) $qu['id'] ?>">
            <button class="btn small danger">Delete</button></form></td></tr>
      <?php endforeach; ?>
    </table></div>

    <details class="card" style="margin-top:16px">
      <summary>➕ Add quiz question</summary>
      <form method="post"><?= csrf_field() ?>
        <input type="hidden" name="action" value="add_question"><input type="hidden" name="course_id" value="<?= $cid ?>">
        <label>Question</label><input type="text" name="question" required>
        <label>Option A</label><input type="text" name="a" required>
        <label>Option B</label><input type="text" name="b" required>
        <label>Option C</label><input type="text" name="c" required>
        <label>Option D</label><input type="text" name="d" required>
        <label>Correct answer</label><select name="answer"><option value="0">A</option><option value="1">B</option><option value="2">C</option><option value="3">D</option></select>
        <label>Explanation (shown after the quiz)</label><input type="text" name="explanation">
        <p><button class="btn">Add question</button></p>
      </form>
    </details>
  </section>
  <?php endif; ?>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
