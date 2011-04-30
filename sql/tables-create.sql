
CREATE TABLE `scrl_role_capability` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_role`       BIGINT UNSIGNED NOT NULL,
  `id_capability` BIGINT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* separator */

CREATE TABLE `scrl_user_role` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_user`    BIGINT UNSIGNED NOT NULL,
  `id_role`    BIGINT UNSIGNED NOT NULL,
  `id_context` BIGINT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* separator */

CREATE TABLE `scrl_capability` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title`          VARCHAR(150) NOT NULL,
  `sortOrder`    BIGINT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* separator */

CREATE TABLE `scrl_context` (
  `id`     BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_contextType` BIGINT UNSIGNED,
  `title`           VARCHAR(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* separator */

CREATE TABLE `scrl_user_capability` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_user`       BIGINT UNSIGNED NOT NULL,
  `id_capability` BIGINT UNSIGNED NOT NULL,
  `id_context`    BIGINT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* separator */

CREATE TABLE `scrl_role` (
  `id`    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title`       VARCHAR(150) NOT NULL,
  `sortOrder` BIGINT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* separator */

CREATE TABLE `scrl_contextType` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title`           VARCHAR(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
