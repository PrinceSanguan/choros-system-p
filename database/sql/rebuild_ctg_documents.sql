-- SQL script to rebuild the CTG documents table
-- This script drops and recreates the ctg_documents table

-- Drop the existing table if it exists
DROP TABLE IF EXISTS ctg_documents;

-- Create the table structure
CREATE TABLE ctg_documents (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    ctg_id BIGINT UNSIGNED NOT NULL,
    document_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    file_size BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    -- Add foreign key constraint
    CONSTRAINT ctg_documents_ctg_id_foreign FOREIGN KEY (ctg_id)
    REFERENCES ctgs (id) ON DELETE CASCADE
);

-- Add indexes for performance
CREATE INDEX ctg_documents_ctg_id_index ON ctg_documents (ctg_id);

-- Insert sample documents for CTG records (assuming CTG IDs start from 1)
-- You may need to adjust the CTG IDs to match your actual CTG IDs

-- Common document names and types
-- Document 1 - Intelligence Report
INSERT INTO ctg_documents (`ctg_id`, `file_path`, `file_name`, `file_type`, `created_at`, `updated_at`)
SELECT id, CONCAT('ctg-documents/', SUBSTRING(MD5(RAND()) FROM 1 FOR 10), '-intelligence_report.pdf'), 'intelligence_report.pdf', 'application/pdf', NOW(), NOW()
FROM `ctgs` WHERE id IN (1, 5, 9, 13, 17);

-- Document 2 - Surveillance Photos
INSERT INTO ctg_documents (`ctg_id`, `file_path`, `file_name`, `file_type`, `created_at`, `updated_at`)
SELECT id, CONCAT('ctg-documents/', SUBSTRING(MD5(RAND()) FROM 1 FOR 10), '-surveillance_photo.jpg'), 'surveillance_photo.jpg', 'image/jpeg', NOW(), NOW()
FROM `ctgs` WHERE id IN (2, 6, 10, 14, 18);

-- Document 3 - Identification Documents
INSERT INTO ctg_documents (`ctg_id`, `file_path`, `file_name`, `file_type`, `created_at`, `updated_at`)
SELECT id, CONCAT('ctg-documents/', SUBSTRING(MD5(RAND()) FROM 1 FOR 10), '-identification.pdf'), 'identification.pdf', 'application/pdf', NOW(), NOW()
FROM `ctgs` WHERE id IN (3, 7, 11, 15);

-- Document 4 - Activity Logs
INSERT INTO ctg_documents (`ctg_id`, `file_path`, `file_name`, `file_type`, `created_at`, `updated_at`)
SELECT id, CONCAT('ctg-documents/', SUBSTRING(MD5(RAND()) FROM 1 FOR 10), '-activity_log.docx'), 'activity_log.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', NOW(), NOW()
FROM `ctgs` WHERE id IN (4, 8, 12, 16);

-- Document 5 - Faction Details
INSERT INTO ctg_documents (`ctg_id`, `file_path`, `file_name`, `file_type`, `created_at`, `updated_at`)
SELECT id, CONCAT('ctg-documents/', SUBSTRING(MD5(RAND()) FROM 1 FOR 10), '-faction_details.pdf'), 'faction_details.pdf', 'application/pdf', NOW(), NOW()
FROM `ctgs`;

-- Document 6 - Location History (for selected CTGs)
INSERT INTO ctg_documents (`ctg_id`, `file_path`, `file_name`, `file_type`, `created_at`, `updated_at`)
SELECT id, CONCAT('ctg-documents/', SUBSTRING(MD5(RAND()) FROM 1 FOR 10), '-location_history.xlsx'), 'location_history.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', NOW(), NOW()
FROM `ctgs` WHERE id IN (1, 3, 5, 7, 9, 11, 13, 15, 17);

-- Document 7 - Network Analysis (for selected CTGs)
INSERT INTO ctg_documents (`ctg_id`, `file_path`, `file_name`, `file_type`, `created_at`, `updated_at`)
SELECT id, CONCAT('ctg-documents/', SUBSTRING(MD5(RAND()) FROM 1 FOR 10), '-network_analysis.pdf'), 'network_analysis.pdf', 'application/pdf', NOW(), NOW()
FROM `ctgs` WHERE id IN (2, 4, 6, 8, 10, 12, 14, 16, 18);

-- Document 8 - Operation Details (for all CTGs)
INSERT INTO ctg_documents (`ctg_id`, `file_path`, `file_name`, `file_type`, `created_at`, `updated_at`)
SELECT id, CONCAT('ctg-documents/', SUBSTRING(MD5(RAND()) FROM 1 FOR 10), '-operation_details.pdf'), 'operation_details.pdf', 'application/pdf', NOW(), NOW()
FROM `ctgs`;
