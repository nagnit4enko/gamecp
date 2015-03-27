CREATE TABLE IF NOT EXISTS `params` (
`id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `key` varchar(1024) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `servers` (
`id` int(11) NOT NULL,
  `type` varchar(128) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `ip` varchar(128) NOT NULL,
  `port` int(11) NOT NULL,
  `settings` varchar(256) DEFAULT NULL,
  `go_status` int(11) NOT NULL DEFAULT '0',
  `go_suspend` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `login` varchar(128) NOT NULL,
  `passwd` varchar(128) NOT NULL,
  `session` varchar(128) DEFAULT NULL,
  `nginx_key` varchar(128) DEFAULT NULL,
  `block` int(11) NOT NULL DEFAULT '0',
  `admin` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `params`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `servers`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`), ADD KEY `name` (`type`);

ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `mail` (`login`,`session`), ADD KEY `block` (`block`);

ALTER TABLE `params`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `servers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
