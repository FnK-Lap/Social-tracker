Social-tracker
==============
[![Build Status](https://travis-ci.org/FnK-Lap/Social-tracker.svg?branch=develop)](https://travis-ci.org/FnK-Lap/Social-tracker)


A school project for Sup'Internet.

Connect to Facebook, Instagram, Twitter and Youtube and get your own feed in one website.

Instagram:
  - Get your feed (images and videos)
  - Like media
  - Get last comments on a media
  - Get number of like and comment

Facebook:
  - Get your feed (status, videos, photos and link)
  - Post a status
  
Twitter:
  - In progress

Youtube:
  - In progress

#Instalation
Change the Client_id and Secret for each social network with your own ids
```YML
#app/config/parameters.yml
instagram_client_id:  YourOwnInstagramClientId
instagram_secret:     YourOwnInstagramSecret

facebook_client_id:   YourOwnFacebookClientId
facebook_secret:      YourOwnFacebookSecret
```

#Commands
Commands to fetch new posts

For Instagram: 
```BASH
php app/console social:instagram:fetch
````

For Facebook:
```BASH
php app/console social:facebook:fetch
```
