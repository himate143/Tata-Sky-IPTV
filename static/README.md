# Static URL

- Upload both files to any php-supported hosting (under the public_html folder)
> replace url in Line 7 of download.php
- Now open download.php (xyz.com/download.php). It will download your playlist from GitHub or Vercel and save it as a Txt file(mym3u.txt).
- After that, open mym3u.php (xyz.com/mym3u.php). It will display the URL according to the tvg-id

## You must map it as per channel data to use it in a playlist.
E.g. for id 48, the URL will be - xyz.com/mym3u.php?tvg-id=48
- Your playlist will be like this

```
#EXTM3U x-tvg-url="https://www.tsepg.cf/epg.xml.gz"

#EXTINF:-1 tvg-id="48" tvg-logo="https://ltsk-cdn.s3.eu-west-1.amazonaws.com/jumpstart/Temp_Live/cdn/HLS/Channel/imageContent-141-j5fpeji0-v3/imageContent-141-j5fpeji0-m3.png" group-title="Entertainment", SONY SAB HD
#KODIPROP:inputstream.adaptive.license_type=com.widevine.alpha
#KODIPROP:inputstream.adaptive.license_key=https://xyz.com/mym3u.php?tvg-id=48
https://bpprod7linear.akamaized.net/bpk-tv/irdeto_com_Channel_307/output/manifest.mpd
```
### Set the cron job to execute download.php as per your need to update JWT
