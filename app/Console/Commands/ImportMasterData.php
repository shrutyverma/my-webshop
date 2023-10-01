<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
class ImportMasterData extends Command
{
    protected $signature = 'import:masterdata';
    protected $description = 'Import master data from CSV files';

    public function handle()
    {
        try {
            $username = 'loop';
            $password = 'backend_dev';
            // Import Customers
            $customerCsvData = $this->downloadAndParseCSV($username, $password, 'https://backend-developer.view.agentur-loop.com/customers.csv');
            $importedCustomers = $this->importCustomers($customerCsvData);

            // Import Products
            $productCsvData = $this->downloadAndParseCSV($username, $password, 'https://backend-developer.view.agentur-loop.com/products.csv');
            $importedProducts = $this->importProducts($productCsvData);

            // // Log import results
            Log::info("Imported $importedCustomers customers.");
            Log::info("Imported $importedProducts products.");

            $this->info('Master data import completed successfully.');
            Log::info("Imported customers.");
        } catch (\Exception $e) {
            Log::error('Error during master data import: ' . $e->getMessage());
            $this->error('Master data import failed.');
        }
    }

    private function downloadAndParseCSV($username, $password, $url)
    {

        $response = Http::withBasicAuth($username, $password)->get($url);

        if ($response->successful()) {
            $csvData = $response->body();
            $this->error('HTTP request passed with status code: ' . $response->status());
            //Continue processing the CSV data
            return collect(explode("\n", $csvData))->map(function ($row) {
                return str_getcsv($row);
            });
        } else {
            // Handle the error, e.g., log it
            Log::error('HTTP request failed with status code: ' . $response->status());
            $this->error('HTTP request failed with status code: ' . $response->status());
        }
    }

    private function importCustomers($data)
    {
        $importedCount = 0;
        $data = $data->slice(1);
        foreach ($data as $row) {
            // Assuming CSV structure: ID,Job Title,Email Address,FirstName LastName,registered_since,phone
            list($id, $jobTitle, $email, $fullName, $registeredSince, $phone) = $row;

            // Extract first and last name from full name
            [$firstName, $lastName] = explode(' ', $fullName, 2);

            // Create a DateTime object from the original string
            $dateTime = new \DateTime($registeredSince);

            // Format the DateTime object in the desired format
            $formattedDate = $dateTime->format('Y-m-d H:i:s'); // Example format

            // Log data before insertion
            // Log::info('Data before insertion:', [
            //     'id' => $id,
            //     'job_title' => $jobTitle,
            //     'email' => $email,
            //     'first_name' => $firstName,
            //     'last_name' => $lastName,
            //     'registered_since' => $formattedDate,
            //     'phone' => $phone,
            // ]);

            // Attempt to create a new Customer
            try {
                Customer::create([
                    'id' => $id,
                    'job_title' => $jobTitle,
                    'email' => $email,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'registered_since' => $formattedDate,
                    'phone' => $phone,
                ]);
                
                // Log success if no exceptions are thrown
                Log::info('Customer created successfully.');
            } catch (\Exception $e) {
                // Log any exceptions or errors
                Log::error('Error creating customer: ' . $e->getMessage());
            }

            $importedCount++;
        }

        return $importedCount;
    }

    private function importProducts($data)
    {
        $importedCount = 0;
        $data = $data->slice(1);
        foreach ($data as $row) {
            // Assuming CSV structure: ID,productname,price
            list($id, $productName, $price) = $row;

            // Insert product into the database
            Product::create([
                'id' => $id,
                'productname' => $productName,
                'price' => $price,
            ]);

            $importedCount++;
        }

        return $importedCount;
    }
}
