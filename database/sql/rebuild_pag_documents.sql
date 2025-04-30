-- SQL script to rebuild the PAG documents table
-- This script drops and recreates the pag_documents table

-- Drop the existing table if it exists
DROP TABLE IF EXISTS pag_documents;

-- Create the table structure
CREATE TABLE pag_documents (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    pag_id BIGINT UNSIGNED NOT NULL,
    document_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    file_size BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    -- Add foreign key constraint
    CONSTRAINT pag_documents_pag_id_foreign FOREIGN KEY (pag_id)
    REFERENCES pags (id) ON DELETE CASCADE
);

-- Add indexes for performance
CREATE INDEX pag_documents_pag_id_index ON pag_documents (pag_id);

-- Insert sample documents for PAG records (assuming PAG IDs exist)
-- Common document names and types
-- Document 1 - Intelligence Report
INSERT INTO pag_documents (pag_id, document_name, file_path, file_type, file_size, created_at, updated_at)
SELECT id, 'Intelligence Report', CONCAT('pag-documents/', SUBSTRING(MD5(RAND()) FROM 1 FOR 10), '-intelligence_report.pdf'), 'application/pdf', FLOOR(RAND() * 5000000) + 1000000, NOW(), NOW()
FROM pags WHERE id IN (1, 5, 9, 13, 17) LIMIT 5;

-- Document 2 - Surveillance Photos
INSERT INTO pag_documents (pag_id, document_name, file_path, file_type, file_size, created_at, updated_at)
SELECT id, 'Surveillance Photos', CONCAT('pag-documents/', SUBSTRING(MD5(RAND()) FROM 1 FOR 10), '-surveillance_photo.jpg'), 'image/jpeg', FLOOR(RAND() * 3000000) + 500000, NOW(), NOW()
FROM pags WHERE id IN (2, 6, 10, 14, 18) LIMIT 5;

-- Document 3 - Identification Documents
INSERT INTO pag_documents (pag_id, document_name, file_path, file_type, file_size, created_at, updated_at)
SELECT id, 'Identification Documents', CONCAT('pag-documents/', SUBSTRING(MD5(RAND()) FROM 1 FOR 10), '-identification.pdf'), 'application/pdf', FLOOR(RAND() * 2000000) + 800000, NOW(), NOW()
FROM pags WHERE id IN (3, 7, 11, 15) LIMIT 4;

-- Document 4 - Activity Logs
INSERT INTO pag_documents (pag_id, document_name, file_path, file_type, file_size, created_at, updated_at)
SELECT id, 'Activity Logs', CONCAT('pag-documents/', SUBSTRING(MD5(RAND()) FROM 1 FOR 10), '-activity_log.docx'), 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', FLOOR(RAND() * 1500000) + 300000, NOW(), NOW()
FROM pags WHERE id IN (4, 8, 12, 16) LIMIT 4;

-- Document 5 - Operation Reports
INSERT INTO pag_documents (pag_id, document_name, file_path, file_type, file_size, created_at, updated_at)
SELECT id, 'Operation Reports', CONCAT('pag-documents/', SUBSTRING(MD5(RAND()) FROM 1 FOR 10), '-operation_report.pdf'), 'application/pdf', FLOOR(RAND() * 4000000) + 1200000, NOW(), NOW()
FROM pags LIMIT 10;

-- Document 6 - Location History
INSERT INTO pag_documents (pag_id, document_name, file_path, file_type, file_size, created_at, updated_at)
SELECT id, 'Location History', CONCAT('pag-documents/', SUBSTRING(MD5(RAND()) FROM 1 FOR 10), '-location_history.xlsx'), 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', FLOOR(RAND() * 800000) + 200000, NOW(), NOW()
FROM pags WHERE id IN (1, 3, 5, 7, 9) LIMIT 5;

-- Document 7 - Network Analysis
INSERT INTO pag_documents (pag_id, document_name, file_path, file_type, file_size, created_at, updated_at)
SELECT id, 'Network Analysis', CONCAT('pag-documents/', SUBSTRING(MD5(RAND()) FROM 1 FOR 10), '-network_analysis.pdf'), 'application/pdf', FLOOR(RAND() * 3500000) + 900000, NOW(), NOW()
FROM pags WHERE id IN (2, 4, 6, 8, 10) LIMIT 5;

-- Document 8 - Case Files
INSERT INTO pag_documents (pag_id, document_name, file_path, file_type, file_size, created_at, updated_at)
SELECT id, 'Case Files', CONCAT('pag-documents/', SUBSTRING(MD5(RAND()) FROM 1 FOR 10), '-case_files.pdf'), 'application/pdf', FLOOR(RAND() * 6000000) + 1500000, NOW(), NOW()
FROM pags LIMIT 8;
