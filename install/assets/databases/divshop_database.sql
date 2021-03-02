SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `divs_admins`
--

CREATE TABLE `divs_admins` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `email` varchar(125) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `lastIP` varchar(36) DEFAULT NULL,
  `lastLogin` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `divs_antybot`
--

CREATE TABLE `divs_antybot` (
  `username` varchar(255) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `divs_failedlogins`
--

CREATE TABLE `divs_failedlogins` (
  `id` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `section` varchar(255) NOT NULL,
  `details` varchar(255) NOT NULL,
  `ipAddress` varchar(120) NOT NULL,
  `time` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `divs_guilds`
--

CREATE TABLE `divs_guilds` (
  `uuid` varchar(100) NOT NULL,
  `name` text NOT NULL,
  `tag` text NOT NULL,
  `owner` text NOT NULL,
  `home` text NOT NULL,
  `region` text NOT NULL,
  `regions` text NOT NULL,
  `members` text NOT NULL,
  `points` int(11) NOT NULL,
  `lives` int(11) NOT NULL,
  `ban` bigint(20) NOT NULL,
  `born` bigint(20) NOT NULL,
  `validity` bigint(20) NOT NULL,
  `pvp` tinyint(1) NOT NULL,
  `attacked` bigint(20) DEFAULT NULL,
  `allies` text DEFAULT NULL,
  `info` text DEFAULT NULL,
  `deputy` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `divs_logs`
--

CREATE TABLE `divs_logs` (
  `id` int(11) NOT NULL,
  `user` varchar(60) NOT NULL,
  `section` varchar(255) NOT NULL,
  `details` varchar(255) NOT NULL,
  `logIP` varchar(36) DEFAULT NULL,
  `time` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `divs_modules`
--

CREATE TABLE `divs_modules` (
  `id` int(11) NOT NULL,
  `moduleName` varchar(56) NOT NULL,
  `moduleEnabled` int(1) NOT NULL DEFAULT 1 COMMENT 'Module status: \r\n• 0 - Disabled \r\n• 1 - Enabled '
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `divs_modules`
--

INSERT INTO `divs_modules` (`id`, `moduleName`, `moduleEnabled`) VALUES
(1, 'news', 1),
(2, 'bans', 1),
(3, 'stats', 1),
(4, 'antibot', 1),
(5, 'vouchers', 1),
(6, 'lastbuyers', 1),
(7, 'pages', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `divs_news`
--

CREATE TABLE `divs_news` (
  `id` int(11) NOT NULL,
  `title` varchar(355) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `date` varchar(255) NOT NULL,
  `newsActive` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `divs_pages`
--

CREATE TABLE `divs_pages` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `icon` varchar(120) DEFAULT NULL,
  `link` varchar(355) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `pageActive` int(1) NOT NULL COMMENT ' 	Page status: • 0 - Disabled • 1 - Enabled '
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `divs_payments`
--

CREATE TABLE `divs_payments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `config` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `divs_payments`
--

INSERT INTO `divs_payments` (`id`, `name`, `config`) VALUES
(1, 'MicroSMS.pl', '{\"sms\":{\"userid\":null}}'),
(2, 'PayPal', '{\"address\":null}'),
(3, 'Transfer', '{\"transfer\":{\"shopid\":\"null,\"userid\":null,\"hash\":null}}');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `divs_paypal`
--

CREATE TABLE `divs_paypal` (
  `id` int(11) NOT NULL,
  `service` int(11) NOT NULL,
  `buyer` varchar(255) NOT NULL,
  `transactionId` varchar(255) DEFAULT NULL,
  `hash` varchar(255) NOT NULL,
  `gross` double NOT NULL,
  `currency` varchar(36) NOT NULL,
  `status` varchar(120) NOT NULL,
  `fee` double DEFAULT NULL,
  `payerEmail` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `divs_players`
--

CREATE TABLE `divs_players` (
  `uuid` varchar(36) NOT NULL,
  `name` text NOT NULL,
  `points` int(11) NOT NULL,
  `kills` int(11) NOT NULL,
  `deaths` int(11) NOT NULL,
  `guild` varchar(100) DEFAULT NULL,
  `ban` bigint(20) DEFAULT NULL,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `divs_purchases`
--

CREATE TABLE `divs_purchases` (
  `id` int(11) NOT NULL,
  `buyerName` varchar(255) NOT NULL,
  `service` int(11) NOT NULL,
  `server` int(11) NOT NULL,
  `method` varchar(12) NOT NULL,
  `details` text NOT NULL,
  `payId` varchar(120) NOT NULL,
  `status` varchar(56) NOT NULL,
  `profit` varchar(255) DEFAULT NULL,
  `date` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `divs_regions`
--

CREATE TABLE `divs_regions` (
  `name` varchar(100) NOT NULL,
  `center` text NOT NULL,
  `size` int(11) NOT NULL,
  `enlarge` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `divs_servers`
--

CREATE TABLE `divs_servers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `ip` varchar(255) NOT NULL,
  `port` varchar(36) NOT NULL,
  `serverVersion` varchar(36) DEFAULT NULL,
  `rconPort` varchar(36) NOT NULL,
  `rconPass` varchar(255) NOT NULL,
  `showPort` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `divs_services`
--

CREATE TABLE `divs_services` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `server` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `smsConfig` text DEFAULT NULL,
  `paypalCost` varchar(11) DEFAULT NULL,
  `transferCost` varchar(11) DEFAULT NULL,
  `commands` varchar(255) NOT NULL,
  `serviceActive` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `divs_settings`
--

CREATE TABLE `divs_settings` (
  `id` int(11) NOT NULL,
  `pageTitle` varchar(255) NOT NULL DEFAULT 'DIVShop.pro - Sklep dla serwerów Minecraft',
  `pageDescription` text DEFAULT NULL,
  `pageTags` text DEFAULT NULL,
  `pageCharset` varchar(50) NOT NULL DEFAULT 'utf-8',
  `pageLogo` varchar(355) DEFAULT 'https://cdn-n.divshop.pro/images/divshop-logo.png',
  `pageFavicon` varchar(355) DEFAULT 'https://cdn-n.divshop.pro/images/favicon.png',
  `pageBackground` varchar(355) DEFAULT NULL,
  `pageCustomCSS` varchar(255) DEFAULT NULL,
  `pagePreloader` int(1) NOT NULL DEFAULT 1 COMMENT 'Page preloader status:\r\n • 0 - Disabled\r\n • 1 - Enabled',
  `voucherPrfx` varchar(50) DEFAULT 'rvns_',
  `voucherLength` int(11) NOT NULL DEFAULT 10,
  `pageSidebarPosition` int(1) NOT NULL DEFAULT 1 COMMENT 'Sidebar position:\r\n • 0 - Left\r\n • 1 - Right',
  `pageTheme` varchar(33) NOT NULL DEFAULT 'defaultlight' COMMENT 'Page theme:\r\n • defaultlight - Default Light\r\n • defaultdark - Default Dark\r\n • darkred - Dark-Red',
  `shopDiscordWebhookEnabled` int(1) NOT NULL,
  `shopDiscordWebhookUrl` varchar(255) DEFAULT NULL,
  `shopDiscordWebhookEmbedTitle` varchar(255) NOT NULL,
  `shopDiscordWebhookDesc` varchar(999) NOT NULL DEFAULT 'Gracz {BUYER} zakupił usługę {SERVICE}',
  `shopDiscordWebhookHex` varchar(12) NOT NULL,
  `shopDiscordWebhookBotName` varchar(100) NOT NULL,
  `recaptchaSiteKey` varchar(120) DEFAULT NULL,
  `recaptchaSecretKey` varchar(120) DEFAULT NULL,
  `demoMode` int(1) DEFAULT 0,
  `pageActive` int(1) NOT NULL DEFAULT 1 COMMENT 'Page status:\r\n • 0 - Inactive\r\n • 1 - Active',
  `pageBreakTitle` varchar(999) NOT NULL,
  `pageBreakDescription` varchar(999) DEFAULT NULL,
  `smsOperator` int(1) NOT NULL DEFAULT 1,
  `panelNotes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `divs_settings`
--

INSERT INTO `divs_settings` (`id`, `pageTitle`, `pageDescription`, `pageTags`, `pageCharset`, `pageLogo`, `pageFavicon`, `pageBackground`, `pageCustomCSS`, `pagePreloader`, `voucherPrfx`, `voucherLength`, `pageSidebarPosition`, `pageTheme`, `shopDiscordWebhookEnabled`, `shopDiscordWebhookUrl`, `shopDiscordWebhookEmbedTitle`, `shopDiscordWebhookDesc`, `shopDiscordWebhookHex`, `shopDiscordWebhookBotName`, `recaptchaSiteKey`, `recaptchaSecretKey`, `demoMode`, `pageActive`, `pageBreakTitle`, `pageBreakDescription`, `smsOperator`, `panelNotes`) VALUES
(1, 'DIVShop.pro - Sklep dla serwerów Minecraft', 'DIVShop.pro to projekt sklepu dla serwerów Minecraft', NULL, 'utf-8', 'https://cdn-n.divshop.pro/images/divshop-logo.png', 'https://cdn-n.divshop.pro/images/divshop-avatar.png', NULL, NULL, 1, 'divs_', 10, 0, 'defaultlight', 0, NULL, 'Zakupiono usługę', 'Gracz {BUYER} zakupił usługę {SERVICE}', '2c3e50', 'DIVShop.pro', '', '', 0, 1, 'Przerwa techniczna', NULL, 1, 'Dziękujemy za wybranie i instalację naszego sklepu.\r\n\r\nPozdrawiamy - Zespół DIVShop.pro');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `divs_transfers`
--

CREATE TABLE `divs_transfers` (
  `id` int(11) NOT NULL,
  `service` int(11) NOT NULL,
  `serviceName` varchar(255) DEFAULT NULL,
  `serverName` varchar(255) DEFAULT NULL,
  `payId` varchar(255) NOT NULL,
  `orderId` int(11) DEFAULT NULL,
  `buyer` varchar(255) NOT NULL,
  `control` varchar(255) NOT NULL,
  `amount` double NOT NULL,
  `profit` double DEFAULT NULL,
  `test` int(1) NOT NULL DEFAULT 0,
  `status` varchar(120) NOT NULL,
  `payerEmail` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `divs_vouchers`
--

CREATE TABLE `divs_vouchers` (
  `id` int(11) NOT NULL,
  `server` int(11) NOT NULL,
  `service` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `generated` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `punishmenthistory`
--

CREATE TABLE `punishmenthistory` (
  `id` int(11) NOT NULL,
  `name` varchar(16) DEFAULT NULL,
  `uuid` varchar(35) DEFAULT NULL,
  `reason` varchar(100) DEFAULT NULL,
  `operator` varchar(16) DEFAULT NULL,
  `punishmentType` varchar(16) DEFAULT NULL,
  `start` mediumtext DEFAULT NULL,
  `end` mediumtext DEFAULT NULL,
  `calculation` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `punishments`
--

CREATE TABLE `punishments` (
  `id` int(11) NOT NULL,
  `name` varchar(16) DEFAULT NULL,
  `uuid` varchar(35) DEFAULT NULL,
  `reason` varchar(100) DEFAULT NULL,
  `operator` varchar(16) DEFAULT NULL,
  `punishmentType` varchar(16) DEFAULT NULL,
  `start` mediumtext DEFAULT NULL,
  `end` mediumtext DEFAULT NULL,
  `calculation` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `divs_admins`
--
ALTER TABLE `divs_admins`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `divs_antybot`
--
ALTER TABLE `divs_antybot`
  ADD PRIMARY KEY (`username`);

--
-- Indeksy dla tabeli `divs_failedlogins`
--
ALTER TABLE `divs_failedlogins`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `divs_guilds`
--
ALTER TABLE `divs_guilds`
  ADD PRIMARY KEY (`uuid`);

--
-- Indeksy dla tabeli `divs_logs`
--
ALTER TABLE `divs_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `divs_modules`
--
ALTER TABLE `divs_modules`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `divs_news`
--
ALTER TABLE `divs_news`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `divs_pages`
--
ALTER TABLE `divs_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `divs_payments`
--
ALTER TABLE `divs_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `divs_paypal`
--
ALTER TABLE `divs_paypal`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `divs_players`
--
ALTER TABLE `divs_players`
  ADD PRIMARY KEY (`uuid`);

--
-- Indeksy dla tabeli `divs_purchases`
--
ALTER TABLE `divs_purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `divs_regions`
--
ALTER TABLE `divs_regions`
  ADD PRIMARY KEY (`name`);

--
-- Indeksy dla tabeli `divs_servers`
--
ALTER TABLE `divs_servers`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `divs_services`
--
ALTER TABLE `divs_services`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `divs_settings`
--
ALTER TABLE `divs_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `divs_transfers`
--
ALTER TABLE `divs_transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `divs_vouchers`
--
ALTER TABLE `divs_vouchers`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `punishmenthistory`
--
ALTER TABLE `punishmenthistory`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `punishments`
--
ALTER TABLE `punishments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `divs_admins`
--
ALTER TABLE `divs_admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `divs_failedlogins`
--
ALTER TABLE `divs_failedlogins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `divs_logs`
--
ALTER TABLE `divs_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `divs_modules`
--
ALTER TABLE `divs_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT dla tabeli `divs_news`
--
ALTER TABLE `divs_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `divs_pages`
--
ALTER TABLE `divs_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `divs_payments`
--
ALTER TABLE `divs_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `divs_paypal`
--
ALTER TABLE `divs_paypal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `divs_purchases`
--
ALTER TABLE `divs_purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `divs_servers`
--
ALTER TABLE `divs_servers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `divs_services`
--
ALTER TABLE `divs_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `divs_settings`
--
ALTER TABLE `divs_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `divs_transfers`
--
ALTER TABLE `divs_transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `divs_vouchers`
--
ALTER TABLE `divs_vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `punishmenthistory`
--
ALTER TABLE `punishmenthistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `punishments`
--
ALTER TABLE `punishments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
