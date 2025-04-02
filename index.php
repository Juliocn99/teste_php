<?php
ini_set('display_errors', 0);

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$products = [
    1 => ['name' => 'Camiseta PHP', 'price' => 49.90],
    2 => ['name' => 'Caneca Developer', 'price' => 29.90],
    3 => ['name' => 'Teclado Mecânico', 'price' => 299.90],
    4 => ['name' => 'Livro PHP Avançado', 'price' => 89.90]
];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Adicionar ao carrinho    
    if (isset($_POST['action']) && $_POST['action'] == 'add' && isset($_POST['id'])) {
        $id = (int)$_POST['id'];

        if (array_key_exists($id, $products)) {
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity']++;
            } else {
                $_SESSION['cart'][$id] = [
                    'name' => $products[$id]['name'],
                    'price' => $products[$id]['price'],
                    'quantity' => 1 
                ];
            }
        }
        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    }
    // Remover item do carrinho
    if (isset($_POST['action']) && $_POST['action'] == 'remove' && isset($_POST['id'])) {
        $id = (int)$_POST['id'];
        
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    }
}

$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Loja de Produtos para Devs</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Lista de Produtos -->
            <div class="md:col-span-2">
                <h2 class="text-xl font-semibold mb-4">Produtos Disponíveis</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach ($products as $id => $product): ?>
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h3 class="font-bold text-lg"><?= $product['name'] ?></h3>
                            <p class="text-gray-600 mb-2">R$ <?= number_format($product['price'], 2, ',', '.') ?></p>
                            <form method="POST" action ="">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded inline-block">
                                    Adicionar ao Carrinho
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Carrinho de Compras -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Seu Carrinho</h2>
                
                <?php if (empty($_SESSION['cart'])): ?>
                    <p class="text-gray-500">Seu carrinho está vazio</p>
                <?php else: ?>
                    <ul class="divide-y divide-gray-200 mb-4">
                        <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                            <li class="py-3 flex justify-between">
                                <div>
                                    <span class="font-medium"><?= $item['name'] ?></span>
                                    <span class="text-gray-500 text-sm">x<?= $item['quantity'] ?></span>
                                </div>
                                <div class="flex items-center">
                                    <span class="mr-4">R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></span>
                                    <form method="POST" action="">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="id" value="<?= $id ?>">
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total:</span>
                            <span>R$ <?= number_format($total, 2, ',', '.') ?></span>
                        </div>
                    </div>
                    <button class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mt-4">
                        Finalizar Compra
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
