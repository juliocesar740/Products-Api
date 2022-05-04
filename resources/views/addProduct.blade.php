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

    <title>Add task</title>
</head>

<body class="mx-auto d-flex flex-column" style="width:80%;font-family:'Nunito', sans-serif">

    <div class="align-self-start" style="margin: 25px 0">

        <a href="/" class="btn btn-primary btn-lg">Home</a>
        <h1 class="mt-4">Add Product</h1>

    </div>

    <div>
        <form enctype="multipart/form-data" action="/product" method="POST" class="w-100  d-flex flex-column gap-3">
            @csrf

            <div class="d-flex flex-column gap-2">
                <label for="image">Image of the product</label>
                <input type="hidden" name="filename" id="filename">
                <input type="file" name="picture" id="image-input" class="p-2"
                    style="border-radius: 7.5px;border:1px solid black" id="image">
            </div>

            @if ($errors->productCreate->first('name'))
                <span class="fw-bold text-danger">{{ $errors->productCreate->first('name') }}</span>
            @endif

            <div class="d-flex flex-column gap-2">
                <label for="name">name</label>
                <input type="text" name="name" class="p-2" style="border-radius: 7.5px;border:1px solid black"
                    id="name" placeholder="Name of the product">
            </div>

            @if ($errors->productCreate->first('price'))
                <span class="fw-bold text-danger">{{ $errors->productCreate->first('price') }}</span>
            @endif

            <div class="d-flex flex-column gap-2">
                <label for="price">price</label>
                <input type="text" name="price" class="p-2"
                    style="border-radius: 7.5px;border:1px solid black" id="price" placeholder="price of the product"
                    min="0" max="9999">
            </div>

            @if ($errors->productCreate->first('category'))
                <span class="fw-bold text-danger">{{ $errors->productCreate->first('category') }}</span>
            @endif

            <div class=" d-flex flex-column gap-2">
                <label for="status">Category</label>
                <select id="category" name="category" class="p-2">
                    <option value="none" selected disabled hidden>Select the category of the product</option>

                    @foreach ($categories as $category)
                        <option value="{{ $category['name'] }}">{{ $category['name'] }}</option>
                    @endforeach

                </select>
            </div>
            
            <div class=" d-flex flex-column gap-2">
                <label for="description">Description</label>
                <textarea name="description" class="p-2" style="resize: none" id="" cols="30" rows="5"
                    placeholder="Description of the product"></textarea>
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
