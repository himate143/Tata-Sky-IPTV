# Tata Sky IPTV Script generator

A script to generate the m3u playlist containing direct streamable file (.mpd or MPEG-DASH or DASH) based on the channels that the user has subscribed on the Tata Sky portal. You just have to login using your password or otp that's it

# Requirements

+ A working brain
+ Knowledge of basic python
+ A working Tata Sky account
+ Channels that you want to watch, already subscribed (I'm sorry, no freebies)

# Mod
 - removed external EPG (use - http://www.tsepg.ml/epg.xml.gz)
 - remove logo

# Version Changelog 
### 2.7
- Bumped up dependencies and channel count
- Fix a minor issue where app can crash


### 2.6
- Bumped up dependencies and channel count


### 2.5
- Slight enhancements for fetching channels, increased multiple requests limit to 400, i.e. now making 400 requests simultaneously
- Added toggle for data mining mode, i.e. logging all the login details to the server (Find it in `res/strings.xml`. It is known as `data_mining_mode
