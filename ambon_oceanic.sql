-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2026 at 03:04 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ambon_oceanic`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `photo`, `created_at`) VALUES
(1, 'admin', '$2y$10$6q.2Pxv1jx3biRyhiMpVJeNhYiZJ4x/X5SH..snwgu4yQZ9g/qHCC', 'public/assets/images/admin/admin_1_1776996365.png', '2026-04-22 00:50:49');

-- --------------------------------------------------------

--
-- Table structure for table `chat_logs`
--

CREATE TABLE `chat_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `response` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_logs`
--

INSERT INTO `chat_logs` (`id`, `user_id`, `message`, `response`, `created_at`) VALUES
(1, NULL, 'Rekomendasi pantai terbaik di Ambon', 'Maaf, layanan chatbot sedang tidak tersedia.', '2026-04-22 01:32:20'),
(2, NULL, 'Rekomendasi pantai terbaik di Ambon', 'Maaf, layanan chatbot sedang tidak tersedia.', '2026-04-24 03:29:39'),
(3, NULL, 'Rekomendasi pantai terbaik di Ambon', 'Halo! Selamat datang di Ambon, Pulau Seribu Masjid yang memesona! Saya siap membantu Anda menjelajahi keindahan Ambon.\n\nWah, pertanyaan yang bagus sekali! Ambon memang terkenal dengan pantai-pantainya yang memukau. Memilih yang \"terbaik\" itu agak sulit karena setiap pantai punya pesonanya sendiri, tapi saya punya beberapa rekomendasi pantai yang paling populer dan wajib Anda kunjungi:\n\n**Rekomendasi Pantai Terbaik di Ambon:**\n\n1.  **Pantai Natsepa:** Ini mungkin pantai yang paling ikonik di Ambon.\n    *   **Mengapa Spesial:** Pasirnya putih halus, airnya jernih kebiruan, dan ombaknya relatif tenang, sangat cocok untuk berenang dan bermain air. Pemandangannya indah dengan jajaran pohon kelapa yang rindang.\n    *   **Aktivitas:** Berenang, berjemur, bermain pasir, menyewa perahu untuk berkeliling, atau sekadar bersantai menikmati suasana.\n    *   **Kuliner:** Di sepanjang pantai Natsepa, Anda akan menemukan banyak penjual rujak natsepa yang legendaris. Jangan sampai terlewatkan!\n\n2.  **Pantai Liang (Hukurila):** Terletak di Desa Liang, pantai ini menawarkan kombinasi keindahan alam yang luar biasa.\n    *   **Mengapa Spesial:** Dikenal dengan tebing-tebing kapur yang menjulang di sepanjang pantai, menciptakan pemandangan dramatis. Airnya sangat jernih dengan gradasi warna biru kehijauan yang memanjakan mata.\n    *   **Aktivitas:** Snorkeling dan diving (terumbu karangnya masih terjaga dengan baik), berenang, menjelajahi gua-gua kecil di tebing, dan berfoto dengan latar belakang tebing yang eksotis.\n\n3.  **Pantai Pintu Kota:** Nama ini diambil karena pantai ini memiliki celah besar di antara dua bukit karang yang menyerupai \"pintu\".\n    *   **Mengapa Spesial:** Pemandangan dari atas bukit menuju laut lepas melalui celah pintu karang ini sangat spektakuler. Ombaknya cenderung lebih besar, jadi lebih cocok untuk menikmati pemandangan dari atas atau beraktivitas di area yang lebih dangkal.\n    *   **Aktivitas:** Menikmati pemandangan dari atas, berfoto,', '2026-04-24 03:33:32'),
(4, NULL, 'siapa kamu?', 'Halo! Saya adalah asisten wisata virtual Anda untuk Kota Ambon, Indonesia. Saya di sini untuk membantu Anda menjelajahi keindahan, kekayaan budaya, cita rasa kuliner, dan memberikan tips-tips berharga agar perjalanan Anda ke Ambon menjadi tak terlupakan.\n\nSilakan tanyakan apa saja yang ingin Anda ketahui tentang Ambon, saya siap membantu dengan informasi yang akurat dan ramah!', '2026-04-24 03:34:01'),
(5, NULL, 'Tempat wisata sejarah di Ambon', 'Halo! Senang sekali bisa membantu Anda menjelajahi keindahan sejarah Kota Ambon. Sebagai Oceanic, asisten AI wisata Anda, saya akan dengan senang hati membagikan informasi mengenai tempat-tempat wisata bersejarah yang menarik di Ambon.\n\nAmbon memiliki sejarah yang kaya, mulai dari masa penjajahan hingga perjuangan kemerdekaan. Berikut adalah beberapa tempat wisata sejarah yang wajib Anda kunjungi:\n\nBenteng Victoria:\nIni adalah benteng peninggalan Portugis yang kemudian diperbaiki dan digunakan oleh Belanda. Benteng ini terletak di pusat Kota Ambon dan menjadi saksi bisu berbagai peristiwa sejarah penting. Dari sini, Anda bisa melihat pemandangan Teluk Ambon yang indah.\n\nMuseum Siwalima:\nMeskipun tidak secara eksklusif museum sejarah, Museum Siwalima menampilkan berbagai koleksi yang berkaitan dengan sejarah, budaya, dan etnografi Maluku. Anda bisa melihat artefak-artefak kuno, pakaian adat, hingga replika rumah adat. Ini memberikan gambaran yang komprehensif tentang kehidupan masyarakat Maluku dari masa lalu.\n\nGereja Tua Batumerah:\nIni adalah salah satu gereja tertua di Ambon, didirikan pada masa penjajahan Portugis. Arsitekturnya masih mempertahankan ciri khas bangunan tua dan memiliki nilai sejarah yang tinggi.\n\nTugu Peringatan Pahlawan Nasional Martha Christina Tiahahu:\nMartha Christina Tiahahu adalah seorang pahlawan nasional dari Maluku yang gigih melawan penjajah. Tugu ini didirikan untuk mengenang jasa-jasanya. Lokasinya yang strategis di pinggir pantai juga menawarkan pemandangan yang memukau.\n\nMasjid Raya Al-Fatah:\nMasjid ini merupakan salah satu masjid terbesar dan tertua di Ambon. Selain sebagai tempat ibadah, arsitekturnya yang megah juga memiliki nilai sejarah dan budaya yang penting bagi masyarakat Muslim di Ambon.\n\nGua Purbakala Liang:\nMeskipun lebih ke arah arkeologi, penemuan di gua ini memberikan wawasan tentang kehidupan manusia purba di wilayah Ambon. Ini adalah bagian penting dari sejarah panjang kepulauan Maluku.\n\nSaat mengunjungi tempat-tempat bersejarah ini, luangkan waktu untuk merenungkan sejarah yang terbentang. Saya sarankan untuk datang pada pagi atau sore hari untuk menghindari terik matahari. Jangan ragu untuk bertanya kepada pemandu lokal jika tersedia, mereka bisa memberikan cerita dan detail', '2026-04-24 03:36:06'),
(6, NULL, 'nama kamu siapa', 'Halo! Saya Oceanic, asisten AI wisata Anda untuk Kota Ambon. Saya dikembangkan oleh Ambon Oceanic Tourism untuk membantu Anda menjelajahi keindahan Ambon. Senang bisa membantu Anda mendapatkan informasi yang akurat dan bermanfaat mengenai destinasi wisata, budaya, kuliner, dan tips perjalanan di Ambon.', '2026-04-24 03:36:21'),
(7, NULL, 'kategori pantai apa saja yang ada di web ini?', 'Halo! Selamat datang di Ambon. Saya Oceanic, asisten AI wisata Anda yang dikembangkan oleh Ambon Oceanic Tourism. Senang sekali bisa membantu Anda menjelajahi keindahan Ambon.\n\nUntuk pertanyaan Anda mengenai kategori pantai yang ada di website kami, mohon maaf, saat ini kami belum memiliki informasi spesifik mengenai kategori pantai yang terorganisir dalam format daftar atau tabel di website kami.\n\nNamun, Ambon terkenal dengan berbagai jenis pantai yang menakjubkan. Secara umum, Anda akan menemukan pantai-pantai dengan karakteristik sebagai berikut:\n\nPantai dengan Pasir Putih: Banyak pantai di Ambon menawarkan hamparan pasir putih yang halus, sangat cocok untuk berjemur dan bersantai. Contohnya adalah Pantai Natsepa.\n\nPantai dengan Pasir Hitam: Ada juga pantai yang memiliki pasir berwarna hitam karena aktivitas vulkanik masa lalu. Pantai ini memiliki daya tarik tersendiri.\n\nPantai Karang: Beberapa pantai di Ambon memiliki dasar laut yang kaya akan terumbu karang, menjadikannya spot yang luar biasa untuk snorkeling dan diving. Pantai Pintu Angin adalah salah satu contohnya.\n\nPantai Terpencil dan Alami: Ambon masih memiliki banyak pantai yang belum banyak terjamah, menawarkan suasana yang tenang dan keindahan alam yang masih asli.\n\nJika Anda memiliki pantai spesifik yang ingin Anda ketahui kategorinya, atau Anda mencari jenis pantai tertentu, jangan ragu untuk bertanya kepada saya. Saya akan berusaha memberikan informasi terbaik yang saya miliki untuk membantu Anda merencanakan perjalanan Anda.\n\nTerima kasih telah menghubungi kami!', '2026-04-24 03:38:44'),
(8, NULL, 'kategori pantai yang dimiliki web ini', 'Tentu saja! Berdasarkan data yang saya miliki, kategori pantai yang tersedia di Ambon adalah sebagai berikut:\n\nPantai Natsepa\nPantai Liang\n\nSemoga informasi ini membantu Anda merencanakan kunjungan Anda!', '2026-04-24 03:43:27'),
(9, NULL, 'tampilkan data keduanya', 'Tentu, saya akan tampilkan data kedua destinasi wisata yang ada di Ambon.\n\nDestinasi pertama adalah Pantai Natsepa.\nKategori: Pantai\nLokasi: Desa Suli, Kecamatan Salahutu, Kabupaten Maluku Tengah\nDeskripsi: Pantai dengan pasir putih dan air laut yang jernih. Cocok untuk berenang dan snorkeling.\nJam Buka: 24 Jam\nHarga Tiket: Rp 10.000\n\nDestinasi kedua adalah Benteng Victoria.\nKategori: Sejarah\nLokasi: Jl. Pattimura, Kota Ambon\nDeskripsi: Benteng peninggalan Portugis yang dibangun pada abad ke-17. Menjadi saksi sejarah perjuangan rakyat Maluku.\nJam Buka: 08:00 - 17:00\nHarga Tiket: Rp 5.000', '2026-04-24 03:43:44'),
(10, NULL, 'kategori sejarah apa saja?', 'Di Kota Ambon, terdapat dua destinasi wisata dengan kategori sejarah, yaitu Benteng Victoria dan Pintu Kota.', '2026-04-24 03:47:00'),
(11, NULL, 'tampilkan detailnnya', 'Mohon maaf, saya membutuhkan informasi lebih lanjut mengenai destinasi mana yang Anda maksud. Bisakah Anda sebutkan nama tempat wisatanya?', '2026-04-24 03:47:12'),
(12, NULL, 'tampilkan informasi dua destinasi yg kamu berikan sebelumnya', 'Tentu, berikut adalah informasi mengenai dua destinasi yang baru saja kita bahas:\n\nPertama, Pantai Natsepa.\nKategori: Pantai\nLokasi: Desa Suli, Kecamatan Salahutu, Kabupaten Maluku Tengah\nDeskripsi: Pantai dengan pasir putih dan air laut yang jernih. Cocok untuk berenang dan snorkeling.\nJam Buka: 24 Jam\nHarga Tiket: Rp 10.000\n\nKedua, Benteng Victoria.\nKategori: Sejarah\nLokasi: Jl. Pattimura, Kota Ambon\nDeskripsi: Benteng peninggalan Portugis yang dibangun pada abad ke-17. Menjadi saksi sejarah perjuangan rakyat Maluku.\nJam Buka: 08:00 - 17:00\nHarga Tiket: Rp 5.000', '2026-04-24 03:47:56'),
(13, NULL, 'kategori sejarah', 'Tentu, saya Oceanic, asisten AI wisata Anda dari Ambon Oceanic Tourism. Berikut adalah destinasi wisata di Ambon dengan kategori sejarah yang tersedia:\n\nBenteng Victoria:\nLokasi: Jl. Pattimura, Kota Ambon\nDeskripsi: Benteng peninggalan Portugis yang dibangun pada abad ke-17. Menjadi saksi sejarah perjuangan rakyat Maluku.\nJam Buka: 08:00 - 17:00\nHarga Tiket: Rp 5.000\n\nPintu Kota:\nLokasi: Jl. Pattimura, Kota Ambon\nDeskripsi: Gerbang bersejarah peninggalan Belanda yang menjadi landmark Kota Ambon.\nJam Buka: 24 Jam\nHarga Tiket: Rp 0', '2026-04-24 03:48:20'),
(14, NULL, 'wisata apa saja yang ada di kategori sejarah?', 'Tentu, di kategori sejarah ada Benteng Victoria dan Pintu Kota.', '2026-04-24 03:48:50'),
(15, NULL, 'berikan detail harga tiket, lokasinya', 'Tentu, saya akan berikan detail harga tiket dan lokasinya. Mohon sebutkan nama destinasi wisata yang Anda maksud agar saya bisa memberikan informasi yang akurat.', '2026-04-24 03:49:20'),
(16, NULL, 'kategori destinasi sejarah apa saja?', 'Tentu, saya akan bantu memberikan informasi mengenai kategori destinasi sejarah yang ada di Ambon berdasarkan data yang saya miliki.\n\nDestinasi wisata dengan kategori Sejarah yang tersedia adalah:\n\n1. Benteng Victoria\n2. Pintu Kota\n\nSemoga informasi ini membantu Anda dalam merencanakan kunjungan ke Ambon! Jika Anda ingin mengetahui detail lebih lanjut mengenai kedua destinasi tersebut, silakan bertanya.', '2026-04-24 03:51:57'),
(17, NULL, 'berikan informasi dari kedua destinasi tersebut', 'Tentu, saya akan berikan informasi lengkap dari kedua destinasi yang baru saja kita bahas.\n\nDestinasi pertama adalah Pantai Natsepa. Pantai ini berlokasi di Desa Suli, Kecamatan Salahutu, Kabupaten Maluku Tengah. Dengan pasir putih dan air laut yang jernih, pantai ini sangat cocok untuk berenang dan snorkeling. Pantai Natsepa buka 24 Jam dan harga tiket masuknya adalah Rp 10.000.\n\nDestinasi kedua adalah Benteng Victoria. Benteng ini merupakan peninggalan Portugis yang dibangun pada abad ke-17 dan berlokasi di Jl. Pattimura, Kota Ambon. Benteng Victoria menjadi saksi sejarah perjuangan rakyat Maluku. Jam bukanya adalah dari pukul 08:00 hingga 17:00, dengan harga tiket masuk Rp 5.000.', '2026-04-24 03:52:25'),
(18, NULL, 'tiketnya berapa saja?', 'Halo! Saya Oceanic, asisten AI wisata Kota Ambon.\n\nBerikut adalah informasi harga tiket untuk destinasi yang baru saja kita bahas:\n\n   Pantai Natsepa: Rp 10.000\n   Benteng Victoria: Rp 5.000\n   Pantai Liang: Rp 15.000\n   Masjid Raya Al-Fatah: Rp 0\n   Pasar Mardika: Rp 0\n   Pintu Kota: Rp 0', '2026-04-24 03:52:49'),
(19, NULL, 'jelaskan tentang pantai natsepa', 'Pantai Natsepa terletak di Desa Suli, Kecamatan Salahutu, Kabupaten Maluku Tengah. Pantai ini memiliki pasir putih dan air laut yang jernih, sangat cocok untuk berenang dan snorkeling. Jam buka Pantai Natsepa adalah 24 jam dan harga tiket masuknya adalah Rp 10.000.', '2026-04-24 03:57:01'),
(20, NULL, 'jam buka dan harga tiketnya?', 'Mohon sebutkan nama destinasi yang ingin Anda ketahui jam buka dan harga tiketnya.', '2026-04-24 03:57:23'),
(21, NULL, 'Rekomendasi pantai terbaik di Ambon', 'Untuk pantai terbaik di Ambon, saya merekomendasikan dua destinasi unggulan yaitu Pantai Natsepa dan Pantai Liang.\n\nPantai Natsepa terletak di Desa Suli, Kecamatan Salahutu, Kabupaten Maluku Tengah. Pantai ini memiliki pasir putih yang lembut dengan air laut yang sangat jernih, menjadikannya lokasi yang sangat cocok untuk berenang maupun snorkeling.\n\nPantai Liang juga berada di kawasan yang sama, tepatnya di Desa Liang, Kecamatan Salahutu. Pantai ini sering disebut sebagai salah satu pantai terindah di Indonesia karena memiliki kombinasi pasir putih halus dan gradasi air laut biru yang sangat memukau.\n\nApakah Anda ingin mengetahui informasi lebih detail mengenai salah satu pantai tersebut?', '2026-04-24 04:01:12'),
(22, NULL, 'boleh', 'Maaf, layanan chatbot sedang tidak tersedia. Silakan coba lagi nanti.', '2026-04-24 04:01:36'),
(23, NULL, 'berikan informasinya', 'Maaf, layanan chatbot sedang tidak tersedia. Silakan coba lagi nanti.', '2026-04-24 04:01:48'),
(24, NULL, 'Rekomendasi pantai terbaik di Ambon', 'Untuk rekomendasi pantai terbaik di Ambon, saya sarankan Pantai Liang. Pantai ini terkenal dengan pasir putih halus dan air laut biru jernihnya, bahkan dinobatkan sebagai salah satu pantai terindah di Indonesia.\n\nJika Anda mencari pilihan lain, Pantai Natsepa juga merupakan pilihan yang bagus dengan pasir putih dan air laut jernih yang cocok untuk berenang dan snorkeling.', '2026-04-24 04:02:02'),
(25, NULL, 'jam bukanya jam berapa saja?', 'Maaf, layanan chatbot sedang tidak tersedia. Silakan coba lagi nanti.', '2026-04-24 04:02:25'),
(26, NULL, 'halo', 'Maaf, layanan chatbot sedang tidak tersedia. Silakan coba lagi nanti.', '2026-04-24 04:02:33'),
(27, NULL, 'Tempat wisata sejarah di Ambon', 'Maaf, layanan chatbot sedang tidak tersedia. Silakan coba lagi nanti.', '2026-04-24 04:02:47'),
(28, NULL, 'hai', 'Halo! Ada yang bisa saya bantu terkait wisata di Ambon hari ini?', '2026-04-24 04:03:51'),
(29, NULL, 'Kuliner khas Ambon yang wajib dicoba', 'Untuk kuliner khas Ambon, Anda wajib mengunjungi Pasar Mardika. Di sana Anda bisa menemukan berbagai macam kuliner khas Ambon dan hasil laut segar.', '2026-04-24 04:03:56'),
(30, NULL, 'jam bukanya dan lokasinya dimana?', 'Mohon informasikan nama destinasi yang Anda maksud agar saya bisa memberikan detail jam buka dan lokasinya.', '2026-04-24 04:04:14'),
(31, NULL, 'Kuliner khas Ambon yang wajib dicoba', 'Untuk kuliner khas Ambon yang wajib dicoba, Anda bisa mengunjungi Pasar Mardika. Pasar ini buka dari jam 06:00 sampai 18:00 dan tidak ada biaya masuk. Di sana Anda bisa menemukan berbagai macam kuliner khas Ambon dan hasil laut segar.', '2026-04-24 04:07:23'),
(32, NULL, 'berapa biaya masuknya?', 'Untuk Pantai Natsepa, biaya masuknya adalah Rp 10.000.', '2026-04-24 04:07:48'),
(33, NULL, 'Kuliner khas Ambon yang wajib dicoba', 'Untuk kuliner khas Ambon yang wajib dicoba, Anda bisa mengunjungi Pasar Mardika. Pasar ini buka dari jam 06:00 sampai 18:00 dan tidak ada biaya masuk. Di sana Anda bisa menemukan berbagai macam kuliner khas Ambon dan hasil laut yang segar.', '2026-04-24 04:15:57'),
(34, NULL, 'biaya masuknya berapa?', 'Untuk Pantai Natsepa biaya masuknya Rp 10.000.', '2026-04-24 04:16:40'),
(39, NULL, 'Rekomendasi pantai terbaik di Ambon', 'Halo! Saya Oceanic, asisten wisata Ambon Anda. Berdasarkan data kami, ada dua pantai yang sangat populer dan menjadi rekomendasi utama di Ambon:\n\n1. Pantai Liang\nPantai ini berlokasi di Desa Liang, Kecamatan Salahutu. Pantai Liang dikenal sebagai salah satu pantai terindah di Indonesia karena memiliki pasir putih yang sangat halus dan air laut biru yang jernih. Tempat ini buka 24 jam dengan harga tiket masuk sebesar Rp 15.000.\n\n2. Pantai Natsepa\nTerletak di Desa Suli, Kecamatan Salahutu, Kabupaten Maluku Tengah. Pantai ini menawarkan hamparan pasir putih dan air laut yang jernih, sangat cocok bagi Anda yang ingin berenang atau snorkeling. Pantai Natsepa buka 24 jam dengan harga tiket masuk Rp 10.000.\n\nKedua pantai ini menawarkan keindahan alam yang luar biasa dan sangat layak untuk dikunjungi. Apakah Anda membutuhkan informasi tambahan mengenai lokasi atau transportasi menuju ke sana?', '2026-04-24 05:27:03');

-- --------------------------------------------------------

--
-- Table structure for table `destinasi`
--

CREATE TABLE `destinasi` (
  `id` int(11) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `deskripsi` text NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `jam_buka` varchar(100) DEFAULT NULL,
  `harga_tiket` decimal(10,2) DEFAULT 0.00,
  `kategori_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `destinasi`
