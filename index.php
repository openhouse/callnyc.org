<?php
/*
Copyright 2016 Thick Arts LLC

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

*/

  //phpinfo();
  //exit();


  //var_dump(pathinfo($_SERVER['REQUEST_URI']));
  // super simple router

  $pathInfo = pathinfo($_SERVER['REQUEST_URI']);
  $active['category'] = substr($pathInfo["dirname"],1);
  $active['subCategory'] = $pathInfo["filename"];

  //var_dump($active);

  include_once('functions.php');

  function safe_trim($value, string $character_mask = " \t\n\r\0\x0B"): string {
    return trim((string)($value ?? ''), $character_mask);
  }

  //open db
  include_once('library/db.php');
  $db = get_db_connection();

  $baseUrl = base_url();
  $advocacyRecipients = 'data@council.nyc.gov,opendatateam@oti.nyc.gov';
  $advocacySubject = 'Restore constituent services data publishing';
  $advocacyBody = "Hello NYC Council Data Team and NYC Open Data Team,\n\nPlease establish a privacy-protected public data connection from Council Connect to the NYC Open Data Portal.\n\nNYC Open Data preserves Council casework from 2015 through early 2025 as historical data, while a current Council posting describes Council Connect as the Council-wide constituent service database. By comparison, NYC publishes 311 service requests as a daily, current dataset.\n\nPlease publish a documented, regularly updated aggregate or de-identified Council Connect feed so New Yorkers can understand the services Council offices provide without exposing constituent case details.\n\nThank you.";
  $advocacyMailto = 'mailto:' . $advocacyRecipients
    . '?subject=' . rawurlencode($advocacySubject)
    . '&body=' . rawurlencode($advocacyBody);



  include_once('components/category-tree/category-tree.php');
  $categoryTree = getCategoryTree($db);
  //var_dump($categoryTree);


  $hasActiveRoute = isset($categoryTree[$active['category']]['subCategories'][$active['subCategory']]);
  $activeCategory = $hasActiveRoute ? $categoryTree[$active['category']] : null;
  $activeSubCategory = $hasActiveRoute ? $categoryTree[$active['category']]['subCategories'][$active['subCategory']] : null;
  if (!$activeCategory || !$activeSubCategory) {
    $defaultCategorySlug = array_key_first($categoryTree);
    $activeCategory = $defaultCategorySlug ? $categoryTree[$defaultCategorySlug] : null;
    $defaultSubCategorySlug = $activeCategory ? array_key_first($activeCategory['subCategories'] ?? []) : null;
    $activeSubCategory = $defaultSubCategorySlug ? $activeCategory['subCategories'][$defaultSubCategorySlug] : null;
  }
  $activeCategoryName = $activeCategory['name'] ?? '';
  $activeCategorySlug = $activeCategory['slug'] ?? '';
  $activeSubCategoryName = $activeSubCategory['name'] ?? '';
  $activeSubCategorySlug = $activeSubCategory['slug'] ?? '';
  //var_dump($activeCategory);
  //var_dump($activeSubCategory);


  // get member data
  $contents = substr(file_get_contents('data/districts-data.js'), 20);

  $results = json_decode($contents,true);

  foreach( $results['features'] as &$feature ) {
    $member['name'] = $feature["properties"]["description"];
    $member['districtFull'] = $feature["properties"]["title"];
    $member['district'] = intval( $feature["properties"]["number"] );
    $allMembers[$member['district']] = $member;
  }


  if($hasActiveRoute){


    $statement = $db->prepare("SELECT `ACCOUNT`, COUNT(*) AS count FROM `cases` WHERE `COMPLAINT_TYPE` LIKE :complaint_type AND `DESCRIPTOR` LIKE :descriptor AND `BOROUGH` != '' GROUP BY `ACCOUNT` ORDER BY count DESC LIMIT 10");
    $statement->execute([
      ':complaint_type' => $activeCategory["COMPLAINT_TYPE"],
      ':descriptor' => $activeSubCategory["DESCRIPTOR"],
    ]);
    foreach( $statement->fetchAll() as $row ) {
      $member['ACCOUNT'] = $row['ACCOUNT'];
      $member['count'] = $row['count'];
      $member['monthly'] = ceil($member['count'] / 428 * (365.25/12)); //TODO: get number of days in data
      $member['annual'] = ceil($member['count'] / 428 * (365.25)); //TODO: get number of days in data

      $member['district'] = intval(trim(substr(trim($member['ACCOUNT']),4)));
      $member['name'] = $allMembers[$member['district']]['name'];
      $member['districtFull'] = $allMembers[$member['district']]['districtFull'];
      $member['categories'] = [];

      $statement2 = $db->prepare("SELECT `COMPLAINT_TYPE`, `DESCRIPTOR`, COUNT(*) AS count FROM `cases` WHERE `ACCOUNT` LIKE :account AND `BOROUGH` != '' GROUP BY `COMPLAINT_TYPE`, `DESCRIPTOR` ORDER BY count DESC LIMIT 7");
      $statement2->execute([
        ':account' => $member['ACCOUNT'],
      ]);
      foreach( $statement2->fetchAll() as $row2 ) {
        $memberCategory['name'] = safe_trim($row2["DESCRIPTOR"], ' /');
        $memberCategory['DESCRIPTOR'] = $row2["DESCRIPTOR"];
        $memberCategory['slug'] = slugify($memberCategory['name']);
        $memberCategory['count'] = $row2["count"];

        $memberCategory['parent']['name'] = safe_trim($row2["COMPLAINT_TYPE"], ' /');
        $memberCategory['parent']['COMPLAINT_TYPE'] = $row2["COMPLAINT_TYPE"];
        $memberCategory['parent']['slug'] = slugify($memberCategory['parent']['name']);
        $memberCategory['url']="/".$memberCategory['parent']['slug'].'/'.$memberCategory['slug'].'.html';

        if(
          $memberCategory['parent']['slug'] != 'n-a'
          && $memberCategory['parent']['slug'] != 'select-one'
          && $memberCategory['parent']['slug'] != 'other'
          && $memberCategory['slug'] != 'n-a'
          && $memberCategory['slug'] != 'select'
        ){
          $member['categories'][]=$memberCategory;
        }
      }
      $members[$row['ACCOUNT']]=$member;
    }
    $frontPage=false;

  } else {
    //nocategory

    $frontPage=true;

    $query = "SELECT `ACCOUNT`, COUNT(*) AS count FROM `cases` WHERE `BOROUGH` != '' GROUP BY `ACCOUNT` ORDER BY count DESC";
    foreach( $db->query($query) as $row ) {
      $member['ACCOUNT'] = $row['ACCOUNT'];
      $member['count'] = $row['count'];
      $member['monthly'] = ceil($member['count'] / 428 * (365.25/12)); //TODO: get number of days in data
      $member['annual'] = ceil($member['count'] / 428 * (365.25)); //TODO: get number of days in data

      $member['district'] = intval(trim(substr(trim($member['ACCOUNT']),4)));
      $member['name'] = $allMembers[$member['district']]['name'];
      $member['districtFull'] = $allMembers[$member['district']]['districtFull'];
      $member['categories'] = [];

      $statement2 = $db->prepare("SELECT `COMPLAINT_TYPE`, `DESCRIPTOR`, COUNT(*) AS count FROM `cases` WHERE `ACCOUNT` LIKE :account AND `BOROUGH` != '' GROUP BY `COMPLAINT_TYPE`, `DESCRIPTOR` ORDER BY count DESC LIMIT 7");
      $statement2->execute([
        ':account' => $member['ACCOUNT'],
      ]);
      foreach( $statement2->fetchAll() as $row2 ) {
        $memberCategory['name'] = safe_trim($row2["DESCRIPTOR"], ' /');
        $memberCategory['DESCRIPTOR'] = $row2["DESCRIPTOR"];
        $memberCategory['slug'] = slugify($memberCategory['name']);
        $memberCategory['count'] = $row2["count"];

        $memberCategory['parent']['name'] = safe_trim($row2["COMPLAINT_TYPE"], ' /');
        $memberCategory['parent']['COMPLAINT_TYPE'] = $row2["COMPLAINT_TYPE"];
        $memberCategory['parent']['slug'] = slugify($memberCategory['parent']['name']);
        $memberCategory['url']="/".$memberCategory['parent']['slug'].'/'.$memberCategory['slug'].'.html';

        if(
          $memberCategory['parent']['slug'] != 'n-a'
          && $memberCategory['parent']['slug'] != 'select-one'
          && $memberCategory['parent']['slug'] != 'other'
          && $memberCategory['slug'] != 'n-a'
          && $memberCategory['slug'] != 'select'
        ){
          $member['categories'][]=$memberCategory;
        }
      }
      $members[$row['ACCOUNT']]=$member;
    }




  }

  include_once('library/closedb.php');

