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
  function closeMenu() {
    if (!mobileMenu) return;
    mobileMenu.classList.remove('open');
    if (menuBtn) { menuBtn.setAttribute('aria-expanded', 'false'); menuBtn.focus(); }
  }
  function openMenu() {
    if (!mobileMenu) return;
    mobileMenu.classList.add('open');
    if (menuBtn) menuBtn.setAttribute('aria-expanded', 'true');
    if (menuClose) menuClose.focus();
  }
  if (menuBtn) menuBtn.addEventListener('click', openMenu);
  if (menuClose) menuClose.addEventListener('click', closeMenu);
  if (mobileMenu) $$('a', mobileMenu).forEach(function (a) { a.addEventListener('click', closeMenu); });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && mobileMenu && mobileMenu.classList.contains('open')) closeMenu();
  });

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
      Object.keys(navMap).forEach(function (k) { navMap[k].classList.remove('active'); navMap[k].removeAttribute('aria-current'); });
      if (current && navMap[current]) { navMap[current].classList.add('active'); navMap[current].setAttribute('aria-current', 'true'); }
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
        var typeSpd = 70;
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
}());
