<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 10px;
            padding-bottom: 5px
        }

        /* Zebra striping */
        tbody tr:nth-child(odd) {
            background-color: #d3d9db;
        }

        td,
        th {
            border: 1px solid #ccc;
            text-align: left;
            padding: 20px 30px;
        }

        td {
            font-size: 14.75px;
            word-wrap: break-word;
        }

        .pagination-link {
            color: rgb(255, 255, 255);
            background-color: rgb(19, 100, 186);
            font-weight: 600;
        }

        .current-page-link {
            color: rgb(10, 149, 236);
            background-color: rgb(248, 248, 248);
        }

    </style>
    <title>Products</title>
</head>

<body class="mx-auto d-flex flex-column justify-content-center align-items-center "
    style="width:95%;font-family:'Nunito', sans-serif">

    <div class="align-self-start w-100" style="margin: 25px 0">
        <h1>Products</h1>
        <a href="/" class="btn btn-primary btn-lg">Home</a>
    </div>

    <div class="align-self-start w-100" style="margin: 5px 0">
        <form action="/products" method="get">

            <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" id="" placeholder="Search a product"
                    value="{{ $search ?? '' }}">
                @if ($sortByDate)
                    <input type="hidden" name="sortByDate" value="{{ $sortByDate }}">
                @endif
                <button class="btn input-group-text btn-outline-secondary" type="submit"><i
                        class="bi bi-search"></i></button>
            </div>

        </form>
    </div>

    <div class="align-self-start w-100" style="margin: 5px 0">
        <form action="/products" method="get">

            <div class="input-group mb-3" style="width:500px;">
                <label class="input-group-text">Sort the products</label>
                <select class="form-select form-control" name="sortByDate" id="">
                    <option value="newest" {{ $sortByDate === 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="oldest" {{ $sortByDate === 'oldest' ? 'selected' : '' }}>Oldest</option>
                </select>
                @if ($search)
                    <input type="hidden" name="search" value="{{ $search }}">
                @endif
                <button class="btn input-group-text btn-outline-secondary" type="submit">Sort</button>
            </div>

        </form>
    </div>

    <div class="w-100 gap-4">
        <table>
            <thead>
                <tr>
                    <th scope="col">Actions</th>
                    <th scope="col">Product Image</th>
                    <th scope="col">Product id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Category</th>
                    <th scope="col">Created_at</th>
                    <th scope="col">Updated_at</th>
                    <th scope="col">Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>
                            <div class="d-flex flex-row gap-1">
                                <a href={{ '/updateProduct/' . formatRouteParameter($product['name']) }}
                                    class="btn btn-success btn-lg">Edit</a>
                                <form action={{ '/product/' . $product['id'] }} method="post">
                                    @csrf
                                    @method('DELETE')
                                    <input class="btn btn-danger btn-lg" type="submit" value="Delete">
                                </form>
                            </div>

                        </td>
                        <td scope="row"><img height="150" width="150"
                                src="storage/{{ empty($product['filename']) ? 'images/image_not_found.png' : "images/$product[filename]" }}" alt=""></td>
                        <td scope="row">{{ $product['id'] }}</td>
                        <td scope="row" class="fw-bold">{{ $product['name'] }}</td>
                        <td scope="row" class="fw-bold">{{ '$' . $product['price'] }}</td>
                        <td scope="row" class="fw-bold m-0">{{ getCategory($product['category'])['name'] }}</td>
                        <td scope="row" class="fw-bold">{{ $product['created_at'] }}</td>
                        <td scope="row" class="fw-bold">{{ $product['updated_at'] }}</td>
                        <td scope="row" class="fw-bold">{{ $product['description'] ?? 'not found' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <nav aria-label="Page navigation example" style="margin-top: 25px;margin-bottom: 10px;">
        <ul class="pagination pagination-lg">

            <li class="page-item">

                @if (!$products_json->prev_page_url)
                    <span class="page-link pagination-link">Previous</span>
                @else
                    <a class="page-link pagination-link" href="{{ $products_json->prev_page_url }}">Previous</a>
                @endif

            </li>

            @for ($i = 1; $i < $products_json->last_page + 1; $i++)
                <li class="page-item"><a
                        class="page-link pagination-link {{ $i === $products_json->current_page ? 'current-page-link' : '' }}"
                        href="/products?page={{ $i }}">{{ $i }}</a></li>
            @endfor

            <li class="page-item">
                @if (!$products_json->next_page_url)
                    <span class="page-link pagination-link">Next</span>
                @else
                    <a class="page-link pagination-link" href="{{ $products_json->next_page_url }}">Next</a>
                @endif
            </li>

        </ul>
    </nav>

</body>

</html>