?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="description" content="CallNYC is a preserved 2016 civic-data project for exploring New York City Council constituent services. The historical data is not current.">
    <?php
      if($frontPage){
        ?>
        <title>Call NYC - Free Assistance from Top NYC Council Members</title>
        <link rel="canonical" href="<?php echo $baseUrl; ?>" />
        <meta name="apple-mobile-web-app-title"
              content="Call NYC">

        <?php
      } else {
        ?>
          <title><?php echo ucwords($activeSubCategoryName) ?> Assistance Top <?php echo count($members);?> - CallNYC.org | Free <?php echo ucwords($activeCategoryName) ?> Services from New York City Council</title>
          <link rel="canonical" href="<?php echo $baseUrl; ?>/<?php echo $activeCategorySlug;?>/<?php echo $activeSubCategorySlug;?>.html" />
          <meta name="apple-mobile-web-app-title"
                content="<?php echo ucwords($activeSubCategoryName) ?>">

        <?php
      }
    ?>

    <!-- Favicons-->

    <!--  Android 5 Chrome Color-->
    <meta name="theme-color" content="#EE6E73">
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#EE6E73">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">


    <meta name="apple-mobile-web-app-capable"
          content="yes">


    <!-- CSS-->
    <!-- <link href="/css/prism.css" rel="stylesheet"> -->
    <link href="/css/ghpages-materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="/css/launch.css" type="text/css" rel="stylesheet" media="screen,projection">

    <style>
      ul#nav-mobile.side-nav.fixed {
        overflow-x: hidden !important;
      }
      .side-nav {
        overflow: hidden;

        overflow-y: scroll; /* has to be scroll, not auto */
        -webkit-overflow-scrolling: touch;

      }
      html, body {
        width: 100%;
        overflow-x: hidden;
      }

      .chip {
        overflow: hidden;
      }
      ul.side-nav.fixed li a {
        overflow: hidden;
      }

      nav.top-nav {
        overflow: hidden;
      }

      .card.large .card-image {
        max-height: 68%;
      }

      .card .card-image .card-title {
        text-shadow: 0 2px 1px rgba(0,0,0,0.48), 0 2px 2px rgba(0,0,0,0.40), 0 2px 5px rgba(0,0,0,0.32), 0 2px 10px rgba(0,0,0,0.24);
      }
    </style>
  </head>
  <body>
    <a class="skip-link" href="#index-banner">Skip to main content</a>
    <header>
      <div class="container"><a href="#" data-activates="nav-mobile" class="button-collapse top-nav full hide-on-large-only" aria-label="Open service categories"><i class="mdi-navigation-menu" aria-hidden="true"></i></a></div>
      <ul id="nav-mobile" class="side-nav fixed">
        <li class="logo"><a id="logo-container" href="/" class="brand-logo">
            <img id="front-page-logo" data-testid="callnyc-logo" src="/call-nyc-logo.svg" alt="Call NYC"></a></li>
        <li class="search">
          <div class="search-wrapper card">
            <input id="search" aria-label="Search service categories"><i class="mdi-action-search" aria-hidden="true"></i>
            <div class="search-results"></div>
          </div>
        </li>
        <li class="no-padding">
          <ul class="collapsible collapsible-accordion">
            <?php
              foreach($categoryTree as &$topCategory){
                ?>
                  <li class="bold"><a href="#!" role="button" aria-expanded="<?php echo $topCategory['slug'] === $activeCategory['slug'] ? 'true' : 'false'; ?>" class="collapsible-header <?php if($topCategory['slug'] === $activeCategory['slug']){echo 'active';}?> waves-effect waves-teal"><?php echo $topCategory['name'];?></a>
                    <div class="collapsible-body">
                      <ul>
                        <?php foreach($topCategory['subCategories'] as &$subCategory ){
                          ?>
                            <li class="<?php if($subCategory['slug'] === $activeSubCategory['slug']){echo 'active';}?>"><a href="/<?php echo $topCategory['slug'];?>/<?php echo $subCategory['slug'];?>.html"><?php echo $subCategory['name'];?></a></li>
                          <?php
                        }?>
                      </ul>
                    </div>
                  </li>
                <?php
              }
            ?>
          </ul>
        </li>
      </ul>
    </header>
    <main>
      <div class="section" id="index-banner">
  <div class="container">
    <div class="row">
      <div class="col s12 m12 flow-text">
        <h1 class="header center-on-small-only" style="font-size: 2.5em;"><?php
            if($frontPage){
              ?>
              Call NYC
              <?php
            } else {
              echo $activeSubCategory['name'];
            }
        ?></h1>
        <h4 class="light red-text text-lighten-4 center-on-small-only " style="font-size: 1.35714285714286em;">
          <?php
            if($frontPage) {
              ?>
                In 2016, New York City Council published anonymized records of <b>free constituent assistance</b> provided by district offices.
                Explore the historical release, then <b>find a current Council contact</b> for help today.
              <?php
            } else {
              ?>
                The <?php if(count($members) > 1){echo count($members);}?> Council member<?php if(count($members)>1){echo 's';}?> with the most <b><span style="text-transform: uppercase;"><?php echo $activeSubCategory['name'];?></span></b> cases in the preserved 2016 dataset.
              <?php
            }
          ?>
        </h4>
      </div>
    </div>
  </div>
