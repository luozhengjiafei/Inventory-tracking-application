<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InventoryController extends Controller
{
    public function insert(Request $request){
        $validate = Validator::make($request->all(), [
            'itemName' => 'required|max:255',
            'price' => 'required',
            'quantity' => 'required'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->with("errors", $validate->errors());
        }

        DB::table('inventory')->insert([
            'item_name' => $request->itemName,
            'unit_price' => $request->price,
            'quantity' => $request->quantity,
            'description' => $request->description
        ]);

        return back()->with('success', 'Item successful inserted');
    }


    public function delete(Request $request){
        if (!$request->id) {
            return redirect()->back()->with("errors", "Fail to delete item.");
        }

        $deletedItem = DB::table('inventory')->where('id', $request->id)->delete();

        return back()->with('success', 'Item successful deleted');
    }

    public function update(Request $request){
        if(!$request->id){
            return redirect()->back()->with("errors", "Fail to update item.");
        }

        $validate = Validator::make($request->all(), [
            'edit_itemName' => 'required|max:255',
            'edit_price' => 'required',
            'edit_quantity' => 'required'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->with("errors", $validate->errors());
        }

        DB::table('inventory')->where('id', $request->id)->update([
            'item_name' => $request->edit_itemName,
            'unit_price' => $request->edit_price,
            'quantity' => $request->edit_quantity,
            'description' => $request->edit_description
        ]);

        return back()->with('success', 'Item successful updated');
    }

    public function export(Request $request){

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=export.csv",
            "Pragma"              => "public",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $inventory_items = DB::table('inventory')->get(['item_name','unit_price','quantity','description']);
        $inventory_items = json_decode($inventory_items, true);

        // Add title to the csv file
        $title = array("Item name","Unit price","Quantitys", "Description");
        array_unshift($inventory_items, $title);

        $callback = function () use ($inventory_items) {
            $file = fopen('php://output', 'w');
            foreach ($inventory_items as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return (new StreamedResponse($callback, 200, $headers))->sendContent();
    }
}
