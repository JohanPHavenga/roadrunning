<!-- BEGIN: PAGE CONTAINER -->
<div class="c-layout-page">
    <?= $title_bar; ?>
    <!-- END: LAYOUT/BREADCRUMBS/BREADCRUMBS-1 -->

    <!-- BEGIN: PAGE CONTENT -->

    <!-- BEGIN: CONTENT/FEATURES/FEATURES-1 -->
    <div class="c-content-box c-size-md c-bg-white">
        <div class="container">

            <div class="row">
                TEST
            </div>
        </div>
    </div>
    <!-- END: CONTENT/FEATURES/FEATURES-1 -->

    <!-- END: PAGE CONTENT -->
</div>
<!-- END: PAGE CONTAINER -->


<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "Event",
  "name": "TEST",
  "startDate": "2017-04-24T19:30-08:00",
  "location": {
    "@type": "Place",
    "name": "Santa Clara City Library, Central Park Library",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "2635 Homestead Rd",
      "addressLocality": "Santa Clara",
      "postalCode": "95051",
      "addressRegion": "CA",
      "addressCountry": "US"
    }
  },
  "image": "http://www.example.com/event_image/12345",
  "description": "Join us for an afternoon of Jazz with Santa Clara resident and pianist Andy Lagunoff. Complimentary food and beverages will be served.",
  "endDate": "2017-04-24T23:00-08:00",
  "offers": {
    "@type": "Offer",
    "url": "https://www.example.com/event_offer/12345_201803180430",
    "price": "30",
    "priceCurrency": "USD",
    "availability": "http://schema.org/InStock",
    "validFrom": "2017-01-20T16:20-08:00"
  },
  "performer": {
    "@type": "PerformingGroup",
    "name": "Andy Lagunoff"
  }
}
</script>