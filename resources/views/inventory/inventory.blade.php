@extends('layouts.app')

@section('content')
<!-- success or error message -->
@if (session('success'))
  <h5 class="pop_out">{{ session('success') }}</h5>
@elseif (session('error'))
  <h5 class="pop_out">{{ session('error') }}</h5>
@endif

<div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
            <h1 class="title">Inventory tracking web application
        </div>

        <div>
            <h3>Create a new item</h3>
            <form action="{{url('/insert')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="itemName">Inventory Item Name:</label>
                    <input class="form-control" id="itemName" name="itemName" aria-describedby="emailHelp" placeholder="Enter Item Name" required>

                </div>
                <div class="form-group">
                    <label for="price">Unit price (In Dollor):</label>
                    <input type="number" class="form-control" id="price" name="price" placeholder="5" required min="0" step="any">
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" placeholder="15" required min="0">
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea class="form-control" id="description" name="description"></textarea>
                    <small id="emailHelp" class="form-text text-muted">This is optional</small>
                </div>
                <button type="submit" class="btn btn-dark">Submit</button>
            </form>
        </div>

        @if(count($inventory_items) == 0)
        <div>
            <h3>Sorry, The inventory does not contain any items! </h3>
        </div>
        @else
        <form action="{{url('/export')}}" method="GET">
            @csrf
            <button type="submit" class="btn btn-secondary csv_button">
            Export to CSV file
            </button>
        </form>
        <h3 class="csv_button">Inventory table</h3>
        <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
            <table class="table">
                <tr>
                   <th scope="col">Item Name</th>
                   <th scope="col">Unit price</th>
                   <th scope="col">Quantity</th>
                   <th scope="col">Total Values</th>
                   <th scope="col">Description</th>
                   <th scope="col">Edit or Delete</th>
                </tr>
                @foreach ($inventory_items as $inventory_item)
                    <tr>
                        <td>{{$inventory_item->item_name}}</td>
                        <td>{{$inventory_item->unit_price}}</td>
                        <td>{{$inventory_item->quantity}}</td>
                        <td>{{$inventory_item->quantity * $inventory_item->unit_price}}</td>
                        <td>{{$inventory_item->description}}</td>
                        <td>
                            <button type="button" class="fas fa-pencil-alt edit_icon" data-bs-toggle="modal" data-bs-target="#editModal"></button>

                            <!-- Pop up modal for update item -->
                            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Edit box</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        <form action="{{url('/update', ['id' => $inventory_item->id])}}" method="POST" id="update_form">
                                        @method('PUT')
                                        @csrf

                                        <div class="form-group">
                                            <label for="edit_itemName" class="col-form-label">Inventory Item Name:</label>
                                            <input class="form-control" id="edit_itemName" name="edit_itemName" aria-describedby="emailHelp" value={{$inventory_item->item_name}} required>
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_price" class="col-form-label">Unit price (In Dollor):</label>
                                            <input type="number" class="form-control" id="edit_price" name="edit_price" value={{$inventory_item->unit_price}} required min="0" step="any">
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_quantity" class="col-form-label">Quantity:</label>
                                            <input type="number" class="form-control" id="edit_quantity" name="edit_quantity" value={{$inventory_item->quantity}} required min="0">
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_description" class="col-form-label">Description:</label>
                                            <textarea class="form-control" id="edit_description" name="edit_description">{{$inventory_item->description}}</textarea>
                                        </div>
                                        </form>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" form="update_form">Updates</button>
                                    </div>

                                    </div>
                                </div>
                            </div>

                            <div style="float: right;margin-right:35%">
                            <form action="{{url('/delete', ['id' => $inventory_item->id])}}" method="POST" style="">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="fas fa-minus-circle delete_button"></button>
                            </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>

            @endif
        </div>

        <div class="flex justify-center mt-4 sm:items-center sm:justify-between">
            <div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </div>
        </div>
    </div>
</div>

@endsection
