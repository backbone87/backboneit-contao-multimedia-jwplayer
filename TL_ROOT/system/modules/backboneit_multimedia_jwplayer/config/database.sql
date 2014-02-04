-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

CREATE TABLE `tl_bbit_mm_jwp` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',

  `title` varchar(255) NOT NULL default '',
  `fallback` char(1) NOT NULL default '',

  `jwplayer` varchar(255) NOT NULL default '',
  `html5` char(1) NOT NULL default '',
  `smoothing` char(1) NOT NULL default '1',

  `stretching` char(10) NOT NULL default 'uniform',
  `volume` smallint(5) NOT NULL default '90',
  `mute` char(1) NOT NULL default '',
  `repeatplay` char(10) NOT NULL default 'none',
  `autoplay` char(1) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  `size` varchar(255) NOT NULL default '',
  `skin` char(10) NOT NULL default '',
  `css` varchar(255) NOT NULL default '',
  `skinxml` varchar(255) NOT NULL default '',
  `skinswf` varchar(255) NOT NULL default '',
  `dock` char(1) NOT NULL default '1',
  `icons` char(1) NOT NULL default '1',
  `controlbar` varchar(255) NOT NULL default 'over',
  `controlbarIdlehide` char(1) NOT NULL default '',
  `backcolor` varchar(6) NOT NULL default '',
  `frontcolor` varchar(6) NOT NULL default '',
  `lightcolor` varchar(6) NOT NULL default '',
  `screencolor` varchar(6) NOT NULL default '',

  `logo` char(1) NOT NULL default '',
  `logoLink` char(1) NOT NULL default '',
  `logoLinkURL` varchar(255) NOT NULL default '',
  `logoLinkTarget` char(1) NOT NULL default '1',
  `logoFile` varchar(255) NOT NULL default '',
  `logoMargin` smallint(5) NOT NULL default '8',
  `logoPosition` char(12) NOT NULL default 'bottom-left',
  `logoOver` smallint(5) NOT NULL default '100',
  `logoOut` smallint(5) NOT NULL default '50',
  `logoHide` char(1) NOT NULL default '1',
  `logoTimeout` smallint(5) NOT NULL default '3',

  `template` varchar(255) NOT NULL default '',

  PRIMARY KEY  (`id`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `tl_bbit_mm_jwp_plugins` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',

  `plugin` varchar(255) NOT NULL default '',
  `enabled` char(1) NOT NULL default '1',

  `generic_name` varchar(255) NOT NULL default '',
  `generic_params` blob NULL,

  `captions_state` char(1) NOT NULL default '',
  `captions_back` char(1) NOT NULL default '',

  `audiodesc_state` char(1) NOT NULL default '',
  `audiodesc_volume` smallint(5) NOT NULL default '90',
  `audiodesc_ducking` char(1) NOT NULL default '1',
  `audiodesc_debug` char(1) NOT NULL default '',

  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `tl_theme` (
  `bbit_mm_jwp` int(10) unsigned NOT NULL default '0',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `tl_layout` (
  `bbit_mm_jwp` int(10) unsigned NOT NULL default '0',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `tl_content` (
  `bbit_mm_jwp` int(10) unsigned NOT NULL default '0',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
