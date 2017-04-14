DROP TABLE IF EXISTS `glpi_plugin_fournitures_fournitures`;
CREATE TABLE `glpi_plugin_fournitures_fournitures` (
   `id` int(11) NOT NULL auto_increment,
   `entities_id` int(11) NOT NULL default '0',
   `name` varchar(255) collate utf8_unicode_ci default NULL,
   `plugin_fournitures_fournituretypes_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_fournitures_fournituretypes (id)',
   `plugin_fournitures_fournituremarques_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_fournitures_fournituremarques (id)',
   `plugin_fournitures_fournituremodeles_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_fournitures_fournituremodeles (id)',
   `quantite` int(11) NOT NULL default '0',
   `seuil` int(11) NOT NULL default '0',
   `date_mod` datetime default NULL,
   `comment` text collate utf8_unicode_ci,
   `notepad` longtext collate utf8_unicode_ci,
   `is_deleted` tinyint(1) NOT NULL default '0',
   PRIMARY KEY  (`id`),
   KEY `name` (`name`),
   KEY `entities_id` (`entities_id`),
   KEY `plugin_fournitures_fournituretypes_id` (`plugin_fournitures_fournituretypes_id`),
   KEY `plugin_fournitures_fournituremarques_id` (`plugin_fournitures_fournituremarques_id`),
   KEY `plugin_fournitures_fournituremodeles_id` (`plugin_fournitures_fournituremodeles_id`),
   KEY `is_deleted` (`is_deleted`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_fournitures_fournituretypes`;
   CREATE TABLE `glpi_plugin_fournitures_fournituretypes` (
   `id` int(11) NOT NULL auto_increment,
   `name` varchar(255) collate utf8_unicode_ci default NULL,
   `comment` text collate utf8_unicode_ci,
   PRIMARY KEY  (`id`),
   KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_fournitures_fournituremarques`;
   CREATE TABLE `glpi_plugin_fournitures_fournituremarques` (
   `id` int(11) NOT NULL auto_increment,
   `name` varchar(255) collate utf8_unicode_ci default NULL,
   `comment` text collate utf8_unicode_ci,
   PRIMARY KEY  (`id`),
   KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_fournitures_fournituremodeles`;
   CREATE TABLE `glpi_plugin_fournitures_fournituremodeles` (
   `id` int(11) NOT NULL auto_increment,
   `name` varchar(255) collate utf8_unicode_ci default NULL,
   `comment` text collate utf8_unicode_ci,
   PRIMARY KEY  (`id`),
   KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_fournitures_profiles`;
CREATE TABLE `glpi_plugin_fournitures_profiles` (
   `id` int(11) NOT NULL auto_increment,
   `profiles_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
   `fournitures` char(1) collate utf8_unicode_ci default NULL,
   PRIMARY KEY  (`id`),
   KEY `profiles_id` (`profiles_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginFournituresFourniture','3','2','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginFournituresFourniture','4','3','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginFournituresFourniture','5','4','0');