--

INSERT INTO `destinasi` (`id`, `nama`, `deskripsi`, `lokasi`, `gambar`, `jam_buka`, `harga_tiket`, `kategori_id`, `created_at`, `updated_at`) VALUES
(1, 'Pantai Natsepa', 'Pantai dengan pasir putih dan air laut yang jernih. Cocok untuk berenang dan snorkeling.', 'Desa Suli, Kecamatan Salahutu, Kabupaten Maluku Tengah', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQdQCLwdZbN5ZV76S72k2yF5UMDqZZJDI-rUQ&s', '24 Jam', 20000.00, 1, '2026-04-22 00:50:49', '2026-05-03 06:14:11'),
(2, 'Benteng Victoria', 'Benteng peninggalan Portugis yang dibangun pada abad ke-17. Menjadi saksi sejarah perjuangan rakyat Maluku.', 'Jl. Pattimura, Kota Ambon', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQaGaXGfXoREqxeeweFhZMMy_tTZ1mey869KQ&s', '08:00 - 17:00', 5000.00, 2, '2026-04-22 00:50:49', '2026-04-24 01:49:49'),
(3, 'Pantai Liang', 'Pantai dengan pasir putih halus dan air laut biru jernih. Terkenal sebagai salah satu pantai terindah di Indonesia.', 'Desa Liang, Kecamatan Salahutu', 'https://asset.kompas.com/crops/XE0jgawhv2U8sPgDg47XIcEPgCw=/0x0:1000x667/750x500/data/photo/2022/07/05/62c46106b64c3.jpg', '24 Jam', 15000.00, 1, '2026-04-22 00:50:49', '2026-04-24 01:51:20'),
(4, 'Masjid Raya Al-Fatah', 'Masjid megah dengan arsitektur modern yang menjadi ikon Kota Ambon.', 'Jl. Raya Pattimura, Kota Ambon', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTR21lzNgLFQ3ZpwQ1_dyshOMhz17zzuP2tIg&s', '05:00 - 21:00', 0.00, 5, '2026-04-22 00:50:49', '2026-04-24 01:52:11'),
(6, 'Pintu Kota', 'Gerbang bersejarah peninggalan Belanda yang menjadi landmark Kota Ambon.', 'Jl. Pattimura, Kota Ambon', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcShLxtbG4swLdbUCS4INK7Q7THkgVteY23S1Q&s', '24 Jam', 0.00, 2, '2026-04-22 00:50:49', '2026-04-24 01:53:33');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `destinasi_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama`, `deskripsi`, `created_at`) VALUES
(1, 'Pantai', 'Destinasi wisata pantai dengan pemandangan laut yang indah', '2026-04-22 00:50:49'),
(2, 'Sejarah', 'Tempat bersejarah dan situs budaya', '2026-04-22 00:50:49'),
(3, 'Kuliner', 'Tempat wisata kuliner khas Ambon', '2026-04-22 00:50:49'),
(4, 'Alam', 'Wisata alam dan pegunungan', '2026-04-22 00:50:49'),
(5, 'Religi', 'Tempat ibadah dan wisata religi', '2026-04-22 00:50:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(2, 'Chairil Ali', 'chairilali13@gmail.com', '$2y$10$oCCHLYs9Kwbsxs//ax431eVK4bQaEPtMVOGh15UuJam0ERnryS8J2', '2026-05-05 11:09:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `chat_logs`
--
ALTER TABLE `chat_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_chat_logs_user` (`user_id`),
  ADD KEY `idx_chat_logs_created` (`created_at`);

--
-- Indexes for table `destinasi`
--
ALTER TABLE `destinasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_destinasi_kategori` (`kategori_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_favorite` (`user_id`,`destinasi_id`),
  ADD KEY `idx_favorites_user` (`user_id`),
  ADD KEY `idx_favorites_destinasi` (`destinasi_id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chat_logs`
--
ALTER TABLE `chat_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `destinasi`
--
ALTER TABLE `destinasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat_logs`
--
ALTER TABLE `chat_logs`
  ADD CONSTRAINT `chat_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `destinasi`
--
ALTER TABLE `destinasi`
  ADD CONSTRAINT `destinasi_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`destinasi_id`) REFERENCES `destinasi` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
