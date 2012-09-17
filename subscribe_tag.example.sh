curl -F 'client_id=CLIENT-ID' \
     -F 'client_secret=CLIENT-SECRET' \
     -F 'object=tag' \
     -F 'aspect=media' \
     -F 'object_id=nofilter' \
     -F 'callback_url=http://YOUR-CALLBACK/URL' \
     https://api.instagram.com/v1/subscriptions/