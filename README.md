# FeaturedUser
MediaWiki extension to enhance SocialProfiles. Extension allows to place an user (incl. avatar) and several social profile elements into any article, using the ```<featureduser user="User" bits="bit1,bit2,bit3" />``` tag.

![Demo of two user profiles in table](http://i.imgur.com/sM4yucq.png)

## Installation

1. Make sure SocialPages extension is installed correctly.
1. Clone FeaturedUser extension in your ```extensions/``` folder 

        cd extensions
        git clone https://github.com/magnific0/FeaturedUser.git

1. Add the following line to your ```LocaleSettings``` (including any configuration variables)

        require_once("$IP/extensions/FeaturedUser/FeaturedUser.php");

## Configuration

The following configuration options are available:

* ```$wgFeaturedUser['avatar']``` display the user avatar (default ```true```)
* ```$wgFeaturedUser['points']``` display the user points (default ```true```)
* ```$wgFeaturedUser['titles']``` display titles in front of the bits (default ```true```)
* ```$wgFeaturedUser['inline']``` display titles inline with the bits (default ```true```)

## Supported bits
The current list of bits supported are: 

* **Personal info** ```real_name```, ```location```, ```hometown```, ```birthday```, ```occupation```, ```websites```, ```places_lived```, ```schools```, ```about```
* **Other info** ```movies```, ```tv```, ```music```, ```books```, ```video-games```, ```magazines```, ```snacks```, ```drinks```
* **Custom info** ```custom_1```, ```custom_2```, ```custom_3```, ```custom_4```

## Information ##
This extension is developed for [MediaWiki](https://www.mediawiki.org/wiki/MediaWiki) to work together with the [SocialProfile extension](https://www.mediawiki.org/wiki/Extension:SocialProfile). It is based on the [RandomFeaturedUser extension](https://www.mediawiki.org/wiki/Extension:RandomFeaturedUser).
