# login_register

1. 將 Config.sample.php 改名為 Config.php
2. 將 Config.php 註解取消掉，輸入正確 
3. 匯入 db.sql 建立 members table
4. 變更 config/MySQL.php 符合你本地端的資料庫帳號密碼
5. 執行 composer install 有需要的話 composer dump 重新讀取 class map
6. 這是一個使用 Clean URL 的自訂框架所以都統一進入 index.php 處理
7. 在 init.php 執行需要全域初始化的動作
8. 路由 route.php 轉接 URL 對應到 controller 資料夾
9. libraies 專門放低耦合，可共用的 class 函式庫
10. view 是畫面元件，主要分割成 header, body, footer
11. validators 專門放驗證用、高耦合性的 class 
12. 本範例 Database 採用單例模式以 PDO 連接 MySQL