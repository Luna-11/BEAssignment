<?php
$products = [
    ["id" => 201, "name" => "Wireless Mouse", "category" => "Electronics", "price" => 15000, "stock" => "In Stock"],
    ["id" => 202, "name" => "USB-C Charger", "category" => "Accessories", "price" => 22000, "stock" => "In Stock"],
    ["id" => 203, "name" => "Gaming Keyboard", "category" => "Electronics", "price" => 45000, "stock" => "Out of Stock"],
    ["id" => 204, "name" => "LED Desk Lamp", "category" => "Home & Office", "price" => 18000, "stock" => "In Stock"],
    ["id" => 205, "name" => "Bluetooth Speaker", "category" => "Audio", "price" => 32000, "stock" => "Limited Stock"]
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>

    <style>
        table {
            width: 75%;
            margin: 20px auto;
            border-collapse: collapse;
            font-family: Verdana, sans-serif;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: rgb(37, 61, 86);
            color: white;
        }

        tr:nth-child(even) {
            background-color: rgb(160, 192, 204);
        }

        .out-of-stock {
            color: red;
            font-weight: bold;
        }

        .limited-stock {
            color: orange;
            font-weight: bold;
        }

        .in-stock {
            color: green;
            font-weight: bold;
        }
    </style>
</head>

<body>

<h2 style="text-align:center;">🛍️ Product Catalog</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Price (MMK)</th>
            <th>Stock</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($products as $p){ ?>
        <tr>
            <td><?php echo $p["id"]; ?></td>
            <td><?php echo $p["name"]; ?></td>
            <td><?php echo $p["category"]; ?></td>
            <td><?php echo number_format($p["price"]); ?></td>
            <td class="<?php
                if ($p["stock"] == "Out of Stock") {
                    echo 'out-of-stock';
                } elseif ($p["stock"] == "Limited Stock") {
                    echo 'limited-stock';
                } elseif ($p["stock"] == "In Stock") {
                    echo 'in-stock';
                }
            ?>">
                <?php
                    if ($p["stock"] == "Out of Stock") {
                        echo "❌ ".$p["stock"];
                    } elseif ($p["stock"] == "Limited Stock") {
                        echo "‼️ ".$p["stock"];
                    } elseif ($p["stock"] == "In Stock") {
                        echo "✔️ ".$p["stock"];
                    }
                ?>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

</body>
</html>
