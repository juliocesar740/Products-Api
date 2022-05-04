<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Products Api</title>

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

    </style>
</head>

<body class="mx-auto d-flex flex-column justify-content-center align-items-center "
    style="width:95%;font-family:'Nunito', sans-serif">
    <div class="align-self-start w-100" style="margin: 25px 0">

        @if (session('product_msg'))
            <div class="alert alert-success">
                {{ session('product_msg') }}
            </div>
        @endif

        @if (session('category_msg'))
            <div class="alert alert-success">
                {{ session('category_msg') }}
            </div>
        @endif

        <h1>Products Api</h1>
        <a href="/addProduct" class="btn btn-primary btn-lg">Add a product</a>
        <a href="/addCategory" class="btn btn-primary btn-lg">Add a Category</a>

        @if (count($products) > 0)
            <a href="/products" class="btn btn-success btn-lg">All products</a>
        @endif

        @if (count($categories) > 0)
            <a href="/categories" class="btn btn-success btn-lg">All categories</a>
        @endif


        <div style="margin-top: 20px;">
            <h4><b style="color: rgb(19, 100, 186)">There are {{ count($products) }} products</b></h4>
            <h4><b style="color: rgb(19, 100, 186)">There are {{ count($categories) }} categories</b></h4>
        </div>

    </div>
    <div class="w-100 gap-4">

        <h4>Lastest products added</h4>

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
                                src="storage/{{ empty($product['filename']) ? 'images/image_not_found.png' : "images/$product[filename]"  }}" alt=""></td>
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
    <br>
</body>

</html>
