<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\Project\StoreProjectMarketingStatus;
use App\ProjectMarketingStatus;
use Illuminate\Http\Request;

class ManageProjectMarketingStatusController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->marketingstatus = ProjectMarketingStatus::all();
        return view('admin.marketing-status.create', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCat()
    {
        $this->marketingstatus = ProjectMarketingStatus::all();
        return view('admin.projects.create-marketing-status', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectMarketingStatus $request)
    {
        $category = new ProjectMarketingStatus();
        $category->marketing_status = $request->marketing_status;
        $category->save();

        return Reply::success(__('messages.categoryAdded'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeCat(StoreProjectMarketingStatus $request)
    {
        $category = new ProjectMarketingStatus();
        $category->marketing_status = $request->marketing_status;
        $category->save();
        $categoryData = ProjectMarketingStatus::all();
        return Reply::successWithData(__('messages.marketingStatusAdded'), ['data' => $categoryData]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ProjectMarketingStatus::destroy($id);
        $categoryData = ProjectMarketingStatus::all();
        return Reply::successWithData(__('messages.marketingStatusDeleted'), ['data' => $categoryData]);
    }

}
