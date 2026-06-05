/* ============================================================
   main.js — FolioCraft theme interactions (vanilla)
   Ported from docs/design-reference/project/assets/site.js.
   Projects block removed (server-rendered via PHP/CPT).
   ============================================================ */
(function () {
  'use strict';

  var prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var $ = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };

  /* ---------- year ---------- */
  var yr = $('#year'); if (yr) yr.textContent = new Date().getFullYear();

  /* ---------- theme toggle ---------- */
  var toggle = $('#themeToggle');
  function setTheme(t) {
    document.documentElement.setAttribute('data-theme', t);
    try { localStorage.setItem('foliocraft-theme', t); } catch (e) {}
  }
  if (toggle) {
    toggle.addEventListener('click', function () {
      var cur = document.documentElement.getAttribute('data-theme');
      setTheme(cur === 'dark' ? 'light' : 'dark');
    });
  }

  /* ---------- nav scrolled + progress ---------- */
  var nav = $('#nav');
  var progress = $('#progress');
  function onScroll() {
    var y = window.scrollY || window.pageYOffset;
    if (nav) nav.classList.toggle('scrolled', y > 20);
    if (progress) {
      var h = document.documentElement.scrollHeight - window.innerHeight;
      progress.style.width = (h > 0 ? (y / h) * 100 : 0) + '%';
    }
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  /* ---------- mobile menu ---------- */
  var menuBtn = $('#menuBtn'), menuClose = $('#menuClose'), mobileMenu = $('#mobileMenu');
  function closeMenu() { if (mobileMenu) mobileMenu.classList.remove('open'); }
  if (menuBtn) menuBtn.addEventListener('click', function () { mobileMenu.classList.add('open'); });
  if (menuClose) menuClose.addEventListener('click', closeMenu);
  if (mobileMenu) $$('a', mobileMenu).forEach(function (a) { a.addEventListener('click', closeMenu); });

  /* ---------- résumé buttons (prototype) ---------- */
  $$('#resumeHero').forEach(function (b) {
    b.addEventListener('click', function (e) {
      e.preventDefault();
      flash('Résumé PDF would download here — wired to a Customizer setting in the theme.');
    });
  });

  /* lightweight toast */
  var toastEl;
  function flash(msg) {
    if (!toastEl) {
      toastEl = document.createElement('div');
      toastEl.style.cssText = 'position:fixed;left:50%;bottom:26px;transform:translateX(-50%) translateY(20px);z-index:300;background:var(--panel);color:var(--text);border:1px solid var(--accent-line);border-radius:12px;padding:13px 20px;font:14px var(--font-mono,monospace);box-shadow:var(--shadow);opacity:0;transition:opacity .3s,transform .3s;max-width:88vw;text-align:center;';
      document.body.appendChild(toastEl);
    }
    toastEl.textContent = msg;
    requestAnimationFrame(function () {
      toastEl.style.opacity = '1';
      toastEl.style.transform = 'translateX(-50%) translateY(0)';
    });
    clearTimeout(toastEl._t);
    toastEl._t = setTimeout(function () {
      toastEl.style.opacity = '0';
      toastEl.style.transform = 'translateX(-50%) translateY(20px)';
    }, 3200);
  }

  /* ---------- scroll reveal (rect-based, IO-independent) ---------- */
  var revealEls = $$('[data-reveal]');
  if (prefersReduced || !document.documentElement.classList.contains('reveal-on')) {
    document.documentElement.classList.remove('reveal-on');
  } else {
    var pending = revealEls.slice();
    var checkReveal = function () {
      var vh = window.innerHeight || document.documentElement.clientHeight;
      for (var i = pending.length - 1; i >= 0; i--) {
        var el = pending[i];
        var r = el.getBoundingClientRect();
        if (r.top < vh * 0.92 && r.bottom > 0) {
          el.classList.add('in');
          pending.splice(i, 1);
        }
      }
      if (!pending.length) {
        window.removeEventListener('scroll', checkReveal);
        window.removeEventListener('resize', checkReveal);
      }
    };
    window.addEventListener('scroll', checkReveal, { passive: true });
    window.addEventListener('resize', checkReveal);
    requestAnimationFrame(checkReveal);
    // safety net: revert to the always-visible base state so nothing stays hidden
    setTimeout(function () { document.documentElement.classList.remove('reveal-on'); }, 2600);
  }

  /* ---------- active nav link (scroll-based) ---------- */
  var sections = ['about', 'experience', 'work', 'opensource', 'stack', 'writing', 'contact'];
  var navMap = {};
  $$('#navLinks a').forEach(function (a) { navMap[a.getAttribute('href').slice(1)] = a; });
  var lastActive = null;
  function updateActiveNav() {
    var mid = (window.scrollY || window.pageYOffset) + window.innerHeight * 0.4;
    var current = null;
    sections.forEach(function (id) {
      var el = document.getElementById(id);
      if (el && el.offsetTop <= mid) current = id;
    });
    if (current !== lastActive) {
      Object.keys(navMap).forEach(function (k) { navMap[k].classList.remove('active'); });
      if (current && navMap[current]) navMap[current].classList.add('active');
      lastActive = current;
    }
  }
  window.addEventListener('scroll', updateActiveNav, { passive: true });
  updateActiveNav();

  /* ---------- typed role ---------- */
  var typed = $('#typed');
  var roles = (window.FolioCraftData && window.FolioCraftData.roles && window.FolioCraftData.roles.length)
    ? window.FolioCraftData.roles
    : [
        'Software Engineer',
        'Open Source Contributor',
        'WordPress Product Engineer',
        'Laravel + Vue developer',
        'Clean-architecture advocate'
      ];
  if (typed) {
    if (prefersReduced) {
      typed.textContent = roles[0];
    } else {
      var ri = 0, ci = 0, deleting = false;
      function tick() {
        var motion = document.documentElement.getAttribute('data-motion') || 'medium';
        var typeSpd = motion === 'expressive' ? 55 : motion === 'subtle' ? 90 : 70;
        var word = roles[ri];
        if (!deleting) {
          ci++;
          typed.textContent = word.slice(0, ci);
          if (ci === word.length) { deleting = true; return setTimeout(tick, 1500); }
        } else {
          ci--;
          typed.textContent = word.slice(0, ci);
          if (ci === 0) { deleting = false; ri = (ri + 1) % roles.length; }
        }
        setTimeout(tick, deleting ? 32 : typeSpd);
      }
      setTimeout(tick, 700);
    }
  }

  /* ---------- count up (scroll-triggered, IO-independent) ---------- */
  $$('[data-count]').forEach(function (el) {
    var target = parseInt(el.getAttribute('data-count'), 10);
    var suffix = el.textContent.replace(/[0-9]/g, '');
    if (prefersReduced || isNaN(target)) return;
    var done = false;
    function maybeRun() {
      if (done) return;
      var r = el.getBoundingClientRect();
      if (r.top < (window.innerHeight || 800) * 0.95 && r.bottom > 0) {
        done = true;
        window.removeEventListener('scroll', maybeRun);
        var n = 0, step = Math.max(1, Math.round(target / 24));
        var iv = setInterval(function () {
          n += step;
          if (n >= target) { n = target; clearInterval(iv); }
          el.textContent = n + suffix;
        }, 34);
      }
    }
    window.addEventListener('scroll', maybeRun, { passive: true });
    requestAnimationFrame(maybeRun);
  });

  /* ---------- project filter ---------- */
  /* grid is referenced here so the filter resolves server-rendered .proj-card elements */
  var grid = $('#projGrid');
  var filterBar = $('#filterBar');
  if (filterBar) {
    filterBar.addEventListener('click', function (e) {
      var btn = e.target.closest('.filter-btn');
      if (!btn) return;
      $$('.filter-btn', filterBar).forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');
      var f = btn.getAttribute('data-filter');
      $$('.proj-card', grid).forEach(function (card) {
        var match = f === 'all' || card.getAttribute('data-filters').split(' ').indexOf(f) !== -1;
        card.classList.toggle('is-hidden', !match);
      });
    });
  }

  /* ---------- contact form validation ---------- */
  var form = $('#contactForm');
  if (form) {
    var fName = $('#name'), fEmail = $('#email'), fMsg = $('#message');
    var emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    function validate(field, ok) {
      $('#f-' + field).classList.toggle('invalid', !ok);
      return ok;
    }
    function checkName() { return validate('name', fName.value.trim().length > 0); }
    function checkEmail() { return validate('email', emailRe.test(fEmail.value.trim())); }
    function checkMsg() { return validate('message', fMsg.value.trim().length >= 10); }

    [['name', checkName, fName], ['email', checkEmail, fEmail], ['message', checkMsg, fMsg]].forEach(function (pair) {
      pair[2].addEventListener('input', function () {
        if ($('#f-' + pair[0]).classList.contains('invalid')) pair[1]();
      });
      pair[2].addEventListener('blur', pair[1]);
    });

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var ok = checkName() & checkEmail() & checkMsg();
      if (!ok) {
        var firstBad = $('.field.invalid input, .field.invalid textarea');
        if (firstBad) firstBad.focus();
        return;
      }
      var btn = $('button[type="submit"]', form);
      btn.disabled = true;
      btn.style.opacity = '.6';
      var fd = new FormData(form);
      fd.append('foliocraft_ajax', '1');
      fetch(form.action, { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function () {
          form.reset();
          $('#formOk').classList.add('show');
          setTimeout(function () { $('#formOk').classList.remove('show'); }, 6000);
        })
        .catch(function () { form.submit(); })
        .finally(function () { btn.disabled = false; btn.style.opacity = ''; });
    });
  }
}());
