<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class APIController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    
    public function index(Request $request)
    {
        $paginatedData = null;
        try {
            
            // Baca data dari file API atau gunakan data manual jika tidak ada data dari API
            if (file_exists(storage_path('api_data.json'))) {
                $localData = json_decode(file_get_contents(storage_path('api_data.json')), true);
            } else {
                throw new \Exception('File api_data.json not found or cannot be read.');
        }

            // Mengambil data time series dari data lokal menggunakan foreach
        $timeSeries = $localData['Time Series (Daily)'];
        krsort($timeSeries);
        foreach ($timeSeries as $date => $data) {
            // Lakukan sesuatu dengan data, jika diperlukan
            Log::info("Date: $date, Data: " . json_encode($data));
        }
            
            
        Log::info('Initial Time Series Data: ' . json_encode($timeSeries));


            

             // Get the current page from the request
        $currentPage = $request->input('page', 1);
            if (isset($localData['Time Series (Daily)'])) {
                $timeSeriesData = $localData['Time Series (Daily)'];
                $metaData = $localData['Meta Data'];

                Log::info('Request Parameters: ' . json_encode($request->all()));
                Log::info('Total Items in Collection: ' . count($timeSeriesData));
            // Tambahkan log di berbagai titik untuk debugging
                

            Log::info('Current Page: ' . $currentPage);
            if ($paginatedData !== null) {
                Log::info('Data on Current Page: ' . json_encode($paginatedData->items()));
            } else {
                Log::info('Pagination data is null.');
            }


                

                // Filter data berdasarkan tahun jika search_date berupa tahun
$searchDate = $request->input('search_date');
$dateFormat = '';

if (empty($searchDate)) {
    // Jika pencarian kosong, tampilkan seluruh data
    $timeSeriesData = $localData['Time Series (Daily)'];

} elseif (strlen($searchDate) === 4) {
    $targetYear = $searchDate;
    Log::info('Searching for Year: ' . $targetYear);
    // Ambil array kunci (tanggal) dari $timeSeriesData
    $dates = array_keys($timeSeriesData);
    
    
    // Filter tanggal untuk tahun yang diinginkan
    $filteredDates = array_filter($dates, function ($date) use ($targetYear) {
        $dateTime = new \DateTime($date);
        $year = $dateTime->format('Y');
        return (int)$year == (int)$targetYear;
        
    });
    Log::info('Filtered Dates for Year ' . $targetYear . ': ' . implode(', ', $filteredDates));

    // Buat array baru hanya dengan tanggal-tanggal yang sesuai
    $timeSeriesData = array_intersect_key($timeSeriesData, array_flip($filteredDates));

    // Urutkan data setelah filtrasi
    krsort($timeSeriesData);

} elseif (strlen($searchDate) === 7) {
    $dateFormat = 'Y-m';
    Log::info('Searching for Year-Month: ' . $searchDate);

    // Filter data untuk tahun dan bulan yang diinginkan
    $targetYearMonth = $searchDate;
    $timeSeriesData = array_filter($timeSeriesData, function ($date) use ($targetYearMonth) {
        return isset($timeSeriesData[$date]) && substr($date, 0, 7) === $targetYearMonth;
    });
    Log::info('Filtered Dates for Year-Month ' . $targetYearMonth . ': ' . implode(', ', array_keys($timeSeriesData)));

    // Urutkan data setelah filtrasi
    krsort($timeSeriesData);
} elseif (strlen($searchDate) === 10) {
    $dateFormat = 'Y-m-d';
    Log::info('Searching for Date: ' . $searchDate);

    // Filter data untuk tanggal yang diinginkan
    $targetDate = $searchDate;
    $timeSeriesData = array_filter($timeSeriesData, function ($date) use ($targetDate) {
        return isset($timeSeriesDatza[$date]) && $date === $targetDate;
    });
    Log::info('Filtered Dates for Date ' . $targetDate . ': ' . implode(', ', array_keys($timeSeriesData)));

krsort($timeSeriesData);
            }
// Log data setelah filtrasi
Log::info('Filtered Time Series Data: ' . json_encode($timeSeries));
Log::info('Filtered Time Series Data: ' . json_encode($timeSeriesData));

// Check if search date is empty, then display all data
if (empty($searchDate)) {
    $timeSeriesData = $localData['Time Series (Daily)'];
} elseif (!empty($dateFormat)) {
    // Perform search based on the determined date format
    $searchResult = $this->searchTimeSeries($timeSeries, $searchDate, $dateFormat);

    if (!empty($searchResult)) {
        // Handle the search result
        $timeSeriesData = $searchResult;
    } else {
        $timeSeriesData = [];
        Log::info('No match for the search date.');
    }
    // Add the following log to check if the format is correct but no data is found
    Log::info('Search Date: ' . $searchDate);
    Log::info('Date Format: ' . $dateFormat);
    Log::info('Search Result: ' . json_encode($searchResult));
}



               // Perform binary search based on the determined date format
if (!empty($dateFormat)) {
    ksort($localData['Time Series (Daily)']);
    $resultIndex = $this->binarySearch(array_keys($localData['Time Series (Daily)']), date($dateFormat, strtotime($searchDate)), $dateFormat);

    Log::info('Search Date: ' . $searchDate);
    Log::info('Date Format: ' . $dateFormat);
    Log::info('Available Datesq: ' . implode(', ', array_keys($localData['Time Series (Daily)'])));
    Log::info('Binary Search Result Indexq: ' . $resultIndex);

    if ($resultIndex !== null) {
        $foundDate = array_keys($localData['Time Series (Daily)'])[$resultIndex];

        // Check if the found date matches the search date based on the given format
        if ($this->datesMatch($foundDate, $searchDate, $dateFormat)) {
            Log::info('Found Date: ' . $foundDate);
            Log::info('Found Date Data: ' . json_encode($localData['Time Series (Daily)'][$foundDate]));

            // If the search date has a specific day, return only that day's data
            if (strlen($searchDate) === 10) {
                $timeSeriesData = [$foundDate => $localData['Time Series (Daily)'][$foundDate]];
            } else {
                // If the search date only has a year or year and month, return data for the entire month
                $timeSeriesData = $this->searchTimeSeries($localData['Time Series (Daily)'], $searchDate, $dateFormat);
            }
        } else {
            $timeSeriesData = [];
            Log::info('No match for the search date.');
        }
    } else {
        $timeSeriesData = [];
        Log::info('No result from binary search.');
    }
}
                
                $closePrices = [];
                foreach ($timeSeriesData as $date => $data) {
                    if (isset($data['4. close']) && is_numeric($data['4. close'])) {
                        $closePrices[] = (float)$data['4. close'];
                    }
                }

                Log::info('Close Prices: ' . json_encode($closePrices));

                // Log the sum and count of close prices
                Log::info('Sum of Close Prices: ' . array_sum($closePrices));
                Log::info('Count of Close Prices: ' . count($closePrices));

                $invalidValues = array_filter($closePrices, function ($value) {
                    return !is_finite($value);
                });

                // Log any invalid values
                if (!empty($invalidValues)) {
                    Log::warning('Invalid Values: ' . json_encode($invalidValues));
                }
                
                //menghitung average
                $average = count($closePrices) > 0 ? array_sum($closePrices) / count($closePrices) : 0;
                Log::info('Average: ' . $average);
                

                //menghitung median
                sort($closePrices);
                $count = count($closePrices);
                $middle = floor(($count - 1) / 2);
                $median = $count > 0 ? $closePrices[$middle] : $average;
                Log::info('Median: ' . $median);

                
                $sumSquaredDeviations = array_sum(array_map(function ($x) use ($average) {
                    return is_numeric($x) ? pow($x - $average, 2) : 0;
                }, $closePrices));
                $standardDeviation = $count > 0 ? sqrt($sumSquaredDeviations / $count) : 0;
                Log::info('Standard Deviation: ' . $standardDeviation);
                Log::info('Closing Price: ' . json_encode($closePrices));
                Log::info('Total Closing Price: ' . array_sum($closePrices));
                Log::info('Closing Price Count: ' . count($closePrices));

                $circulatingShares = 7033462308;
$marketCap = $circulatingShares * $data['4. close'];

function formatMarketCap($marketCap) {
    $suffix = 'B €'; // Set the default suffix to Billion Euros

    // Convert to Billion Euros if greater than or equal to 1 Billion
    if ($marketCap < 1000000000) {
        $marketCap = $marketCap / 1000000000;
        $suffix = 'B €';
    }

    return number_format($marketCap, 4) . $suffix;
}

Log::info('Market Cap: ' . formatMarketCap($marketCap));


$percentage = 1; // Change this to your desired percentage
$volume = $data['5. volume'];
$turnover = $volume * $data['4. close'];
$percentageTurnover = $turnover * 100 * $percentage;
Log::info('Turnover: ' . number_format($percentageTurnover, 2) . '%');



                



// Mengambil data time series dari data lokal
$timeSeries = $localData['Time Series (Daily)'];

// Menentukan jumlah item per halaman
$perPage = 10;

// Mendapatkan halaman yang diminta dari parameter request atau default ke halaman 1
$currentPage = $request->input('page', 1);

// Menggunakan koleksi Laravel untuk mendukung paginasi
$dataCollection = collect($timeSeries);

// Menggunakan metode skip dan take untuk memotong data berdasarkan halaman dan jumlah per halaman

// Membuat objek paginasi

$paginatedData = new LengthAwarePaginator(
    $dataCollection->forPage($currentPage, $perPage),
    count($dataCollection),
    $perPage,
    $currentPage,
    ['path' => $request->url(), 'query' => $request->query()]
);

// Mengatur koleksi untuk paginator

Log::info('Paginated Data: ' . json_encode($paginatedData));
Log::info('Current Page: ' . $paginatedData->currentPage());


                
$turnoverPercentage = ($turnover / $marketCap) * 100;
                
                
                // Pass the statistics to the view
                $statistics = [
                    'average' => $average,
                    'median' => $median,
                    'standardDeviation' => $standardDeviation,
                    'marketCap' => $marketCap,
                    'turnover' => $turnoverPercentage,
                ];
                return view('trading.index', compact('metaData', 'statistics', 'timeSeriesData', 'paginatedData'));
} else {
    return view('trading.index', ['paginatedData' => $paginatedData]);
}

} catch (\Exception $e) {
    // Handle error
    Log::error('Error processing data: ' . $e->getMessage());
    return response()->json(['error' => $e->getMessage()], 500);
}
    }



    private function datesMatch($foundDate, $searchDate, $dateFormat)
    {
        $foundTimestamp = strtotime($foundDate);
        $searchTimestamp = strtotime($searchDate);
    
        // Check if the found date matches the search date based on the given format
        if ($dateFormat === 'Y') {
            // Check if the year matches
            return date('Y', $foundTimestamp) === date('Y', $searchTimestamp);
        } elseif ($dateFormat === 'Y-m') {
            // Check if the year and month match
            return date('Y-m', $foundTimestamp) === date('Y-m', $searchTimestamp);
        } elseif ($dateFormat === 'Y-m-d') {
            // Check if the full date matches
            return date('Y-m-d', $foundTimestamp) === date('Y-m-d', $searchTimestamp);
        }
    
        return false;
    }
