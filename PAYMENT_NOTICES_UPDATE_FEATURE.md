# Payment Notices Management

This feature provides comprehensive management of payment notices, including automatic updates when installation dates change, flexible clearing capabilities, and advanced sorting options.

## Features

### Automatic Updates
When you change a customer's `plan_installed_at` date, the system will automatically:

1. **Delete existing unpaid notices** (pending/overdue) for that customer
2. **Regenerate payment notices** based on the new installation date
3. **Preserve paid notices** to maintain payment history
4. **Skip periods already covered by confirmed payments**
5. **Set appropriate status** (overdue for past periods, pending for future)

### Clear Payment Notices
Clean up payment notices with flexible filtering options:

- Clear all notices
- Clear by status (pending, overdue, paid, cancelled)
- Clear for specific customers
- Clear notices older than a specific date
- Clear with multiple criteria combinations

### Sorting & Filtering Options
Advanced sorting and filtering capabilities for payment notices:

- **Sort by Customer Name** (alphabetical A-Z or Z-A)
- **Sort by Unpaid Months** (lowest to highest or highest to lowest)
- **Filter by Status** (pending, overdue, paid, cancelled)
- **Filter by Customer** (specific customer notices)
- **Group by Categories** (unpaid months ranges, overdue ranges, amount ranges)

## Usage

### Manual Updates

#### Single Customer Update
```php
use App\Services\PaymentService;

$paymentService = app(PaymentService::class);
$noticesCreated = $paymentService->updateNoticesForInstallationDateChange($customer);
```

#### Bulk Customer Updates
```php
$results = $paymentService->bulkUpdateNoticesForInstallationDateChanges($customerIds);
// Returns: ['customers_processed' => int, 'total_notices_created' => int, 'errors' => array]
```

#### Sorting & Filtering Payment Notices
```php
// Sort by customer name (A-Z)
$notices = $paymentService->getPaymentNoticesWithSorting([
    'sort_by' => 'name',
    'sort_direction' => 'asc',
    'status' => ['pending', 'overdue']
]);

// Sort by unpaid months (highest first)
$customers = $paymentService->getCustomersWithNoticesSorted([
    'sort_by' => 'unpaid_months',
    'sort_direction' => 'desc'
]);

// Get summary with grouping
$summary = $paymentService->getPaymentNoticesSummary([
    'sort_by' => 'name',
    'group_by' => 'unpaid_months'
]);
```

#### Clear Payment Notices
```php
// Clear all notices
$result = $paymentService->clearPaymentNotices();

// Clear by status
$result = $paymentService->clearPaymentNotices(['status' => 'pending']);

// Clear for specific customer
$result = $paymentService->clearPaymentNotices(['customer_id' => 123]);

// Clear with multiple criteria
$result = $paymentService->clearPaymentNotices([
    'customer_id' => 123,
    'status' => 'overdue'
]);

// Clear old notices
$deletedCount = $paymentService->clearNoticesOlderThan(Carbon::now()->subMonths(3));

// Clear unpaid notices for a customer
$deletedCount = $paymentService->clearUnpaidNoticesForCustomer($customer);
```

#### Using the Trait in Controllers
```php
use App\Traits\UpdatesPaymentNotices;

class CustomerController extends Controller
{
    use UpdatesPaymentNotices;
    
    public function update(Request $request, Customer $customer)
    {
        $customer->update($request->validated());
        
        // Payment notices are automatically updated if installation date changed
        // Or manually trigger update:
        $result = $this->updateCustomerPaymentNotices($customer);
        
        return response()->json([
            'message' => 'Customer updated successfully',
            'payment_notices' => $result
        ]);
    }
    
    public function getNoticesSorted(Request $request)
    {
        $notices = $this->getPaymentNoticesSorted([
            'sort_by' => $request->get('sort_by', 'name'),
            'sort_direction' => $request->get('sort_direction', 'asc'),
            'status' => $request->get('status', ['pending', 'overdue'])
        ]);
        
        return response()->json($notices);
    }
    
    public function clearNotices(Customer $customer)
    {
        $result = $this->clearCustomerPaymentNotices($customer);
        
        return response()->json([
            'message' => 'Payment notices cleared',
            'result' => $result
        ]);
    }
}
```

## API Endpoints

