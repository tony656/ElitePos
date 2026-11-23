-- ── Stock snapshot cron — runs every 5 minutes ────────────────────────────────
--
-- IDEMPOTENT BY DESIGN:
--   rows are unique on (snapshot_date, product_id, category).
--   • opening_stock  = yesterday's closing_stock per product_id+category.
--                      Falls back to product_id-only match (old NULL category
--                      snapshots), then to current quantity if no history.
--   • closing_stock  = current live products.quantity (overwritten every run)
--
-- Only account id 7 (mainstore/godown) products. Rows keyed by product_id+category.
-- Each product row gets its own snapshot row — same product_id with different
-- categories (CTN vs PC) get separate snapshot rows.

-- 1) Delete today's rows so we recreate fresh (no duplicates possible)
DELETE FROM daily_stock_snapshots WHERE snapshot_date = CURDATE();

-- 2) Insert today's snapshot for every product per category.
--    GROUP BY p.product_id, p.category ensures separate rows for CTN vs PC.
INSERT INTO daily_stock_snapshots (snapshot_date, product_id, category, opening_stock, closing_stock)
SELECT CURDATE(),
       p.product_id,
       p.category,
       COALESCE(
           prev_cat.latest_closing,   -- exact product_id+category match
           prev_id.latest_closing,    -- product_id only match (NULL category snapshots)
           MAX(p.quantity)            -- no history, use current quantity
       ),
       MAX(p.quantity)
FROM products p
-- Exact match: product_id AND category
LEFT JOIN (
    SELECT s1.product_id, s1.category, s1.closing_stock AS latest_closing
    FROM daily_stock_snapshots s1
    INNER JOIN (
        SELECT product_id, category, MAX(snapshot_date) AS max_date
        FROM daily_stock_snapshots
        WHERE snapshot_date < CURDATE()
        GROUP BY product_id, category
    ) s2 ON s1.product_id = s2.product_id
        AND s1.category = s2.category
        AND s1.snapshot_date = s2.max_date
) prev_cat ON p.product_id = prev_cat.product_id AND p.category = prev_cat.category
-- Fallback match: product_id only (NULL category snapshots)
LEFT JOIN (
    SELECT s1.product_id, s1.closing_stock AS latest_closing
    FROM daily_stock_snapshots s1
    INNER JOIN (
        SELECT product_id, MAX(snapshot_date) AS max_date
        FROM daily_stock_snapshots
        WHERE snapshot_date < CURDATE() AND category IS NULL
        GROUP BY product_id
    ) s2 ON s1.product_id = s2.product_id
        AND s1.snapshot_date = s2.max_date
        AND s1.category IS NULL
) prev_id ON p.product_id = prev_id.product_id
WHERE p.product_id IS NOT NULL
  AND p.product_id <> ''
  AND p.account = '7'
GROUP BY p.product_id, p.category;

-- 3) Always overwrite closing_stock with the latest live numbers
--    GROUP BY to avoid ambiguity when products has duplicate product_id+category
UPDATE daily_stock_snapshots dss
JOIN (
    SELECT product_id, category, MAX(quantity) AS qty
    FROM products
    WHERE account = '7'
    GROUP BY product_id, category
) p ON dss.product_id = p.product_id AND dss.category = p.category
SET dss.closing_stock = p.qty,
    dss.category = p.category
WHERE dss.snapshot_date = CURDATE();
