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
                      <h2><?php print $ad['title'] ?></h2>
                    <?php } else { ?>
                      <h3><?php print $ad['ad_type'] . ' > ' . $ad['category']; ?></h3>
                      <?php print $ad['title'] ?>
                    <?php } ?>
                  </div>
                </div>
                <div class="rtcl-listing-badge-wrap"><span class="badge rtcl-badge-new">New</span></div>
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
                    <div class="rtcl-price price-type-regular"><span class="rtcl-price-amount amount"><bdi><span class="rtcl-price-currencySymbol">$</span><?php print $ad['price']; ?></bdi></span></div>                            </div>
                  <div class="rtcl-listing-description"><p> <?php print $ad['details']; ?></p></div></div></div><div class="swp-content-locator"></div></div>
            <div class="order-2 rtcl-listing-bottom-sidebar">
              <div class="listing-sidebar">
                <div class="rtcl-listing-user-info">
                  <div class="rtcl-listing-side-title">
                    <h3>Contact</h3>
                  </div>
                  <div class="list-group">
                    <div class="list-group-item">
                      <div class="media">
                        <span class="rtcl-icon rtcl-icon-location mr-2"></span>
                        <div class="media-body"><span>Location</span>
                          <div class="locations"><?php print $ad['country']; ?>, <?php if($ad['zip']) { print 'ZIP ' . $ad['zip']; } ?></div>
                        </div>
                      </div>
                    </div>
                    <div class="list-group-item reveal-phone" data-options="{&quot;safe_phone&quot;:&quot;3333333XXX&quot;,&quot;phone_hidden&quot;:&quot;333&quot;}">
                      <div class="media">
                        <span class="rtcl-icon rtcl-icon-phone mr-2"></span>
                        <div class="media-body"><span>Contact Info</span>
                          <?php print $ad['name']; ?><br />
                          <?php print $ad['email']; ?><br />
                          <?php if($ad['website']) { print '<a href="' . $ad['website'] . '" target="_blank">' . $ad['website'] . '</a>'; } ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  
  </div>
</div>