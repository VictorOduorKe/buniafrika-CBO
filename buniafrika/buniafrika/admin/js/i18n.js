(function () {
  'use strict';

  var LANG_KEY = 'buniafrika_i18n_lang';
  var LEGACY_LANG_KEY = 'ypl_i18n_lang';

 var LANGUAGES = [
    { code: 'en',    label: 'English',             flag: '🇬🇧' },
    { code: 'fr',    label: 'Français',            flag: '🇫🇷' },
    { code: 'sw',    label: 'Kiswahili',           flag: '🇰🇪' },
    { code: 'luo',   label: 'Luo',              flag: '🇰🇪' },
    { code: 'sn',    label: 'Shona',               flag: '🇿🇼' },
    { code: 'de',    label: 'Deutsch',             flag: '🇩🇪' },
    { code: 'es',    label: 'Español',             flag: '🇪🇸' },
    { code: 'ar',    label: 'العربية',             flag: '🇸🇦' },
    { code: 'zh-CN', label: '中文',                 flag: '🇨🇳' },
    { code: 'en-ZA', label: 'South African English', flag: '🇿🇦' },
    { code: 'nl',    label: 'Nederlands',          flag: '🇳🇱' },
    { code: 'pt',    label: 'Português',           flag: '🇵🇹' },
    { code: 'sv',    label: 'Svenska',             flag: '🇸🇪' },
    { code: 'ur',    label: 'اردو',                flag: '🇵🇰' },
    { code: 'bn',    label: 'বাংলা',               flag: '🇧🇩' },
    { code: 'ja',    label: '日本語',              flag: '🇯🇵' },
    { code: 'so',    label: 'Somali',              flag: '🇸🇴' },
    { code: 'am',    label: 'አማርኛ',              flag: '🇪🇹' },
    { code: 'it',    label: 'Italiano',            flag: '🇮🇹' },
    { code: 'ru',    label: 'Русский',             flag: '🇷🇺' },
    { code: 'ko',    label: '한국어',              flag: '🇰🇷' },
    { code: 'hr',    label: 'Hrvatski',            flag: '🇭🇷' },
    { code: 'id',    label: 'Bahasa Indonesia',    flag: '🇮🇩' },
    { code: 'yo',    label: 'Yorùbá',             flag: '🇳🇬' },
    { code: 'pa',    label: 'ਪੰਜਾਬੀ',             flag: '🇮🇳' }
];

  function getCurrentLang() {
    var m = document.cookie.match(/googtrans=\/en\/([^;]+)/);
    var savedLang = localStorage.getItem(LANG_KEY);
    if (!savedLang) {
      savedLang = localStorage.getItem(LEGACY_LANG_KEY);
      if (savedLang) {
        localStorage.setItem(LANG_KEY, savedLang);
      }
    }
    return m ? m[1] : (savedLang || 'en');
  }

  function switchLanguage(code) {
    if (code === 'en') {
      setCookie('googtrans', '', -1);
      setCookie('googtrans', '', -1, '.' + location.hostname);
      localStorage.setItem(LANG_KEY, 'en');
    } else {
      var val = '/en/' + code;
      setCookie('googtrans', val, 1);
      setCookie('googtrans', val, 1, '.' + location.hostname);
      localStorage.setItem(LANG_KEY, code);
    }
    location.reload();
  }

  function setCookie(name, value, days, domain) {
    var expires = '';
    if (days) {
      var d = new Date();
      d.setTime(d.getTime() + days * 24 * 60 * 60 * 1000);
      expires = '; expires=' + d.toUTCString();
    }
    var domainStr = domain ? '; domain=' + domain : '';
    document.cookie = name + '=' + value + expires + '; path=/' + domainStr;
  }

  function protectIcons() {
    document.querySelectorAll(
      '.material-symbols-outlined, .material-symbols-rounded, .material-icons'
    ).forEach(function (el) {
      el.classList.add('notranslate');
      el.setAttribute('translate', 'no');
    });
  }

  function translatePage() {
    if (!document.body || typeof fetch !== 'function') return;

    var lang = getCurrentLang();
    if (lang === 'en') return;

    document.documentElement.lang = lang;

    var cacheKey = 'buniafrika_i18n_cache_' + lang;
    var cache = {};
    try {
      cache = JSON.parse(localStorage.getItem(cacheKey) || '{}');
    } catch (error) {
      console.warn('Could not read saved translations.', error);
    }

    var pageRecords = [];
    var records = [];
    var walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
    var node;
    while ((node = walker.nextNode())) {
      var parent = node.parentElement;
      if (!parent || parent.closest('script,style,noscript,textarea,option,[translate="no"],.notranslate')) continue;
      var raw = node.nodeValue || '';
      var text = raw.trim();
      if (!text) continue;

      var leading = raw.match(/^\s*/)[0];
      var trailing = raw.match(/\s*$/)[0];
      var parts = splitText(text, 320);
      var record = { node: node, leading: leading, trailing: trailing, parts: [] };
      pageRecords.push(record);

      parts.forEach(function (part) {
        var translated = Object.prototype.hasOwnProperty.call(cache, part) ? cache[part] : null;
        record.parts.push(translated);
        if (translated === null) records.push({ record: record, index: record.parts.length - 1, text: part });
      });
    }

    if (!records.length) {
      applyTranslations();
      return;
    }

    var batches = [];
    var batch = [];
    var batchLength = 0;
    records.forEach(function (item) {
      var itemLength = item.text.length + 36;
      if (batch.length && batchLength + itemLength > 450) {
        batches.push(batch);
        batch = [];
        batchLength = 0;
      }
      batch.push(item);
      batchLength += itemLength;
    });
    if (batch.length) batches.push(batch);

    var nextBatch = 0;
    var hadErrors = false;
    var apiBase = (window.__apiBase || window.__backendUrl || window.__i18nBase || '').replace(/\/?$/, '/');
    var endpoint = apiBase + 'translate.php';

    function translateBatch(items) {
      var markup = items.map(function (item, index) {
        return '<span id="ba-' + index + '">' + escapeHtml(item.text) + '</span>';
      }).join('');

      return fetch(endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ text: markup, target: lang })
      }).then(function (response) {
        return response.json().then(function (data) {
          if (!response.ok || data.error) {
            throw new Error(data.error || 'Translation service returned HTTP ' + response.status);
          }
          var translatedDoc = new DOMParser().parseFromString(data.translated, 'text/html');
          var translatedSpans = translatedDoc.querySelectorAll('[id^="ba-"]');
          if (translatedSpans.length !== items.length) {
            throw new Error('Translation service did not preserve text markers.');
          }
          translatedSpans.forEach(function (span) {
            var index = Number(span.id.slice(3));
            if (!Number.isInteger(index) || !items[index]) return;
            var translated = span.textContent;
            items[index].record.parts[items[index].index] = translated;
            cache[items[index].text] = translated;
          });
        });
      });
    }

    function worker() {
      if (nextBatch >= batches.length) return Promise.resolve();
      var current = batches[nextBatch++];
      return translateBatch(current).catch(function (error) {
        hadErrors = true;
        console.error('Page translation failed.', error);
        nextBatch = batches.length;
      }).then(worker);
    }

    function applyTranslations() {
      pageRecords.forEach(function (record) {
        if (record.parts.every(function (part) { return part !== null; })) {
          record.node.nodeValue = record.leading + record.parts.join(' ') + record.trailing;
        }
      });
      try {
        localStorage.setItem(cacheKey, JSON.stringify(cache));
      } catch (error) {
        console.warn('Could not save translated text.', error);
      }
      var status = document.getElementById('i18n-status');
      if (status && hadErrors) {
        status.textContent = 'Some text could not be translated and remains in English.';
      }
    }

    worker().then(applyTranslations);
  }

  function splitText(text, limit) {
    var parts = [];
    while (text.length > limit) {
      var boundary = text.lastIndexOf(' ', limit);
      if (boundary < limit / 2) boundary = limit;
      parts.push(text.slice(0, boundary).trim());
      text = text.slice(boundary).trim();
    }
    if (text) parts.push(text);
    return parts;
  }

  function escapeHtml(text) {
    return text.replace(/[&<>"']/g, function (character) {
      return {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;'
      }[character];
    });
  }

  function buildPill(activeLang) {
    var widget = document.createElement('div');
    widget.id  = 'i18n-widget';
    widget.classList.add('notranslate');
    widget.setAttribute('translate', 'no');

    var toggle = document.createElement('button');
    toggle.id  = 'i18n-toggle';
    toggle.classList.add('notranslate');
    toggle.setAttribute('translate', 'no');
    toggle.setAttribute('aria-haspopup', 'listbox');
    toggle.setAttribute('aria-expanded', 'false');
    toggle.setAttribute('title', 'Change language');

    var activeMeta = LANGUAGES.find(function (l) { return l.code === activeLang; }) || LANGUAGES[0];
    toggle.innerHTML =
      '<span class="material-symbols-outlined notranslate" translate="no" ' +
        'style="font-size:18px;vertical-align:middle;line-height:1">language</span> ' +
      '<span id="i18n-lbl">' + activeMeta.code.split('-')[0].toUpperCase() + '</span> ' +
      '<span class="material-symbols-outlined notranslate" translate="no" id="i18n-chv" ' +
        'style="font-size:16px;vertical-align:middle;line-height:1;transition:transform .25s">expand_more</span>';

    var dropdown = document.createElement('ul');
    dropdown.id  = 'i18n-dd';
    dropdown.classList.add('notranslate');
    dropdown.setAttribute('translate', 'no');
    dropdown.setAttribute('role', 'listbox');
    dropdown.hidden = true;

    LANGUAGES.forEach(function (lang) {
      var li = document.createElement('li');
      li.setAttribute('role', 'option');
      li.setAttribute('aria-selected', lang.code === activeLang ? 'true' : 'false');
      li.classList.add('notranslate');
      li.setAttribute('translate', 'no');
      if (lang.code === activeLang) li.classList.add('i18n-on');
      li.dataset.code = lang.code;
      li.textContent  = lang.flag + '\u2002' + lang.label;

      li.addEventListener('click', function () {
        if (lang.code === activeLang) { closePill(); return; }
        li.textContent = 'Loading…';
        setTimeout(function () { switchLanguage(lang.code); }, 150);
      });

      dropdown.appendChild(li);
    });

    toggle.addEventListener('click', function (e) {
      e.stopPropagation();
      var open = !dropdown.hidden;
      dropdown.hidden = open;
      toggle.setAttribute('aria-expanded', String(!open));
      var chv = document.getElementById('i18n-chv');
      if (chv) chv.style.transform = open ? '' : 'rotate(180deg)';
    });

    document.addEventListener('click', closePill);

    widget.appendChild(toggle);
    widget.appendChild(dropdown);
    var status = document.createElement('span');
    status.id = 'i18n-status';
    status.setAttribute('role', 'status');
    status.setAttribute('aria-live', 'polite');
    widget.appendChild(status);
    return widget;
  }

  function closePill() {
    var dd  = document.getElementById('i18n-dd');
    var tg  = document.getElementById('i18n-toggle');
    var chv = document.getElementById('i18n-chv');
    if (dd)  dd.hidden = true;
    if (tg)  tg.setAttribute('aria-expanded', 'false');
    if (chv) chv.style.transform = '';
  }

  function injectStyles() {
    if (document.getElementById('i18n-css')) return;
    var s = document.createElement('style');
    s.id  = 'i18n-css';
    s.textContent = `
      /* Pill container */
      #i18n-widget {
        position: relative;
        display: inline-flex;
        align-items: center;
        margin-right: 8px;
      }

      /* Pill button */
      #i18n-toggle {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 12px;
        background: var(--surface-container-high, #e4e7eb);
        color: var(--on-surface, #1a1c1e);
        border: 1px solid var(--outline-variant, #c4c6cf);
        border-radius: 999px;
        font-family: var(--font-body, inherit);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .05em;
        cursor: pointer;
        transition: background .2s, box-shadow .2s;
        white-space: nowrap;
      }
      #i18n-toggle:hover {
        background: var(--secondary-container, #d0e8cc);
        box-shadow: 0 2px 8px rgba(0,0,0,.12);
      }

      /* Dropdown */
      #i18n-dd {
        position: absolute;
        top: calc(100% + 6px);
        right: 0;
        min-width: 175px;
        background: var(--surface, #fff);
        border: 1px solid var(--outline-variant, #c4c6cf);
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,.15);
        list-style: none;
        padding: 6px 0;
        z-index: 9999;
        animation: i18nFade .18s ease;
        overflow:scroll;
        height:300px;
      }
      #i18n-dd::-webkit-scrollbar {
        width: 8px;
      }
      @keyframes i18nFade {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
      }
      #i18n-dd li {
        padding: 10px 16px;
        font-size: 13px;
        color: var(--on-surface, #1a1c1e);
        cursor: pointer;
        border-radius: 8px;
        margin: 2px 6px;
        transition: background .15s;
        user-select: none;
      }
      #i18n-dd li:hover { background: var(--surface-container-high, #e4e7eb); }
      #i18n-dd li.i18n-on {
        background: var(--secondary-container, #d0e8cc);
        color: var(--on-secondary-container, #0f2010);
        font-weight: 700;
      }
      #i18n-status {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
      }
    `;
    document.head.appendChild(s);
  }

  function init() {
    protectIcons();
    injectStyles();

    var activeLang = getCurrentLang();

    var header = document.querySelector('header.header');
    if (header) {
      var pill = buildPill(activeLang);
      header.insertBefore(pill, header.lastElementChild);
    }

    translatePage();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
