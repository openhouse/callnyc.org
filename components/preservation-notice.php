<?php

declare(strict_types=1);

require_once __DIR__ . '/../functions.php';

function render_preservation_notice(): string {
  $mailto = htmlspecialchars(advocacy_mailto_url(), ENT_QUOTES, 'UTF-8');

  return <<<HTML
<section class="preservation-notice" aria-labelledby="preservation-title">
  <div class="preservation-notice-copy">
    <h2 id="preservation-title">Archived and unofficial</h2>
    <p><strong>Built in a 24-hour sprint</strong> after NYC Council released constituent-services data in 2016, CallNYC made district-office activity explorable. The dataset is now historical; these rankings do not describe current members or services.</p>
    <p class="preservation-request">Tell NYC Council and NYC Open Data: <strong>restore constituent services data publishing</strong> from Council Connect—with privacy protections for the people who asked for help.</p>
    <div class="preservation-actions">
      <a class="btn waves-effect waves-light red darken-2" href="{$mailto}">Email NYC to restore the data</a>
      <a href="https://council.nyc.gov/districts/">Find your current Council member</a>
    </div>
  </div>
  <a class="preservation-press" href="/data/media/Politico-Website-provides-new-information-about-council-members-focus.pdf" aria-label="Read the archived March 14, 2016 Politico New York article about CallNYC">
    <img src="/data/media/politico-callnyc-2016-page-1.png" alt="Screenshot of the March 14, 2016 Politico New York article about CallNYC">
    <span>As seen in Politico New York</span>
  </a>
</section>
HTML;
}
