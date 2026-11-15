<?php
return [
'slug' => 'javascript', 'title' => 'JavaScript', 'icon' => '⚡', 'color' => '#f0b90b', 'level' => 'Beginner',
'description' => 'Bring pages to life. Variables, functions, arrays, objects, the DOM, events and asynchronous code.',
'lessons' => [

['title' => 'Variables and Data Types', 'try' => 1,
'content' => <<<'LESSON_END'
<p><strong>JavaScript</strong> is the programming language of the browser. Declare variables with <code>let</code> (can change) or <code>const</code> (cannot be reassigned). Avoid the old <code>var</code>.</p>
<h3>Main data types</h3>
<ul>
  <li><code>string</code> – text: <code>"Hello"</code></li>
  <li><code>number</code> – integers and decimals: <code>42</code>, <code>3.14</code></li>
  <li><code>boolean</code> – <code>true</code> or <code>false</code></li>
  <li><code>undefined</code>, <code>null</code>, <code>object</code>, <code>bigint</code>, <code>symbol</code></li>
</ul>
<p>Template literals use backticks and <code>${...}</code> to embed values in strings. Use <code>typeof</code> to inspect a type.</p>
<div class="exercise"><strong>Practice:</strong> add a variable <code>city</code> and print a sentence that uses it.</div>
LESSON_END,
'code' => <<<'CODE_END'
<!DOCTYPE html>
<html>
<body style="font-family: sans-serif">
<h3>Console output</h3>
<pre id="out"></pre>
<script>
const out = document.getElementById('out');
const log = (...a) => out.textContent += a.join(' ') + '\n';

let name = "Amina";
const age = 21;
let isStudent = true;

log(`Hello ${name}, you are ${age}.`);
log("typeof name      =", typeof name);
log("typeof age       =", typeof age);
log("typeof isStudent =", typeof isStudent);
log("10 / 4 =", 10 / 4, "| 10 % 4 =", 10 % 4);
</script>
</body>
</html>
CODE_END],

['title' => 'Functions and Control Flow', 'try' => 1,
'content' => <<<'LESSON_END'
<p>A <strong>function</strong> is a reusable block of code. Modern JavaScript often uses <em>arrow functions</em>: <code>const add = (a, b) =&gt; a + b;</code></p>
<h3>Decisions and loops</h3>
<ul>
  <li><code>if / else if / else</code> – choose between paths</li>
  <li><code>for</code> and <code>while</code> – repeat code</li>
  <li>Use <code>===</code> (strict equality) rather than <code>==</code>.</li>
</ul>
<div class="exercise"><strong>Practice:</strong> write <code>isEven(n)</code> and print whether 1 to 10 are even.</div>
LESSON_END,
'code' => <<<'CODE_END'
<!DOCTYPE html>
<html>
<body style="font-family: sans-serif">
<pre id="out"></pre>
<script>
const out = document.getElementById('out');
const log = (...a) => out.textContent += a.join(' ') + '\n';

function grade(score) {
  if (score >= 80) return "A";
  else if (score >= 60) return "B";
  return "C";
}
const square = (n) => n * n;

log("grade(85) =", grade(85));
log("grade(50) =", grade(50));
for (let i = 1; i <= 5; i++) {
  log(i + " squared is " + square(i));
}
</script>
</body>
</html>
CODE_END],

['title' => 'Arrays and Objects', 'try' => 1,
'content' => <<<'LESSON_END'
<p>An <strong>array</strong> is an ordered list: <code>[10, 20, 30]</code>. An <strong>object</strong> groups related values as key/value pairs: <code>{ name: "Amina", age: 21 }</code>.</p>
<h3>Powerful array methods</h3>
<table>
<tr><th>Method</th><th>What it does</th></tr>
<tr><td><code>push(x)</code></td><td>adds x to the end</td></tr>
<tr><td><code>map(fn)</code></td><td>returns a <em>new</em> array with each item transformed</td></tr>
<tr><td><code>filter(fn)</code></td><td>keeps only items where fn returns true</td></tr>
<tr><td><code>reduce(fn, start)</code></td><td>combines all items into one value</td></tr>
<tr><td><code>find(fn)</code></td><td>first matching item</td></tr>
</table>
<div class="exercise"><strong>Practice:</strong> use <code>filter</code> to keep only scores above 70.</div>
LESSON_END,
'code' => <<<'CODE_END'
<!DOCTYPE html>
<html>
<body style="font-family: sans-serif">
<pre id="out"></pre>
<script>
const out = document.getElementById('out');
const log = (...a) => out.textContent += a.join(' ') + '\n';

const scores = [55, 72, 90, 64];
const student = { name: "Jean", scores };

log("doubled :", scores.map(s => s * 2));
log("passed  :", scores.filter(s => s >= 60));
log("total   :", scores.reduce((sum, s) => sum + s, 0));
log("average :", student.scores.reduce((a, b) => a + b) / student.scores.length);
log(JSON.stringify(student));
</script>
</body>
</html>
CODE_END],

['title' => 'The DOM and Events', 'try' => 1,
'content' => <<<'LESSON_END'
<p>The <strong>DOM</strong> (Document Object Model) is the browser's live tree of your HTML. JavaScript can read and change it.</p>
<ul>
  <li><code>document.querySelector('#id')</code> – find an element with a CSS selector</li>
  <li><code>el.textContent = 'text'</code> – change text</li>
  <li><code>el.classList.toggle('on')</code> – add/remove a CSS class</li>
  <li><code>el.addEventListener('click', handler)</code> – react to user actions</li>
</ul>
<div class="exercise"><strong>Practice:</strong> add a "Reset" button that sets the counter back to 0.</div>
LESSON_END,
'code' => <<<'CODE_END'
<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; text-align: center; padding-top: 30px">
  <h1 id="count">0</h1>
  <button id="plus">+1</button>
  <button id="minus">−1</button>
<script>
let count = 0;
const display = document.querySelector('#count');

document.querySelector('#plus').addEventListener('click', () => {
  count++;
  display.textContent = count;
});
document.querySelector('#minus').addEventListener('click', () => {
  count--;
  display.textContent = count;
  display.style.color = count < 0 ? 'crimson' : 'black';
});
</script>
</body>
</html>
CODE_END],

['title' => 'Asynchronous JavaScript', 'try' => 1,
'content' => <<<'LESSON_END'
<p>Some tasks take time – loading data, timers, file access. JavaScript doesn't freeze while waiting; it uses <strong>promises</strong>.</p>
<ul>
  <li>A <em>Promise</em> is a value that will be available later (or fail).</li>
  <li><code>async</code> functions always return a promise.</li>
  <li><code>await</code> pauses <em>that function</em> until the promise settles.</li>
  <li><code>fetch(url)</code> requests data from a server; wrap it in <code>try/catch</code> for errors.</li>
</ul>
<p>The example simulates a slow request with <code>setTimeout</code> so it works without a network.</p>
<div class="exercise"><strong>Practice:</strong> make <code>getUser</code> reject for id 0 and handle it with <code>catch</code>.</div>
LESSON_END,
'code' => <<<'CODE_END'
<!DOCTYPE html>
<html>
<body style="font-family: sans-serif">
<pre id="out"></pre>
<script>
const out = document.getElementById('out');
const log = (...a) => out.textContent += a.join(' ') + '\n';

const wait = (ms) => new Promise(resolve => setTimeout(resolve, ms));

async function getUser(id) {
  await wait(800);                       // pretend this is a network call
  return { id, name: "User " + id };
}

async function main() {
  log("Loading...");
  try {
    const user = await getUser(7);
    log("Loaded:", JSON.stringify(user));
  } catch (err) {
    log("Error:", err.message);
  }
  log("Done.");
}
main();
log("(this line prints first!)");
</script>
</body>
</html>
CODE_END],

],
'questions' => [
 ['q' => 'Which keyword declares a variable that cannot be reassigned?', 'options' => ['var', 'let', 'const', 'final'], 'answer' => 2, 'explain' => 'const creates a binding that cannot be reassigned.'],
 ['q' => 'What does typeof "5" return?', 'options' => ['"number"', '"string"', '"undefined"', '"object"'], 'answer' => 1, 'explain' => 'Anything in quotes is a string.'],
 ['q' => 'Which array method returns a NEW array with every item transformed?', 'options' => ['forEach', 'map', 'push', 'find'], 'answer' => 1, 'explain' => 'map applies a function to each item and returns the results as a new array.'],
 ['q' => 'How do you run a function when a button is clicked?', 'options' => ["button.addEventListener('click', fn)", "button.listen(click)", "button.bind('click')", "attachClick(button)"], 'answer' => 0, 'explain' => 'addEventListener is the standard way to attach event handlers.'],
 ['q' => 'What does "await" do inside an async function?', 'options' => ['Pauses that function until the promise settles', 'Blocks the whole browser', 'Creates a new thread', 'Cancels the promise'], 'answer' => 0, 'explain' => 'await pauses only the async function; the rest of the page keeps running.'],
],
];
