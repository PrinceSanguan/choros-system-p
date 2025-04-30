-- Drop the table if it exists
DROP TABLE IF EXISTS `ctg_documents`;

-- Create the ctg_documents table
CREATE TABLE `ctg_documents` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ctg_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ctg_documents_ctg_id_foreign` (`ctg_id`),
  CONSTRAINT `ctg_documents_ctg_id_foreign` FOREIGN KEY (`ctg_id`) REFERENCES `ctgs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
