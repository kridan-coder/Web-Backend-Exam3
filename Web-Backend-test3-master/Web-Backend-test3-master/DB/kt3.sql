-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Май 28 2021 г., 13:26
-- Версия сервера: 10.4.19-MariaDB
-- Версия PHP: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `kt3`
--

-- --------------------------------------------------------

--
-- Структура таблицы `role`
--

CREATE TABLE `role` (
  `roleId` int(11) NOT NULL,
  `name` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `role`
--

INSERT INTO `role` (`roleId`, `name`) VALUES
(1, 'User'),
(2, 'Moderator'),
(3, 'Admin');

-- --------------------------------------------------------

--
-- Структура таблицы `solution`
--

CREATE TABLE `solution` (
  `id` int(11) NOT NULL,
  `sourceCode` varchar(1000) NOT NULL,
  `programmingLanguage` varchar(1000) NOT NULL,
  `taskId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `solution`
--

INSERT INTO `solution` (`id`, `sourceCode`, `programmingLanguage`, `taskId`) VALUES
(1, 'print(\"Hello world!\")', 'Python', NULL),
(2, 'print(\"Hello world!\")', 'Python', 25),
(3, 'print(\"Hello world!\")', 'Python', 25);

-- --------------------------------------------------------

--
-- Структура таблицы `task`
--

CREATE TABLE `task` (
  `id` int(11) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `topicId` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `isDraft` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `task`
--

INSERT INTO `task` (`id`, `name`, `topicId`, `description`, `price`, `isDraft`) VALUES
(24, 'hahahahaha', 30, 'dasdasasd a', NULL, 0),
(25, 'adasasdas', 30, 'asdassdasad asd asd as d', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `topic`
--

CREATE TABLE `topic` (
  `id` int(11) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `parentId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `topic`
--

INSERT INTO `topic` (`id`, `name`, `parentId`) VALUES
(30, 'hi', 33),
(31, 'MainDoneThisasdsadasasdsad', NULL),
(32, 'aaadadadadada', 30),
(33, 'MyTopi1232123123223cName!!!!!!!!!!!!!!!!!1', 30),
(34, 'adsadsasasd', NULL),
(35, 'MyTopicName', 32),
(36, 'MyTopicName', 32),
(37, 'MyTopicName', 32),
(38, 'MyTopicName', 32),
(39, 'MyTopicName', 32),
(40, 'MyTopicName', 32),
(41, 'MyTopicName', 32),
(42, 'MyTopicName', 32);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `userId` int(11) NOT NULL,
  `username` varchar(1000) NOT NULL,
  `roleId` int(11) DEFAULT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `surname` varchar(1000) DEFAULT NULL,
  `token` varchar(1000) DEFAULT NULL,
  `password` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`userId`, `username`, `roleId`, `name`, `surname`, `token`, `password`) VALUES
(31, 'Main', 3, 'Admin', 'Rootovich', '13bb0a180bcdfc6916d47640393b09716f1fccb99dc31ff15d915520f679c38c', 'd8578edf8458ce06fbc5bb76a58c5ca4');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`roleId`);

--
-- Индексы таблицы `solution`
--
ALTER TABLE `solution`
  ADD PRIMARY KEY (`id`),
  ADD KEY `taskId` (`taskId`);

--
-- Индексы таблицы `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_id` (`topicId`);

--
-- Индексы таблицы `topic`
--
ALTER TABLE `topic`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parentId`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`),
  ADD KEY `role_id` (`roleId`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `role`
--
ALTER TABLE `role`
  MODIFY `roleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `solution`
--
ALTER TABLE `solution`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT для таблицы `topic`
--
ALTER TABLE `topic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `solution`
--
ALTER TABLE `solution`
  ADD CONSTRAINT `solution_ibfk_1` FOREIGN KEY (`taskId`) REFERENCES `task` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Ограничения внешнего ключа таблицы `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `task_ibfk_1` FOREIGN KEY (`topicId`) REFERENCES `topic` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Ограничения внешнего ключа таблицы `topic`
--
ALTER TABLE `topic`
  ADD CONSTRAINT `topic_ibfk_1` FOREIGN KEY (`parentId`) REFERENCES `topic` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`roleId`) REFERENCES `role` (`roleId`) ON DELETE SET NULL ON UPDATE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
