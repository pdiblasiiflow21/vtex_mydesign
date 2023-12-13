CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255),
  `role` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `stores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appkey` text NOT NULL,
  `apptoken` text NOT NULL,
  `environment` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `city` varchar(255) not null,
  `state` varchar(255) not null,
  `street` varchar(255) not null,
  `street_number` int(11) not null,
  `postal_code` varchar(255) not null,
  `user_api_iflow` varchar(255) not null,
  `pass_api_iflow` varchar(255) not null,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id_vtex` varchar(255) not null,
  `tracking_id` varchar(255) not null,
  `invoice_number` varchar(255) not null,
  `response_order_iflow` text not null,
  `response_tracking_vtex` text not null,
  `created` timestamp default CURRENT_TIMESTAMP,
  UNIQUE (`order_id_vtex`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert into users(username, password, role, created_at) values('iflow', '$2y$10$UsbpCqQR005BJYK4i8Fjc.sbiKa/UcRukDmUjxE/RUq5UES53wZtK', 'admin', current_timestamp);