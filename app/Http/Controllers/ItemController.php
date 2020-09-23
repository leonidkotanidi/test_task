<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Validator;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::all();

        return response()->json([
            'items' => $items
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedForm = Validator::make($request->all(), [
            'name' => 'required|unique:items',
        ]);
        if ($validatedForm->fails()) {
            return response()->json(['success' => false, 'errors' => $validatedForm->messages()]);
        }
//        if ($validatedData->fails()) {
//            return response()->json([
//                'isValid'=>false,'errors'=>$validatedData->messages()
//            ]);
//        }

        $item = Item::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'item' => $item
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
            $item->selected = !$item->selected;
            $item->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
