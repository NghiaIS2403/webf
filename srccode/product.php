<?php

// Kiểm tra xem tham số id có được chỉ định trong URL hay không
if (isset($_GET['id'])) {
    // Truy vấn không an toàn (dễ bị SQL Injection)
    $id = $_GET['id'];
    $query = "SELECT * FROM products WHERE id = $id";  // Trực tiếp chèn tham số từ URL vào truy vấn SQL
    $result = mysqli_query($conn, $query);

    // Lấy sản phẩm từ cơ sở dữ liệu và trả kết quả dưới dạng mảng
    $product = mysqli_fetch_assoc($result);

    if (!$product) {
        // Hiển thị lỗi đơn giản nếu id của sản phẩm không tồn tại (mảng rỗng)
        exit('Sản phẩm không tồn tại!');
    }
} else {
    // Hiển thị lỗi đơn giản nếu id không được chỉ định
    exit('Sản phẩm không tồn tại!');
}
?>
<?=template_header('Product')?>

<div class="product content-wrapper">
    <img src="imgs/<?=$product['img']?>" width="500" height="500" alt="<?=$product['title']?>">
    <div>
        <h1 class="name"><?=$product['title']?></h1>
        <span class="price">
            &dollar;<?=$product['price']?>
            <?php if ($product['rrp'] > 0): ?>
            <span class="rrp">&dollar;<?=$product['rrp']?></span>
            <?php endif; ?>
        </span>
        <form action="index.php?page=cart" method="post">
            <input type="number" name="quantity" value="1" min="1" max="<?=$product['quantity']?>" placeholder="Quantity" required>
            <input type="hidden" name="product_id" value="<?=$product['id']?>">
            <input type="submit" value="Add To Cart">
        </form>
        <div class="description">
            <?=$product['description']?>
        </div>
    </div>
</div>

<?=template_footer()?>