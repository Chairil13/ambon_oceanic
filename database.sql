-- Database: ambon_oceanic
-- Create database
CREATE DATABASE IF NOT EXISTS ambon_oceanic CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ambon_oceanic;

-- Table: admin
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: kategori
CREATE TABLE IF NOT EXISTS kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: destinasi
CREATE TABLE IF NOT EXISTS destinasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(200) NOT NULL,
    deskripsi TEXT NOT NULL,
    lokasi VARCHAR(255) NOT NULL,
    gambar VARCHAR(255) NOT NULL,
    jam_buka VARCHAR(100),
    harga_tiket DECIMAL(10,2) DEFAULT 0,
    kategori_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: favorites
CREATE TABLE IF NOT EXISTS favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    destinasi_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (destinasi_id) REFERENCES destinasi(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorite (user_id, destinasi_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: chat_logs
CREATE TABLE IF NOT EXISTS chat_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    message TEXT NOT NULL,
    response TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin (username: admin, password: admin123)
INSERT INTO admin (username, password) VALUES 
('admin', '$2y$10$F6Vpj0EhA7YQBoPh2ouKY.M9hLpKn9R92WHcnFlslS14m5djvVW5S');

-- Insert sample categories
INSERT INTO kategori (nama, deskripsi) VALUES
('Pantai', 'Destinasi wisata pantai dengan pemandangan laut yang indah'),
('Sejarah', 'Tempat bersejarah dan situs budaya'),
('Kuliner', 'Tempat wisata kuliner khas Ambon'),
('Alam', 'Wisata alam dan pegunungan'),
('Religi', 'Tempat ibadah dan wisata religi');

-- Insert sample destinations
INSERT INTO destinasi (nama, deskripsi, lokasi, gambar, jam_buka, harga_tiket, kategori_id) VALUES
('Pantai Natsepa', 'Pantai dengan pasir putih dan air laut yang jernih. Cocok untuk berenang dan snorkeling.', 'Desa Suli, Kecamatan Salahutu, Kabupaten Maluku Tengah', 'https://via.placeholder.com/400x300?text=Pantai+Natsepa', '24 Jam', 10000, 1),
('Benteng Victoria', 'Benteng peninggalan Portugis yang dibangun pada abad ke-17. Menjadi saksi sejarah perjuangan rakyat Maluku.', 'Jl. Pattimura, Kota Ambon', 'https://via.placeholder.com/400x300?text=Benteng+Victoria', '08:00 - 17:00', 5000, 2),
('Pantai Liang', 'Pantai dengan pasir putih halus dan air laut biru jernih. Terkenal sebagai salah satu pantai terindah di Indonesia.', 'Desa Liang, Kecamatan Salahutu', 'https://via.placeholder.com/400x300?text=Pantai+Liang', '24 Jam', 15000, 1),
('Masjid Raya Al-Fatah', 'Masjid megah dengan arsitektur modern yang menjadi ikon Kota Ambon.', 'Jl. Raya Pattimura, Kota Ambon', 'https://via.placeholder.com/400x300?text=Masjid+Al-Fatah', '05:00 - 21:00', 0, 5),
('Pasar Mardika', 'Pasar tradisional yang menjual berbagai kuliner khas Ambon dan hasil laut segar.', 'Jl. Said Perintah, Kota Ambon', 'https://via.placeholder.com/400x300?text=Pasar+Mardika', '06:00 - 18:00', 0, 3),
('Pintu Kota', 'Gerbang bersejarah peninggalan Belanda yang menjadi landmark Kota Ambon.', 'Jl. Pattimura, Kota Ambon', 'https://via.placeholder.com/400x300?text=Pintu+Kota', '24 Jam', 0, 2);

-- Create indexes for better performance
CREATE INDEX idx_destinasi_kategori ON destinasi(kategori_id);
CREATE INDEX idx_favorites_user ON favorites(user_id);
CREATE INDEX idx_favorites_destinasi ON favorites(destinasi_id);
CREATE INDEX idx_chat_logs_user ON chat_logs(user_id);
CREATE INDEX idx_chat_logs_created ON chat_logs(created_at);
