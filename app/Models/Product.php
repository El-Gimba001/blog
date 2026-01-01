<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'name',
        'category',
        'unit',
        'quantity',
        'cost_price',
        'selling_price',
        'profit',
        'reorder_point',      // ✅ Added
        'alert_sent_at',      // ✅ Added
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'profit' => 'decimal:2',
        'alert_sent_at' => 'datetime',
    ];

    /**
     * Relationships (if you have these models)
     */
    public function category()
    {
        // If you have a Category model, update this
        // return $this->belongsTo(Category::class);
        
        // Otherwise, keep as is or adjust according to your setup
        return $this->belongsTo(Category::class, 'category', 'id');
    }

    /**
     * Check if product is low on stock
     */
    public function isLowOnStock(): bool
    {
        return $this->quantity <= $this->reorder_point;
    }

    /**
     * Check if alert needs to be sent
     * Returns true if stock is low AND alert hasn't been sent recently
     */
    public function shouldSendLowStockAlert(int $cooldownHours = 24): bool
    {
        if (!$this->isLowOnStock()) {
            return false;
        }

        // If alert was never sent
        if (!$this->alert_sent_at) {
            return true;
        }

        // If alert was sent more than X hours ago
        return $this->alert_sent_at->diffInHours(now()) >= $cooldownHours;
    }

    /**
     * Mark alert as sent
     */
    public function markAlertSent(): void
    {
        $this->update(['alert_sent_at' => now()]);
        Log::info("Low stock alert sent for product: {$this->name} (ID: {$this->id})");
    }

    /**
     * Reset alert sent status when stock is replenished
     */
    public function resetAlertIfReplenished(): void
    {
        if (!$this->isLowOnStock() && $this->alert_sent_at) {
            $this->update(['alert_sent_at' => null]);
            Log::info("Alert reset for product: {$this->name} (ID: {$this->id}) - stock replenished");
        }
    }

    /**
     * Scope for low stock products
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('quantity <= reorder_point');
    }

    /**
     * Scope for critical stock (below 50% of reorder point)
     */
    public function scopeCriticalStock($query)
    {
        return $query->whereRaw('quantity <= (reorder_point * 0.5)');
    }

    /**
     * Scope for products needing alert (low stock & alert cooldown expired)
     */
    public function scopeNeedsAlert($query, int $cooldownHours = 24)
    {
        return $query->whereRaw('quantity <= reorder_point')
            ->where(function ($q) use ($cooldownHours) {
                $q->whereNull('alert_sent_at')
                  ->orWhereRaw('TIMESTAMPDIFF(HOUR, alert_sent_at, NOW()) >= ?', [$cooldownHours]);
            });
    }

    /**
     * Scope for out of stock products
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '<=', 0);
    }

    /**
     * Scope for products with sufficient stock
     */
    public function scopeSufficientStock($query)
    {
        return $query->where('quantity', '>', \DB::raw('reorder_point'));
    }

    /**
     * Calculate stock status for display
     */
    // In your Product model (app/Models/Product.php):
public function getStockStatusAttribute(): array
{
    if ($this->quantity <= 0) {
        return [
            'status' => 'out_of_stock',
            'label' => 'Out of Stock',
            'color' => 'dark',
            'icon' => 'x-circle', // Lucide icon
        ];
    }

    if ($this->quantity <= ($this->reorder_point * 0.5)) {
        return [
            'status' => 'critical',
            'label' => 'Critical',
            'color' => 'danger',
            'icon' => 'alert-circle', // Lucide icon
        ];
    }

    if ($this->quantity <= $this->reorder_point) {
        return [
            'status' => 'low',
            'label' => 'Low Stock',
            'color' => 'warning',
            'icon' => 'alert-triangle', // Lucide icon
        ];
    }

    return [
        'status' => 'sufficient',
        'label' => 'Sufficient',
        'color' => 'success',
        'icon' => 'check-circle', // Lucide icon
    ];
}

    /**
     * Get the stock level percentage (0-100)
     */
    public function getStockLevelPercentageAttribute(): float
    {
        // Assuming safe stock is 200% of reorder point
        $safeStock = $this->reorder_point * 2;
        
        if ($safeStock <= 0) {
            return 100;
        }
        
        $percentage = ($this->quantity / $safeStock) * 100;
        
        return min(100, max(0, $percentage));
    }

    /**
     * Calculate days of stock remaining based on average usage
     * You might need to adjust this based on your sales data
     */
    public function getEstimatedDaysRemainingAttribute(): ?float
    {
        // This is a simplified version - you might want to implement
        // based on your actual sales history
        
        // Example: If you have daily_average_usage field
        if ($this->daily_average_usage && $this->daily_average_usage > 0) {
            return round($this->quantity / $this->daily_average_usage, 1);
        }
        
        return null;
    }

    /**
     * Boot method to auto-reset alerts when quantity changes
     */
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($product) {
            $originalQuantity = $product->getOriginal('quantity');
            $newQuantity = $product->quantity;
            
            // Reset alert if quantity increased above reorder point
            if ($originalQuantity <= $product->reorder_point && 
                $newQuantity > $product->reorder_point) {
                $product->resetAlertIfReplenished();
            }
        });
    }

    /**
     * Get products that need to be reordered urgently
     */
    public static function getUrgentReorderProducts()
    {
        return self::criticalStock()
            ->orWhere(function ($query) {
                $query->outOfStock();
            })
            ->orderBy('quantity')
            ->get();
    }

    /**
     * Get low stock summary statistics
     */
    public static function getLowStockSummary(): array
    {
        return [
            'total_low_stock' => self::lowStock()->count(),
            'critical_stock' => self::criticalStock()->count(),
            'out_of_stock' => self::outOfStock()->count(),
            'needs_alert' => self::needsAlert()->count(),
        ];
    }
}