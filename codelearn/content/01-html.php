<?php
return [
'slug' => 'html', 'title' => 'HTML', 'icon' => '🌐', 'color' => '#e34f26', 'level' => 'Beginner',
'description' => 'The language of the web. Learn to structure pages with headings, links, images, lists, tables and forms.',
'lessons' => [

['title' => 'What is HTML?', 'try' => 1,
'content' => <<<'LESSON_END'
<p><strong>HTML</strong> (HyperText Markup Language) describes the <em>structure</em> of a web page. Browsers read HTML and turn it into headings, paragraphs, images, links and forms.</p>
<h3>Anatomy of an element</h3>
<p>An element has an opening tag, content and a closing tag: <code>&lt;p&gt;Hello&lt;/p&gt;</code>. <em>Attributes</em> add extra information to the opening tag, e.g. <code>&lt;a href="https://example.com"&gt;</code>.</p>
<h3>A minimal page</h3>
<ul>
  <li><code>&lt;!DOCTYPE html&gt;</code> tells the browser this is an HTML5 document.</li>
  <li><code>&lt;html&gt;</code> is the root element.</li>
  <li><code>&lt;head&gt;</code> holds metadata such as the page title (not shown on the page).</li>
  <li><code>&lt;body&gt;</code> holds everything the visitor sees.</li>
</ul>
<div class="exercise"><strong>Practice:</strong> change the heading text, add a second paragraph, then press <em>Run</em>.</div>
LESSON_END,
'code' => <<<'CODE_END'
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My First Page</title>
</head>
<body>
  <h1>Hello, World!</h1>
  <p>This is my first web page.</p>
</body>
</html>
CODE_END],

['title' => 'Text, Links and Images', 'try' => 1,
'content' => <<<'LESSON_END'
<p>Headings run from <code>&lt;h1&gt;</code> (most important) to <code>&lt;h6&gt;</code>. Use <code>&lt;p&gt;</code> for paragraphs, <code>&lt;strong&gt;</code> for important text and <code>&lt;em&gt;</code> for emphasis.</p>
<h3>Links</h3>
<p>The anchor element <code>&lt;a&gt;</code> creates a link. The <code>href</code> attribute holds the destination.</p>
<h3>Images</h3>
<p><code>&lt;img&gt;</code> embeds an image. <code>src</code> is the file location and <code>alt</code> is a text description used by screen readers and shown if the image fails to load.</p>
<div class="exercise"><strong>Practice:</strong> add a link to your favourite website that opens in a new tab (<code>target="_blank"</code>).</div>
LESSON_END,
'code' => <<<'CODE_END'
<!DOCTYPE html>
<html>
<body style="font-family: sans-serif">
  <h1>About Me</h1>
  <p>I am <strong>learning</strong> to build <em>web pages</em>.</p>
  <p><a href="https://developer.mozilla.org" target="_blank">Visit MDN Web Docs</a></p>
  <img alt="A purple rectangle" width="160"
       src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='160' height='90'><rect width='160' height='90' rx='12' fill='%234f46e5'/></svg>">
</body>
</html>
CODE_END],

['title' => 'Lists and Tables', 'try' => 1,
'content' => <<<'LESSON_END'
<h3>Lists</h3>
<ul>
  <li><code>&lt;ul&gt;</code> – unordered (bulleted) list</li>
  <li><code>&lt;ol&gt;</code> – ordered (numbered) list</li>
  <li><code>&lt;li&gt;</code> – a single list item (always inside ul or ol)</li>
</ul>
<h3>Tables</h3>
<p>Use tables for tabular data only (not for page layout). <code>&lt;tr&gt;</code> is a row, <code>&lt;th&gt;</code> a header cell and <code>&lt;td&gt;</code> a data cell.</p>
<div class="exercise"><strong>Practice:</strong> add a third row to the table for a student of your own.</div>
LESSON_END,
'code' => <<<'CODE_END'
<!DOCTYPE html>
<html>
<body style="font-family: sans-serif">
  <h2>Shopping list</h2>
  <ul>
    <li>Bread</li>
    <li>Milk</li>
  </ul>
  <h2>Steps</h2>
  <ol>
    <li>Open the editor</li>
    <li>Write code</li>
    <li>Press Run</li>
  </ol>
  <table border="1" cellpadding="8" style="border-collapse: collapse">
    <tr><th>Name</th><th>Score</th></tr>
    <tr><td>Amina</td><td>92</td></tr>
    <tr><td>Jean</td><td>85</td></tr>
  </table>
</body>
</html>
CODE_END],

['title' => 'Forms and Inputs', 'try' => 1,
'content' => <<<'LESSON_END'
<p>Forms collect information from visitors. The <code>&lt;form&gt;</code> element wraps the controls; its <code>action</code> says where to send the data and <code>method</code> says how (<code>GET</code> or <code>POST</code>).</p>
<table>
<tr><th>Element</th><th>Purpose</th></tr>
<tr><td><code>&lt;input type="text"&gt;</code></td><td>single-line text</td></tr>
<tr><td><code>&lt;input type="email"&gt;</code></td><td>email address with built-in validation</td></tr>
<tr><td><code>&lt;input type="password"&gt;</code></td><td>masked text</td></tr>
<tr><td><code>&lt;select&gt;</code></td><td>drop-down list</td></tr>
<tr><td><code>&lt;textarea&gt;</code></td><td>multi-line text</td></tr>
</table>
<p>Always pair an input with a <code>&lt;label&gt;</code> – it improves accessibility. Add <code>required</code> for simple validation.</p>
<div class="exercise"><strong>Practice:</strong> add a "Phone" field using <code>type="tel"</code>.</div>
LESSON_END,
'code' => <<<'CODE_END'
<!DOCTYPE html>
<html>
<body style="font-family: sans-serif">
  <form onsubmit="event.preventDefault(); alert('Thanks, ' + this.name.value + '!')">
    <p><label>Name <input type="text" name="name" required></label></p>
    <p><label>Email <input type="email" name="email" required></label></p>
    <p><label>Course
      <select name="course">
        <option>HTML</option>
        <option>CSS</option>
        <option>JavaScript</option>
      </select></label></p>
    <p><label>Message<br><textarea name="msg" rows="3" cols="30"></textarea></label></p>
    <button type="submit">Send</button>
  </form>
</body>
</html>
CODE_END],

['title' => 'Semantic HTML', 'try' => 1,
'content' => <<<'LESSON_END'
<p><strong>Semantic</strong> elements describe their meaning, which helps search engines, screen readers and other developers.</p>
<ul>
  <li><code>&lt;header&gt;</code> – introductory content or logo</li>
  <li><code>&lt;nav&gt;</code> – main navigation links</li>
  <li><code>&lt;main&gt;</code> – the unique main content (use once)</li>
  <li><code>&lt;section&gt;</code> / <code>&lt;article&gt;</code> – thematic groups / self-contained items</li>
  <li><code>&lt;aside&gt;</code> – side content</li>
  <li><code>&lt;footer&gt;</code> – footer information</li>
</ul>
<p>Prefer these over a pile of anonymous <code>&lt;div&gt;</code> elements.</p>
<div class="exercise"><strong>Practice:</strong> add a second <code>&lt;article&gt;</code> inside <code>&lt;main&gt;</code>.</div>
LESSON_END,
'code' => <<<'CODE_END'
<!DOCTYPE html>
<html lang="en">
<body style="font-family: sans-serif; max-width: 560px; margin: auto">
  <header>
    <h1>My Blog</h1>
    <nav><a href="#">Home</a> | <a href="#">About</a> | <a href="#">Contact</a></nav>
  </header>
  <main>
    <article>
      <h2>Why semantic HTML matters</h2>
      <p>Meaningful tags make pages easier to read, index and navigate.</p>
    </article>
  </main>
  <footer><small>© 2026 My Blog</small></footer>
</body>
</html>
CODE_END],

],
'questions' => [
 ['q' => 'Which tag defines the most important (largest) heading?', 'options' => ['<h6>', '<head>', '<h1>', '<header>'], 'answer' => 2, 'explain' => '<h1> is the top-level heading; <h6> is the least important.'],
 ['q' => 'Which attribute provides alternative text for an image?', 'options' => ['src', 'alt', 'title', 'href'], 'answer' => 1, 'explain' => 'alt describes the image for screen readers and when the image cannot load.'],
 ['q' => 'Which element creates an ordered (numbered) list?', 'options' => ['<ul>', '<ol>', '<li>', '<dl>'], 'answer' => 1, 'explain' => '<ol> is ordered, <ul> is unordered, <li> is just an item.'],
 ['q' => 'Which input type hides the characters the user types?', 'options' => ['text', 'hidden', 'password', 'secret'], 'answer' => 2, 'explain' => 'type="password" masks the characters.'],
 ['q' => 'Which element is intended for the main navigation links of a page?', 'options' => ['<div class="nav">', '<nav>', '<menu>', '<links>'], 'answer' => 1, 'explain' => '<nav> is the semantic element for major navigation blocks.'],
],
];
