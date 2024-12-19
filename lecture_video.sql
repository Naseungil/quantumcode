-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 24-12-18 13:55
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
-- 테이블 구조 `lecture_video`
--

CREATE TABLE `lecture_video` (
  `lvid` int(11) NOT NULL COMMENT '강의영상 고유번호',
  `lid` int(11) NOT NULL COMMENT '연결된 강의 고유번호',
  `t_id` varchar(20) NOT NULL,
  `video_lecture` varchar(100) NOT NULL COMMENT '강의 영상 파일경로',
  `video_desc` text DEFAULT NULL COMMENT '강의 설명',
  `regdate` datetime NOT NULL DEFAULT current_timestamp() COMMENT '등록 시간'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='강의 영상 테이블';

--
-- 테이블의 덤프 데이터 `lecture_video`
--

INSERT INTO `lecture_video` (`lvid`, `lid`, `t_id`, `video_lecture`, `video_desc`, `regdate`) VALUES
(28, 120, 'admin', '/qc/admin/upload/20241218134516128623.mp4', NULL, '2024-12-18 21:45:16'),
(29, 120, 'admin', '/qc/admin/upload/20241218134516142223.mp4', NULL, '2024-12-18 21:45:16'),
(30, 119, 'admin', '/qc/admin/upload/20241218134526814862.mp4', NULL, '2024-12-18 21:45:26'),
(31, 119, 'admin', '/qc/admin/upload/20241218134526495427.mp4', NULL, '2024-12-18 21:45:26'),
(32, 124, 'admin', '/qc/admin/upload/20241218134535928400.mp4', NULL, '2024-12-18 21:45:35'),
(33, 124, 'admin', '/qc/admin/upload/20241218134535162397.mp4', NULL, '2024-12-18 21:45:35'),
(34, 130, 'admin', '/qc/admin/upload/20241218134546258812.mp4', NULL, '2024-12-18 21:45:46'),
(35, 130, 'admin', '/qc/admin/upload/20241218134546867603.mp4', NULL, '2024-12-18 21:45:46'),
(36, 140, 'admin', '/qc/admin/upload/20241218134556183963.mp4', NULL, '2024-12-18 21:45:56'),
(37, 140, 'admin', '/qc/admin/upload/20241218134556572186.mp4', NULL, '2024-12-18 21:45:56'),
(38, 125, 'admin', '/qc/admin/upload/20241218134613158418.mp4', NULL, '2024-12-18 21:46:13'),
(39, 125, 'admin', '/qc/admin/upload/20241218134613960575.mp4', NULL, '2024-12-18 21:46:13'),
(40, 137, 'admin', '/qc/admin/upload/20241218134631108945.mp4', NULL, '2024-12-18 21:46:31'),
(41, 137, 'admin', '/qc/admin/upload/20241218134631904878.mp4', NULL, '2024-12-18 21:46:31'),
(42, 142, 'admin', '/qc/admin/upload/20241218134641139000.mp4', NULL, '2024-12-18 21:46:41'),
(43, 142, 'admin', '/qc/admin/upload/20241218134641134410.mp4', NULL, '2024-12-18 21:46:41'),
(44, 126, 'admin', '/qc/admin/upload/20241218134653145816.mp4', NULL, '2024-12-18 21:46:53'),
(45, 126, 'admin', '/qc/admin/upload/20241218134653441373.mp4', NULL, '2024-12-18 21:46:53'),
(46, 133, 'admin', '/qc/admin/upload/20241218134704181760.mp4', NULL, '2024-12-18 21:47:04'),
(47, 133, 'admin', '/qc/admin/upload/20241218134704165603.mp4', NULL, '2024-12-18 21:47:04'),
(48, 138, 'admin', '/qc/admin/upload/20241218134714164430.mp4', NULL, '2024-12-18 21:47:14'),
(49, 138, 'admin', '/qc/admin/upload/20241218134714111327.mp4', NULL, '2024-12-18 21:47:14'),
(50, 141, 'admin', '/qc/admin/upload/20241218134725497586.mp4', NULL, '2024-12-18 21:47:25'),
(51, 141, 'admin', '/qc/admin/upload/20241218134725197059.mp4', NULL, '2024-12-18 21:47:25'),
(52, 123, 'admin', '/qc/admin/upload/20241218134736172412.mp4', NULL, '2024-12-18 21:47:36'),
(53, 123, 'admin', '/qc/admin/upload/20241218134736156901.mp4', NULL, '2024-12-18 21:47:36'),
(54, 128, 'admin', '/qc/admin/upload/20241218134749524934.mp4', NULL, '2024-12-18 21:47:49'),
(55, 128, 'admin', '/qc/admin/upload/20241218134749182446.mp4', NULL, '2024-12-18 21:47:49'),
(56, 144, 'admin', '/qc/admin/upload/20241218134801179663.mp4', NULL, '2024-12-18 21:48:01'),
(57, 144, 'admin', '/qc/admin/upload/20241218134801105452.mp4', NULL, '2024-12-18 21:48:01'),
(58, 121, 'admin', '/qc/admin/upload/20241218134811114165.mp4', NULL, '2024-12-18 21:48:11'),
(59, 121, 'admin', '/qc/admin/upload/20241218134811908451.mp4', NULL, '2024-12-18 21:48:11'),
(60, 131, 'admin', '/qc/admin/upload/20241218134821231904.mp4', NULL, '2024-12-18 21:48:21'),
(61, 131, 'admin', '/qc/admin/upload/20241218134821127610.mp4', NULL, '2024-12-18 21:48:21'),
(62, 127, 'admin', '/qc/admin/upload/20241218134831608978.mp4', NULL, '2024-12-18 21:48:31'),
(63, 127, 'admin', '/qc/admin/upload/20241218134831906287.mp4', NULL, '2024-12-18 21:48:31'),
(64, 139, 'admin', '/qc/admin/upload/20241218134842189118.mp4', NULL, '2024-12-18 21:48:42'),
(65, 139, 'admin', '/qc/admin/upload/20241218134842205901.mp4', NULL, '2024-12-18 21:48:42'),
(66, 143, 'admin', '/qc/admin/upload/20241218134856681638.mp4', NULL, '2024-12-18 21:48:56'),
(67, 143, 'admin', '/qc/admin/upload/20241218134856190166.mp4', NULL, '2024-12-18 21:48:56'),
(68, 132, 'admin', '/qc/admin/upload/20241218134908148129.mp4', NULL, '2024-12-18 21:49:08'),
(69, 132, 'admin', '/qc/admin/upload/20241218134908604809.mp4', NULL, '2024-12-18 21:49:08'),
(70, 118, 'admin', '/qc/admin/upload/20241218134920123215.mp4', NULL, '2024-12-18 21:49:20'),
(71, 118, 'admin', '/qc/admin/upload/20241218134920771500.mp4', NULL, '2024-12-18 21:49:20'),
(72, 135, 'admin', '/qc/admin/upload/20241218134929195415.mp4', NULL, '2024-12-18 21:49:29'),
(73, 135, 'admin', '/qc/admin/upload/20241218134929109403.mp4', NULL, '2024-12-18 21:49:29'),
(74, 116, 'admin', '/qc/admin/upload/20241218134940192942.mp4', NULL, '2024-12-18 21:49:40'),
(75, 116, 'admin', '/qc/admin/upload/20241218134940797170.mp4', NULL, '2024-12-18 21:49:40'),
(76, 117, 'admin', '/qc/admin/upload/20241218134953863810.mp4', NULL, '2024-12-18 21:49:53'),
(77, 117, 'admin', '/qc/admin/upload/20241218134953793341.mp4', NULL, '2024-12-18 21:49:53'),
(78, 134, 'admin', '/qc/admin/upload/20241218135009164238.mp4', NULL, '2024-12-18 21:50:09'),
(79, 134, 'admin', '/qc/admin/upload/20241218135009134336.mp4', NULL, '2024-12-18 21:50:09'),
(80, 136, 'admin', '/qc/admin/upload/20241218135426119665.mp4', NULL, '2024-12-18 21:54:26'),
(81, 136, 'admin', '/qc/admin/upload/20241218135426320356.mp4', NULL, '2024-12-18 21:54:26');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `lecture_video`
--
ALTER TABLE `lecture_video`
  ADD PRIMARY KEY (`lvid`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `lecture_video`
--
ALTER TABLE `lecture_video`
  MODIFY `lvid` int(11) NOT NULL AUTO_INCREMENT COMMENT '강의영상 고유번호', AUTO_INCREMENT=82;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
