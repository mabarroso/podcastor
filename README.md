== Deploy
    cap production deploy

== Cron
   11      *  *   *      *        cd /www/prod/site/mabarroso.com/_cron/podcastcollector && /usr/bin/php src/Download.php >/www/prod/site/mabarroso.com/_data/podcastcollector/_download.log 2>/www/prod/site/mabarroso.com/_data/podcastcollector/_download.err.log
   0     4,16 *   *      *        cd /www/prod/site/mabarroso.com/_cron/podcastcollector && /usr/bin/php src/Collector.php >/www/prod/site/mabarroso.com/_data/podcastcollector/_collector.log 2>/www/prod/site/mabarroso.com/_data/podcastcollector/_collector.err.log
