<?php
include 'database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nylon Tiles | SMJ Furnishings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .product-card {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }
        .product-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
        .product-card .view-btn {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            padding: 10px 30px;
            letter-spacing: 2px;
            opacity: 0;
            font-weight: 600;
            transition: opacity 0.3s ease;
            z-index: 3;
        }
        .product-card:hover .view-btn {
            opacity: 1;
        }
        .status-text {
            color: red; /* Ensure text is red */
            font-size: 0.9rem; /* Slightly smaller font for status */
        }
    </style>
</head> 
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5 mb-5">
        <h1 class="fw-bold mb-5">Nylon Tiles</h1>
        <div class="row g-4">
            <?php
            $result = $conn->query("SELECT id, style_name, photo, in_stock, on_sale FROM nylon_tiles");
            if ($result->num_rows > 0):
                while ($item = $result->fetch_assoc()):
                    $photo = !empty($item['photo']) && file_exists("Uploads/products/" . $item['photo'])
                        ? $item['photo']
                        : 'placeholder.jpg';
            ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="product-card">
                            <img src="Uploads/products/<?= htmlspecialchars($photo) ?>" alt="<?= htmlspecialchars($item['style_name']) ?>" class="card-img-top">
                            <a href="item_details.php?id=<?= $item['id'] ?>&type=Nylon Tiles" class="view-btn">View</a>
                        </div>
                        <div class="text-center mt-3">
                            <h5 class="card-title"><?= htmlspecialchars($item['style_name']) ?></h5>
                            <p class="status-text">
                                <?= $item['in_stock'] ? 'In Stock' : 'Out of Stock' ?>
                                <?= $item['on_sale'] ? ' | On Sale' : '' ?>
                            </p>
                        </div>
                    </div>
            <?php
                endwhile;
            else:
            ?>
                <p class="text-muted">No products available at the moment.</p>
            <?php endif; ?>
        </div>
        <a href="products.php" class="btn btn-dark btn-lg mt-5 mb-5">Back to Products</a>
    </div>
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>