</div>

      <section id="archive-status" class="archive-status" aria-labelledby="archive-status-title">
        <div class="container">
          <div class="row archive-status__row">
            <div class="col s12 l5 archive-status__intro">
              <h2 id="archive-status-title">CallNYC is a 2016 civic-data archive</h2>
              <p data-claim-id="claim.callnyc-archival-origin">
                <a href="https://jamieburk.art" target="_blank" rel="noopener noreferrer">Jamie Burkart</a> built CallNYC in a 24-hour sprint from NYC Council’s daily, anonymized CouncilStat data. This preserves the experiment.
              </p>
              <p class="archive-status__limit" data-claim-id="claim.council-connect-publication-gap">
                NYC Open Data labels <a href="https://data.cityofnewyork.us/d/b9km-gdpy" target="_blank" rel="noopener noreferrer">Council casework through early 2025</a> as historical. A <a href="https://council.nyc.gov/amanda-farias/join-our-team-constituent-liaison/" target="_blank" rel="noopener noreferrer">2026 Council posting</a> names Council Connect as its Council-wide constituent-service database; our Aug. 13, 2026 catalog check found no current export comparable to NYC’s <a href="https://data.cityofnewyork.us/d/erm2-nwe9" target="_blank" rel="noopener noreferrer">daily 311 feed</a>. Rankings are historical.
              </p>
            </div>

            <div class="col s12 m6 l3 archive-status__evidence">
              <a class="politico-evidence" data-testid="politico-evidence-link" href="/data/media/Politico-Website-provides-new-information-about-council-members-focus.pdf" target="_blank" rel="noopener noreferrer">
                <img data-testid="politico-thumbnail" src="/data/media/politico-callnyc-2016-page-1.png" alt="First page of the March 14, 2016 Politico New York article about CallNYC">
                <span>
                  <strong>As seen in POLITICO</strong>
                  <span>“Website provides new information about council members' focus”</span>
                  <small>Miranda Neubauer · Mar. 14, 2016</small>
                </span>
              </a>
            </div>

            <div class="col s12 m6 l4 archive-status__action">
              <h3>Restore current public data</h3>
              <p data-claim-id="claim.council-connect-publication-gap">Ask NYC Council and NYC Open Data for a documented, privacy-protected Council Connect feed.</p>
              <a class="btn waves-effect waves-light red lighten-2 restore-data-button" data-testid="restore-data-email" href="<?php echo htmlspecialchars($advocacyMailto, ENT_QUOTES, 'UTF-8'); ?>">
                Restore constituent services data publishing
              </a>
              <small>Opens a draft for your review.</small>
            </div>
          </div>
        </div>
      </section>


      <div class="container">
  <div class="row">
    <div class="col s12 m9 l10">
      <?php if ($frontPage) {
        ?>
          <p class="caption hide">
             New York City Council offers <b>free personal assistance</b> on hundreds of topics to New Yorkers like you every day.
             <b>Find a council member</b> who specializes in your issue and <i>CALL NYC</i>.
          </p>
        <?php
      } else {
        ?>
          <p class="caption hide">
            Need <?php echo $activeSubCategory['name'];?> assistance?  Call a New York City Council Member for <b>free personal help</b>.
          </p>

        <?php
      }
      ?>
    </div>
    <div class="col s12 m5 l5">

      <?php
      $n = 1;

      foreach($members as &$member){
        if($member['district'] > 0) {

          ?>

            <div id="<?php echo trim($member['ACCOUNT'])?>" class="section scrollspy">

              <div class="card">
                <a  target="_blank" href="https://web.archive.org/web/20170710152429/https://council.nyc.gov/district-<?php echo $member['district'];?>/">
                  <div class="card-image">
                    <img src="/data/photos/banner/<?php echo $member['district']?>.jpg" alt="<?php echo htmlspecialchars($member['name'] . ', ' . $member['districtFull'], ENT_QUOTES, 'UTF-8'); ?>">
                    <span class="card-title">
                      <?php if($n){
                        /*
                        <span style="float:left;font-size: 0.75em; padding-right: 0.5em;background-color: #ffcc4c;border-radius: 2.5em;width: 2.5em;height: 2.5em;m;text-align: center;text-shadow: none;color: black;font-weight: bold;padding-top: 0.5em;padding-right: 0;padding-left: 0;margin-right: 0.5em;margin-top: 0.75em;box-shadow: 0.25em 0px rgba(255,204,76,0.68), 0.5em 0px rgba(255,204,76,0.4624), 0.75em 0px rgba(255,204,76,0.314432);" class="">TOP</span>

                        */
                        ?>
                        <span style="float:left;font-size: 0.75em; padding-right: 0.5em;background-color: white;border-radius: 2em;width: 2em;height: 2em;m;text-align: center;text-shadow: none;color: black;font-weight: bold;pa;padding-top: 0.25em;padding-right: 0;padding-left: 0;margin-right: 0.5em;margin-top: 1em;box-shadow: 0 0 0px 3px black;" class=""><?php if($n<10){echo '<span style="font-weight: 300;">#</span>';}?><?Php echo $n; ?></span>

                        <?php
                      }?>
                      <span style="display:inline-block;">
                        <?php echo $member['name']?><br/><small><?php echo $member['districtFull']?></small>
                      </span>
                    </span>
                  </div>
                </a>
                <div class="card-content">
                  <p>
                    <?php if(!$frontPage){
                      ?>
                      <b><?php echo $activeSubCategory['name'];?></b> </br>
                      <?php
                    } else {
                      ?>
                      <b>Overall</b> </br>
                      <?php
                    }?>
                    About <?php echo number_format($member['annual']); ?> annualized case<?php if ($member['annual'] > 1) {echo 's';}?> in the 2016 snapshot
                  </p>
                  <p>
                    <b>Top Services in the Snapshot</b> </br>



                    <?php
                      foreach($member['categories'] as $cat){
                        echo '<a style="" href="'.$cat['url'].'"> <span class="chip" style="margin-bottom:4px;">'.$cat['name']."</span></a> ";
                      }
                    ?>
                  </p>


                </div>
                <div class="card-action">
                  <a target="_blank" rel="noopener noreferrer" href="https://council.nyc.gov/districts/">Find current Council contact <span style="float:right;" aria-hidden="true">→</span></a>
                </div>
              </div>

            </div>


          <?php
          //var_dump($member);
          $n++;
        }
      }
      ?>







    </div>

    <div class="col hide-on-small-only m3 l2 offset-m4 offset-l4">
      <div class="toc-wrapper">
        <div style="height: 1px;">
          <ul class="section table-of-contents">
            <?php
              foreach($members as &$member){
                ?>
                <li><a href="#<?php echo trim($member['ACCOUNT'])?>"><?php echo $member['name']?></a></li>
                <?php
              }
            ?>
          </ul>
        </div>
      </div>
    </div>

  </div>
