<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Add head section as needed -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stock Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        body {
        background-color: black; /* Set the background color of the page to black */
        
    }
    .table-container {
        background-color: rgba(51, 51, 51, 0.7); /* Warna latar belakang dengan alpha (0.7 untuk tingkat transparansi) */
        color: purple; /* Set text color for table content */
    }

    .table th,
    .table td {
        border-color: #555; /* Set border color for table cells */
    }

    .metadata-list {
        list-style-type: none;
        padding: 0;
    }

    .metadata-item {
        font-family: 'poppins', sans-serif;
        font-size: 16px;
        margin-bottom: 10px;
    }

    .metadata-item strong {
        color: #ff00f7; /* Ganti dengan warna yang diinginkan untuk judul metadata */
    }

    .additional-info-item {
        font-family: 'Arial', sans-serif;
        font-size: 14px;
        margin-bottom: 8px;
        color: #555;
    }

    .pagination {
        margin-top: 20px; /* Add some space above the pagination */
    }

    .pagination .page-item.active .page-link {
        background-color: #51024e; /* Set background color for active page */
        border-color: #ff00cc; /* Set border color for active page */
    }

    .pagination .page-link {
        color: #ff00d9; /* Set text color for pagination links */
    }

    .logo-container {
            text-align: left;
            margin-bottom: 5px;
        }

        .logo-container img {
            max-width: 100%;
            max-height: 60px;
            height: auto;
        }
        .my-statistics-container {
        

    
    }
    .my-average-container {
        display: flex;
width: 200px;
height: 50px;
padding: 7px 55px;
justify-content: center;
align-items: center;
gap: 10px;
flex-shrink: 0;
border-radius: 30px;
background: var(--fig-jam-gradation-pink, linear-gradient(90deg, #E95241 0%, #FF62EF 100%));
/* FigJam/Deep Shadow */
box-shadow: 15px 15px 30px 0px rgba(166, 171, 189, 0.50), -15px -15px 30px 0px #FAFBFF;
    }

    .my-median-container {
        display: flex;
width: 200px;
height: 50px;
padding: 7px 55px;
justify-content: center;
align-items: center;
gap: 10px;
flex-shrink: 0;

border-radius: 30px;
background: var(--fig-jam-gradation-purple, linear-gradient(90deg, #DF59FF 0%, #656FFF 100%));
/* FigJam/Deep Shadow */
box-shadow: 15px 15px 30px 0px rgba(166, 171, 189, 0.50), -15px -15px 30px 0px #FAFBFF;


    }

    .my-standard-deviation-container {
    display: flex;
    width: 260px;
    height: 50px;
    padding: 7px 20px; /* Mengurangi padding di sisi kanan dan kiri */
    justify-content: center;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
    border-radius: 30px;
    background: var(--fig-jam-gradation-orange, linear-gradient(90deg, #FF703F 0%, #FFC547 100%));
    /* FigJam/Deep Shadow */
    box-shadow: 15px 15px 30px 0px rgba(166, 171, 189, 0.50), -15px -15px 30px 0px #FAFBFF;
}

.my-marketcap-container {
    display: flex;
width: 260px;
height: 50px;
padding: 7px 55px;
justify-content: center;
align-items: center;
gap: 10px;
flex-shrink: 0;

border-radius: 30px;
background: var(--fig-jam-gradation-blue, linear-gradient(90deg, #1475dd 0%, #28ceec 100%));

/* FigJam/Deep Shadow */
box-shadow: 15px 15px 30px 0px rgba(166, 171, 189, 0.50), -15px -15px 30px 0px #FAFBFF;
}

.my-turnover-container {
    display: flex;
width: 200px;
height: 50px;
padding: 7px 55px;
justify-content: center;
align-items: center;
gap: 10px;
flex-shrink: 0;

border-radius: 30px;
background: var(--fig-jam-gradation-green, linear-gradient(90deg, #06ad33 0%, #17cbcb 100%));

/* FigJam/Deep Shadow */
box-shadow: 15px 15px 30px 0px rgba(166, 171, 189, 0.50), -15px -15px 30px 0px #FAFBFF;
}

    

    
    
    </style>
</head>

<body>
    <main class="container">
        

        <!-- Display Meta Data -->
        @if (!empty($metaData))
            <div class="my-3 p-3 bg-body rounded shadow-sm">
                <div class="row">
                    <div class="col-md-6">
                        
                        <ul>
                            <!-- Logo Section -->
<div class="logo-container">
    <img src="{{ asset('images/logo_tesco_resized.svg_.png') }}" alt="Company Logo">
</div>


                            
                            @foreach ($metaData as $key => $value)
                                <li><strong>{{ $key }}:</strong> {{ $value }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-6">
                        
                        
                        <div class="tradingview-widget-container" style="padding-left: 15px;">
                            <!-- Tambahkan padding ke kiri di sini -->
                            <div id="tradingview_29e7f" style="height: 250px;"></div>
                            <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
                            <script type="text/javascript">
                                new TradingView.widget({
                                    "autosize": true,
                                    "symbol": "LSE:TSCO",
                                    "interval": "D",
                                    "timezone": "Etc/UTC",
                                    "theme": "light",
                                    "style": "1",
                                    "locale": "id",
                                    "enable_publishing": false,
                                    "allow_symbol_change": true,
                                    "container_id": "tradingview_29e7f"
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Display Statistics -->
@if (!empty($statistics))
<div class="my-statistics-container my-3 p-3 bg-body rounded shadow-sm">
    <h5 class="border-bottom pb-2 mb-4">Statistics</h5>

    <div class="row align-items-center">

        <!-- Average Container -->
        <div class="col-md-2 mb-3">
            <div class="my-average-container text-white">
                <strong>Average:</strong> {{ number_format($statistics['average'], 4) }}
            </div>
        </div>

        <!-- Median Container -->
        <div class="col-md-2 mb-3">
            <div class="my-median-container text-white">
                <strong>Median:</strong> {{ number_format($statistics['median'], 4) }}
            </div>
        </div>

         <!-- Standard Deviation Container -->
         <div class="col-md-3 mb-3">
            <div class="my-standard-deviation-container text-white">
                <strong>Standard Deviation:</strong> {{ number_format($statistics['standardDeviation'], 4) }}
            </div>
        </div>

        @php
    if (!function_exists('formatMarketCap')) {
    function formatMarketCap($marketCap) {
        $suffix = ' ';
        if ($marketCap >= 1000000000) {
            $marketCap = $marketCap / 1000000000;
            $suffix = 'B ';
        } else {
            $suffix = 'M ';
        }
        $formattedMarketCap = number_format($marketCap, 2); // Use 6 digits to include a comma
        $formattedMarketCap = substr($formattedMarketCap, 0, -1); // Remove the last digit after the comma
        
        $formattedMarketCap = number_format($marketCap, 1);
        return '€ ' . $formattedMarketCap . $suffix;
    }
}
@endphp

<div class="col-md-3 mb-3">
    <div class="my-marketcap-container text-white text-end">
        <strong>Mktcap:</strong> {{ formatMarketCap($statistics['marketCap']) }}
    </div>
</div>







        <!-- Turnover Container -->
<div class="col-md-2 ms-auto mb-3">
    <div class="my-turnover-container text-white">
        <strong>Turnover:</strong> {{ number_format($statistics['turnover'], 2) }}%
    </div>
</div>

@endif









        <!-- Add the search form -->
        <form action="{{ url()->current() }}" method="get">
            <div class="input-group mb-3">
                <input type="text" class="form-control datepicker" name="search_date" placeholder="Years/Month/Day"
                    value="{{ request('search_date') }}">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>

        <!-- Display Stock Data -->
        <div class="table-container my-3 p-3 bg-body rounded shadow-sm">
            <h5 class="border-bottom pb-2 mb-4">Stock Data</h5>
            @if (!empty($timeSeriesData))
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="col-md-1">No</th>
                            <th class="col-md-2">Date</th>
                            <th class="col-md-2">Open</th>
                            <th class="col-md-2">High</th>
                            <th class="col-md-2">Low</th>
                            <th class="col-md-2">Close</th>
                            <th class="col-md-1">Volume</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($timeSeriesData as $date => $item)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $date }}</td>
                                <td>{{ $item['1. open'] }}</td>
                                <td>{{ $item['2. high'] }}</td>
                                <td>{{ $item['3. low'] }}</td>
                                <td>{{ $item['4. close'] }}</td>
                                <td>{{ $item['5. volume'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                


<!-- Pagination links -->
<div class="pagination d-flex justify-content-start">
<ul class="pagination">
    {{-- Previous page link --}}
    @if ($paginatedData->onFirstPage())
        <!-- Disabled link for the first page -->
        <li class="page-item disabled">
            <span class="page-link">&laquo; Prev</span>
        </li>
    @else
        <li class="page-item">
            <a href="{{ $paginatedData->previousPageUrl() }}" class="page-link" aria-label="Previous">
                <span aria-hidden="true">&laquo; Prev</span>
            </a>
        </li>
    @endif

    {{-- Calculate $startPage and $endPage --}}
    @php
        $startPage = max($paginatedData->currentPage() - 2, 1);
        $endPage = min($paginatedData->currentPage() + 2, $paginatedData->lastPage());
    @endphp

    {{-- Page links --}}
    @for ($i = $startPage; $i <= $endPage; $i++)
        <li class="page-item {{ $paginatedData->currentPage() == $i ? 'active' : '' }}">
            <a class="page-link" href="{{ $paginatedData->url($i) }}">{{ $i }}</a>
        </li>
    @endfor

    {{-- Next page link --}}
    @if ($paginatedData->hasMorePages())
        <li class="page-item">
            <a href="{{ $paginatedData->nextPageUrl() }}" class="page-link" aria-label="Next">
                <span aria-hidden="true">Next &raquo;</span>
            </a>
        </li>
    @else
        <!-- Disabled link for the last page -->
        <li class="page-item disabled">
            <span class="page-link">Next &raquo;</span>
        </li>
    @endif
</ul>
</div>

            @else
                <p>No data available.</p>
            @endif
        </div>

    </main>
    <!-- Add Bootstrap JS script as needed -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function() {
            // Add your JavaScript code here
        });
    </script>
</body>

</html>
