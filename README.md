# 📚 圖書館管理系統（Library System）

一個基於 **PHP + SQL（MySQL / MariaDB）** 建置的網頁版圖書館管理系統，涵蓋書籍與館藏副本管理、會員帳號與借還書流程，以及後台管理與操作紀錄等功能。

---

## 🔍 專案分析

### 1. 系統定位與核心功能
從專案檔案結構來看，這是一套具備前後台分流與完整 CRUD 業務邏輯的傳統伺服器端渲染（SSR）Web 應用：
- **身分認證與權限控管**：提供登入（`login.php`）、驗證（`authenticate.php`）、登入狀態檢查（`check_login.php`）、登出（`logout.php`）、註冊（`register.php`）與管理員建立（`create_admin.php`），並設有 `security.php` 處理安全性相關邏輯。
- **書籍與館藏副本管理**：區分「書目（Book）」與「實體館藏副本（Copy）」，支援新增、編輯與刪除書籍（`add_book.php`、`edit_book.php`、`delete_book.php`、`manage_books.php`）及館藏副本（`add_copy.php`、`edit_copy.php`、`delete_copy.php`、`manage_copies.php`）。
- **借還書與會員服務**：會員可查看個人借閱狀態（`my_loans.php`）並執行借書（`borrow.php`）與還書（`return.php`）；管理員可於 `admin_dashboard.php` 統整營運資訊並管理會員（`manage_members.php`、`edit_member.php`、`delete_member.php`）。
- **系統紀錄追蹤**：包含 `log.php`，可用於追蹤系統操作或借還紀錄。

### 2. 專案結構摘要
- **設定與資料庫**：`config.php`、`db.php`、`init_db.sql`、`library_system.sql`
- **認證與安全**：`login.php`、`authenticate.php`、`check_login.php`、`logout.php`、`register.php`、`create_admin.php`、`security.php`
- **書籍與副本模組**：`add_book.php`、`edit_book.php`、`delete_book.php`、`manage_books.php`、`add_copy.php`、`edit_copy.php`、`delete_copy.php`、`manage_copies.php`
- **借還與會員模組**：`borrow.php`、`return.php`、`my_loans.php`、`manage_members.php`、`edit_member.php`、`delete_member.php`
- **入口、儀表板與靜態資源**：`index.php`、`admin_dashboard.php`、`log.php`、`assets/`、`images/`

### 3. 工程現況與改進建議
- **優點**：業務模組切分清楚，將「書目」與「館藏副本」拆開符合真實圖書館領域模型，且附帶完整 SQL 初始化與匯入腳本。
- **建議改進**：
  1. 檢查資料庫連線與查詢是否全面採用 Prepared Statements 與 `password_hash()` / `password_verify()`，並確認 `create_admin.php` 在正式環境的存取限制。
  2. 補充系統截圖、預設測試帳號說明（若為展示環境）與完整部署步驟，提升專案可讀性。

---

## 🛠️ 安裝與使用說明

### 1. 環境需求
- PHP（建議 PHP 8.x）與 Web 伺服器（如 Apache / Nginx，或 XAMPP / Laragon 等本機整合環境）
- MySQL / MariaDB 資料庫

### 2. 匯入資料庫
1. 建立新的資料庫（例如 `library_system`）。
2. 匯入專案中的 SQL 檔案（依需求使用 `library_system.sql` 或 `init_db.sql`）：
   ```bash
   mysql -u <username> -p library_system < library_system.sql
   ```

### 3. 設定與啟動
1. 將專案放置於 Web 伺服器的根目錄（例如 `htdocs/library_system`）。
2. 依實際環境修改 `config.php` 與 `db.php` 中的資料庫連線資訊。
3. 透過瀏覽器開啟 `index.php` 開始使用，或執行 `create_admin.php` 建立管理員帳號後登入 `admin_dashboard.php`。
