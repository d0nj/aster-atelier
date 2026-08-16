<?php

namespace App\Admin;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;

class StoreSummary
{
    /**
     * Category options for admin forms: curated defaults merged with any in use.
     *
     * @return Collection<int, string>
     */
    public function categoryOptions(): Collection
    {
        return Product::categoryOptions()
            ->merge(Product::query()->select('category')->distinct()->pluck('category'))
            ->unique()
            ->values();
    }

    /**
     * Every number and list the admin dashboard shows.
     *
     * @return array<string, mixed>
     */
    public function dashboard(): array
    {
        $products = Product::query()->orderBy('sort_order')->orderBy('name')->get();
        $totalOrders = Order::query()->count();
        $totalRevenue = (float) Order::query()->sum('total_amount');
        $guestOrders = Order::query()->whereNull('user_id')->count();

        return [
            'products' => $products,
            'categories' => $this->categoryOptions(),
            'totalProducts' => $products->count(),
            'featuredProducts' => Product::query()->where('is_featured', true)->count(),
            'categoryCount' => Product::query()->distinct('category')->count('category'),
            'saleProducts' => Product::query()->whereNotNull('compare_price')->count(),
            'totalOrders' => $totalOrders,
            'pendingOrders' => Order::query()->where('status', 'pending')->count(),
            'guestOrders' => $guestOrders,
            'registeredOrders' => max($totalOrders - $guestOrders, 0),
            'guestOrderShare' => $totalOrders > 0 ? round(($guestOrders / $totalOrders) * 100) : 0,
            'totalRevenue' => $totalRevenue,
            'averageOrderValue' => $totalOrders > 0 ? $totalRevenue / $totalOrders : 0,
            'recentOrders' => Order::query()->latest()->take(5)->get(),
            'recentlyUpdatedProducts' => Product::query()->latest('updated_at')->take(6)->get(),
            'topCategories' => Product::query()
                ->selectRaw('category, COUNT(*) as total')
                ->groupBy('category')
                ->orderByDesc('total')
                ->take(5)
                ->get(),
        ];
    }
}
