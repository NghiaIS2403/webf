<?php
// Số lượng sản phẩm hiển thị trên mỗi trang
$num_products_on_each_page = 4;
// Trang hiện tại - trong URL, sẽ xuất hiện dưới dạng index.php?page=products&p=1, index.php?page=products&p=2, v.v.
$current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
// Chọn các sản phẩm được sắp xếp theo ngày thêm
$query = 'SELECT * FROM products ORDER BY date_added DESC LIMIT ?,?';
$stmt = mysqli_prepare($conn, $query);
// bindValue sẽ cho phép chúng ta sử dụng số nguyên trong câu lệnh SQL, điều này cần thiết cho mệnh đề LIMIT
$limit_start = ($current_page - 1) * $num_products_on_each_page;
$limit_count = $num_products_on_each_page;
mysqli_stmt_bind_param($stmt, 'ii', $limit_start, $limit_count);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
// Lấy các sản phẩm từ cơ sở dữ liệu và trả kết quả dưới dạng mảng.
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
// Lấy tổng số lượng sản phẩm
$total_products_query = 'SELECT COUNT(*) AS total FROM products';
$total_products_result = mysqli_query($conn, $total_products_query);
$total_products_row = mysqli_fetch_assoc($total_products_result);
$total_products = $total_products_row['total'];
?>
<?=template_header('Products')?>

<div class="products content-wrapper">
    <h1>Products</h1>
    <p><?=$total_products?> Products</p>
    <div class="products-wrapper">
        <?php foreach ($products as $product): ?>
        <a href="index.php?page=product&id=<?=$product['id']?>" class="product">
            <img src="imgs/<?=$product['img']?>" width="200" height="200" alt="<?=$product['title']?>">
            <span class="name"><?=$product['title']?></span>
            <span class="price">
                &dollar;<?=$product['price']?>
                <?php if ($product['rrp'] > 0): ?>
                <span class="rrp">&dollar;<?=$product['rrp']?></span>
                <?php endif; ?>
            </span>
        </a>
        <?php endforeach; ?>
    </div>
    <div class="buttons">
        <?php if ($current_page > 1): ?>
        <a href="index.php?page=products&p=<?=$current_page-1?>">Prev</a>
        <?php endif; ?>
        <?php if ($total_products > ($current_page * $num_products_on_each_page) - $num_products_on_each_page + count($products)): ?>
        <a href="index.php?page=products&p=<?=$current_page+1?>">Next</a>
        <?php endif; ?>
    </div>
</div>

<?=template_footer()?>