<?php

declare(strict_types=1);

function render_contemporary_home(): string {
  $robots = getenv('CALLNYC_ROBOTS');
  $robotsMeta = $robots === 'noindex'
    ? '<meta name="robots" content="noindex, nofollow">'
    : '<meta name="robots" content="index, follow">';

  return <<<HTML
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="CallNYC preserves the 2016 constituent-services archive and asks NYC Council and NYC OTI to restore privacy-protected public data from Council Connect.">
  {$robotsMeta}
  <meta name="theme-color" content="#f3f6f8">
  <title>CallNYC.org — The public record stopped updating. The work did not.</title>
  <link rel="canonical" href="https://callnyc.org/">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
  <link rel="stylesheet" href="/css/contemporary.css">
</head>
<body>
  <!--
  THESIS: The public record stopped updating. The work did not.
  OWN-WORLD: A contemporary civic record room in the visual language of Jamie Burkart's portfolio, without its personal navigation.
  STORY: Present-day help, sourced status, a bounded request, and the preserved 2016 archive.
  FIRST VIEWPORT: Residents can get official help, understand the break in the record, or join the request.
  FORM: Open public letter, assigned structure 7; seed a96de65f.
  FINISH: Review the desktop and mobile artifact, detector findings, source boundaries, and DESIGN.md before calling it complete.
  -->
  <a class="skip-link" href="#main">Skip to the main content</a>

  <header class="site-header">
    <a class="wordmark" href="/" aria-label="CallNYC.org home"><span>Call</span><strong>NYC</strong><small>.org</small></a>
    <nav aria-label="Primary navigation">
      <a href="#restore">The request</a>
      <a href="#evidence">Evidence</a>
      <a href="/archive/2016/">2016 archive</a>
    </nav>
  </header>

  <main id="main">
    <section class="hero" aria-labelledby="hero-title">
      <div class="hero-letter">
        <h1 id="hero-title">The public record stopped updating.<br>The work did not.</h1>
        <p class="hero-deck">CallNYC once translated constituent-services data into a public path through the work of New York City Council district offices. That public dataset is now historical. Council offices continue to record cases in Council Connect, but we have found no current Council Connect export on NYC Open Data.</p>
        <p>We do not know why that connection ended. We know what its absence makes harder to see.</p>
        <div class="actions">
          <a class="button button-primary" href="#restore">Ask NYC to restore the data <span aria-hidden="true">→</span></a>
          <a class="button button-secondary" href="/archive/2016/">Explore the 2016 archive <span aria-hidden="true">→</span></a>
        </div>
      </div>

      <aside class="help-now" aria-labelledby="help-title">
        <h2 id="help-title">Need help now?</h2>
        <p>If you need help with a City service, use these official resources.</p>
        <div class="help-route">
          <h3>Contact your Council member</h3>
          <p>Find your district and member to get help with a problem.</p>
          <a href="https://council.nyc.gov/districts/">Find my Council district <span aria-hidden="true">→</span></a>
        </div>
        <div class="help-route">
          <h3>NYC311</h3>
          <p>Report a problem or get information about City services.</p>
          <a href="https://portal.311.nyc.gov/">Visit NYC311 <span aria-hidden="true">→</span></a>
        </div>
        <p class="emergency">In an emergency, call <a href="tel:911">911</a>.</p>
      </aside>
    </section>

    <section class="evidence" id="evidence" aria-labelledby="evidence-title">
      <div class="section-heading">
        <h2 id="evidence-title">What the sources establish</h2>
        <p>Known, inferred, and unanswered are not the same category.</p>
      </div>
      <ol class="evidence-docket">
        <li>
          <span class="status status-confirmed">Confirmed</span>
          <div><h3>The public dataset is historical.</h3><p>NYC Open Data describes the constituent-services dataset as covering 2015 to 2025 and identifies it as historical.</p></div>
          <a href="https://data.cityofnewyork.us/d/b9km-gdpy">View the dataset <span aria-hidden="true">→</span></a>
        </li>
        <li>
          <span class="status status-confirmed">Confirmed</span>
          <div><h3>Council offices still use Council Connect.</h3><p>A current Council district-office job posting says constituent cases are entered into Council Connect.</p></div>
          <a href="https://council.nyc.gov/amanda-farias/join-our-team-constituent-liaison/">View the Council source <span aria-hidden="true">→</span></a>
        </li>
        <li>
          <span class="status status-open">Open question</span>
          <div><h3>Why did the public connection end?</h3><p>No current Council Connect export was found on NYC Open Data. No official explanation for that change has been located.</p></div>
          <a href="#sources">Review all sources <span aria-hidden="true">→</span></a>
        </li>
      </ol>
    </section>

    <section class="request" id="restore" aria-labelledby="request-title">
      <div class="request-letter">
        <h2 id="request-title">Restore the public path from Council Connect to NYC Open Data.</h2>
        <p>To New York City Council and NYC Office of Technology and Innovation:</p>
        <p>Constituent service is where government becomes specific: a broken boiler, a benefit delayed, a street that needs attention. A public record can help New Yorkers see the shape of that work without exposing the people who asked for help.</p>
        <p>We ask the City to publish a current, privacy-protected view of Council Connect activity through the NYC Open Data Portal. The release should be machine-readable, documented, regularly updated, and clear about its responsible steward. It should include a data dictionary, update schedule, change log, and public feedback path.</p>
        <p class="landing">Keep the person protected. Let the public work be seen.</p>
        <div class="actions">
          <a class="button button-primary" href="https://opendata.cityofnewyork.us/engage/">Contact NYC Open Data <span aria-hidden="true">→</span></a>
          <a class="button button-secondary" href="https://council.nyc.gov/committees/technology/">Contact the Council Technology Committee <span aria-hidden="true">→</span></a>
        </div>
      </div>
      <aside class="request-notes" aria-label="Request boundaries">
        <h3>The request is bounded</h3>
        <ul>
          <li>Publish useful aggregate or de-identified information—not residents’ private case details.</li>
          <li>Separate confirmed facts from unanswered questions.</li>
          <li>Document changes so future readers can understand the record.</li>
        </ul>
        <p>NYC Open Data is managed by NYC OTI in coordination with publishing agencies.</p>
        <a href="https://opendata.cityofnewyork.us/overview/">How NYC Open Data works <span aria-hidden="true">→</span></a>
      </aside>
    </section>

    <section class="archive-exhibit" id="archive" aria-labelledby="archive-title">
      <figure>
        <a href="/data/media/Politico-Website-provides-new-information-about-council-members-focus.pdf" aria-label="Open the archived Politico New York article PDF">
          <img src="/data/media/politico-callnyc-2016-page-1.png" alt="Screenshot of the March 14, 2016 Politico New York article about CallNYC">
        </a>
        <figcaption>“Website provides new information about council members’ focus,” Miranda Neubauer, Politico New York, March 14, 2016. Preserved as evidence of contemporary coverage, not as an endorsement. <a href="/data/media/Politico-Website-provides-new-information-about-council-members-focus.pdf">Read the archived PDF.</a></figcaption>
      </figure>
      <div>
        <h2 id="archive-title">An archive can show what became possible.</h2>
        <p>The reconstructed 2016 site preserves an unofficial public-interest project built from historical CouncilStat data. It is not a current ranking of Council members and not an official City service.</p>
        <a class="text-link" href="/archive/2016/">Enter the 2016 CallNYC archive <span aria-hidden="true">→</span></a>
      </div>
    </section>

    <section class="sources" id="sources" aria-labelledby="sources-title">
      <h2 id="sources-title">Sources and limits</h2>
      <div class="source-list">
        <p><a href="https://data.cityofnewyork.us/d/b9km-gdpy">NYC Open Data: City Council Constituent Services (2015 to 2025)</a><span>Official dataset metadata; historical status.</span></p>
        <p><a href="https://council.nyc.gov/amanda-farias/join-our-team-constituent-liaison/">NYC Council district-office constituent liaison posting</a><span>Official evidence that constituent cases are entered in Council Connect.</span></p>
        <p><a href="https://opendata.cityofnewyork.us/overview/">NYC Open Data overview</a><span>Official description of NYC OTI’s role and agency publication process.</span></p>
      </div>
      <p class="limits">This page reports what these sources establish and what our review did not locate. It does not claim to know why the public export changed. Corrections and stronger primary sources are welcome through the project repository.</p>
    </section>
  </main>

  <footer class="site-footer">
    <p><strong>CallNYC.org</strong> is an independent, unofficial civic archive and open-data request. It is not affiliated with New York City Council or the City of New York.</p>
    <p>Built and stewarded by <a href="https://jamieburk.art/">Jamie Burkart</a>. <a href="https://github.com/openhouse/callnyc.org">View the project on GitHub</a>.</p>
  </footer>
</body>
</html>
HTML;
}