</div>

    </main>    <footer class="page-footer">
      <div class="container">
        <div class="row">
          <div class="col l8 s12">
            <h5 class="white-text">Powered by 2016 NYC Council Constituent Services Data</h5>
            <p class="grey-text text-lighten-4">This is an archived, unofficial project. Its Council-member roster, rankings, and case counts describe a historical release and should not be read as current constituent-service information.</p>
            <p class="grey-text text-lighten-4" data-claim-id="claim.callnyc-archival-origin">In 2016, New York City Council began publishing anonymized daily records from CouncilStat. CallNYC used that data to make the assistance provided by district offices easier to discover.</p>
            <p class="grey-text text-lighten-4" data-claim-id="claim.council-connect-publication-gap">NYC Open Data now preserves Council casework from 2015 through early 2025 as historical data. Council Connect is the current Council-wide casework database named in a 2026 Council posting, but our Aug. 13, 2026 catalog check found no current Council Connect export. Until a regularly updated public feed exists, there can be no present-tense CallNYC.</p>
            <a class="btn waves-effect waves-light red lighten-3" target="_blank" rel="noopener noreferrer" href="https://web.archive.org/web/20170710152429/https://labs.council.nyc/districts/data/">Explore the archived data page</a>

          </div>


          <?php /*
          <!--
          <div class="col l4 s12 hide">
            <h5 class="white-text">Join the Discussion</h5>
            <p class="grey-text text-lighten-4">We have a Gitter chat room set up where you can talk directly with us. Come in and discuss new features, future goals, general problems or questions, or anything else you can think of.</p>
            <a class="btn waves-effect waves-light red lighten-3" target="_blank" href="https://gitter.im/Dogfalo/materialize">Chat</a>
          </div>
          -->
          */ ?>
          <div class="col l4 s12" style="overflow: hidden;">
            <h5 class="white-text">Connect</h5>
            <a target="_blank" rel="noopener noreferrer" class="waves-effect waves-light btn" href="https://github.com/openhouse/callnyc.org">
              View source on GitHub
            </a>
            <br/>
            <br/>

            <br/>
          </div>
        </div>
      </div>
      <div class="footer-copyright">
        <div class="container">
        2016 Open House Projects
        <?php /*
        <!--<a class="grey-text text-lighten-4 right" href="https://github.com/Dogfalo/materialize/blob/master/LICENSE">MIT License</a>-->
        */ ?>
        </div>
      </div>
    </footer>
    <!--  Scripts-->
    <script src="/bin/jquery-2.2.1.min.js"></script>
    <script src="/js/jquery.timeago.min.js"></script>
    <script src="/jade/lunr.min.js"></script>
    <script src="/search.php"></script>
    <script src="/js/materialize.js"></script>
    <script src="/js/init.js"></script>
  </body>
</html>
