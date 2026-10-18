<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nalar — Belajar bukan sekadar menghafal. Pahami lebih dalam.</title>
    <meta name="description" content="Nalar adalah platform pembelajaran digital untuk belajar lebih terstruktur, memahami konsep, dan menguji pemahaman melalui kuis, dengan dukungan AI sebagai pendamping berpikir.">
    <meta name="theme-color" content="#FFFCF8">
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='7' fill='%23EA580C'/%3E%3Cpath d='M9 24V8l14 16V8' fill='none' stroke='%23FFFCF8' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E">

    {{-- Self-contained: tidak bergantung pada Vite/Tailwind, tanpa font eksternal. --}}
    <style>
        :root {
            --primary: #EA580C;
            --accent: #FB923C;
            --soft: #FFEDD5;
            --bg: #FFFCF8;
            --text: #292524;
            --highlight: #2563EB;
            --muted: rgba(41, 37, 36, .72);
            --line: rgba(41, 37, 36, .14);
            --serif: "Iowan Old Style", "Palatino Linotype", Palatino, "Book Antiqua", Georgia, serif;
            --sans: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            --wrap: 1160px;
        }
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; scroll-padding-top: 76px; }
        body { margin: 0; background: var(--bg); color: var(--text); font-family: var(--sans); font-size: 1.0625rem; line-height: 1.65; -webkit-font-smoothing: antialiased; }
        img, svg { max-width: 100%; display: block; }
        a { color: inherit; }
        h1, h2, h3, p { margin: 0; }
        .wrap { width: min(100% - 2.5rem, var(--wrap)); margin-inline: auto; }
        .skip { position: absolute; left: -999px; top: 0; background: var(--text); color: var(--bg); padding: .6rem 1rem; z-index: 100; }
        .skip:focus { left: 1rem; top: 1rem; }
        :focus-visible { outline: 3px solid var(--highlight); outline-offset: 3px; border-radius: 4px; }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: .5rem; min-height: 48px; padding: .7rem 1.5rem; border-radius: 8px; font-weight: 700; font-size: 1rem; text-decoration: none; border: 2px solid transparent; transition: background-color .18s, color .18s, border-color .18s, transform .18s; cursor: pointer; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: #C2410C; transform: translateY(-1px); }
        .btn-ghost { border-color: var(--text); color: var(--text); background: transparent; }
        .btn-ghost:hover { background: var(--text); color: var(--bg); }
        .btn-sm { min-height: 42px; padding: .45rem 1.1rem; font-size: .95rem; }

        /* Eyebrow */
        .eyebrow { display: flex; align-items: center; gap: .7rem; font-size: .8rem; font-weight: 700; letter-spacing: .12em; margin-bottom: 1.1rem; }
        .eyebrow::before { content: ""; width: 28px; height: 3px; background: var(--primary); }

        /* Nav */
        .nav { position: sticky; top: 0; z-index: 50; background: var(--bg); border-bottom: 1px solid var(--line); }
        .nav-in { display: flex; align-items: center; justify-content: space-between; height: 64px; gap: 1rem; }
        .brand { display: inline-flex; align-items: center; gap: .6rem; text-decoration: none; font-family: var(--serif); font-weight: 700; font-size: 1.5rem; letter-spacing: -.01em; }
        .menu { display: flex; align-items: center; gap: 2rem; }
        .menu ul { display: flex; gap: 1.75rem; list-style: none; margin: 0; padding: 0; }
        .menu ul a { text-decoration: none; font-weight: 500; font-size: .98rem; padding: .35rem 0; border-bottom: 2px solid transparent; transition: border-color .18s, color .18s; }
        .menu ul a:hover, .menu ul a[aria-current="true"] { border-bottom-color: var(--primary); }
        .auth { display: flex; align-items: center; gap: .75rem; }
        .link-plain { font-weight: 600; text-decoration: none; padding: .5rem .6rem; border-radius: 6px; }
        .link-plain:hover { color: var(--highlight); }
        .burger { display: none; width: 44px; height: 44px; border: 1.5px solid var(--text); border-radius: 8px; background: transparent; color: var(--text); cursor: pointer; align-items: center; justify-content: center; }

        /* Hero */
        .hero { padding: clamp(2.5rem, 6vw, 5.5rem) 0 clamp(3rem, 7vw, 6rem); }
        .hero-grid { display: grid; grid-template-columns: 1.02fr 1fr; gap: clamp(2rem, 5vw, 4.5rem); align-items: center; }
        h1 { font-family: var(--serif); font-weight: 700; font-size: clamp(2.4rem, 5.6vw, 4.5rem); line-height: 1.04; letter-spacing: -.02em; text-wrap: balance; }
        h1 em { font-style: normal; text-decoration: underline; text-decoration-color: var(--accent); text-decoration-thickness: .09em; text-underline-offset: .12em; }
        .lead { margin-top: 1.5rem; max-width: 34em; font-size: clamp(1.05rem, 1.6vw, 1.2rem); color: var(--muted); }
        .cta-row { display: flex; flex-wrap: wrap; gap: .85rem; margin-top: 2rem; }
        .micro { margin-top: 1.4rem; font-family: var(--serif); font-style: italic; color: var(--muted); }

        /* Mockup frames */
        .mock { background: #fff; border: 1.5px solid var(--text); border-radius: 12px; box-shadow: 8px 8px 0 var(--soft); overflow: hidden; }
        .mock-bar { display: flex; justify-content: space-between; align-items: center; gap: .75rem; padding: .6rem 1rem; border-bottom: 1.5px solid var(--text); background: var(--soft); font-size: .78rem; font-weight: 600; }
        .tag { display: inline-block; font-size: .72rem; font-weight: 700; padding: .12rem .5rem; border: 1px solid var(--text); border-radius: 4px; background: var(--bg); white-space: nowrap; }
        .mock-body { padding: 1.25rem; display: grid; gap: 1rem; }
        .mock h3 { font-family: var(--serif); font-size: 1.3rem; line-height: 1.25; }
        .mock p { font-size: .92rem; color: var(--muted); line-height: 1.55; }
        .progress { display: grid; gap: .35rem; font-size: .8rem; font-weight: 600; }
        .progress .track { height: 8px; background: var(--soft); border-radius: 4px; overflow: hidden; }
        .progress .fill { height: 100%; width: 40%; background: var(--highlight); }
        .progress .row { display: flex; justify-content: space-between; color: var(--muted); }
        .split { display: grid; grid-template-columns: 1fr 1fr; gap: .85rem; }
        .panel { border: 1px solid var(--line); border-radius: 8px; padding: .85rem; background: var(--bg); }
        .panel h4 { margin: 0 0 .5rem; font-size: .76rem; letter-spacing: .08em; display: flex; align-items: center; gap: .4rem; }
        .panel h4 svg { color: var(--highlight); }
        .opt { display: flex; align-items: center; gap: .55rem; font-size: .85rem; padding: .38rem .55rem; border: 1px solid var(--line); border-radius: 6px; background: #fff; margin-top: .4rem; }
        .opt i { font-style: normal; font-weight: 700; width: 1.35rem; height: 1.35rem; display: grid; place-items: center; border-radius: 4px; background: var(--soft); font-size: .72rem; flex: none; }
        .opt.sel { border: 2px solid var(--primary); font-weight: 600; }
        .opt.sel i { background: var(--primary); color: #fff; }
        .bubble { font-size: .85rem; padding: .6rem .75rem; border-radius: 8px; line-height: 1.5; }
        .bubble.q { background: var(--soft); margin-left: 12%; }
        .bubble.a { background: #fff; border: 1px solid var(--line); border-left: 3px solid var(--highlight); margin-right: 8%; }
        .bubble small { display: block; font-weight: 700; font-size: .7rem; letter-spacing: .06em; margin-bottom: .15rem; color: var(--highlight); }

        /* Sections */
        section { scroll-margin-top: 76px; }
        .sec { padding: clamp(3.5rem, 8vw, 7rem) 0; }
        .sec + .sec { border-top: 1px solid var(--line); }
        h2 { font-family: var(--serif); font-weight: 700; font-size: clamp(1.9rem, 4vw, 3rem); line-height: 1.12; letter-spacing: -.015em; text-wrap: balance; }
        .sec-lead { margin-top: 1.25rem; max-width: 38em; color: var(--muted); }

        /* About */
        .about-grid { display: grid; grid-template-columns: 1fr 1.1fr; gap: clamp(2rem, 6vw, 5rem); align-items: start; }
        .principles { list-style: none; margin: 0; padding: 0; display: grid; }
        .principles li { display: grid; grid-template-columns: 2.6rem 1fr; gap: 1rem; padding: 1.25rem 0; border-top: 1px solid var(--line); }
        .principles li:last-child { border-bottom: 1px solid var(--line); }
        .ico { width: 2.6rem; height: 2.6rem; display: grid; place-items: center; background: var(--soft); border-radius: 8px; color: var(--primary); }
        .principles strong { display: block; font-family: var(--serif); font-size: 1.15rem; line-height: 1.3; margin-bottom: .15rem; }
        .principles span { color: var(--muted); font-size: .98rem; }

        /* Features */
        .feat-head { max-width: 46rem; }
        .feat { display: grid; grid-template-columns: 1fr 1.15fr; gap: clamp(1.75rem, 5vw, 4.5rem); align-items: center; margin-top: clamp(2.5rem, 6vw, 4.5rem); }
        .feat:nth-of-type(even) .feat-text { order: 2; }
        .feat-text h3 { font-family: var(--serif); font-size: clamp(1.4rem, 2.6vw, 1.9rem); line-height: 1.2; margin-bottom: .75rem; }
        .feat-text p { color: var(--muted); max-width: 30em; }
        .feat-no { font-size: .8rem; font-weight: 700; color: var(--highlight); margin-bottom: .6rem; display: block; }
        .lesson-list { list-style: none; margin: 0; padding: 0; }
        .lesson-list li { display: flex; align-items: center; gap: .8rem; padding: .7rem 1.1rem; border-top: 1px solid var(--line); font-size: .95rem; }
        .lesson-list li:first-child { border-top: 0; }
        .lesson-list .n { width: 1.7rem; height: 1.7rem; display: grid; place-items: center; border-radius: 50%; border: 1.5px solid var(--text); font-size: .75rem; font-weight: 700; flex: none; }
        .lesson-list li.done .n { background: var(--highlight); border-color: var(--highlight); color: #fff; }
        .lesson-list li.now { background: var(--soft); font-weight: 700; }
        .lesson-list li.now .n { background: var(--primary); border-color: var(--primary); color: #fff; }
        .lesson-list .st { margin-left: auto; font-size: .74rem; font-weight: 600; color: var(--muted); white-space: nowrap; }

        /* Steps */
        .steps { list-style: none; margin: clamp(2rem, 5vw, 3.5rem) 0 0; padding: 0; display: grid; grid-template-columns: repeat(3, 1fr); position: relative; }
        .step { position: relative; padding: 0 2rem 0 0; }
        .step .no { display: grid; place-items: center; width: 3.2rem; height: 3.2rem; border-radius: 50%; background: var(--primary); color: #fff; font-family: var(--serif); font-size: 1.15rem; font-weight: 700; position: relative; z-index: 1; }
        .step::before { content: ""; position: absolute; top: 1.6rem; left: 0; right: 0; border-top: 2px dashed var(--line); }
        .step:last-child::before { right: 100%; }
        .step h3 { font-family: var(--serif); font-size: 1.4rem; margin: 1.25rem 0 .4rem; }
        .step p { color: var(--muted); max-width: 20em; }

        /* AI philosophy: dark contrast band */
        .ai { background: var(--text); color: var(--bg); padding: clamp(3.5rem, 8vw, 7rem) 0; }
        .ai .eyebrow::before { background: var(--accent); }
        .ai .sec-lead { color: rgba(255, 252, 248, .8); }
        .ai h2 .hl { color: var(--accent); }
        .ai-grid { display: grid; grid-template-columns: 1.1fr 1fr; gap: clamp(2rem, 6vw, 5rem); align-items: center; }
        .ai .mock { box-shadow: 8px 8px 0 var(--primary); border-color: var(--bg); color: var(--text); }
        .ai .mock-bar { border-bottom-color: var(--text); }
        .think { display: grid; gap: .7rem; }
        .think li { list-style: none; display: grid; grid-template-columns: auto 1fr; gap: .7rem; font-size: .9rem; }
        .think b { color: var(--highlight); font-size: .75rem; letter-spacing: .05em; padding-top: .2rem; }

        /* Final CTA */
        .final { padding: clamp(3rem, 7vw, 6rem) 0; }
        .final-box { background: var(--soft); border: 1.5px solid var(--text); border-radius: 14px; padding: clamp(2rem, 6vw, 4.5rem); display: grid; grid-template-columns: 1.4fr auto; gap: 2rem; align-items: center; }
        .final-box p { margin-top: 1rem; font-family: var(--serif); font-size: 1.15rem; color: var(--muted); }

        /* Footer */
        footer { border-top: 1px solid var(--line); padding: 3rem 0 2rem; font-size: .96rem; }
        .foot { display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 2rem; }
        .foot p { color: var(--muted); max-width: 26em; margin-top: .8rem; }
        .foot h2 { font-family: var(--sans); font-size: .8rem; letter-spacing: .1em; margin-bottom: .8rem; }
        .foot ul { list-style: none; margin: 0; padding: 0; display: grid; gap: .35rem; }
        .foot a { text-decoration: none; }
        .foot ul a:hover { color: var(--highlight); text-decoration: underline; }
        .copy { margin-top: 2.5rem; padding-top: 1.25rem; border-top: 1px solid var(--line); color: var(--muted); font-size: .9rem; }

        /* Responsive */
        @media (max-width: 960px) {
            .hero-grid, .about-grid, .ai-grid, .feat { grid-template-columns: 1fr; }
            .feat:nth-of-type(even) .feat-text { order: 0; }
            .final-box { grid-template-columns: 1fr; }
            .foot { grid-template-columns: 1fr 1fr; }
            .foot > :first-child { grid-column: 1 / -1; }
        }
        @media (max-width: 820px) {
            .burger { display: inline-flex; }
            .menu { display: none; position: absolute; top: 64px; left: 0; right: 0; background: var(--bg); border-bottom: 1px solid var(--line); flex-direction: column; align-items: stretch; gap: 1rem; padding: 1.25rem; }
            .menu.open { display: flex; }
            .menu ul { flex-direction: column; gap: 0; }
            .menu ul a { display: block; padding: .85rem 0; border-bottom: 1px solid var(--line); }
            .auth { flex-direction: column; align-items: stretch; }
            .auth .btn, .auth .link-plain { text-align: center; }
        }
        @media (max-width: 720px) {
            .steps { grid-template-columns: 1fr; gap: 2rem; }
            .step { padding: 0 0 0 4.4rem; }
            .step .no { position: absolute; left: 0; top: 0; }
            .step::before { top: 3.2rem; bottom: -2rem; left: 1.6rem; right: auto; border-top: 0; border-left: 2px dashed var(--line); }
            .step:last-child::before { display: none; }
            .step h3 { margin-top: .2rem; }
            .cta-row .btn, .final-box .btn { width: 100%; }
            .split { grid-template-columns: 1fr; }
            .foot { grid-template-columns: 1fr; }
        }
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            *, *::before, *::after { transition: none !important; }
        }
    </style>
</head>
<body>
    <a class="skip" href="#konten">Lewati ke konten utama</a>

    {{-- A. Navigasi --}}
    <header class="nav">
        <div class="wrap nav-in">
            <a class="brand" href="/" aria-label="Nalar — ke beranda">
                <svg width="32" height="32" viewBox="0 0 32 32" aria-hidden="true">
                    <rect width="32" height="32" rx="7" fill="#EA580C"/>
                    <path d="M9 24V8l14 16V8" fill="none" stroke="#FFFCF8" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="23" cy="8" r="2.2" fill="#FB923C"/>
                </svg>
                Nalar
            </a>

            <button class="burger" id="burger" type="button" aria-expanded="false" aria-controls="menu" aria-label="Buka menu navigasi">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path id="burger-icon" d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>

            <div class="menu" id="menu">
                <nav aria-label="Navigasi utama">
                    <ul>
                        <li><a href="#beranda" aria-current="true">Beranda</a></li>
                        <li><a href="#tentang">Tentang</a></li>
                        <li><a href="#fitur">Fitur</a></li>
                        <li><a href="#cara-kerja">Cara Kerja</a></li>
                    </ul>
                </nav>
                <div class="auth">
                    @auth
                        <a class="btn btn-primary btn-sm" href="{{ url('/dashboard') }}">Dashboard</a>
                    @else
                        <a class="link-plain" href="{{ route('login') }}">Masuk</a>
                        <a class="btn btn-primary btn-sm" href="{{ route('register') }}">Daftar Gratis</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main id="konten">
        {{-- B. Hero --}}
        <section class="hero" id="beranda" aria-labelledby="hero-title">
            <div class="wrap hero-grid">
                <div>
                    <p class="eyebrow">RUANG BELAJAR DIGITAL</p>
                    <h1 id="hero-title">Belajar bukan sekadar menghafal. <em>Pahami lebih dalam.</em></h1>
                    <p class="lead">Nalar membantumu belajar lebih terarah, memahami konsep dengan lebih baik, dan menguji pemahaman melalui pengalaman belajar yang interaktif.</p>
                    <div class="cta-row">
                        @auth
                            <a class="btn btn-primary" href="{{ url('/dashboard') }}">Mulai Belajar</a>
                        @else
                            <a class="btn btn-primary" href="{{ route('register') }}">Mulai Belajar</a>
                        @endauth
                        <a class="btn btn-ghost" href="#tentang">Kenali Nalar</a>
                    </div>
                    <p class="micro">Mulai dari rasa ingin tahu, lanjutkan dengan pemahaman.</p>
                </div>

                <div class="mock" role="img" aria-label="Ilustrasi tampilan ruang belajar Nalar: materi, kuis, dan bantuan AI. Hanya contoh, bukan data nyata.">
                    <div class="mock-bar"><span>Contoh tampilan ruang belajar</span><span class="tag">Ilustrasi</span></div>
                    <div class="mock-body" aria-hidden="true">
                        <div>
                            <h3>Memahami Dasar Logika</h3>
                            <p>Mengenal pernyataan, premis, dan cara menarik kesimpulan yang masuk akal.</p>
                        </div>
                        <div class="progress">
                            <div class="row"><span>Progres belajar (contoh)</span><span>2 dari 5 bagian</span></div>
                            <div class="track"><div class="fill"></div></div>
                        </div>
                        <div class="split">
                            <div class="panel">
                                <h4>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5h6a3 3 0 0 1 3 3v12a2 2 0 0 0-2-2H4zM20 5h-6a3 3 0 0 0-1 .2V20a2 2 0 0 1 2-2h5z"/></svg>
                                    KUIS SINGKAT
                                </h4>
                                <p style="color:var(--text);font-size:.85rem">Jika semua A adalah B, dan C adalah A, maka…</p>
                                <div class="opt"><i>A</i> C adalah B</div>
                                <div class="opt sel"><i>B</i> B adalah C</div>
                            </div>
                            <div class="panel">
                                <h4>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a8 8 0 0 1-11.6 7.1L4 20l1-4.6A8 8 0 1 1 21 12z"/></svg>
                                    TANYA PENDAMPING
                                </h4>
                                <div class="bubble q">Apa beda premis dan kesimpulan?</div>
                                <div class="bubble a" style="margin-top:.5rem"><small>PENJELASAN</small>Premis adalah dasar berpikir…</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- C. Tentang --}}
        <section class="sec" id="tentang" aria-labelledby="tentang-title">
            <div class="wrap about-grid">
                <div>
                    <p class="eyebrow">TENTANG NALAR</p>
                    <h2 id="tentang-title">Belajar seharusnya membuatmu mengerti.</h2>
                    <p class="sec-lead">Nalar adalah platform pembelajaran digital yang dirancang untuk membantu proses belajar menjadi lebih terstruktur, interaktif, dan berfokus pada pemahaman.</p>
                </div>
                <ul class="principles">
                    <li>
                        <span class="ico" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M10 21h4M12 3a6 6 0 0 0-3.5 10.9c.6.5 1 1.2 1 2.1h5c0-.9.4-1.6 1-2.1A6 6 0 0 0 12 3z"/></svg></span>
                        <div><strong>Memahami, bukan hanya menghafal</strong><span>Konsep yang dipahami lebih mudah diingat dan dipakai kembali.</span></div>
                    </li>
                    <li>
                        <span class="ico" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3 8-8M20 12v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h9"/></svg></span>
                        <div><strong>Ukur pemahamanmu sendiri</strong><span>Kuis membantumu melihat bagian mana yang sudah dikuasai.</span></div>
                    </li>
                    <li>
                        <span class="ico" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l1.8 4.6L18 9l-4.2 1.4L12 15l-1.8-4.6L6 9l4.2-1.4zM18 15l.9 2.1L21 18l-2.1.9L18 21l-.9-2.1L15 18l2.1-.9z"/></svg></span>
                        <div><strong>AI mendukung, bukan menggantikan</strong><span>Berpikir kritis tetap menjadi bagian terpenting dalam belajar.</span></div>
                    </li>
                    <li>
                        <span class="ico" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h10"/></svg></span>
                        <div><strong>Terarah dan mudah didekati</strong><span>Belajar dimulai dari langkah kecil yang tersusun jelas.</span></div>
                    </li>
                </ul>
            </div>
        </section>

        {{-- D. Fitur --}}
        <section class="sec" id="fitur" aria-labelledby="fitur-title">
            <div class="wrap">
                <div class="feat-head">
                    <p class="eyebrow">APA YANG BISA KAMU LAKUKAN?</p>
                    <h2 id="fitur-title">Semua yang kamu butuhkan untuk mulai memahami.</h2>
                </div>

                <article class="feat">
                    <div class="feat-text">
                        <span class="feat-no">Fitur 1</span>
                        <h3>Materi Pembelajaran</h3>
                        <p>Pelajari materi secara terstruktur berdasarkan topik agar proses belajar lebih terarah.</p>
                    </div>
                    <div class="mock" role="img" aria-label="Contoh daftar bagian materi. Hanya ilustrasi.">
                        <div class="mock-bar"><span>Memahami Dasar Logika</span><span class="tag">Contoh</span></div>
                        <ul class="lesson-list" aria-hidden="true">
                            <li class="done"><span class="n">&#10003;</span> Apa itu pernyataan? <span class="st">Selesai</span></li>
                            <li class="done"><span class="n">&#10003;</span> Premis dan kesimpulan <span class="st">Selesai</span></li>
                            <li class="now"><span class="n">3</span> Penalaran deduktif <span class="st">Sedang dipelajari</span></li>
                            <li><span class="n">4</span> Kesalahan berpikir umum <span class="st">Belum dimulai</span></li>
                            <li><span class="n">5</span> Latihan dan rangkuman <span class="st">Belum dimulai</span></li>
                        </ul>
                    </div>
                </article>

                <article class="feat">
                    <div class="feat-text">
                        <span class="feat-no">Fitur 2</span>
                        <h3>Bantuan Belajar Berbantuan AI</h3>
                        <p>Dapatkan dukungan untuk mengeksplorasi pertanyaan dan memahami konsep yang sedang kamu pelajari.</p>
                    </div>
                    <div class="mock" role="img" aria-label="Konsep tampilan tanya jawab dengan pendamping belajar. Hanya ilustrasi.">
                        <div class="mock-bar"><span>Tanya pendamping belajar</span><span class="tag">Konsep</span></div>
                        <div class="mock-body" aria-hidden="true">
                            <div class="bubble q">Kenapa “semua kucing hewan” tidak berarti “semua hewan kucing”?</div>
                            <div class="bubble a"><small>PENJELASAN</small>Kelompok “kucing” lebih kecil dari “hewan”. Arah hubungannya tidak bisa dibalik. Coba cari contoh hewan yang bukan kucing.</div>
                        </div>
                    </div>
                </article>

                <article class="feat">
                    <div class="feat-text">
                        <span class="feat-no">Fitur 3</span>
                        <h3>Kuis dan Evaluasi</h3>
                        <p>Uji pemahaman melalui kuis sederhana dan gunakan hasilnya untuk mengetahui materi yang perlu dipelajari kembali.</p>
                    </div>
                    <div class="mock" role="img" aria-label="Konsep tampilan kuis pilihan ganda. Hanya ilustrasi.">
                        <div class="mock-bar"><span>Kuis: Penalaran deduktif</span><span class="tag">Konsep</span></div>
                        <div class="mock-body" aria-hidden="true">
                            <h3 style="font-size:1.1rem">Manakah kesimpulan yang sahih dari premis di atas?</h3>
                            <div>
                                <div class="opt"><i>A</i> Sebagian hewan adalah kucing</div>
                                <div class="opt sel"><i>B</i> Semua kucing adalah hewan</div>
                                <div class="opt"><i>C</i> Semua hewan adalah kucing</div>
                                <div class="opt"><i>D</i> Tidak ada kesimpulan</div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        {{-- E. Cara Kerja --}}
        <section class="sec" id="cara-kerja" aria-labelledby="cara-title">
            <div class="wrap">
                <p class="eyebrow">MULAI DARI SINI</p>
                <h2 id="cara-title">Tiga langkah untuk memulai perjalanan belajar.</h2>
                <ol class="steps">
                    <li class="step"><span class="no" aria-hidden="true">01</span><h3><span class="sr-only" style="position:absolute;left:-999px">Langkah 1: </span>Buat Akun</h3><p>Daftar untuk mendapatkan akses ke ruang belajar Nalar.</p></li>
                    <li class="step"><span class="no" aria-hidden="true">02</span><h3><span class="sr-only" style="position:absolute;left:-999px">Langkah 2: </span>Pelajari Materi</h3><p>Mulai dari materi yang tersedia dan pelajari konsep secara bertahap.</p></li>
                    <li class="step"><span class="no" aria-hidden="true">03</span><h3><span class="sr-only" style="position:absolute;left:-999px">Langkah 3: </span>Uji Pemahaman</h3><p>Kerjakan kuis untuk mengevaluasi pemahamanmu.</p></li>
                </ol>
            </div>
        </section>

        {{-- F. Filosofi AI --}}
        <section class="ai" aria-labelledby="ai-title">
            <div class="wrap ai-grid">
                <div>
                    <p class="eyebrow">PERAN AI DI NALAR</p>
                    <h2 id="ai-title">AI membantu menjelaskan. <span class="hl">Kamu tetap membangun pemahaman.</span></h2>
                    <p class="sec-lead">Teknologi seharusnya mendukung proses berpikir, bukan menggantikannya. Nalar dirancang dengan gagasan bahwa belajar yang baik tetap membutuhkan rasa ingin tahu, latihan, dan pemahaman.</p>
                </div>
                <div class="mock" role="img" aria-label="Diagram konsep alur belajar dengan bantuan AI: bertanya, membaca penjelasan, mencoba menjelaskan sendiri. Hanya ilustrasi.">
                    <div class="mock-bar"><span>Alur belajar yang diharapkan</span><span class="tag">Konsep</span></div>
                    <div class="mock-body" aria-hidden="true">
                        <ul class="think" style="margin:0;padding:0">
                            <li><b>KAMU</b><span>Mengajukan pertanyaan tentang konsep yang membingungkan.</span></li>
                            <li><b>AI</b><span>Memberi penjelasan dan contoh sebagai titik awal.</span></li>
                            <li><b>KAMU</b><span>Menjelaskan ulang dengan kata-katamu sendiri, lalu menguji lewat kuis.</span></li>
                        </ul>
                        <div class="panel" style="border-left:3px solid var(--highlight)"><p style="color:var(--text);font-size:.85rem">Pemahaman terbentuk di langkah terakhir, bukan di jawaban pertama.</p></div>
                    </div>
                </div>
            </div>
        </section>

        {{-- G. CTA akhir --}}
        <section class="final" aria-labelledby="final-title">
            <div class="wrap">
                <div class="final-box">
                    <div>
                        <h2 id="final-title">Siap memulai cara belajar yang lebih bermakna?</h2>
                        <p>Mulai dari satu materi, satu pertanyaan, dan satu pemahaman baru.</p>
                    </div>
                    @auth
                        <a class="btn btn-primary" href="{{ url('/dashboard') }}">Bergabung dengan Nalar</a>
                    @else
                        <a class="btn btn-primary" href="{{ route('register') }}">Bergabung dengan Nalar</a>
                    @endauth
                </div>
            </div>
        </section>
    </main>

    {{-- H. Footer --}}
    <footer>
        <div class="wrap">
            <div class="foot">
                <div>
                    <a class="brand" href="/" aria-label="Nalar — ke beranda">
                        <svg width="28" height="28" viewBox="0 0 32 32" aria-hidden="true"><rect width="32" height="32" rx="7" fill="#EA580C"/><path d="M9 24V8l14 16V8" fill="none" stroke="#FFFCF8" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Nalar
                    </a>
                    <p>Platform pembelajaran digital untuk belajar lebih terstruktur, memahami konsep, dan menguji pemahaman.</p>
                </div>
                <nav aria-label="Navigasi footer">
                    <h2>Jelajahi</h2>
                    <ul>
                        <li><a href="#beranda">Beranda</a></li>
                        <li><a href="#fitur">Fitur</a></li>
                        <li><a href="#tentang">Tentang</a></li>
                        <li><a href="#cara-kerja">Cara Kerja</a></li>
                    </ul>
                </nav>
                <div>
                    <h2>Akun</h2>
                    <ul>
                        @auth
                            <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        @else
                            <li><a href="{{ route('login') }}">Masuk</a></li>
                            <li><a href="{{ route('register') }}">Daftar Gratis</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
            <p class="copy">&copy; {{ date('Y') }} Nalar. Hak cipta dilindungi.</p>
        </div>
    </footer>

    <script>
        (function () {
            var btn = document.getElementById('burger');
            var menu = document.getElementById('menu');
            var icon = document.getElementById('burger-icon');
            function set(open) {
                menu.classList.toggle('open', open);
                btn.setAttribute('aria-expanded', open);
                btn.setAttribute('aria-label', open ? 'Tutup menu navigasi' : 'Buka menu navigasi');
                icon.setAttribute('d', open ? 'M6 6l12 12M18 6L6 18' : 'M4 7h16M4 12h16M4 17h16');
            }
            btn.addEventListener('click', function () { set(!menu.classList.contains('open')); });
            menu.addEventListener('click', function (e) { if (e.target.closest('a')) set(false); });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') set(false); });
        })();
    </script>
</body>
</html>