-- SQL ALTER statements to add new fields to trading_journal_entries table
-- Run these statements on your external MySQL database

ALTER TABLE trading_journal_entries 
ADD COLUMN order_type ENUM('Market','Limit','Stop') DEFAULT NULL AFTER direction,
ADD COLUMN strategy VARCHAR(255) DEFAULT NULL AFTER order_type,
ADD COLUMN stop_loss DECIMAL(10,5) DEFAULT NULL AFTER strategy,
ADD COLUMN take_profit DECIMAL(10,5) DEFAULT NULL AFTER stop_loss,
ADD COLUMN absolute_pl DECIMAL(10,2) DEFAULT NULL AFTER rr,
ADD COLUMN disciplined ENUM('Y','N') DEFAULT NULL AFTER absolute_pl,
ADD COLUMN followed_rules ENUM('Y','N') DEFAULT NULL AFTER disciplined,
ADD COLUMN rating TINYINT(1) DEFAULT NULL AFTER followed_rules;

-- Add indexes for the new fields that might be used in queries
ALTER TABLE trading_journal_entries
ADD KEY idx_order_type (order_type),
ADD KEY idx_strategy (strategy),
ADD KEY idx_disciplined (disciplined),
ADD KEY idx_followed_rules (followed_rules),
ADD KEY idx_rating (rating);

-- Verify the new structure
DESCRIBE trading_journal_entries;