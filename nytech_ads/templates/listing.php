<?php
$remote_id = 123; // Replace with the actual remote ID
$title = 'example-title'; // Replace with the actual title
$remote_id = '123';
$title = 'example-title';

// Generate the link using add_query_arg()
$link = add_query_arg(
  array(
    'nytech_listing' => '1',
    'remote_id' => $remote_id,
    'title' => $title
  ),
  home_url('/listings/')
);
?>
<div class="listing--listed">
  <div class="rtcl">
    <div class="single-listing">
      <div id="rtcl-listing-74329" class="listing-item rtcl-listing-item post-74329 status-publish is-new is-gear rtcl_category-footwear rtcl_location-andorra">
        <div class="row">
          <!-- Main content -->
          <div class="order-1 listing-content col-sm-12">
            <div class="mb-4 rtcl-single-listing-details">
              <!-- Meta data -->
              <div class="rtcl-listing-meta">
                <div class="row">
                  <div class="col-sm-12">
                    <?php if($ad['ad_type'] == 'BIKE') { ?>
                      <h2>
                        <a href="<?php print $link; ?>"><?php print $ad['title'] ?></a>
                      </h2>
                    <?php } else { ?>
                      <a href="<?php print $link; ?>">
                        <h3><?php print $ad['ad_type'] . ' > ' . $ad['category']; ?></h3>
                        <?php print $ad['title'] ?>
                      </a>
                    <?php } ?>
                  </div>
                </div>
                <div class="rtcl-listing-badge-wrap">
                  <span class="badge rtcl-badge-new">New</span>
                </div>
                <ul class="rtcl-listing-meta-data">
                  <li class="updated"><i class="rtcl-icon rtcl-icon-clock"></i> <?php print $ad['created_date']; ?></li>
                  <li class="author"><i class="rtcl-icon rtcl-icon-user"></i> <?php print $ad['name']; ?></li>
                  <li class="rt-categories"><i class="rtcl-icon rtcl-icon-tags"></i> <?php print $ad['category']; ?></li>
                  <li class="rt-location"><i class="rtcl-icon rtcl-icon-location"></i> <?php print $ad['country']; ?>, <?php if($ad['zip']) { print 'ZIP ' . $ad['zip']; } ?></li>
                  <li class="rt-views"><i class="rtcl-icon rtcl-icon-eye"> </i> <?php print $ad['view_count']; ?> views</li>
                </ul>
              </div>
              <div class="row rtcl-main-content-wrapper">
                <div class="col-md-8">
                  <div class="rtcl-price-wrap">
                    <div class="rtcl-price price-type-regular">
                      <span class="rtcl-price-amount amount">
                        <bdi><span class="rtcl-price-currencySymbol">$</span><?php print $ad['price']; ?></bdi></span>
                    </div>
                  </div>
                  <div class="rtcl-listing-description">
                    <p><?php print $ad['details']; ?></p>
                  </div>
                </div>
              </div>
              <div class="swp-content-locator"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>