### Payment Notices with Sorting
```bash
# Get notices sorted by customer name
GET /admin/api/payment-notices?sort_by=name&sort_direction=asc

# Get notices sorted by unpaid months
GET /admin/api/payment-notices?sort_by=unpaid_months&sort_direction=desc

# Filter by status
GET /admin/api/payment-notices?status[]=pending&status[]=overdue

# Get customer summary with grouping
GET /admin/api/payment-notices/customers-summary?sort_by=unpaid_months&group_by=unpaid_months

# Get notices for specific customer
GET /admin/api/payment-notices/customer/123?sort_by=due_date

# Get payment statistics
GET /admin/api/payment-notices/statistics
```

### API Response Examples

#### Sorted Payment Notices
```json
{
    "data": [
        {
            "id": 1,
            "customer_id": 123,
            "due_date": "2025-08-15",
            "amount_due": 1500.00,
            "status": "pending",
            "customer": {
                "id": 123,
                "full_name": "Alice Johnson",
                "plan": {
                    "name": "Basic Plan",
                    "monthly_rate": 1500.00
                }
            }
        }
    ],
    "total": 10
}
```

#### Customer Summary with Grouping
```json
{
    "total_customers": 15,
    "total_notices": 45,
    "total_amount": 67500.00,
    "customers": [...],
    "grouped": {
        "1 month": {
            "customers": [...],
            "total_notices": 5,
            "total_amount": 7500.00,
            "customer_count": 5
        },
        "2-3 months": {
            "customers": [...],
            "total_notices": 12,
            "total_amount": 18000.00,
            "customer_count": 6
        }
    }
}
```

## Console Commands

### Update Payment Notices Command
```bash
# Update notices for a specific customer
php artisan payment:update-notices --customer=123

# Update notices for all customers
php artisan payment:update-notices --all

# Force update without confirmation
php artisan payment:update-notices --all --force
```

### Clear Payment Notices Command
```bash
# Clear all payment notices (with confirmation)
php artisan payment:clear-notices --all

# Clear notices with specific status
php artisan payment:clear-notices --status=pending

# Clear notices for a specific customer
php artisan payment:clear-notices --customer=123

# Force clear without confirmation
php artisan payment:clear-notices --all --force

# Clear overdue notices
php artisan payment:clear-notices --status=overdue

# Clear paid notices
php artisan payment:clear-notices --status=paid
```

## Key Features

### Smart Period Detection
- Only creates notices for periods not already covered by confirmed payments
- Handles overlapping payment periods correctly
- Maintains payment history integrity

### Status Management
- Past due dates are automatically marked as "overdue"
- Future dates are marked as "pending"
- Paid notices remain unchanged

### Error Handling
- Safe bulk operations with error reporting
- Transaction-safe individual updates
- Detailed logging of created/updated notices

## Examples

### Scenario 1: Installation Date Moved Earlier
```php
// Original installation: 3 months ago
$customer->update(['plan_installed_at' => now()->subMonths(3)]);
// Result: More payment notices created for additional months

// Updated installation: 1 month ago  
$customer->update(['plan_installed_at' => now()->subMonths(1)]);
// Result: Existing notices deleted, fewer notices created
```

### Scenario 2: Installation Date Moved Later
```php
// Original installation: 1 month ago
$customer->update(['plan_installed_at' => now()->subMonths(1)]);

// Updated installation: 3 months ago
$customer->update(['plan_installed_at' => now()->subMonths(3)]);
// Result: Additional notices created for earlier months
```

### Scenario 3: Existing Payments Protected
```php
// Customer has paid for months 1-2
// Installation date changed from 3 months ago to 6 months ago
// Result: Only creates notices for months 3-6, preserves payment records
```

## Testing

Run the feature tests to verify functionality:
```bash
php artisan test --filter=PaymentNoticeInstallationDateUpdateTest
```

## Notes

- Changes are automatically triggered by model events on the Customer model
- The system prevents infinite loops with safety checks
- All operations are logged for audit purposes
- Payment history is always preserved
- Bulk operations provide detailed error reporting

## Migration Considerations

When deploying this feature:

1. **Backup existing payment notices** before bulk updates
2. **Test with a small subset** of customers first
3. **Run the bulk update command** during maintenance windows
4. **Verify payment calculations** after updates

Example deployment sequence:
```bash
# 1. Backup database
mysqldump your_database > backup_before_payment_update.sql

# 2. Test with one customer
php artisan payment:update-notices --customer=1

# 3. Bulk update all customers
php artisan payment:update-notices --all

# 4. Verify results
php artisan tinker
>>> \App\Models\PaymentNotice::count()
>>> \App\Models\Customer::whereNotNull('plan_installed_at')->get()->each->getUnpaidMonths()
```
