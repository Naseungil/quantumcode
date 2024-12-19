-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 24-12-17 08:42
-- 서버 버전: 10.4.32-MariaDB
-- PHP 버전: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 데이터베이스: `quantumcode`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `lecture_order`
--

CREATE TABLE `lecture_order` (
  `odid` int(11) NOT NULL COMMENT '주문번호',
  `mid` varchar(100) NOT NULL COMMENT '회원 고유번호',
  `lid` varchar(20) NOT NULL COMMENT '강의 고유번호',
  `cid` int(11) DEFAULT NULL COMMENT '쿠폰 고유 번호',
  `total_price` decimal(10,0) NOT NULL COMMENT '최종 결제 가격',
  `status` tinyint(4) NOT NULL COMMENT '주문 상태',
  `createdate` datetime NOT NULL DEFAULT current_timestamp() COMMENT '주문 시각'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `lecture_order`
--
ALTER TABLE `lecture_order`
  ADD PRIMARY KEY (`odid`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `lecture_order`
--
ALTER TABLE `lecture_order`
  MODIFY `odid` int(11) NOT NULL AUTO_INCREMENT COMMENT '주문번호', AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
