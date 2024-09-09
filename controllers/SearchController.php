<?php

class SearchController {
    public function search() {
        $query = isset($_GET['query']) ? trim($_GET['query']) : '';

        if (!empty($query)) {
            $results = $this->performSearch($query);
            echo json_encode($results);
        } else {
            echo json_encode([]);
        }
    }

    private function performSearch($query) {
        // Define your full list of pages
        $pages = [
            ['title' => 'Featured', 'url' => '/featured', 'description' => 'View featured products.'],
            ['title' => 'Home', 'url' => '/frontPage', 'description' => 'Go to home page.'],
            ['title' => 'Shop', 'url' => '/shop', 'description' => 'Browse our shop.'],
            ['title' => 'Profile', 'url' => '/profile', 'description' => 'View your profile.'],
            ['title' => 'Order Status', 'url' => '/order_status', 'description' => 'Check your order status.'],
            ['title' => 'Login', 'url' => '/login', 'description' => 'Log in to your account.'],
            ['title' => 'Sign Up', 'url' => '/sign-up', 'description' => 'Create a new account.'],
            ['title' => 'Cart', 'url' => '/cart', 'description' => 'View your shopping cart.']
        ];

        // Filter results based on query
        $filteredResults = array_filter($pages, function($page) use ($query) {
            $queryLower = strtolower($query);
            return stripos(strtolower($page['title']), $queryLower) !== false || stripos(strtolower($page['description']), $queryLower) !== false;
        });

        // Return the filtered results
        return array_values($filteredResults); // Ensure results are returned as a list with re-indexed keys
    }
}
?>
