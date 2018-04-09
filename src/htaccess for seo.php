
RewriteCond %{HTTP_USER_AGENT} (facebookexternalhit/1.1|Twitterbot|Pinterest|Googlebot|Google.*snippet|LinkedInBot|bingbot|Bingbot|Yahoo|MSNBot|YahooSeeker|baiduspider|rogerbot|embedly|quora\ link\ preview|showyoubot|outbrain|Slackbot|Slack-ImgProxy|Slackbot-LinkExpanding|Site\ Analyzer|SiteAnalyzerBot|Viber|WhatsApp|Telegram|skype|woobot|woopingbog/1.1) [NC]
RewriteRule ^(.*)$ crawl.php?q=$1 [L,QSA]

RewriteRule ^ http://127.0.0.1:8080%{REQUEST_URI} [P]