private function searchTimeSeries($timeSeries, $searchDate, $dateFormat)
{
    $result = [];

    foreach ($timeSeries as $date => $data) {
        // Check if the found date matches the search date based on the given format
        if ($this->datesMatch($date, $searchDate, $dateFormat)) {
            $result[$date] = $data;
        }
    }

    return $result;
}


private function binarySearch($array, $searchDate, $dateFormat)
{
    $low = 0;//index perrtama
    $high = count($array) - 1;//index terakhir
    $closestIndex = null;//inisialisasi menyimpan variabel terdekat

    while ($low <= $high) {
        $mid = floor(($low + $high) / 2);//menghitung index tengah memeriksa kecocokan

        if ($array[$mid] == $searchDate || $this->datesMatch($array[$mid], $searchDate, $dateFormat)) {
            return $mid; // Found the date
        } elseif ($array[$mid] < $searchDate) {
            $low = $mid + 1; // Search the right half
        } else {
            $high = $mid - 1; // Search the left half
        }

        // Save the closest index
        if ($closestIndex === null || abs(strtotime($array[$mid]) - strtotime($searchDate)) < abs(strtotime($array[$closestIndex]) - strtotime($searchDate))) {
            $closestIndex = $mid;
        }
    }

    return $closestIndex; // Return the closest index
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
