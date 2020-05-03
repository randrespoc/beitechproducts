<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>Products System</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="{{asset('orders.css')}}">

        <script   src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>

        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

        <script src="{{asset('orders.js')}}"></script>

    </head>
    <body>
        <div class="container ">
            <div class="row head">
                <div class="col-12">
                    <h1>Products System</h1>
                </div>
            </div>
            <div class="row content">
                <div class="col-12 title">
                    <h3>Orders :: List</h3>
                </div>

                <div class="col-12">
                    <form>
                        <div class="form-group col-12 col-lg-3 col-sm-8 float-left">
                            <label for="exampleFormControlSelect1">Customers</label>
                            <select id="customers" class="form-control">
                                <option value="">Loading...</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-sm-6 col-lg-3 float-left">
                            <label for="creation_date_from">Creation Date</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">From</div>
                                </div>
                                <input type="text" class="form-control" id="creation_date_from" placeholder="YYYY-MM-DD">
                            </div>
                        </div>
                        <div class="form-group col-12 col-sm-6 col-lg-3 float-left">
                            <label for="creation_date_to" class="d-none d-sm-block">&nbsp;</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">To</div>
                                </div>
                                <input type="text" class="form-control" id="creation_date_to" placeholder="YYYY-MM-DD">
                            </div>
                        </div>
                        <div class="form-group col-12 col-sm-6 col-lg-3 float-left">
                            <label class="d-none d-sm-block">&nbsp;</label>
                            <button id="btnLoad" type="button" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>

                <div class="col-12 no-data">
                    No data found
                </div>

                <div class="col-12 loading">
                    Loading ...
                </div>

                <div class="col-12 data">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Order Id</th>
                                <th scope="col">Creation Date</th>
                                <th scope="col">Total</th>
                                <th scope="col">Address</th>
                                <th scope="col">Products</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
