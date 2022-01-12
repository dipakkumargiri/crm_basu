<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\LeadStage;
use App\Helper\Reply;
use App\Http\Requests\Lead\StoreLeadStage;

class LeadStageController extends AdminBaseController
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $this->stages = LeadStage::all();
        return view('admin.lead.create_stage', $this->data);
    }
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLeadStage $request)
    {
        // echo $request->stage_name;die();
        $stage = new LeadStage();
        $stage->stage_name = $request->stage_name;
        $stage->save();
        $typeData = LeadStage::all();
        return Reply::successWithData(__('messages.stageAdded'), ['data' => $typeData]);
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
        LeadStage::destroy($id);
        $typeData = LeadStage::all();
        return Reply::successWithData(__('messages.StageDeleted'), ['data' => $typeData]);
    }

}
