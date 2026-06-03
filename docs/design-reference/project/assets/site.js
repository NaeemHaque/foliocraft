/* ============================================================
   site.js — portfolio interactions (vanilla)
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
    try { localStorage.setItem('naeem-theme', t); } catch (e) {}
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
  var roles = [
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

  /* ---------- projects ---------- */
  var icons = {
    dash: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>',
    plug: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 2v5M15 2v5M7 7h10v4a5 5 0 0 1-10 0z"/><path d="M12 16v6"/></svg>',
    db: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v14c0 1.7 3.6 3 8 3s8-1.3 8-3V5M4 12c0 1.7 3.6 3 8 3s8-1.3 8-3"/></svg>',
    blocks: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z"/></svg>',
    rocket: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 15c-1.5 1.3-2 5-2 5s3.7-.5 5-2M12 4c4 0 7 3 7 7l-5 5-4-4 2-8z"/><path d="M9 12 4 11M12 15l1 5"/><circle cx="14.5" cy="9.5" r="1.3"/></svg>',
    globe: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.5 3.8 5.8 3.8 9s-1.3 6.5-3.8 9c-2.5-2.5-3.8-5.8-3.8-9S9.5 5.5 12 3z"/></svg>'
  };
  var iconGithub = '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.58 2 12.25c0 4.53 2.87 8.37 6.84 9.73.5.1.68-.22.68-.49v-1.7c-2.78.62-3.37-1.22-3.37-1.22-.45-1.18-1.11-1.5-1.11-1.5-.91-.64.07-.62.07-.62 1 .07 1.53 1.06 1.53 1.06.89 1.57 2.34 1.12 2.91.86.09-.66.35-1.12.63-1.38-2.22-.26-4.56-1.14-4.56-5.07 0-1.12.39-2.03 1.03-2.75-.1-.26-.45-1.3.1-2.71 0 0 .84-.28 2.75 1.05a9.3 9.3 0 0 1 5 0c1.91-1.33 2.75-1.05 2.75-1.05.55 1.41.2 2.45.1 2.71.64.72 1.03 1.63 1.03 2.75 0 3.94-2.34 4.81-4.57 5.06.36.32.68.94.68 1.9v2.82c0 .27.18.6.69.49A10.26 10.26 0 0 0 22 12.25C22 6.58 17.52 2 12 2z"/></svg>';
  var iconExt = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>';
  var iconStar = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3 2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17.8 6.6 20l1-6.1L3.2 9.5l6.1-.9z"/></svg>';
  var iconDot = '<svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="6"/></svg>';

  var projects = [
    { name: 'DevPulse', icon: 'dash', desc: 'A team productivity dashboard surfacing PRs, deploys, and CI health in real time. Laravel API, Vue front-end, MySQL.', tags: ['Laravel', 'Vue', 'MySQL', 'REST API'], filters: ['laravel', 'vue', 'mysql', 'php'], stars: '312', lang: 'PHP' },
    { name: 'WP Schema Pilot', icon: 'plug', desc: 'WordPress plugin that auto-generates schema.org structured data — Yoast/Rank Math friendly, zero config.', tags: ['WordPress', 'PHP', 'JavaScript'], filters: ['wordpress', 'php'], stars: '1.2k', lang: 'PHP' },
    { name: 'QueryLens', icon: 'db', desc: 'Slow-query analyzer for MySQL with a web UI — visualizes EXPLAIN plans and suggests indexes.', tags: ['PHP', 'MySQL', 'REST API'], filters: ['php', 'mysql'], stars: '486', lang: 'PHP' },
    { name: 'Fluent Blocks Kit', icon: 'blocks', desc: 'A collection of reusable Vue-powered blocks and components for WordPress and EmDash CMS projects.', tags: ['Vue', 'WordPress', 'Tailwind'], filters: ['vue', 'wordpress'], stars: '740', lang: 'Vue' },
    { name: 'LaravelKit Starter', icon: 'rocket', desc: 'Opinionated Laravel starter with auth, queues, and a Vue + Tailwind front-end wired for clean architecture.', tags: ['Laravel', 'Vue', 'MySQL'], filters: ['laravel', 'vue', 'mysql', 'php'], stars: '928', lang: 'PHP' },
    { name: 'Polyglot Helper', icon: 'globe', desc: 'A contributor tool for WordPress Polyglots — speeds up string review and translation suggestions.', tags: ['WordPress', 'Vue', 'i18n'], filters: ['wordpress', 'vue', 'php'], stars: '203', lang: 'JavaScript' }
  ];

  var grid = $('#projGrid');
  if (grid) {
    grid.innerHTML = projects.map(function (p) {
      return '<article class="proj-card" data-filters="' + p.filters.join(' ') + '">' +
        '<div class="proj-top">' +
          '<div class="proj-icon">' + icons[p.icon] + '</div>' +
          '<div class="proj-links">' +
            '<a href="#" aria-label="Live site" title="Live site">' + iconExt + '</a>' +
            '<a href="#" aria-label="GitHub repo" title="GitHub">' + iconGithub + '</a>' +
          '</div>' +
        '</div>' +
        '<h3>' + p.name + '</h3>' +
        '<p class="desc">' + p.desc + '</p>' +
        '<div class="proj-tags">' + p.tags.map(function (t) { return '<span class="chip">' + t + '</span>'; }).join('') + '</div>' +
        '<div class="proj-meta"><span>' + iconStar + p.stars + '</span><span>' + iconDot + p.lang + '</span></div>' +
      '</article>';
    }).join('');

    // prevent dead-link nav inside cards
    $$('.proj-links a', grid).forEach(function (a) {
      a.addEventListener('click', function (e) { e.preventDefault(); flash('Opens the live / GitHub URL stored on the project (CPT meta field).'); });
    });
  }

  /* ---------- project filter ---------- */
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
      setTimeout(function () {
        form.reset();
        $('#formOk').classList.add('show');
        btn.disabled = false;
        btn.style.opacity = '';
        setTimeout(function () { $('#formOk').classList.remove('show'); }, 6000);
      }, 650);
    });
  }
})();
