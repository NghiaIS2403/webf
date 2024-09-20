<?php
    session_start();
// Nếu người dùng nhấn nút thêm vào giỏ hàng trên trang sản phẩm, chúng ta kiểm tra dữ liệu form
if (isset($_POST['product_id'], $_POST['quantity']) && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity'])) {
    // Gán các biến post để dễ dàng nhận dạng, đồng thời đảm bảo chúng là số nguyên
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $query = "SELECT * FROM products WHERE id = $product_id";
    $result = mysqli_query($conn, $query);
    
    // Lấy dữ liệu sản phẩm
    $product = mysqli_fetch_assoc($result);      
    // Kiểm tra nếu sản phẩm tồn tại (mảng không rỗng)
    if ($product && $quantity > 0) {
        // Sản phẩm tồn tại trong cơ sở dữ liệu, bây giờ có thể tạo/cập nhật biến session cho giỏ hàng
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            if (array_key_exists($product_id, $_SESSION['cart'])) { // kiểm tra xem trong mảng session cart có id đó hay chưa
                // Sản phẩm đã tồn tại trong giỏ hàng nên chỉ cần cập nhật số lượng
                $_SESSION['cart'][$product_id] += $quantity;
            } else {
                // Sản phẩm chưa có trong giỏ hàng nên thêm nó vào trong biến session key prdid với value =quantity.
                // câu lệnh này có ý nghĩa giống line 27 đều là cập nhật session cart với mảng prdid=>quantity
                //khác nhau : ở đây chỉ cập nhật thêm sản phẩm cho 1 id vì nó đã có sẵn session trước đó, không làm biến mất những biến cũ
                //
                $_SESSION['cart'][$product_id] = $quantity;
            }
        } else {
            // Chưa có sản phẩm trong giỏ hàng, thêm sản phẩm đầu tiên vào giỏ
            // do chưa có mảng session cart ban đầu nên nó như tạo ra 1 biến mới, nếu nó nằm ở vị trí khác thì nó sẽ có nghĩa là đè lên cả mảng cũ
            // nhưng tại đây thì trước đó nó không có sản phẩm tổn tại nên nó chỉ có ý nghĩa là
            // tạo session cart có mảng vơi key prdid => value quantity.
            $_SESSION['cart'] = array($product_id => $quantity);
        }
    }
    // Ngăn chặn việc gửi lại form
    header('location: index.php?page=cart');
    exit;
}

// Xóa sản phẩm khỏi giỏ hàng, kiểm tra tham số URL "remove", đây là id sản phẩm, đảm bảo nó là số và kiểm tra xem nó có trong giỏ hàng không
if (isset($_GET['remove']) && is_numeric($_GET['remove']) && isset($_SESSION['cart']) && isset($_SESSION['cart'][$_GET['remove']])) {
    // Xóa sản phẩm khỏi giỏ hàng
    unset($_SESSION['cart'][$_GET['remove']]);
}

// Cập nhật số lượng sản phẩm trong giỏ nếu người dùng nhấn nút "Cập nhật" trên trang giỏ hàng
if (isset($_POST['update']) && isset($_SESSION['cart'])) {
    // Lặp qua dữ liệu post để có thể cập nhật số lượng cho từng sản phẩm trong giỏ
    foreach ($_POST as $k => $v) {
        if (strpos($k, 'quantity') !== false && is_numeric($v)) {
            $id = str_replace('quantity-', '', $k);
            $quantity = (int)$v;
            // Luôn thực hiện các kiểm tra và xác thực
            if (is_numeric($id) && isset($_SESSION['cart'][$id]) && $quantity > 0) {
                // Cập nhật số lượng mới
                $_SESSION['cart'][$id] = $quantity;
            }
        }
    }
    // Ngăn chặn việc gửi lại form
    header('Location: index.php?page=cart');
    exit;
}

// Điều hướng người dùng tới trang đặt hàng nếu họ nhấn nút Đặt hàng, đồng thời giỏ hàng không được rỗng
if (isset($_POST['placeorder']) && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    header('Location: index.php?page=placeorder');
    exit;
}
// Kiểm tra biến session cho các sản phẩm trong giỏ hàng
$products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$products = array();
$subtotal = 0.00;

if ($products_in_cart) {
    // Nếu có sản phẩm trong giỏ hàng, chúng ta cần lấy những sản phẩm đó từ cơ sở dữ liệu

    // Tạo danh sách ID sản phẩm từ giỏ hàng, ví dụ: (1, 2, 3,...)
    $ids = implode(',', array_keys($products_in_cart));

    // Tạo câu truy vấn SQL
    $sql = "SELECT * FROM products WHERE id IN ($ids)";

    // Thực thi câu truy vấn
    $result = mysqli_query($conn, $sql);

    // Kiểm tra nếu truy vấn thành công
    if ($result) {
        // Lấy các sản phẩm từ cơ sở dữ liệu và lưu kết quả dưới dạng mảng
        while ($product = mysqli_fetch_assoc($result)) {
            $products[] = $product;

            // Tính toán tổng tiền (subtotal)
            $subtotal += (float)$product['price'] * (int)$products_in_cart[$product['id']];
        }
    } else {
        // Xử lý lỗi nếu có vấn đề với truy vấn
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<?=template_header('Cart')?>

<div class="cart content-wrapper">
    <h1>Shopping Cart</h1>
    <form action="index.php?page=cart" method="post">
        <table>
            <thead>
                <tr>
                    <td colspan="2">Product</td>
                    <td>Price</td>
                    <td>Quantity</td>
                    <td>Total</td>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                <tr>
                    <td colspan="5" style="text-align:center;">Hãy thêm sản phẩm yêu thích vào giỏ hàng</td>
                </tr>
                <?php else: ?>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td class="img">
                        <a href="index.php?page=product&id=<?=$product['id']?>">
                            <img src="imgs/<?=$product['img']?>" width="50" height="50" alt="<?=$product['title']?>">
                        </a>
                    </td>
                    <td>
                        <a href="index.php?page=product&id=<?=$product['id']?>"><?=$product['title']?></a>
                        <br>
                        <a href="index.php?page=cart&remove=<?=$product['id']?>" class="remove" >Remove</a
                    </td>
                    <td class="price">&dollar;<?=$product['price']?></td>
                    <td class="quantity">
                        <input type="number" name="quantity-<?=$product['id']?>" value="<?=$products_in_cart[$product['id']]?>" min="1" max="<?=$product['quantity']?>" placeholder="Quantity" required>
                    </td>
                    <td class="price">&dollar;<?=$product['price'] * $products_in_cart[$product['id']]?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="subtotal">
            <span class="text">Subtotal</span>
            <span class="price">&dollar;<?=$subtotal?></span>
        </div>
        <div class="buttons">
            <input type="submit" value="Update" name="update">
            <input type="submit" value="Place Order" name="placeorder">
        </div>
    </form>
</div>

<?=template_footer()?>
