<?php
return [
'slug' => 'php', 'title' => 'PHP', 'icon' => '🐘', 'color' => '#777bb3', 'level' => 'Intermediate',
'description' => 'Server-side programming for dynamic websites: syntax, functions, arrays, forms and safe database access with PDO.',
'lessons' => [

['title' => 'PHP Syntax and Variables',
'content' => <<<'LESSON_END'
<p><strong>PHP</strong> runs on the server and generates HTML that is sent to the browser. PHP code lives between <code>&lt;?php</code> and <code>?&gt;</code> tags and every statement ends with a semicolon.</p>
<ul>
  <li>Variables start with <code>$</code>: <code>$name = "Amina";</code></li>
  <li><code>echo</code> prints output.</li>
  <li>Join strings with the dot operator <code>.</code> or use double-quoted interpolation: <code>"Hi $name"</code>.</li>
  <li>Types: string, int, float, bool, array, null.</li>
</ul>
<p>To try the code, save it as <code>hello.php</code> and run <code>php hello.php</code> or <code>php -S localhost:8000</code>.</p>
<div class="exercise"><strong>Practice:</strong> calculate the area of a circle with <code>M_PI</code> and radius 5, rounded with <code>round()</code>.</div>
LESSON_END,
'code' => <<<'CODE_END'
<?php
$name  = "Amina";
$age   = 21;
$price = 19.99;

echo "Hello, $name!\n";
echo "Next year you will be " . ($age + 1) . ".\n";
echo "Price with 18% tax: " . round($price * 1.18, 2) . "\n";

var_dump($age);          // int(21)
echo gettype($price);    // double
CODE_END],

['title' => 'Control Structures and Functions',
'content' => <<<'LESSON_END'
<p>PHP has the usual control structures: <code>if / elseif / else</code>, <code>switch</code>, <code>for</code>, <code>while</code> and <code>foreach</code>.</p>
<h3>Functions</h3>
<p>Define with <code>function</code>. Modern PHP supports <em>type declarations</em> for parameters and return values, default values and arrow functions (<code>fn($x) =&gt; $x * 2</code>).</p>
<ul>
  <li>Use <code>===</code> for strict comparison.</li>
  <li>The null coalescing operator <code>??</code> gives a default: <code>$_GET['page'] ?? 1</code>.</li>
</ul>
<div class="exercise"><strong>Practice:</strong> write a function <code>isPrime(int $n): bool</code> and list the primes below 30.</div>
LESSON_END,
'code' => <<<'CODE_END'
<?php
function grade(int $score): string {
    if ($score >= 80)      return "A";
    elseif ($score >= 60)  return "B";
    return "C";
}

function greet(string $name, string $greeting = "Hello"): string {
    return "$greeting, $name!";
}

echo greet("Jean") . "\n";
echo greet("Amina", "Welcome") . "\n";

foreach ([95, 72, 40] as $s) {
    echo "$s => " . grade($s) . "\n";
}

$double = fn($x) => $x * 2;
echo $double(21) . "\n";    // 42
CODE_END],

['title' => 'Arrays and Handling Forms',
'content' => <<<'LESSON_END'
<p>PHP arrays are both lists and dictionaries. <em>Indexed</em>: <code>[1, 2, 3]</code>. <em>Associative</em>: <code>['name' =&gt; 'Amina', 'age' =&gt; 21]</code>.</p>
<p>Useful functions: <code>count</code>, <code>array_push</code>, <code>array_map</code>, <code>array_filter</code>, <code>in_array</code>, <code>sort</code>, <code>implode</code>, <code>explode</code>.</p>
<h3>Forms</h3>
<p>Data from a form with <code>method="post"</code> arrives in <code>$_POST</code> (query-string data in <code>$_GET</code>).</p>
<p><strong>Security rule #1:</strong> never trust user input. Escape output with <code>htmlspecialchars()</code> so visitors cannot inject HTML or JavaScript (XSS).</p>
<div class="exercise"><strong>Practice:</strong> extend the form handler to validate that the email is valid using <code>filter_var($email, FILTER_VALIDATE_EMAIL)</code>.</div>
LESSON_END,
'code' => <<<'CODE_END'
<?php
$student = ['name' => 'Amina', 'scores' => [88, 92, 79]];
$avg = array_sum($student['scores']) / count($student['scores']);
echo $student['name'] . " average: " . round($avg, 1) . "\n";

// --- form handling (put this in form.php and open it in a browser) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name === '') {
        echo "Name is required";
    } else {
        echo "Hello, " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    }
}
?>
<form method="post">
    <input type="text" name="name" placeholder="Your name">
    <button>Send</button>
</form>
CODE_END],

['title' => 'Databases with PDO',
'content' => <<<'LESSON_END'
<p><strong>PDO</strong> (PHP Data Objects) is the standard way to talk to MySQL, PostgreSQL, SQLite and more with one API. <em>This very e-learning system uses PDO with SQLite.</em></p>
<h3>Prepared statements stop SQL injection</h3>
<p>Never paste user input into a query string. Use placeholders (<code>?</code> or <code>:name</code>) and pass the values separately – the database treats them strictly as data.</p>
<h3>Passwords</h3>
<p>Store passwords with <code>password_hash()</code> and check them with <code>password_verify()</code>. Never store plain text or use MD5/SHA1.</p>
<div class="exercise"><strong>Practice:</strong> add a <code>DELETE</code> statement that removes a user by id using a prepared statement.</div>
LESSON_END,
'code' => <<<'CODE_END'
<?php
$pdo = new PDO('sqlite::memory:');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec("CREATE TABLE users (id INTEGER PRIMARY KEY, name TEXT, password TEXT)");

// INSERT with a prepared statement
$stmt = $pdo->prepare("INSERT INTO users (name, password) VALUES (?, ?)");
$stmt->execute(["Amina", password_hash("secret123", PASSWORD_DEFAULT)]);

// SELECT with a named placeholder
$stmt = $pdo->prepare("SELECT * FROM users WHERE name = :name");
$stmt->execute([':name' => "Amina"]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo "Found user #{$user['id']}: {$user['name']}\n";
var_dump(password_verify("secret123", $user['password']));   // bool(true)
CODE_END],

],
'questions' => [
 ['q' => 'Which tag starts a block of PHP code?', 'options' => ['<?php', '<php>', '<script php>', '{php}'], 'answer' => 0, 'explain' => 'PHP code begins with <?php.'],
 ['q' => 'PHP variable names begin with which character?', 'options' => ['@', '$', '#', '&'], 'answer' => 1, 'explain' => 'Every PHP variable starts with a dollar sign.'],
 ['q' => 'Which superglobal holds data sent by a form using method="post"?', 'options' => ['$_GET', '$_POST', '$_FORM', '$_DATA'], 'answer' => 1, 'explain' => '$_POST contains the POST request body fields.'],
 ['q' => 'What is the best defence against SQL injection?', 'options' => ['Escape quotes by hand', 'Use prepared statements', 'Use die()', 'Use eval()'], 'answer' => 1, 'explain' => 'Prepared statements keep user data separate from the SQL command.'],
 ['q' => 'Which function makes user text safe to print inside HTML?', 'options' => ['echo', 'htmlspecialchars', 'strlen', 'trim'], 'answer' => 1, 'explain' => 'htmlspecialchars converts < > & " \' into harmless entities, preventing XSS.'],
],
];
