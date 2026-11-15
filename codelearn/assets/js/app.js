(function () {
  'use strict';
  var root = document.documentElement;

  /* dark / light theme */
  var themeBtn = document.getElementById('themeToggle');
  if (themeBtn) {
    themeBtn.addEventListener('click', function () {
      var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      root.setAttribute('data-theme', next);
      try { localStorage.setItem('theme', next); } catch (e) {}
    });
  }

  /* mobile menu */
  var navBtn = document.getElementById('navToggle');
  var navLinks = document.getElementById('navLinks');
  if (navBtn && navLinks) {
    navBtn.addEventListener('click', function () { navLinks.classList.toggle('open'); });
  }

  /* copy-to-clipboard buttons */
  document.querySelectorAll('[data-copy]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var target = document.querySelector(btn.getAttribute('data-copy'));
      if (!target) return;
      var text = target.innerText;
      var done = function () {
        var old = btn.textContent;
        btn.textContent = 'Copied!';
        setTimeout(function () { btn.textContent = old; }, 1400);
      };
      if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(done);
      } else {
        var ta = document.createElement('textarea');
        ta.value = text; document.body.appendChild(ta); ta.select();
        document.execCommand('copy'); document.body.removeChild(ta); done();
      }
    });
  });

  /* course search + level filter on the home page */
  var search = document.getElementById('courseSearch');
  var level = document.getElementById('levelFilter');
  var cards = document.querySelectorAll('[data-course]');
  function applyFilter() {
    var term = (search ? search.value : '').toLowerCase().trim();
    var lvl = level ? level.value : '';
    cards.forEach(function (c) {
      var okText = c.getAttribute('data-course').indexOf(term) !== -1;
      var okLvl = !lvl || c.getAttribute('data-level') === lvl;
      c.style.display = okText && okLvl ? '' : 'none';
    });
  }
  if (search) search.addEventListener('input', applyFilter);
  if (level) level.addEventListener('change', applyFilter);

  /* "Try it yourself" live editor (HTML / CSS / JavaScript lessons) */
  document.querySelectorAll('[data-tryit]').forEach(function (box) {
    var editor = box.querySelector('[data-editor]');
    var frame = box.querySelector('[data-preview]');
    var original = editor.value;
    function run() { frame.srcdoc = editor.value; }
    box.querySelector('[data-run]').addEventListener('click', run);
    box.querySelector('[data-reset]').addEventListener('click', function () { editor.value = original; run(); });
    editor.addEventListener('keydown', function (e) {
      if (e.key === 'Tab') {            // insert two spaces instead of leaving the box
        e.preventDefault();
        var s = editor.selectionStart;
        editor.value = editor.value.slice(0, s) + '  ' + editor.value.slice(editor.selectionEnd);
        editor.selectionStart = editor.selectionEnd = s + 2;
      }
      if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') run();
    });
    run();
  });

  /* quiz: confirm before leaving with unanswered questions */
  var quiz = document.getElementById('quizForm');
  if (quiz) {
    quiz.addEventListener('submit', function (e) {
      var total = quiz.querySelectorAll('.question').length;
      var answered = quiz.querySelectorAll('input[type=radio]:checked').length;
      if (answered < total && !confirm('You answered ' + answered + ' of ' + total + ' questions. Submit anyway?')) {
        e.preventDefault();
      }
    });
  }
})();
