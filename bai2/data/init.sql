CREATE TABLE IF NOT EXISTS sinh_vien (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ma_sv  VARCHAR(10)  NOT NULL,
  ho_ten VARCHAR(100) NOT NULL,
  lop    VARCHAR(20)  NOT NULL
) CHARACTER SET utf8mb4;

INSERT INTO sinh_vien (ma_sv, ho_ten, lop) VALUES
('SV001', 'Nguyễn Văn An',  'CNTT-K20'),
('SV002', 'Trần Thị Bình',  'ATTT-K21'),
('SV003', 'Lê Hoàng Cường', 'CNTT-K20');
