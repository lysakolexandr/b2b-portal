<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Draft;
use App\Models\DraftItem;
use App\Models\Product;
//use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DraftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        Setting::set('foo', 'bar');
        dd('222');

        $products = Product::orderBy('id', 'desc')->get();
        $categories = Category::orderBy('id', 'desc')->where(['parent_id' => null])->get();
        $drafts = Draft::where(['user_id' => Auth::user()->id])->get();

        if ($request->ajax()) {
            return view('layouts.breakpoints_xxl.draft_edit_table_xxl', compact('products', 'drafts'));
        }
        return view('catalog.drafts', compact('products', 'drafts'));
    }

    public function DraftCopy(Request $request, $id = null)
    {


        $draft = Draft::where(['id' => $request->id])->first();

        $newDraft = $draft->replicate();

        $newDraft->name = $draft->name . '_copy';
        $newDraft->save();


        $items = DraftItem::where(['draft_id' => $request->id])->get();
        foreach ($items as $key => $item) {
            $newItem = $item->replicate();
            $newItem->draft_id = $newDraft->id;
            $newItem->save();
        }
        if ($request->ajax()) {
            return $newDraft->id;
        }
        return redirect('/edit-draft/' . $newDraft->id);
    }

    public function DraftDelete(Request $request, $id = null)
    {


        $draft = Draft::where(['id' => $request->id])->first();
        $draft->delete();

        return redirect('/drafts');
    }

    public function DraftEdit(Request $request, $id = null)
    {
        $products = Product::orderBy('id', 'desc')->get();
        $categories = Category::orderBy('id', 'desc')->where(['parent_id' => null])->get();

        if ($id == null) {
            $draft = new Draft();
            $draft->user_id = Auth::user()->id;
            $draft->name = $request->name;

            $draft->save();
            return redirect('/draft-edit/' . $draft->id);
        } else {
            $draft = Draft::where(['id' => $id])->first();
        }

        if ($request->ajax()) {
            return view('layouts.breakpoints_xxl.draft_edit_table_xxl', compact('products', 'draft'));
        }
        return view('catalog.draft_edit', compact('products', 'draft'));
    }

    public function add(Request $request)
    {
        //dd($request->product_id);
        $product_id = $request->product_id;
        if ($product_id <> null) {
            $draft_item = DraftItem::firstOrNew(
                ['product_id' => $product_id],
                ['draft_id' => $request->draft_id]
            );

            $draft_item->qty = $draft_item->qty + $request->qty;
            $draft_item->draft_id = $request->draft_id;
            $res = $draft_item->save();
            dd($res);
        } elseif ($request->qty == 'delete') {
            //dd($request->row_id);
            $row = DraftItem::where(['id' => $request->row_id])->first();
            //dd($row);
            $row->delete();
        };
        return true;
    }

    public function createOrder(Request $request, $id)
    {
        $draft = Draft::where(['id' => $request->id])->first();

        // $newOrder = $draft->replicate();

        // $newDraft->name = $draft->name . '_copy';
        // $newDraft->save();


        // $items = DraftItem::where(['draft_id' => $request->id])->get();
        // foreach ($items as $key => $item) {
        //     $newItem = $item->replicate();
        //     $newItem->draft_id = $newDraft->id;
        //     $newItem->save();
        // }
        // if ($request->ajax()) {
        //     return $newDraft->id;
        // }
        // return redirect('/edit-draft/' . $newDraft->id);
        return true;
    }
}
