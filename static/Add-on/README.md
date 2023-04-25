# Add-on
Here are the add-on scripts to enhance your playlist file
#

> #### filter-url.php

Use an existing file or from the URL, sort it according to the data from the channels.txt file, and replace the License URL with your server URL

> #### filter-gist.php

Use an existing file or from the URL, sort it according to the data from the channels.txt file, and upload it to gist.

> #### filter-replace-gist.php

Use an existing file or from the URL, sort it according to the data from the channels.txt file, replace any text if required as per the replace.txt, and upload it to gist.

> #### extract-id-name.php

You can extract only id, name, or id with name from your playlist

> #### cf-worker.js

Run cronjob on Cloudflare worker, use single or two URL scripts, then set trigger time as convenient to update playlist automatically. I am running it at 12 hr intervals 

### channels.txt
The data needs to be a tvg id; optionally, you can add a channel name for convenience.

e.g.
```
8
8 - STAR Plus HD
8 - Star plus
```
### replace.txt

The data needs to be OLD = NEW
Here I've replaced the star gold non-working MPD with a working one

e.g.

```
6574644 = 6575549
```



> PS - If channels.txt is empty or does not exist, then it will process the playlist as it is without any sorting.
