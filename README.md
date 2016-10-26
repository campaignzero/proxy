![Campaign Zero Logo](https://github.com/campaignzero/artwork/raw/master/logo/campaign-zero/web/306x128/campaign-zero.png "Campaign Zero Logo")

Image Proxy
===

This repository is setup to load HTTP images over the secure HTTPS protocol.  This is mainly because OpenStates.org returns whatever `photo_url` was provided to them, and sometimes those are HTTP based images.  Since loading insecure content over SSL certificates invalidates the certificate ... which is no good.  Invalid SSL certificates actually prevent Geolocation services on some modern browsers, which is why an image proxy service is needed.

Example Usage:
---

#### Loading an Image:

`https://proxy.joincampaignzero.org/http://www.flsenate.gov/PublishedContent/Senators/2014-2016/Photos/s22_5095.jpg`

This Proxy only works with the following file types:

* .jpg
* .jpeg
* .png
* .gif

Fallback Image:
---

If there are issues returning an image from this proxy, for any reason, you will receive a 1x1 pixel PNG file instead.  Here are the known conditions where an image will fail to load through this proxy:

* HTTP 404 Error
* Invalid URL
* Invalid File Type
* Using `https://proxy.joincampaignzero.org` in your image path