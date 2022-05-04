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

    <title>Update task</title>
</head>

<body class="mx-auto d-flex flex-column" style="width:80%;font-family:'Nunito', sans-serif">

    <div class="align-self-start" style="margin: 25px 0">

        <a href="/" class="btn btn-primary btn-lg">Home</a>
        <h1 class="mt-4">Update Product</h1>

    </div>

    <div>
        <form enctype="multipart/form-data" action={{ '/product/' . $product['id'] }} method="POST"
            class="w-100  d-flex flex-column gap-3">

            @csrf
            @method('PUT')

            <div class="d-flex flex-column gap-2">

                <img height="300" width="500" src="/storage/{{ empty($product['filename']) ? 'images/image_not_found.png' : "images/$product[filename]" }}"
                    alt="">

                <label for="image">Image of the product</label>
                <input type="hidden" name="filename" id="filename">
                <input type="file" name="picture" class="p-2"
                    style="border-radius: 7.5px;border:1px solid black" id="image-input">
            </div>

            @if ($errors->productUpdate->first('name'))
                <span class="fw-bold text-danger">{{ $errors->productUpdate->first('name') }}</span>
            @endif

            @if (session('product_error_msg'))
                <span class="fw-bold text-danger">{{ session('product_error_msg') }}</span>
            @endif

            <div class="d-flex flex-column gap-2">
                <label for="name">name</label>
                <input type="text" name="name" class="p-2"
                    style="border-radius: 7.5px;border:1px solid black" id="name" placeholder="Name of the product"
                    value="{{ $product['name'] }}">
            </div>

            @if ($errors->productUpdate->first('price'))
                <span class="fw-bold text-danger">{{ $errors->productUpdate->first('price') }}</span>
            @endif

            <div class="d-flex flex-column gap-2">
                <label for="price">price</label>
                <input type="text" name="price" class="p-2"
                    style="border-radius: 7.5px;border:1px solid black" id="price" placeholder="price of the product"
                    min="0" max="9999" value="{{ $product['price'] }}">
            </div>

            <div class=" d-flex flex-column gap-2">
                <label for="status">Category</label>
                <select id="category" name="category" class="p-2">
                    <option value="none" selected disabled hidden>Select the category of the product</option>

                    @foreach ($categories as $category)
                        <option value="{{ $category['name'] }}"
                            {{ getCategory($product['category'])['name'] === $category['name'] ? 'selected' : '' }}>
                            {{ $category['name'] }}</option>
                    @endforeach

                </select>
            </div>

            <div class=" d-flex flex-column gap-2">
                <label for="description">Description</label>
                <textarea name="description" class="p-2" style="resize: none" id="" cols="30" rows="5"
                    placeholder="Description of the product">{{ $product['description'] ?? '' }}</textarea>
            </div>

            <div class="">
                <input type="submit" value="Submit" class="btn btn-primary" style="margin: 7.5px 0;">
            </div>

        </form>
    </div>
    <script>
        document.querySelector('#image-input').addEventListener('input', function(e) {
            document.querySelector('#filename').value = e.target.value;
        })
    </script>
</body>

</html>
