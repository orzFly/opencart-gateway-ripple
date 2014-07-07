opencart-gateway-ripple
=======================

This extension allows you to accept payments in OpenCart via Ripple.com. 

* supports all currencies;
* updates order status from public ripple ledger over JSON-RPC;
* redirects user to their Ripple wallet //send URL;
* creates invoices / sends invoice email upon payment confirmation;
* shows Ripple transaction in Order History;
* configure RPC SSL, user, pass, host, port;
* use Cron jobs to check transactions every 15 minutes;

Usage
-----
1. Download [this plugins](https://github.com/orzFly/opencart-gateway-ripple/archive/master.zip) and unzip into OpenCart's directory.
2. Enable this plugin in OpenCart admin panel.
3. Set up cron jobs.
