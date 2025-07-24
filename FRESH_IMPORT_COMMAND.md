# Fresh Customer Import Command Documentation

## Overview
The `import:fresh-customers` command provides a complete reset and reimport of customer data from the CSV file. This command clears all existing customers and job orders, then imports fresh data with proper plan assignments.

## Command Signature
```bash
php artisan import:fresh-customers [file=import.customer.csv] [--force]
```

## Parameters
- **file** (optional): Path to CSV file (defaults to `import.customer.csv`)
- **--force** (optional): Skip confirmation prompt and force execution

## Features

### 🗑️ Data Clearing
- **Safe Deletion**: Removes data in proper order to respect foreign key constraints
  1. Job Order Messages
  2. Job Orders  
  3. Customers
- **Transaction Safety**: Uses proper deletion methods to avoid constraint violations
- **Progress Reporting**: Shows count of records being deleted

### 📥 CSV Import
- **Progress Bar**: Visual progress indicator during import
- **Plan Matching**: Automatically matches CSV plan names to database plans
- **Date Parsing**: Handles multiple date formats from CSV
- **Email Generation**: Creates unique email addresses for each customer
- **Error Handling**: Graceful handling of import errors with detailed reporting

### 🔍 Data Validation
- **Unique Emails**: Prevents duplicate email addresses
- **Plan Validation**: Warns about missing plans
- **Date Validation**: Handles various date formats gracefully
- **Address Handling**: Provides fallbacks for missing addresses

## Import Results

### Latest Import Statistics
```
📊 Import Summary:
+-----------------+-------+
| Metric          | Count |
+-----------------+-------+
| Imported        | 193   |
| Skipped         | 0     |
| Errors          | 0     |
| Total Processed | 193   |
+-----------------+-------+
```

### Plan Distribution
```
📈 Plan Distribution:
+-----------+----------------+
| Plan Name | Customer Count |
+-----------+----------------+
| HP 5mbps  | 169            |
| HP        | 12             |
| HP 10mbps | 6              |
| HP 15mbps | 2              |
| HP 8mbps  | 1              |
| HP 5mb    | 1              |
| [No Plan] | 2              |
+-----------+----------------+
Total customers: 193
```

## CSV Format Requirements

### Expected Columns
1. **Name**: Full customer name (will be split into first/last name)
2. **Address**: Service address 
3. **Plan**: Plan name (must match existing plan names in database)
4. **Date Installed**: Installation date (various formats supported)

### Sample CSV Row
```csv
Name,Address,Plan,Date Installed
MARY GRACE AGUILARIO,AGSIRAB,HP,"Nov. 8, 2024"
```

### Supported Date Formats
- `July 9, 2025`
- `Nov. 8, 2024`
- `Oct. 8, 2022`
- `Aug 9, 2020`
- `Apr. 26, 2022`
- Standard ISO formats (Y-m-d, d/m/Y, m/d/Y)

## Customer Data Processing

### Name Processing
- Splits full name into `first_name` and `last_name`
- Handles complex names and special characters
- Uses first word as first name, remainder as last name

### Email Generation
- Format: `firstname.lastname@example.com`
- Removes special characters and spaces
- Ensures uniqueness by adding numbers if needed
- Example: `mary.grace1@example.com` for duplicates

### Phone Numbers
- Generates placeholder Philippine mobile numbers
- Format: `+63xxxxxxxxx`
- Random 9-digit numbers after country code

### Plan Assignment
- Matches plan names exactly from CSV to database
- Sets `plan_status` to `active` for customers with plans
- Sets `plan_status` to `null` for customers without plans
- Properly handles installation dates

## Error Handling

### Common Issues Resolved
1. **Foreign Key Constraints**: Uses proper deletion order
2. **Duplicate Emails**: Automatic unique email generation
3. **Missing Plans**: Graceful handling with warnings
4. **Invalid Dates**: Multiple format parsing with fallbacks
5. **Null Plan Status**: Proper handling for customers without plans

### Error Reporting
- Real-time error display during import
- Detailed error messages with row data
- Summary statistics at completion
- No data corruption on partial failures

## Database Changes

### Schema Updates
- Made `plan_status` nullable for customers without plans
- Maintains referential integrity with plans table
- Proper foreign key relationships preserved

### Data Integrity
- All existing relationships maintained
- Plan references properly validated
- Installation dates correctly formatted
- Customer data properly normalized

## Usage Examples

### Basic Import
```bash
php artisan import:fresh-customers
```

### Custom File Import
```bash
php artisan import:fresh-customers custom-customers.csv
```

### Force Import (No Confirmation)
```bash
php artisan import:fresh-customers --force
```

### Production Import with Logging
```bash
php artisan import:fresh-customers --force > import_log.txt 2>&1
```

## Safety Considerations

### ⚠️ Important Warnings
- **Data Loss**: This command DELETES ALL existing customers and job orders
- **Irreversible**: No automatic backup is created
- **Production Use**: Always backup database before running
- **Confirmation**: Use `--force` flag carefully

### Recommended Workflow
1. **Backup Database**: Create full database backup
2. **Test Import**: Run on development/staging environment first
3. **Verify CSV**: Ensure CSV file format is correct
4. **Check Plans**: Verify all plan types exist in database
5. **Execute**: Run command with appropriate flags
6. **Verify Results**: Check import statistics and data quality

## Verification Commands

### Check Import Results
```bash
# Count customers
php artisan tinker --execute="echo 'Customers: ' . App\Models\Customer::count()"

# Check plan distribution
php artisan tinker --execute="
App\Models\Customer::whereNotNull('plan_id')
    ->with('plan')
    ->get()
    ->groupBy('plan.name')
    ->map->count()
    ->each(function(\$count, \$plan) { echo \$plan . ': ' . \$count . PHP_EOL; });
"

# Verify data quality
php artisan tinker --execute="
echo 'With plans: ' . App\Models\Customer::whereNotNull('plan_id')->count() . PHP_EOL;
echo 'Without plans: ' . App\Models\Customer::whereNull('plan_id')->count() . PHP_EOL;
"
```

## Conclusion

The fresh import command provides a reliable way to reset and reimport customer data with proper plan assignments. It handles all the complexities of CSV parsing, data validation, and database constraints while providing comprehensive feedback and error handling.

**Success Rate**: 100% (193/193 records imported successfully)
**Data Quality**: All customers properly assigned to correct plans
**Performance**: Fast import with progress tracking
**Reliability**: Comprehensive error handling and validation
