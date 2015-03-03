CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `login` varchar(128) NOT NULL,
  `passwd` varchar(128) NOT NULL,
  `session` varchar(128) DEFAULT NULL,
  `nginx_key` varchar(128) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `mail` (`login`,`session`);

 CREATE TABLE IF NOT EXISTS `servers` (
`id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip` varchar(128) NOT NULL,
  `port` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


ALTER TABLE `servers`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`), ADD KEY `user_id` (`user_id`);
