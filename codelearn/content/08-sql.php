<?php
return [
'slug' => 'sql', 'title' => 'SQL', 'icon' => '🗄️', 'color' => '#00758f', 'level' => 'Intermediate',
'description' => 'Talk to databases. Query, insert, update and delete data, join tables and summarise results with aggregates.',
'lessons' => [

['title' => 'SELECT and WHERE',
'content' => <<<'LESSON_END'
<p><strong>SQL</strong> (Structured Query Language) is the standard language for relational databases such as MySQL, PostgreSQL, SQLite and SQL Server. Data lives in <em>tables</em> made of rows and columns.</p>
<ul>
  <li><code>SELECT columns FROM table</code> – read data</li>
  <li><code>WHERE</code> – filter rows (<code>=, &lt;&gt;, &lt;, &gt;, BETWEEN, IN, LIKE, IS NULL</code>)</li>
  <li><code>AND</code>, <code>OR</code>, <code>NOT</code> combine conditions</li>
  <li><code>ORDER BY col DESC</code> sorts; <code>LIMIT n</code> restricts the row count</li>
</ul>
<p>Try these in SQLite (<code>sqlite3 test.db</code>) or any MySQL/PostgreSQL client.</p>
<div class="exercise"><strong>Practice:</strong> list students whose name starts with "A" using <code>LIKE 'A%'</code>.</div>
LESSON_END,
'code' => <<<'CODE_END'
CREATE TABLE students (
    id     INTEGER PRIMARY KEY,
    name   TEXT NOT NULL,
    course TEXT,
    score  INTEGER
);

INSERT INTO students (name, course, score) VALUES
  ('Amina', 'PHP', 88),
  ('Jean',  'Python', 72),
  ('Eric',  'PHP', 95),
  ('Alice', 'Java', 60);

SELECT * FROM students;

SELECT name, score
FROM students
WHERE course = 'PHP' AND score >= 90
ORDER BY score DESC;

SELECT name FROM students WHERE name LIKE 'A%';
CODE_END],

['title' => 'INSERT, UPDATE and DELETE',
'content' => <<<'LESSON_END'
<p>These statements change data (often called <em>CRUD</em>: Create, Read, Update, Delete).</p>
<ul>
  <li><code>INSERT INTO table (cols) VALUES (...)</code></li>
  <li><code>UPDATE table SET col = value WHERE ...</code></li>
  <li><code>DELETE FROM table WHERE ...</code></li>
</ul>
<p><strong>Warning:</strong> an <code>UPDATE</code> or <code>DELETE</code> without a <code>WHERE</code> clause affects <em>every</em> row. Run a <code>SELECT</code> with the same WHERE first to check what will change, and use transactions (<code>BEGIN; ... COMMIT;</code>) for safety.</p>
<div class="exercise"><strong>Practice:</strong> give every student in the PHP course +5 points.</div>
LESSON_END,
'code' => <<<'CODE_END'
INSERT INTO students (name, course, score)
VALUES ('Grace', 'Python', 81);

UPDATE students
SET score = score + 5
WHERE course = 'PHP';

DELETE FROM students
WHERE score < 65;

-- Check the result
SELECT * FROM students ORDER BY id;
CODE_END],

['title' => 'JOINs',
'content' => <<<'LESSON_END'
<p>Relational databases split data across tables linked by keys. A <strong>JOIN</strong> combines rows from two tables.</p>
<table>
<tr><th>JOIN</th><th>Returns</th></tr>
<tr><td><code>INNER JOIN</code></td><td>only rows with a match in both tables</td></tr>
<tr><td><code>LEFT JOIN</code></td><td>all rows from the left table, matched rows from the right (NULL if none)</td></tr>
<tr><td><code>RIGHT JOIN</code></td><td>the reverse of LEFT JOIN</td></tr>
<tr><td><code>CROSS JOIN</code></td><td>every combination of rows</td></tr>
</table>
<p>The link is usually a <em>foreign key</em> that points to another table's primary key.</p>
<div class="exercise"><strong>Practice:</strong> use a LEFT JOIN to show courses that currently have no enrolments.</div>
LESSON_END,
'code' => <<<'CODE_END'
CREATE TABLE courses (
    id    INTEGER PRIMARY KEY,
    title TEXT NOT NULL
);
CREATE TABLE enrolments (
    id         INTEGER PRIMARY KEY,
    student    TEXT NOT NULL,
    course_id  INTEGER REFERENCES courses(id)
);

INSERT INTO courses (title) VALUES ('HTML'), ('PHP'), ('C');
INSERT INTO enrolments (student, course_id) VALUES
  ('Amina', 1), ('Jean', 2), ('Eric', 2);

-- INNER JOIN: only courses that have students
SELECT e.student, c.title
FROM enrolments e
INNER JOIN courses c ON c.id = e.course_id;

-- LEFT JOIN: all courses, even with no students
SELECT c.title, e.student
FROM courses c
LEFT JOIN enrolments e ON e.course_id = c.id;
CODE_END],

['title' => 'GROUP BY and Aggregates',
'content' => <<<'LESSON_END'
<p><strong>Aggregate functions</strong> summarise many rows into one value: <code>COUNT()</code>, <code>SUM()</code>, <code>AVG()</code>, <code>MIN()</code>, <code>MAX()</code>.</p>
<p><code>GROUP BY</code> splits rows into groups and applies the aggregate to each group. <code>WHERE</code> filters rows <em>before</em> grouping; <code>HAVING</code> filters groups <em>after</em>.</p>
<p>Order of a query: <code>SELECT … FROM … JOIN … WHERE … GROUP BY … HAVING … ORDER BY … LIMIT</code>.</p>
<div class="exercise"><strong>Practice:</strong> find the course with the highest average score.</div>
LESSON_END,
'code' => <<<'CODE_END'
SELECT COUNT(*) AS total_students, AVG(score) AS avg_score
FROM students;

SELECT course,
       COUNT(*)  AS students,
       AVG(score) AS avg_score,
       MAX(score) AS best
FROM students
GROUP BY course
HAVING COUNT(*) >= 2
ORDER BY avg_score DESC;
CODE_END],

],
'questions' => [
 ['q' => 'Which clause filters rows?', 'options' => ['WHERE', 'ORDER BY', 'GROUP BY', 'LIMIT'], 'answer' => 0, 'explain' => 'WHERE keeps only rows that satisfy a condition.'],
 ['q' => 'Which statement adds a new row to a table?', 'options' => ['ADD ROW', 'INSERT INTO', 'UPDATE', 'CREATE'], 'answer' => 1, 'explain' => 'INSERT INTO ... VALUES ... adds rows.'],
 ['q' => 'Which JOIN returns only rows that match in both tables?', 'options' => ['LEFT JOIN', 'INNER JOIN', 'FULL OUTER JOIN', 'CROSS JOIN'], 'answer' => 1, 'explain' => 'INNER JOIN drops rows without a match.'],
 ['q' => 'Which function counts rows?', 'options' => ['SUM()', 'COUNT()', 'ROWS()', 'AVG()'], 'answer' => 1, 'explain' => 'COUNT(*) returns the number of rows.'],
 ['q' => 'Which clause filters groups after GROUP BY?', 'options' => ['WHERE', 'HAVING', 'FILTER', 'LIMIT'], 'answer' => 1, 'explain' => 'HAVING works on aggregated groups; WHERE works on raw rows.'],
],
];
