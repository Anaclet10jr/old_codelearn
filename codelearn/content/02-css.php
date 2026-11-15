<?php
return [
'slug' => 'css', 'title' => 'CSS', 'icon' => '🎨', 'color' => '#1572b6', 'level' => 'Beginner',
'description' => 'Make the web beautiful. Selectors, the box model, Flexbox, Grid and responsive design.',
'lessons' => [

['title' => 'Selectors and Properties', 'try' => 1,
'content' => <<<'LESSON_END'
<p><strong>CSS</strong> (Cascading Style Sheets) controls how HTML looks. A rule has a <em>selector</em> and a block of <em>declarations</em>:</p>
<p><code>selector { property: value; }</code></p>
<table>
<tr><th>Selector</th><th>Matches</th></tr>
<tr><td><code>p</code></td><td>every &lt;p&gt; element</td></tr>
<tr><td><code>.card</code></td><td>elements with class="card"</td></tr>
<tr><td><code>#title</code></td><td>the element with id="title"</td></tr>
<tr><td><code>nav a</code></td><td>links inside a nav</td></tr>
<tr><td><code>a:hover</code></td><td>a link while the mouse is over it</td></tr>
</table>
<div class="exercise"><strong>Practice:</strong> give the <code>.card</code> a <code>border-radius</code> of 20px and change the hover colour.</div>
LESSON_END,
'code' => <<<'CODE_END'
<!DOCTYPE html>
<html>
<head>
<style>
  body  { font-family: sans-serif; background: #f0f4ff; padding: 24px; }
  h1    { color: #4f46e5; }
  .card { background: white; padding: 16px; border-radius: 8px; }
  #note { color: crimson; font-weight: bold; }
  a:hover { color: orange; }
</style>
</head>
<body>
  <h1>Styled heading</h1>
  <div class="card">
    <p>This text is inside a card.</p>
    <p id="note">This paragraph has an id.</p>
    <a href="#">Hover over me</a>
  </div>
</body>
</html>
CODE_END],

['title' => 'The Box Model', 'try' => 1,
'content' => <<<'LESSON_END'
<p>Every element is a rectangular box made of four layers, from the inside out:</p>
<ol><li><strong>Content</strong> – text or images</li><li><strong>Padding</strong> – space between content and border</li><li><strong>Border</strong> – the edge</li><li><strong>Margin</strong> – space outside the border</li></ol>
<p>By default <code>width</code> only sets the content width. Add <code>box-sizing: border-box;</code> so padding and border are <em>included</em> in the width – it makes layouts much easier to reason about.</p>
<div class="exercise"><strong>Practice:</strong> increase the padding to 40px and watch the box grow.</div>
LESSON_END,
'code' => <<<'CODE_END'
<!DOCTYPE html>
<html>
<head>
<style>
  * { box-sizing: border-box; }
  body { font-family: sans-serif; padding: 20px; }
  .box {
    width: 260px;
    padding: 20px;
    border: 4px solid #4f46e5;
    margin: 20px 0;
    background: #eef0ff;
  }
</style>
</head>
<body>
  <div class="box">Box one – 260px wide including padding and border.</div>
  <div class="box">Box two – 20px margin separates the boxes.</div>
</body>
</html>
CODE_END],

['title' => 'Flexbox Layout', 'try' => 1,
'content' => <<<'LESSON_END'
<p><strong>Flexbox</strong> lays items out in one dimension (a row or a column). Turn it on with <code>display: flex</code> on the <em>container</em>.</p>
<ul>
  <li><code>flex-direction</code> – <code>row</code> (default) or <code>column</code></li>
  <li><code>justify-content</code> – alignment along the <em>main</em> axis (<code>center</code>, <code>space-between</code>…)</li>
  <li><code>align-items</code> – alignment along the <em>cross</em> axis</li>
  <li><code>gap</code> – space between items</li>
  <li><code>flex-wrap: wrap</code> – let items wrap onto new lines</li>
</ul>
<div class="exercise"><strong>Practice:</strong> change <code>justify-content</code> to <code>space-between</code>, then try <code>flex-direction: column</code>.</div>
LESSON_END,
'code' => <<<'CODE_END'
<!DOCTYPE html>
<html>
<head>
<style>
  body { font-family: sans-serif; padding: 20px; }
  .row {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 12px;
    height: 160px;
    background: #eef0ff;
    border-radius: 10px;
  }
  .item { background: #4f46e5; color: white; padding: 18px 26px; border-radius: 8px; }
</style>
</head>
<body>
  <div class="row">
    <div class="item">One</div>
    <div class="item">Two</div>
    <div class="item">Three</div>
  </div>
</body>
</html>
CODE_END],

['title' => 'Grid and Responsive Design', 'try' => 1,
'content' => <<<'LESSON_END'
<p><strong>CSS Grid</strong> arranges items in rows <em>and</em> columns. <code>grid-template-columns: repeat(3, 1fr)</code> creates three equal columns.</p>
<h3>Media queries</h3>
<p>A media query applies rules only when a condition is true – the foundation of <em>responsive design</em>:</p>
<p><code>@media (max-width: 600px) { ... }</code></p>
<p>Always include <code>&lt;meta name="viewport" content="width=device-width, initial-scale=1"&gt;</code> so phones render pages at the right size.</p>
<div class="exercise"><strong>Practice:</strong> make the grid 4 columns wide on large screens by adding another media query.</div>
LESSON_END,
'code' => <<<'CODE_END'
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  body { font-family: sans-serif; padding: 16px; }
  .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
  .cell { background: #7c3aed; color: white; padding: 28px; text-align: center; border-radius: 8px; }
  @media (max-width: 420px) {
    .grid { grid-template-columns: 1fr; }   /* one column on small screens */
  }
</style>
</head>
<body>
  <p>Resize the preview or your window to see the layout change.</p>
  <div class="grid">
    <div class="cell">1</div><div class="cell">2</div><div class="cell">3</div>
    <div class="cell">4</div><div class="cell">5</div><div class="cell">6</div>
  </div>
</body>
</html>
CODE_END],

],
'questions' => [
 ['q' => 'Which selector targets elements with class="card"?', 'options' => ['#card', '.card', 'card', '*card'], 'answer' => 1, 'explain' => 'A dot selects classes; a hash (#) selects ids.'],
 ['q' => 'Box model layers from the inside out are:', 'options' => ['margin, border, padding, content', 'content, padding, border, margin', 'content, border, padding, margin', 'padding, content, margin, border'], 'answer' => 1, 'explain' => 'Content sits inside padding, which sits inside the border, surrounded by margin.'],
 ['q' => 'Which flexbox property aligns items along the main axis?', 'options' => ['align-items', 'justify-content', 'flex-wrap', 'order'], 'answer' => 1, 'explain' => 'justify-content works on the main axis; align-items on the cross axis.'],
 ['q' => 'Which declaration creates three equal columns in CSS Grid?', 'options' => ['grid-template-columns: repeat(3, 1fr)', 'display: columns(3)', 'grid-columns: 3', 'flex-columns: 3'], 'answer' => 0, 'explain' => 'repeat(3, 1fr) makes three columns that share the space equally.'],
 ['q' => 'Which rule applies styles only on narrow screens?', 'options' => ['@import', '@media (max-width: 600px)', '@font-face', '@keyframes'], 'answer' => 1, 'explain' => '@media with max-width targets screens up to a certain width.'],
],
];
