<?php

declare(strict_types=1);

function advocacy_mailto_url(): string {
  $subject = 'Restore constituent services data publishing';
  $body = <<<TEXT
To the New York City Council and NYC Open Data team,

Please restore regular, privacy-protected publication of constituent-services activity from Council Connect through the NYC Open Data Portal.

The public dataset is now historical, while Council offices continue to provide constituent services. A current, documented, machine-readable publication would help New Yorkers understand this work without exposing residents' private case details.

Please publish an update schedule, data dictionary, change log, and responsible point of contact.

Thank you.
TEXT;

  return 'mailto:District10@council.nyc.gov,opendatateam@oti.nyc.gov?'
    . http_build_query(
      ['subject' => $subject, 'body' => $body],
      '',
      '&',
      PHP_QUERY_RFC3986
    );
}

function render_preservation_header(): string {
  $mailto = htmlspecialchars(advocacy_mailto_url(), ENT_QUOTES, 'UTF-8');

  return <<<HTML
<section class="preservation-header" aria-labelledby="preservation-title">
  <div class="container">
    <div class="row preservation-header__row">
      <div class="col s12 l7 preservation-header__copy">
        <p id="preservation-title" class="preservation-header__title">Archived and unofficial</p>
        <p><strong>Built by Jamie Burkart in a 24-hour sprint</strong> after the release of then-new constituent-services data. NYC Open Data lists the public constituent-services dataset as historical. No current Council Connect export has been located. The 2016 records below remain preserved as a working historical interface.</p>
      </div>
      <a class="col s4 l2 preservation-header__press" href="/data/media/Politico-Website-provides-new-information-about-council-members-focus.pdf" target="_blank" rel="noopener" aria-label="Read the archived March 14, 2016 POLITICO New York article about CallNYC">
        <img src="/data/media/politico-callnyc-2016-page-1.png" alt="Screenshot of the March 14, 2016 POLITICO New York article about CallNYC" width="1224" height="1584">
        <span>As seen in POLITICO New York</span>
      </a>
      <div class="col s8 l3 preservation-header__action">
        <p>Tell NYC Council and NYC Open Data Portal: <strong>restore constituent services data publishing.</strong></p>
        <a class="btn waves-effect waves-light red darken-3 preservation-header__button" href="{$mailto}">Email the public-data stewards</a>
      </div>
    </div>
  </div>
</section>
HTML;
}
