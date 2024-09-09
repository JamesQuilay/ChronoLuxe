<?php

// Include your controller files
include_once '../controllers/HomeController.php';
include_once '../controllers/CartController.php';
include_once '../controllers/ProfileController.php';
include_once '../controllers/CheckoutController.php';
include_once '../controllers/OrderController.php';
include_once '../controllers/AdminController.php';
include_once '../controllers/AuthController.php';
include_once '../controllers/SearchController.php';



// Parse the requested URL
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router = str_replace('/ITEC75_php/', '', $request_uri);
$request_method = $_SERVER['REQUEST_METHOD'];

// Route definition
switch ($router) {

    case '/sign-up':
        if ($request_method == 'GET') {
            (new AuthController())->showRegistrationForm();
        } elseif ($request_method == 'POST') {
            (new AuthController())->register();
        }
        break;

    case '/login':
        if ($request_method == 'GET') {
            (new AuthController())->showLoginForm();
        } elseif ($request_method == 'POST') {
            (new AuthController())->login();
        }
        break;

    case '/forgot-password':
        if ($request_method == 'GET') {
            (new AuthController())->showForgotPasswordForm();
        } elseif ($request_method == 'POST') {
            (new AuthController())->handleForgotPassword();
        }
        break;

    case '/reset-password':
        if ($request_method == 'GET') {
            (new AuthController())->showResetPasswordForm();
        } elseif ($request_method == 'POST') {
            (new AuthController())->handleResetPassword();
        }
        break;


    case '/frontPage':
        case '/':
        (new HomeController())->frontPage();
        break;

    
    case '/shop':
        (new HomeController())->index();
        break;


    case '/add_to_cart':
        if ($request_method == 'POST') {
            (new HomeController())->addToCart();
        }
        break;

    case '/cart':
        (new CartController())->home();
        break;

    case '/update_cart':
        if ($request_method == 'POST') {
            (new CartController())->updateCart();
        }
        break;

    case '/remove_cart_item':
        if ($request_method == 'POST') {
            (new CartController())->removeCartItem();
        }
        break;

    case '/cart_count':
        (new CartController())->cartCount();
        break;

    case '/product_details':
        if (isset($_GET['id'])) {
            $productId = $_GET['id'];
            (new HomeController())->productDetails($productId);
        } else {
            echo "Invalid product ID.";
        }
        break;


    case '/checkout':
        if ($request_method == 'GET') {
            $watchId = isset($_GET['watch_id']) ? intval($_GET['watch_id']) : null;
            (new CheckoutController())->index($watchId);
        } elseif ($request_method == 'POST') {
            (new CheckoutController())->checkout();
        }
        break;

    case '/order_confirmation':
        if (isset($_GET['order_id'])) {
            $orderId = $_GET['order_id'];
            (new OrderController())->confirmation($orderId);
        } else {
            http_response_code(404);
            (new HomeController())->pageNotFound();
        }
        break;

    case '/profile':
        if ($request_method == 'GET') {
            (new ProfileController())->profile();
        } elseif ($request_method == 'POST') {
            (new ProfileController())->update();
        }
        break;

    case '/change-password':
        if ($request_method == 'GET') {
            (new ProfileController())->profile();
        } elseif ($request_method == 'POST') {
            (new ProfileController())->changePassword();
        }
        break;


    case '/logout':
        if ($request_method == 'POST') {
            (new AuthController())->logout();
        }
        break;
        

    case '/order_status':
        (new OrderController())->status();
        break;

   
    case '/search':
        if ($request_method == 'GET') {
            (new SearchController())->search();
            exit;
        }
        break;

    case '/blog/1':
        (new HomeController())->blog1();
        break;

    case '/blog/2':
        (new HomeController())->blog2();
        break;

    case '/blog/3':
        (new HomeController())->blog3();
        break;


    case '/adminLogin':
        if ($request_method == 'GET') {
            (new AuthController())->showAdminLoginForm();
        } elseif ($request_method == 'POST') {
            (new AuthController())->adminLogin();
        }
        break;
         

    case '/admin_dashboard':
        
        (new AdminController())->dashboard();
        break;

    
    case preg_match('/^\/admin\/products\/edit\/\d+$/', $router) ? true : false:
        // Dynamic route for product editing
        (new AdminController())->editProduct($router);
        break;

    case preg_match('/^\/admin\/products\/delete\/\d+$/', $router) ? true : false:
        // Dynamic route for product deletion
        (new AdminController())->deleteProduct($router);
        break;

    case '/admin/users':
        if ($request_method == 'GET') {
            if (isset($_GET['action']) && $_GET['action'] == 'search') {
                (new AdminController())->searchUsers();
            } else {
                (new AdminController())->users();
            }
        } 
        break;
        
    case preg_match('/^\/admin\/order_details\/(\d+)$/', $router, $matches) ? true : false:
        $orderId = (int)$matches[1]; // Use $matches[1] to extract the order ID
        (new AdminController())->fetchOrderDetails($orderId);
        break;
    
    // Route for handling order actions (accept/delete)
    case '/admin/order_action':
        if ($request_method === 'POST') {
            (new AdminController())->handleOrderAction();
        }
        break;

    default:
        http_response_code(404);
        (new HomeController())->pageNotFound();
        break;
}
?>
