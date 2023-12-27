<!DOCTYPE html>
<html lang="en">

<head>
  @include('admin.css')
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <style type="text/css">
    .action-column {
      width: 150px;
    }

    table {
      width: 100%;
      text-align: center;
    }

    th, td {
      text-align: center;
      padding: 10px;
    }

    td.title-column {
      max-width: 300px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: normal;
      word-wrap: break-word;
    }

    td.description-column {
      max-width: 700px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: normal;
      word-wrap: break-word;
    }

    .img_size {
      width: 100%;
      max-width: 200px;
      height: auto;
    }
  </style>

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>

  @include('admin.navbar')
  @include('admin.sidebar')

  <div class="main-panel">
    <div class="content-wrapper">

      @if(session()->has('message'))
      <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        {{session()->get('message')}}
      </div>
      @endif

      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body  text-center">
            <h4 class="card-title">All Orders</h4>
            <div style="margin: auto; padding-bottom: 30px; padding-top: 20px;">
              <form id="searchForm" action="{{ url('search_order') }}" method="get" class="mb-3">
                @csrf
                <div class="input-group">
                  <input type="text" id="search_order" name="search_order" class="form-control" placeholder="Search Order" value="{{ old('search_order') }}">
                  <button type="submit" class="btn btn-outline-primary">Search</button>
                </div>
              </form>
            </div>
            <div class="table-responsive">
              <table class="table table-striped" id="orderTable">
                <thead>
                  <tr>
                    <th>NAME</th>
                    <th>EMAIL</th>
                    <th>ADDRESS</th>
                    <th>PHONE</th>
                    <th>PRODUCT TITLE</th>
                    <th>QUANTITY</th>
                    <th>PRICE</th>
                    <th>PAYMENT STATUS</th>
                    <th>DELIVERY STATUS</th>
                    <th>IMAGE</th>
                    <th>ACTION</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($order as $order)
                  <tr>
                    <td class="title-column">{{$order->name}}</td>
                    <td class="title-column">{{$order->email}}</td>
                    <td class="title-column">{{$order->address}}</td>
                    <td class="title-column">{{$order->phone}}</td>
                    <td class="title-column">{{$order->product_title}}</td>
                    <td class="title-column">{{$order->quantity}}</td>
                    <td class="title-column">Rp. {{number_format($order->price, 0, ',', '.')}}</td>
                    <td class="title-column">{{$order->payment_status}}</td>
                    <td class="title-column">{{$order->delivery_status}}</td>
                    <td>
                      <img class="img_size" src="/product/{{$order->image}}">
                    </td>
                    <td class="action-column">
                      <a class="btn btn-gradient-success btn-fw" href="{{url('paid', $order->id)}}"> Paid</a>
                      <a class="btn btn-gradient-info btn-fw" href="{{url('delivered', $order->id)}}">Delivered</a>
                    </td>

                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      @include('admin.script')
      <script>
        $(document).ready(function () {
          // Tangani pengajuan formulir pencarian dengan Ajax
          $('#searchForm').submit(function (e) {
            e.preventDefault();

            $.ajax({
              type: 'GET',
              url: $('#searchForm').attr('action'),
              data: $('#searchForm').serialize(),
              success: function (response) {
                // Mengganti konten tabel dengan hasil pencarian
                $('#orderTable tbody').html($(response).find('#orderTable tbody').html());
              },
              error: function (error) {
                console.log(error);
              }
            });
          });
        });
      </script>
    </div>
  </div>

</body>

</html>